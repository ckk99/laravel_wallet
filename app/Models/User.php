<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Bavix\Wallet\Models\Wallet;
use Bavix\Wallet\Traits\HasWallet;  // Import the HasWallet trait

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasWallet;  // Use the HasWallet trait only once

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // You don't need this method for wallet if you are using the HasWallet trait
    // public function wallet()
    // {
    //     return $this->hasOne(Wallet::class);
    // }
}
