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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){

    Route::group(['prefix' => 'setting_umum'], function(){
        Route::post('/edit', 'App\Http\Controllers\SettingUmumController@edit')->name('edit_setting_umum');
        Route::get('/get', 'App\Http\Controllers\SettingUmumController@get')->name('get_setting_umum');
    });

    Route::group(['prefix' => 'setting_jp'], function(){
        Route::post('/edit', 'App\Http\Controllers\SettingJPController@edit')->name('edit_setting_jp');
        Route::get('/get', 'App\Http\Controllers\SettingJPController@getAll')->name('get_setting_jp');
    });

    Route::group(['prefix' => 'setting_jeda'], function(){
        Route::get('/{id}/delete', 'App\Http\Controllers\SettingJedaController@delete')->name('delete_jeda');
        Route::post('/edit', 'App\Http\Controllers\SettingJedaController@edit')->name('edit_jeda');
        Route::get('/get', 'App\Http\Controllers\SettingJedaController@getAll')->name('get_jeda');
        Route::post('/tambah', 'App\Http\Controllers\SettingJedaController@tambah')->name("tambah_jeda");
    });

    Route::group(['prefix' => 'guru'], function(){
        Route::get('/', 'App\Http\Controllers\GuruController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\GuruController@delete')->name('delete_guru');
        Route::post('/edit', 'App\Http\Controllers\GuruController@edit')->name('edit_guru');
        Route::get('/get', 'App\Http\Controllers\GuruController@getAll')->name('get_guru');
        Route::post('/tambah', 'App\Http\Controllers\GuruController@tambah')->name("tambah_guru");

        Route::get('/jadwal', 'App\Http\Controllers\GuruController@getJadwalGuru')->name('get_jadwal_guru');

    });

    Route::group(['prefix' => 'jadwal'], function(){
        Route::get('/', 'App\Http\Controllers\JadwalController@view');
        Route::get('/tambah', 'App\Http\Controllers\JadwalController@addView');
        Route::get('/{id}/delete', 'App\Http\Controllers\JadwalController@delete')->name('delete_jadwal');
        Route::post('/edit', 'App\Http\Controllers\JadwalController@edit')->name('edit_jadwal');
        Route::get('/get', 'App\Http\Controllers\JadwalController@getAll')->name('get_jadwal');
        Route::post('/tambah', 'App\Http\Controllers\JadwalController@add')->name("tambah_jadwal");

        Route::get('/getJadwalByKelas', 'App\Http\Controllers\JadwalController@getJadwalByKelas')->name('get_jadwal_kelas');
    });

    Route::group(['prefix' => 'jurusan'], function(){
        Route::get('/', 'App\Http\Controllers\JurusanController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\JurusanController@delete')->name('delete_jurusan');
        Route::post('/edit', 'App\Http\Controllers\JurusanController@edit')->name('edit_jurusan');
        Route::get('/get', 'App\Http\Controllers\JurusanController@getAll')->name('get_jurusan');
        Route::post('/tambah', 'App\Http\Controllers\JurusanController@tambah')->name('tambah_jurusan');
    });

    Route::group(['prefix' => 'hari'], function(){
        Route::get('/', 'App\Http\Controllers\HariController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\HariController@delete')->name('delete_hari');
        Route::post('/edit', 'App\Http\Controllers\HariController@edit')->name('edit_hari');
        Route::get('/get', 'App\Http\Controllers\HariController@getAll')->name('get_hari');
        Route::post('/tambah', 'App\Http\Controllers\HariController@tambah')->name('tambah_hari');
    });

    Route::group(['prefix' => 'kelas'], function(){
        Route::get('/', 'App\Http\Controllers\KelasController@view');
        Route::get('/getByIdJurusan', 'App\Http\Controllers\KelasController@getAllByIdJurusan')->name('get_kelas_by_id_jurusan');
        Route::get('/{id}/delete', 'App\Http\Controllers\KelasController@delete')->name('delete_kelas');
        Route::post('/edit', 'App\Http\Controllers\KelasController@edit')->name('edit_kelas');
        Route::get('/get', 'App\Http\Controllers\KelasController@getAll')->name('get_kelas');
        Route::post('/tambah', 'App\Http\Controllers\KelasController@tambah')->name('tambah_kelas');
    });
    Route::group(['prefix' => 'mapel'], function(){
        Route::get('/', 'App\Http\Controllers\MapelController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\MapelController@delete')->name('delete_mapel');
        Route::post('/edit', 'App\Http\Controllers\MapelController@edit')->name('edit_mapel');
        Route::get('/get', 'App\Http\Controllers\MapelController@getAll')->name('get_mapel');
        Route::post('/tambah', 'App\Http\Controllers\MapelController@tambah')->name('tambah_mapel');
    });
    Route::group(['prefix' => 'ruang_kelas'], function(){
        Route::get('/', 'App\Http\Controllers\RuangKelasController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\RuangKelasController@delete')->name('delete_ruang_kelas');
        Route::post('/edit', 'App\Http\Controllers\RuangKelasController@edit')->name('edit_ruang_kelas');
        Route::get('/get', 'App\Http\Controllers\RuangKelasController@getAll')->name('get_ruang_kelas');
        Route::post('/tambah', 'App\Http\Controllers\RuangKelasController@tambah')->name('tambah_ruang_kelas');
    });
    Route::group(['prefix' => 'semester'], function(){
        Route::get('/', 'App\Http\Controllers\SemesterController@view');
        Route::get('/{id}/delete', 'App\Http\Controllers\SemesterController@delete')->name('delete_semester');
        Route::post('/edit', 'App\Http\Controllers\SemesterController@edit')->name('edit_semester');
        Route::get('/get', 'App\Http\Controllers\SemesterController@getAll')->name('get_semester');
        Route::post('/tambah', 'App\Http\Controllers\SemesterController@tambah')->name('tambah_semester');
    });
    Route::group(['prefix' => 'admin'], function(){
        Route::get('/settings', 'App\Http\Controllers\SettingAdminController@view');
        Route::post('/ubahPassword', 'App\Http\Controllers\SettingAdminController@changepassword')->name('ubahPassword');
        Route::get('/logout', 'App\Http\Controllers\SettingAdminController@logout')->name('logout');
    });

});