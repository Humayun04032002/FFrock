<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aerox_notice';

    /**
     * The attributes that aren't mass assignable.
     * An empty array means all attributes are mass assignable.
     * This fixes the "Add [Attribute] to fillable property" error.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     * Your 'aerox_notice' table has 'created_at' and 'updated_at' columns,
     * so this should be true (which is the default).
     *
     * @var bool
     */
    // public $timestamps = true; // এই লাইনটি না লিখলেও চলে, কারণ এটি ডিফল্ট
}