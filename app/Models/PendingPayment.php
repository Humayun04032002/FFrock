<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
    use HasFactory;
    protected $table = 'pending_payments'; // আপনার টেবিলের নাম
    public $timestamps = false; // created_at, updated_at কলাম নেই
    protected $guarded = []; // সব কলাম fillable
}