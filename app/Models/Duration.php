<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'duration',
        'type_service_id'
    ];

    public function typeService(){
        return $this->belongsTo(TypeService::class,'type_service_id');
    }
}
