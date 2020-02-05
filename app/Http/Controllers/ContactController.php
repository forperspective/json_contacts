<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Jobs\TranslateContactQueue;
use Dedicated\GoogleTranslate\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Sentinel;
use Validator;
use Session;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;


class ContactController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $contacts= Contact::all();
        return View('backEnd.contacts.index', compact('contacts'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){

        return View('backEnd.contacts.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        if ($this->validator($request)->fails()) {

            return redirect()->back()
                ->withErrors($this->validator($request))
                ->withInput();
        }

        //make sure you run "php artisan storage:link"
        $document = $request->file('document');

        $extension = $document->getClientOriginalExtension();
        $file=$document->getFilename().'.'.$extension;
        Storage::disk('public')->put($document->getFilename().'.'.$extension,  File::get($document));

        //there is implementation for driver s3
        $url=Storage::disk("public")->url($document->getFilename().'.'.$extension);
        $path = storage_path('app/public/'.$document->getFilename().'.'.$extension);
        //queue for insert data if it huge this run in background
        //need run command "php artisan queue:work"
        TranslateContactQueue::dispatch($path);
        return redirect()->route('contact.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'document' => 'required|file'//|mimetypes:application/json
        ]);
    }
}
