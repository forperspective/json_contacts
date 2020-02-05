<?php

namespace App\Http\Controllers\Auth;

use App\Contact;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use Session;
use Activation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

   

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    
    public function showRegistrationForm(Request $request)
    {
        //validate the params
        return view('auth.register');
    }

    public function register(Request $request){

        $validation = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'phone' => 'required'
            ]);

         if ($validation->fails()) {
                return Redirect::back()->withErrors($validation)->withInput();
         }

         $token=str_random(35);
         $data_array =[
            "email"=>$request->email,
            "password"=>$request->password,
            "first_name"=>$request->first_name,
            "last_name"=>$request->last_name,
            "phone"=>$request->phone,
            "token"=>$token,
         ];



         $user = Sentinel::register($data_array);
         //Activate the user **
         $activation = Activation::create($user);
         $activation = Activation::complete($user, $activation->code);
         //End activation


        if($user){
            $user->roles()->sync([2]); // 2 = client
            Session::flash('message', 'Registration is completed');
            Session::flash('status', 'success');
            return redirect('/');
        }
         Session::flash('message', 'There was an error with the registration' );
         Session::flash('status', 'error');
         return Redirect::back();
    }
}
