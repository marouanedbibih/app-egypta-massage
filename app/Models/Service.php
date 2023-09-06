<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'description'
    ];

    public function typeService(){
        return $this->hasMany(TypeService::class,'service_id');
    }
    public function departement(){
        return $this->belongsToMany(Departement::class,'departement_service');
    }
    public function categorie(){
        return $this->belongsToMany(Categorie::class,'services_categories');
    }
}
