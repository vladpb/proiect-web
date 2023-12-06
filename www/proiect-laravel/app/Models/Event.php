<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        // Adaugă alte câmpuri necesare aici
    ];

    protected $dates = [
        'date',
    ];
    // TODO Relatii cu speakeri, parteneri, sponsori
    // un event ar trebui sa poata avea mai multi
    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }
}
