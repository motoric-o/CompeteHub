<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Model;
 
 class ScoringType extends Model
 {
     protected $fillable = [
         'name'
     ];
 
     public function competitions()
     {
         return $this->hasMany(Competition::class);
     }
 }
