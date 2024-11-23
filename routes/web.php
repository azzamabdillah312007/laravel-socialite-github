<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('login');
});

Route::get('/dashboard', function () {
    return 'anda login sebagai' . auth()->user()->email;
})->middleware('auth');

Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'name' => $githubUser->nickname,
        'email' => $githubUser->email,
        'password' => Hash::make('rahasia'),
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);

    Auth::login($user);
 
    return redirect('/dashboard');
});
