<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

     // creo funzione che chiamo projects che mi prende tutti i project
    public function projects() {
        return $this->belongsToMany(Project::class);
    }
}
