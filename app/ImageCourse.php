<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function toArray() {
        $toArray = parent::toArray();
        $toArray['image'] = $this->image;
        return $toArray;
    }

    public function getImageAttribute() {
        return url('') . Storage::url($this->attributes['image']);   
    }

    public function course() {
        return $this->belongsTo('\App\Course');
    }
}
