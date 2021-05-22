<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mentor extends Model
{
    protected $fillable = [
        'id', 'name', 'profile', 'email', 'profession'
    ];

    protected $hidden = [

    ]; 

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ]; 

    public function toArray() {
        $toArray = parent::toArray();
        $toArray['profile'] = $this->profile;
        return $toArray;
    }

    public function getProfileAttribute() {
        return url('') . Storage::url($this->attributes['profile']);   
    }
}
