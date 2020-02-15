<?php

/**
 * By Mustafa Gamal
 */

namespace App\Jobs;

use App\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use File;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateContactQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        //
        $this->path=$path;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        //
        try{
            $items = [];
            $parser = new \JsonCollectionParser\Parser();
            $parser->parse($this->path, function (array $item) use (&$items) {
                $names=str_replace('[','',str_replace(']','',$item['names']));
                $array_names=explode(',',str_replace('"','',$names));
                $hits=str_replace('[','',str_replace(']','',$item['hits']));
                $hist_array=explode(',',str_replace('"','',$hits));

                $translate = new GoogleTranslate();

                foreach($array_names as $key=>$name) {
                    $text=$translate->translate($name);
                    $current_lang=$translate->getLastDetectedSource();
                    $items[]=$current_lang;

                    //find if this name exits increase hits
                    $contact=Contact::where("names",$name)->first();
                    if($contact){
                        $contact->hits=$contact->hits+1;
                        $contact->update();
                    }else{
                        if($current_lang=='ar'){
                            $translate->setTarget('en');
                            Contact::create([
                                'names'=>$translate->translate($name),
                                'hits'=>$hist_array[$key],
                                'lang'=>$current_lang
                            ]);
                        }else{
                            $translate->setTarget('ar');
                            Contact::create([
                                'names'=>$translate->translate($name),
                                'hits'=>$hist_array[$key],
                                'lang'=>$current_lang
                            ]);
                        }
                    }

                }

            });
            return true;

        }catch (\Exception $ex){
            Log::error('Error message: '.$ex->getMessage().'line'.$ex->getLine());
            return false;
        }
    }
}
