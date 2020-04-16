<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Card;
use App\Game;

class Cardset extends Model
{
    protected $fillable = [
        'id', 'name',
    ];

    public function games() 
    {
        return $this->belongsToMany(Game::class);
    }

    public function cards() 
    {
        return $this->belongsToMany(Card::class);
    }
}
