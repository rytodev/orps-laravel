<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'lokasi';
    protected $fillable = [
        'name',
        'alamat',
        'lng',
        'lat',
        'foto',
    ];
}
