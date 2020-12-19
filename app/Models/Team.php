<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    
    public function soldier(){
        return $this->hasMany(Soldier::class);
    }
    public function leaders(){
        return $this->belongsTo(Soldier::class);
    }
    public function mission(){
        return $this->belongsTo(Mission::class);
    }
    
   
}
