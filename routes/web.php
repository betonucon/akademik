<?php

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


Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/config-cache', function() {
    Artisan::call('config:cache');
    return "Cache is cleared";
});

Route::group(['middleware'    => 'auth'],function(){
    Route::get('/home', 'HomeController@home');
    Route::get('/', 'HomeController@home');
   
    Route::group(['prefix' => 'siswa'], function(){
        Route::get('/', 'SiswaController@index');
        Route::get('/online', 'SiswaController@index_online');
        Route::get('/profil', 'SiswaController@profil');
        Route::get('/kelas', 'SiswaController@index_kelas');
        Route::get('/online/api/{kat}', 'SiswaController@api_online');
        Route::get('/api/{kelas}', 'SiswaController@api');
        Route::get('/view/api/{id}', 'SiswaController@api_riwayat');
        Route::get('/kelas/api/{tahun}/{kelas}', 'SiswaController@api_kelas');
        Route::get('/api_all', 'SiswaController@api_all');
        Route::get('/tambah', 'SiswaController@tambah');
        Route::get('/ubah/{id}', 'SiswaController@ubah');
        Route::get('/view/{id}', 'SiswaController@view');
        Route::get('/cetak/{kategori}', 'SiswaController@cetak');
        Route::get('/kelas/cetak_kelas/{tahun}/{kelas}', 'SiswaController@cetak_kelas');
        Route::post('/simpan', 'SiswaController@simpan');
        Route::post('/simpan_kelas/{tahun}/{kelas}', 'SiswaController@simpan_kelas');
        Route::post('/online/simpan_approve/{sts_penerimaan}', 'SiswaController@simpan_approve');
        Route::post('/kelas/simpan_kelas/{tahun}/{kelas}', 'SiswaController@simpan_kelas');
        Route::post('/simpan_ubah/{id}', 'SiswaController@simpan_ubah');
        Route::post('/profil/simpan_ubah/{id}', 'SiswaController@simpan_ubah_profil');
        Route::post('/kelas/hapus', 'SiswaController@hapus');
        Route::get('/hapus_file/{id}', 'SiswaController@hapus_file');
    });

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', 'HomeController@index');
        Route::get('/api', 'HomeController@api_user');
    });
    
    Route::group(['prefix' => 'master'], function(){
        Route::get('/', 'MasterController@index');
        Route::get('/kelas', 'MasterController@index_kelas');
        Route::get('/title', 'MasterController@index_title');
        Route::get('/api/{kelas}', 'MasterController@api');
        Route::get('/api_tahun', 'MasterController@api_tahun');
        Route::get('/kelas/api_kelas', 'MasterController@api_kelas');
        Route::get('/title/api_title', 'MasterController@api_title');
        Route::get('/tambah', 'MasterController@tambah');
        Route::get('/kelas/tambah', 'MasterController@tambah_kelas');
        Route::get('/ubah/{id}', 'MasterController@ubah');
        Route::get('/kelas/ubah/{id}', 'MasterController@ubah_kelas');
        Route::get('/title/ubah/{id}', 'MasterController@ubah_title');
        Route::get('/cetak/{kategori}', 'MasterController@cetak');
        Route::post('/simpan', 'MasterController@simpan');
        Route::post('/kelas/simpan', 'MasterController@simpan_kelas');
        Route::post('/simpan_ubah/{id}', 'MasterController@simpan_ubah');
        Route::post('/kelas/simpan_ubah/{id}', 'MasterController@simpan_ubah_kelas');
        Route::post('/title/simpan_ubah/{id}', 'MasterController@simpan_ubah_title');
        Route::post('/hapus', 'MasterController@hapus');
        Route::post('/kelas/hapus', 'MasterController@hapus_kelas');
        Route::get('/hapus_file/{id}', 'MasterController@hapus_file');
    });

    
 });

 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/riwayat_pembayaran/', 'HomeController@riwayat');
    Route::get('/spp/{aksi?}/{id?}', 'SppController@index');
    Route::get('/tambah/spp', 'SppController@tambah');
    Route::get('/ubah/spp/{id}', 'SppController@ubah');
    Route::get('/hapus/spp/{id}', 'SppController@hapus');
    Route::get('/api/spp', 'SppController@api');
    Route::post('/simpan/spp', 'SppController@simpan');
    Route::post('/simpan_ubah/spp/{id}', 'SppController@simpan_ubah');
 });

 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/beasiswa/', 'SiswaController@index_beasiswa');
    Route::get('/cari_daftar_ulang/{tahun}', 'SiswaController@cari_daftar_ulang');
    Route::get('/beasiswa_daftar/', 'SiswaController@index_beasiswa_daftar');
    Route::get('/ubah_status/{id}', 'SiswaController@ubah_status');
    Route::get('/ubah_status_aktif/{id}', 'SiswaController@ubah_status_aktif');
    Route::get('/tambah/beasiswa', 'SiswaController@tambah_beasiswa');
    Route::get('/ubah/beasiswa/{id}', 'SiswaController@ubah_beasiswa');
    Route::get('/hapus/beasiswa/{id}', 'SiswaController@hapus_beasiswa');
    Route::get('/api/beasiswa', 'SiswaController@api_beasiswa');
    Route::post('/simpan/beasiswa', 'SiswaController@simpan_beasiswa');
    Route::post('/simpan_ubah/beasiswa/{id}', 'SiswaController@simpan_ubah_beasiswa');
    Route::get('/tambah/beasiswa_daftar', 'SiswaController@tambah_beasiswa_daftar');
    Route::get('/ubah/beasiswa_daftar/{id}', 'SiswaController@ubah_beasiswa_daftar');
    Route::get('/hapus/beasiswa_daftar/{id}', 'SiswaController@hapus_beasiswa_daftar');
    Route::get('/api/beasiswa_daftar', 'SiswaController@api_beasiswa_daftar');
    Route::post('/simpan/beasiswa_daftar', 'SiswaController@simpan_beasiswa_daftar');
    Route::post('/simpan_ubah/beasiswa_daftar/{id}', 'SiswaController@simpan_ubah_beasiswa_daftar');
 });
 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/keuangan/{aksi?}', 'RekapanController@index_keuangan');
    Route::post('/simpan_keuangan/keuangan/', 'RekapanController@simpan_keuangan');
    Route::post('/simpan_ubah_keuangan/keuangan/{id}', 'RekapanController@simpan_ubah_keuangan');
    Route::get('/api/keuangan/{bulan}/{tahun}', 'RekapanController@api_keuangan');
 });

 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/daftarulang/{aksi?}/{id?}', 'DaftarulangController@index');
    Route::get('/tambah/daftarulang', 'DaftarulangController@tambah');
    Route::get('/ubah_daftarulang/daftarulang/{id}', 'DaftarulangController@ubah');
    Route::get('/hapus_daftarulang/daftarulang/{id}', 'DaftarulangController@hapus');
    Route::get('/api_daftarulang/daftarulang', 'DaftarulangController@api');
    Route::post('/simpan_daftarulang/daftarulang', 'DaftarulangController@simpan');
    Route::post('/simpan_ubah_daftarulang/daftarulang/{id}', 'DaftarulangController@simpan_ubah');
 });

 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/rekapan_keuangan', 'RekapanController@index');
    Route::get('/rekapan_keuangan_spp', 'RekapanController@index_spp');
    Route::get('/rekapan_spp_all/', 'RekapanController@rekapan_spp_all');
    Route::get('/rekapan_daftar_ulang/', 'RekapanController@rekapan_daftar_ulang');
    Route::get('/pdf_rekapan_spp_all/{tahun}/{kelas}', 'RekapanController@pdf_rekapan_spp_all');
    Route::get('/pdf_rekapan_daftar_ulang/{tahun}/{kelas}', 'RekapanController@pdf_rekapan_daftar_ulang');
    Route::get('/pdf_rekapan_keuangan/{tahun}/{bulan}', 'RekapanController@pdf_rekapan_keuangan');
    Route::get('/rekapan_keuangan_daftar', 'RekapanController@index_daftar');
    Route::get('/api_rekapan_spp_all/{tahun}/{kelas}', 'RekapanController@api_rekapan_spp_all');
    Route::get('/api_rekapan_daftar_ulang/{tahun}/{kelas}', 'RekapanController@api_rekapan_daftar_ulang');
    Route::get('/api/rekapan_keuangan/{bulan}/{tahun}', 'RekapanController@api');
    Route::get('/api/rekapan_keuangan_spp/{bulan}/{tahun}', 'RekapanController@api_spp');
    Route::get('/api/rekapan_keuangan_daftar/{bulan}/{tahun}', 'RekapanController@api_daftar');
    Route::get('/tambah/daftarulang', 'RekapanController@tambah');
    Route::get('/ubah/daftarulang/{id}', 'RekapanController@ubah');
    Route::get('/hapus/daftarulang/{id}', 'RekapanController@hapus');
    Route::get('/api/daftarulang', 'RekapanController@api');
    Route::post('/simpan/daftarulang', 'RekapanController@simpan');
    Route::post('/simpan_ubah/daftarulang/{id}', 'RekapanController@simpan_ubah');
 });

 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/cari_siswa/{id?}', 'SiswaController@cari_siswa');
 });
 Route::group(['middleware'    => 'auth'],function(){
    Route::get('/rekapan/pembayaran/{id?}/{tahun?}/{kelas?}', 'PembayaranController@rekapan');
    Route::get('/rekapan_spp/pembayaran_spp/{id?}/{tahun?}/{kelas?}', 'PembayaranController@rekapan_spp');
    Route::get('/pembayaran/{aksi?}/{id?}', 'PembayaranController@index');
    Route::get('/pembayaran_spp/{aksi?}/{id?}', 'PembayaranController@index_spp');
    Route::get('/pembayaran_donasi/{aksi?}/{id?}', 'PembayaranController@index_donasi');
    Route::get('/tambah/pembayaran', 'PembayaranController@tambah');
    Route::get('/ubah/pembayaran/{id}', 'PembayaranController@ubah');
    Route::get('/cari_nik/pembayaran/{id}/{tahun_ajaran}', 'PembayaranController@cari_nik');
    Route::get('/cari_nik_spp/pembayaran_spp/{id}/{tahun}', 'PembayaranController@cari_nik_spp');
    Route::get('/hapus/pembayaran/{id}', 'PembayaranController@hapus');
    Route::get('/hapus_spp/pembayaran_spp/{id}', 'PembayaranController@hapus_spp');
    Route::get('/hapus_donasi/pembayaran_donasi/{id}', 'PembayaranController@hapus_donasi');
    Route::get('/api/pembayaran', 'PembayaranController@api');
    Route::get('/api_spp/pembayaran_spp', 'PembayaranController@api_spp');
    Route::get('/api_donasi/pembayaran_donasi', 'PembayaranController@api_donasi');
    Route::get('/api/rekapan/pembayaran/{id?}/{tahun?}/{kelas?}', 'PembayaranController@api_rekapan');
    Route::post('/simpan/pembayaran', 'PembayaranController@simpan');
    Route::post('/simpan_spp/pembayaran_spp', 'PembayaranController@simpan_spp');
    Route::post('/simpan_donasi/pembayaran_donasi', 'PembayaranController@simpan_donasi');
    Route::post('/simpan_ubah/pembayaran/{id}', 'PembayaranController@simpan_ubah');
    Route::post('/simpan_ubah_donasi/pembayaran_donasi/{id}', 'PembayaranController@simpan_ubah_donasi');
 });
Auth::routes();


