<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['*'];

    public function getGenderValueAttribute()
    {
        if($this->gender == 'M') {
            $gender = 'Male';
        } else {
            $gender = 'Female';
        }
        return $gender;
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\User');
    }
}
