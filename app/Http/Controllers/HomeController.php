<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home($value='')
    {
        $services=Contact::get();
    	return view('welcome',["services"=>$services]);
    }
    public function YourhomePage($value='')
    {
    	return view('home');
    }
    public function dashboard($value='')
    {
    	return view('backEnd.dashboard');
    }

}
