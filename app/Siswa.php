<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    public $timestamps = false;
    protected $fillable = ['id','name','kode','sts','sts_penerimaan','tempat_lahir','tgl_lahir','tahun_angkatan','jenis_kelamin','alamat',
    ];
}
