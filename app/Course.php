<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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
