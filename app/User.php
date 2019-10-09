<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [ 'name', 'email', 'password', 'two_factor_token', 'two_factor_expiry'];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $casts = [ 'email_verified_at' => 'datetime' ];

    protected $dates = [ 'two_factor_expiry' ];

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_token = rand(100000, 999999);
        $this->two_factor_expiry = now()->addMinutes(10);
        $this->save();
    }
    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_token = null;
        $this->two_factor_expiry = null;
        $this->save();
    }
}
