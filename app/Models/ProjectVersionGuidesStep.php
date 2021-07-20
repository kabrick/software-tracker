<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectVersionGuidesStep extends Model {
    use HasFactory;

    public function guide() {
        return $this->belongsTo(ProjectVersionGuide::class, 'guide_id');
    }
}
