<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'id', 'user_id', 'course_id', 'rating', 'note'
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
