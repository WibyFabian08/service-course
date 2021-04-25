<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    protected $fillable = [
        'id', 'course_id', 'user_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ]; 

    public function course() {
        return $this->belongsTo('App\Course');
    }
}
