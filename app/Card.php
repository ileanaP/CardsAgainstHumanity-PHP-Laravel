<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cardset;

class Card extends Model
{
    protected $fillable = [
        'id', 'type', 'text', 'pick'
    ];

    public function cardsets() 
    {
        return $this->belongsToMany(Cardset::class);
    }
}
