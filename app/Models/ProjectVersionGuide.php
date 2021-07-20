<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectVersionGuide extends Model {
    use HasFactory;
    use SoftDeletes;

    public function steps() {
        return $this->hasMany(ProjectVersionGuidesStep::class, 'guide_id');
    }
}
