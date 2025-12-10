<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddMoney extends Model
{
    use HasFactory;
    protected $table = 'aerox_addmoney';
    public $timestamps = false;
    protected $guarded = []; // সব কলাম fillable করার সহজ উপায়
}