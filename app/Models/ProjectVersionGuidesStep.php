<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProjectVersionGuidesStep extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    public function guide() {
        return $this->belongsTo(ProjectVersionModule::class, 'module_id');
    }
}
