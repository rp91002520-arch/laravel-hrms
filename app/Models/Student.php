<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'crudapp_student';
    protected $fillable = ['name', 'email'];
    public $timestamps = false;
    
    public function scopeName($query){
       return $query->where('name','dd');
    }
}
