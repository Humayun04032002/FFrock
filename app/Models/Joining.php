<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joining extends Model
{
    use HasFactory;
    protected $table = 'aerox_joining';
    public $timestamps = false;
    protected $guarded = [];
}