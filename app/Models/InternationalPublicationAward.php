<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class InternationalPublicationAward extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'title',
        'date_published',
        'date_awarded',
        'certificate_path',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getCertificateUrlAttribute()
    {
        return $this->certificate_path ? Storage::url($this->certificate_path) : null;
    }
}


