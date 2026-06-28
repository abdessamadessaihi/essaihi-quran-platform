<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware; // 👈 إضافة الواجهة الحديثة
use Illuminate\Routing\Controllers\Middleware;

class RegisterController extends Controller implements HasMiddleware
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard'; // ✨ تم توجيهه مباشرة إلى الـ dashboard أيضاً

    /**
     * إعداد الـ Middleware بالطريقة الحديثة لـ Laravel 11/13
     */
    public static function middleware(): array
    {
        return [
            // منع المستخدم المسجل دخولاً بالفعل من دخول صفحة التسجيل
            new Middleware('guest'),
        ];
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
      $messages = [
        'email.unique' => 'هذا البريد الإلكتروني مسجل لدينا بالفعل! يمكنك الانتقال لصفحة تسجيل الدخول.',
        'email.required' => 'حقل البريد الإلكتروني إلزامي ولا يمكن تركه فارغاً.',
        'password.confirmed' => 'كلمتا المرور غير متطابقتين، يرجى التأكد وإعادة المحاولة.',
    ];

    // 2. تمرير المصفوفة كمعامل ثالث للـ Validator
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ], $messages); 
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}