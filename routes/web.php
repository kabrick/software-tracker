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

    // Project Version Guides
    Route::any('project_versions/create_guide/{id}', ['uses' => 'ProjectVersionGuideController@create_guide']);
    Route::any('project_versions/store_guide', ['as' => 'project_versions.store_guide', 'uses' => 'ProjectVersionGuideController@store_guide']);
    Route::any('project_versions/publish_guide/{id}', ['uses' => 'ProjectVersionGuideController@publish_guide']);
    Route::any('project_versions/edit_guide/{id}', ['uses' => 'ProjectVersionGuideController@edit_guide']);
    Route::any('project_versions/update_guide', ['as' => 'project_versions.update_guide', 'uses' => 'ProjectVersionGuideController@update_guide']);
    Route::any('project_versions/archive_guide/{id}', ['uses' => 'ProjectVersionGuideController@archive_guide']);
    Route::any('project_versions/delete_guide/{id}', ['uses' => 'ProjectVersionGuideController@delete_guide']);
    Route::any('project_versions/view_archived_guides', ['as' => 'project_versions.view_archived_guides', 'uses' => 'ProjectVersionGuideController@view_archived_guides']);
    Route::any('project_versions/restore_guide/{id}', ['uses' => 'ProjectVersionGuideController@restore_guide']);
    Route::any('project_versions/clone_guide/{id}/{version_id}', ['uses' => 'ProjectVersionGuideController@clone_guide']);
    Route::any('project_versions/share_guide_pdf/{version_id}', ['uses' => 'ProjectVersionGuideController@share_guide_pdf']);

    // Project Versions
    Route::any('project_versions/archive_version/{id}', ['uses' => 'ProjectVersionsController@archive_version']);
    Route::any('project_versions/delete_version/{id}', ['uses' => 'ProjectVersionsController@delete_version']);
    Route::any('project_versions/view_archived_versions', ['as' => 'projects.view_archived_versions', 'uses' => 'ProjectVersionsController@view_archived_versions']);
    Route::any('project_versions/restore_version/{id}', ['uses' => 'ProjectVersionsController@restore_version']);
    Route::any('project_versions/clone/{id}', ['uses' => 'ProjectVersionsController@clone']);
    Route::resource('project_versions', 'ProjectVersionsController');

    // Project Version Features
    Route::any('project_version_features/view_features/{version_id}', ['uses' => 'ProjectVersionFeaturesController@view_features']);
    Route::any('project_version_features/search_features', ['as' => 'project_version_features.search_features', 'uses' => 'ProjectVersionFeaturesController@search_features']);
    Route::any('project_version_features/create_feature/{parent_id}', ['uses' => 'ProjectVersionFeaturesController@create_feature']);
    Route::any('project_version_features/store_feature', ['as' => 'project_version_features.store_feature', 'uses' => 'ProjectVersionFeaturesController@store_feature']);
    Route::any('project_version_features/feature_details/{id}', ['uses' => 'ProjectVersionFeaturesController@feature_details']);
    Route::any('project_version_features/archive/{id}', ['uses' => 'ProjectVersionFeaturesController@archive']);
    Route::any('project_version_features/delete/{id}', ['uses' => 'ProjectVersionFeaturesController@delete']);
    Route::any('project_version_features/view_archived', ['as' => 'project_version_features.view_archived', 'uses' => 'ProjectVersionFeaturesController@view_archived']);
    Route::any('project_version_features/restore/{id}', ['uses' => 'ProjectVersionFeaturesController@restore']);
    Route::any('project_version_features/edit/{id}', ['uses' => 'ProjectVersionFeaturesController@edit']);
    Route::any('project_version_features/publish/{id}', ['uses' => 'ProjectVersionFeaturesController@publish']);
    Route::any('project_version_features/unpublish/{id}', ['uses' => 'ProjectVersionFeaturesController@unpublish']);
    Route::any('project_version_features/update', ['as' => 'project_version_features.update', 'uses' => 'ProjectVersionFeaturesController@update']);
    Route::any('project_version_features/share_pdf/{version_id}', ['uses' => 'ProjectVersionFeaturesController@share_pdf']);

    // Project Version Modules
    Route::any('project_version_modules/view/{project_version_id}', ['uses' => 'ProjectVersionModuleController@view']);
    Route::any('project_version_modules/create', ['uses' => 'ProjectVersionModuleController@create']);
    Route::any('project_version_modules/edit', ['uses' => 'ProjectVersionModuleController@edit']);
    Route::any('project_version_modules/fetch_modules/{parent_module_id}', ['uses' => 'ProjectVersionModuleController@fetch_modules']);
    Route::any('project_version_modules/fetch_module_details/{module_id}', ['uses' => 'ProjectVersionModuleController@fetch_module_details']);
    Route::any('project_version_modules/archive/{module_id}', ['uses' => 'ProjectVersionModuleController@archive']);
});

