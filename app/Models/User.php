<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'aerox_users';
    const CREATED_AT = 'Register';
    const UPDATED_AT = null;

    protected $fillable = [
        'Name', 'Number', 'Password', 'Balance', 'Winning',
        'Total_Kill', 'Total_Winning', 'Total_Played', 'UsersBan', 'FCM_Token',
    ];

    protected $hidden = ['Password'];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function username()
    {
        return 'Number';
    }
}