<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'modal', 'year', 'price', 'user_id', 'fuel',
        'kilometer',
        'mileage',
        'no_of_owner',
        'location',
        'description',
    ];
    use SoftDeletes;
    protected $table = 'cars';
}
