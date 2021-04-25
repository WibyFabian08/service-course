<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    protected $fillable = [
        'id', 'course_id', 'image'
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
}
