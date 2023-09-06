<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'description',
        'service_id'
    ];

    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }

    public function duration(){
        return $this->hasMany(Duration::class,'type_service_id');
    }
}
