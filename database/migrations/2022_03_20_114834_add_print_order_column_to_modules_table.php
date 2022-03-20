<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrintOrderColumnToModulesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('project_version_modules', function (Blueprint $table) {
            $table->integer('print_order')->after('parent_module_id');
        });

        Schema::table('project_version_features', function (Blueprint $table) {
            $table->integer('print_order')->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project_version_modules', function (Blueprint $table) {
            $table->dropColumn('print_order');
        });

        Schema::table('project_version_features', function (Blueprint $table) {
            $table->dropColumn('print_order');
        });
    }
}
