<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProjectVersionModule extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

    /*public function hasMany($related, $foreignKey = null, $localKey = null) {
        //
    }*/
}
