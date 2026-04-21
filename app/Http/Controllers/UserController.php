<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }
    public function dashboard(){
        return view('admin.dashboard');
    }
    public function register(){
        return view('auth.register');
    }
    public function login(){
        return view('auth.login');
    }

    public function addUser(Request $req){
        $data=$req->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required|min:6',
        ]);
        User::create($data);
        return redirect('/login');
    }
    public function checkLogin(Request $req){
        $data['email']= $req->email;
        $data['password']= $req->password;
        if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
            if(Auth::user()->role==1){
                return redirect('/admin/dashboard');
            }elseif (Auth::user()->role==0){
                return redirect('/');{
            }
    }else{
        return "Something went wrong";
    }
    }else{
        return redirect('/login')->with("msg","Invalid email or password");
    }
}
}