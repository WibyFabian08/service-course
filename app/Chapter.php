<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'id', 'name', 'course_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ]; 

    public function course() {
        return $this->belongsTo('\App\Course');
    }

    public function lesson() {
        return $this->hasMany('\App\Lesson');
    }
}
