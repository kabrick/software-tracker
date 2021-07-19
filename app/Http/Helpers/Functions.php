<?php

use Illuminate\Support\Facades\DB;

function get_name($id, $id_column, $name_column, $table) {
    $result = DB::table($table)->where($id_column, $id)->first();

    if (!$result) {
        return 'N/A';
    } else {
        return $result->$name_column;
    }
}
