<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldier extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at','created_at',];

    public function team(){
    	return $this->belongsTo(Team::class);
    }
    public function teamOne(){
    	return $this->hasOne(Team::class);
    }
    public function mission(){
        return $this->belongsToMany(Mission::class, 'soldier_missions');
    }

   

   
}
