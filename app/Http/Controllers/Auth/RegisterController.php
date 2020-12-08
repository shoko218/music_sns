<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Model\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:40'],
            'user_name' => ['required', 'string', 'max:15', 'unique:users'],
            'self_introduction' => ['nullable','string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'icon'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Model\User
     */
    protected function create(array $data)
    {
        $user=User::create([
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'self_introduction' => $data['self_introduction'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        try {
            if($data['icon']!=null){
                $file_name = uniqid(rand());
                $image=\Image::make($data['icon']->getRealPath());
                $image->fit(480,480,function($constraint){
                    $constraint->upsize();
                });
                if (env('APP_ENV') === 'production') {
                    Storage::disk('s3')->put('/user_icons/'.$file_name.'.jpg',(string)$image->encode(),'public');
                }else{
                    $image->save('storage/user_icons/'.$file_name.'.jpg');
                }
                $user->update(['icon_path'=>$file_name.'.jpg']);
            }
        } catch (\Throwable $th) {
            $user->delete();
            header('Location: /register');//考える
            exit();
        }
        return $user;
    }
}
