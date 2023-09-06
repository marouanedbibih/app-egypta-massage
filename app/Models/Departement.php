<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'longitude',
        'latitude'
    ];

    public function user(){
        return $this->belongsToMany(User::class,'colabs')->select('id','name','email','phone','profile_image','sex');
    }
    public function service(){
        return $this->belongsToMany(Service::class,'departement_service');
    }
}
