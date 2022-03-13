<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectVersionModule extends Model {
    use HasFactory;
    use SoftDeletes;

    /*public function hasMany($related, $foreignKey = null, $localKey = null) {
        //
    }*/
}
