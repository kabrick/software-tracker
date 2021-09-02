<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectVersionFeaturesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project_version_features', function (Blueprint $table) {
            $table->id();
            $table->integer("version_id");
            $table->text("title");
            $table->longText("description");
            $table->text("image");
            $table->integer("parent_version_id")->default(0);
            $table->integer("is_published")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('project_version_features');
    }
}
