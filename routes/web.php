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

// Route::get('/', function () {
//     return view('welcome');
// });



Route::group(['middleware' => ['auth', 'menu.config']], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    

    Route::group(['prefix' => 'tools','middleware' => 'admin'], function(){
        Route::group(['prefix' => "autoGenerateUser"], function(){
            Route::get('/', 'App\Http\Controllers\Tools\AutoGenerateUser@view')->name('auto_generate_user_view');
            Route::get('/allUser', 'App\Http\Controllers\Tools\AutoGenerateUser@getAll')->name('auto_generate_user_all_data');

            Route::post('/generate', 'App\Http\Controllers\Tools\AutoGenerateUser@generate')->name('auto_generate_user');
            Route::get('/batch/{id_batch}', 'App\Http\Controllers\Tools\AutoGenerateUser@infoBatch')->name('auto_generate_user_batch_info');
            Route::get('/export', 'App\Http\Controllers\Tools\AutoGenerateUser@export')->name('auto_generate_user_export');
    
        });
    });

    Route::group(['prefix' => 'cetak'], function(){

        Route::group(['middleware' => 'admin'], function(){
            Route::get('/semuaJadwal', 'App\Http\Controllers\JadwalController@cetak')->name('cetak_semua_jadwal');
            Route::get('/cetakBerdasarkanGuru', 'App\Http\Controllers\JadwalController@cetakBerdasarkanGuru')->name('cetak_berdasarkan_guru');
        });
        Route::get('/cetakJadwalBerdasarkanIdGuru/{id_guru}', 'App\Http\Controllers\JadwalController@cetakBerdasarkanIdGuru')->name('cetak_jadwal_by_id_guru');
    });

    Route::group(['prefix' => 'setting_umum'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::post('/edit', 'App\Http\Controllers\SettingUmumController@edit')->name('edit_setting_umum');
            Route::get('/get', 'App\Http\Controllers\SettingUmumController@get')->name('get_setting_umum');
        });
    });

    Route::group(['prefix' => 'setting_jp'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::post('/edit', 'App\Http\Controllers\SettingJPController@edit')->name('edit_setting_jp');
            Route::get('/get', 'App\Http\Controllers\SettingJPController@getAll')->name('get_setting_jp');
        });
    });

    Route::group(['prefix' => 'setting_jeda'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/{id}/delete', 'App\Http\Controllers\SettingJedaController@delete')->name('delete_jeda');
            Route::post('/edit', 'App\Http\Controllers\SettingJedaController@edit')->name('edit_jeda');
            Route::get('/get', 'App\Http\Controllers\SettingJedaController@getAll')->name('get_jeda');
            Route::post('/tambah', 'App\Http\Controllers\SettingJedaController@tambah')->name("tambah_jeda");
        });
    });

    Route::group(['prefix' => 'guru'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/{id}/delete', 'App\Http\Controllers\GuruController@delete')->name('delete_guru');
            Route::post('/edit', 'App\Http\Controllers\GuruController@edit')->name('edit_guru');
            Route::get('/get', 'App\Http\Controllers\GuruController@getAll')->name('get_guru');
            Route::post('/tambah', 'App\Http\Controllers\GuruController@tambah')->name("tambah_guru");
    
        });
        Route::get('/jadwal', 'App\Http\Controllers\GuruController@getJadwalGuru')->name('get_jadwal_guru');
        Route::get('/', 'App\Http\Controllers\GuruController@view');

    });

    Route::group(['prefix' => 'user'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/{id}/delete', 'App\Http\Controllers\UserController@delete')->name('delete_user');
            Route::post('/edit', 'App\Http\Controllers\UserController@edit')->name('edit_user');
            Route::get('/get', 'App\Http\Controllers\UserController@getAll')->name('get_user');
            Route::post('/tambah', 'App\Http\Controllers\UserController@tambah')->name("tambah_user");
        });
        Route::get('/', 'App\Http\Controllers\UserController@view');

    });

    Route::group(['prefix' => 'jadwal'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\JadwalController@view');
            Route::get('/tambah', 'App\Http\Controllers\JadwalController@addView');
            Route::get('/{id}/delete', 'App\Http\Controllers\JadwalController@delete')->name('delete_jadwal');
            Route::post('/edit', 'App\Http\Controllers\JadwalController@edit')->name('edit_jadwal');
            Route::get('/get', 'App\Http\Controllers\JadwalController@getAll')->name('get_jadwal');
            Route::post('/tambah', 'App\Http\Controllers\JadwalController@add')->name("tambah_jadwal");
        });
        Route::get('/getAllJadwal', 'App\Http\Controllers\JadwalController@getAllJadwal')->name('get_all_jadwal');
        Route::get('/getById/{id}', 'App\Http\Controllers\JadwalController@getJadwalById')->name('get_jadwal_by_id');

        Route::get('/getJadwalByIdJurusan', 'App\Http\Controllers\JadwalController@getJadwalByIdJurusan')->name('get_jadwal_by_id_jurusan');
        Route::get('/getJadwalByIdGuru/{id_guru}', 'App\Http\Controllers\JadwalController@getJadwalBerdasarkanIdGuru')->name('get_jadwal_by_id_guru');
    });

    Route::group(['prefix' => 'jurusan'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\JurusanController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\JurusanController@delete')->name('delete_jurusan');
            Route::post('/edit', 'App\Http\Controllers\JurusanController@edit')->name('edit_jurusan');
            Route::post('/tambah', 'App\Http\Controllers\JurusanController@tambah')->name('tambah_jurusan');
        });
        Route::get('/get', 'App\Http\Controllers\JurusanController@getAll')->name('get_jurusan');
    });

    Route::group(['prefix' => 'hari'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\HariController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\HariController@delete')->name('delete_hari');
            Route::post('/edit', 'App\Http\Controllers\HariController@edit')->name('edit_hari');
            Route::post('/tambah', 'App\Http\Controllers\HariController@tambah')->name('tambah_hari');
        });
        Route::get('/get', 'App\Http\Controllers\HariController@getAll')->name('get_hari');
    });

    Route::group(['prefix' => 'tingkat'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\TingkatKelasController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\TingkatKelasController@delete')->name('delete_tingkat');
            Route::post('/edit', 'App\Http\Controllers\TingkatKelasController@edit')->name('edit_tingkat');
            Route::post('/tambah', 'App\Http\Controllers\TingkatKelasController@tambah')->name('tambah_tingkat');
        });
        Route::get('/get', 'App\Http\Controllers\TingkatKelasController@getAll')->name('get_tingkat');
    });

    Route::group(['prefix' => 'kelas'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\KelasController@view');
            Route::get('/getByIdJurusan', 'App\Http\Controllers\KelasController@getAllByIdJurusan')->name('get_kelas_by_id_jurusan');
            Route::get('/{id}/delete', 'App\Http\Controllers\KelasController@delete')->name('delete_kelas');
            Route::post('/edit', 'App\Http\Controllers\KelasController@edit')->name('edit_kelas');
            Route::post('/tambah', 'App\Http\Controllers\KelasController@tambah')->name('tambah_kelas');
        });
        Route::get('/get', 'App\Http\Controllers\KelasController@getAll')->name('get_kelas');
        
    });
    Route::group(['prefix' => 'mapel'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\MapelController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\MapelController@delete')->name('delete_mapel');
            Route::post('/edit', 'App\Http\Controllers\MapelController@edit')->name('edit_mapel');
            Route::get('/get', 'App\Http\Controllers\MapelController@getAll')->name('get_mapel');
        });
        Route::post('/tambah', 'App\Http\Controllers\MapelController@tambah')->name('tambah_mapel');
    });
    Route::group(['prefix' => 'ruang_kelas'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\RuangKelasController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\RuangKelasController@delete')->name('delete_ruang_kelas');
            Route::post('/edit', 'App\Http\Controllers\RuangKelasController@edit')->name('edit_ruang_kelas');
            Route::post('/tambah', 'App\Http\Controllers\RuangKelasController@tambah')->name('tambah_ruang_kelas');
        });
        Route::get('/get', 'App\Http\Controllers\RuangKelasController@getAll')->name('get_ruang_kelas');
    });
    Route::group(['prefix' => 'semester'], function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'App\Http\Controllers\SemesterController@view');
            Route::get('/{id}/delete', 'App\Http\Controllers\SemesterController@delete')->name('delete_semester');
            Route::post('/edit', 'App\Http\Controllers\SemesterController@edit')->name('edit_semester');
            Route::post('/tambah', 'App\Http\Controllers\SemesterController@tambah')->name('tambah_semester');
        });
        Route::get('/get', 'App\Http\Controllers\SemesterController@getAll')->name('get_semester');
    });
    Route::group(['prefix' => 'profile'], function(){
        Route::get('/settings', 'App\Http\Controllers\SettingAdminController@view');
        Route::post('/ubahPassword', 'App\Http\Controllers\SettingAdminController@changePassword')->name('ubah_password');
        Route::get('/logout', 'App\Http\Controllers\SettingAdminController@logout')->name('logout');
        Route::post('/edit_user_profile', 'App\Http\Controllers\SettingAdminController@changeProfile')->name('edit_user_profile');
        Route::group(['middleware' => 'guru'], function(){
            Route::post('/change_guru_profile', 'App\Http\Controllers\SettingAdminController@changeGuruProfile')->name('edit_guru_profile');
        });
    });

});

    Route::group(['prefix' => 'jadwal'], function(){
        Route::get('/getJadwalByKelas', 'App\Http\Controllers\JadwalController@getJadwalByKelas')->name('get_jadwal_kelas');
    });

    Route::group(['prefix' => 'kelas'], function(){
        Route::get('/getById', 'App\Http\Controllers\KelasController@getById')->name('get_kelas_by_id');
    });


    Route::group(['prefix' => '/'], function(){
        Route::get('/', 'App\Http\Controllers\IndexController@jurusan')->name('lihat_jurusan');
        Route::get('/preview/kelas/{id}', 'App\Http\Controllers\IndexController@kelas')->name('lihat_kelas');
        Route::get('/preview/jadwal/{id}', 'App\Http\Controllers\IndexController@jadwal')->name('lihat_jadwal');
    });