<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
    Route::get('map', function () {return view('pages.maps');})->name('map');
    Route::get('icons', function () {return view('pages.icons');})->name('icons');
    Route::get('theme_guide', function () {return view('theme_guide');})->name('theme_guide');
    Route::get('table-list', function () {return view('pages.tables');})->name('table');

    // Projects
    Route::any('projects/archive_project/{id}', ['uses' => 'ProjectsController@archive_project']);
    Route::any('projects/delete_project/{id}', ['uses' => 'ProjectsController@delete_project']);
    Route::any('projects/view_archived_projects', ['as' => 'projects.view_archived_projects', 'uses' => 'ProjectsController@view_archived_projects']);
    Route::any('projects/restore_project/{id}', ['uses' => 'ProjectsController@restore_project']);
    Route::resource('projects', 'ProjectsController');

    // Project Versions
    Route::any('project_versions/archive_version/{id}', ['uses' => 'ProjectVersionsController@archive_version']);
    Route::any('project_versions/delete_version/{id}', ['uses' => 'ProjectVersionsController@delete_version']);
    Route::any('project_versions/view_archived_versions', ['as' => 'projects.view_archived_versions', 'uses' => 'ProjectVersionsController@view_archived_versions']);
    Route::any('project_versions/restore_version/{id}', ['uses' => 'ProjectVersionsController@restore_version']);
    Route::resource('project_versions', 'ProjectVersionsController');

    // Project Version Guides
    Route::any('project_versions/create_guide/{id}', ['uses' => 'ProjectVersionGuideController@create_guide']);
    Route::any('project_versions/store_guide', ['as' => 'project_versions.store_guide', 'uses' => 'ProjectVersionGuideController@store_guide']);
    Route::any('project_versions/publish_guide/{id}', ['uses' => 'ProjectVersionGuideController@publish_guide']);
    Route::any('project_versions/edit_guide/{id}', ['uses' => 'ProjectVersionGuideController@edit_guide']);
    Route::any('project_versions/update_guide', ['as' => 'project_versions.update_guide', 'uses' => 'ProjectVersionGuideController@update_guide']);
});

