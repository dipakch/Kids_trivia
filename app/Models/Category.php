<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // app/Models/Category.php
    protected $fillable = ['name', 'nepali_name']; // add categories name and Nepali name here.

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}