<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreefireMatch extends Model
{
    use HasFactory;
    protected $table = 'aerox_freefire';
    public $timestamps = false;
    protected $guarded = [];
}