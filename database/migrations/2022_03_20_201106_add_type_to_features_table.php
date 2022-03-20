<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToFeaturesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('project_version_features', function (Blueprint $table) {
            $table->integer('type')->comment('0 is free text, 1 is steps')->after('print_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project_version_features', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
