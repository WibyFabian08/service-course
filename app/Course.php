<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    // use HasFactory;

    protected $fillable = [
        'id', 'name', 'certificate', 'thumbnail', 'type', 'status', 
        'price', 'level', 'description', 'mentor_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ]; 

    public function toArray() {
        $toArray = parent::toArray();
        $toArray['thumbnail'] = $this->thumbnail;
        return $toArray;
    }

    public function getThumbnailAttribute() {
        return url('') . Storage::url($this->attributes['thumbnail']);   
    }

    public function chapter() {
        return $this->hasMany('\App\Chapter');
    }
    
    public function imageCourse() {
        return $this->hasMany('\App\ImageCourse');
    }

    public function mentor() {
        return $this->belongsTo('\App\Mentor');
    }


}
