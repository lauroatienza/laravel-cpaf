<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        "name","email","class_id","section_id",
    ];

    public function class(){
        return $this->belongsTo(Classes::class);
    }

}
