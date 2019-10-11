<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/administrator';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function showLoginForm(){

        $form = view('admin.login.form', []);

        return view('admin.loginmaster',
            [
                'content'=>$form,
                'controller'=>'admin/login/login.controller.js'
            ]
        );

    }


    public function login(Request $request){

        if(Auth::attempt(['email'=>$request->get('email'), 'password'=>$request->get('password')], $request->get('remember'))){
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }

    }


}
