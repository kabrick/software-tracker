<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProjectVersionFeature extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

    public function steps() {
        return $this->hasMany(ProjectVersionGuidesStep::class, 'feature_id');
    }
}
