<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'modal', 'year', 'price', 'user_id', 'color','description'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
    protected $table = 'cars';

    public function users() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
