<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = [
        'label'
    ];

    public function service(){
        return $this->belongsToMany(Service::class,'services_categories');
    }
}
