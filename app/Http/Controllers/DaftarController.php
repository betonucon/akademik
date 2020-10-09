<?php

namespace App\Http\Controllers;
use App\Siswa;
use App\Kelassiswa;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class DaftarController extends Controller
{
    public function index(request $request){
        if(Auth::user()['role_id']==1){
            if($request->kat==''){
                $kat='all';
            }else{
                $kat=$request->kat;
            }
            $halaman='Daftar Registrasi Online';
            $link='siswa/online';
            return view('siswa.index_online',compact('halaman','link','kat'));
        }else{
            return view('error');
        }
    }

    public function api($kat){
        // error_reporting(0);
        // if(Auth::user()['role_id']==1){
            // if($kat=='all'){
            //     $data=Siswa::where('sts',1)->where('sts_penerimaan',0)->orderBy('name','Asc')->get();
            // }else{
            //     $data=Siswa::where('sts',1)->where('sts_penerimaan',0)->where('tahun_angkatan',$kat)->orderBy('name','Asc')->get();
            // }
            $ssss=Siswa::orderBy('name','Asc')->get();
            foreach($ssss as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "kode" =>$o['kode'],
                    "name" =>$o['name'],
                    "tempat_lahir" =>$o['tempat_lahir'],
                    "tgl_lahir" =>$o['tgl_lahir'],
                    "tahun_angkatan" =>$o['tahun_angkatan'],
                    "jenis_kelamin" =>$o['jenis_kelamin'],
                    "email" =>$o['email'],
                    "alamat"=>$o['alamat'],
                    "kelas"=>cek_kelas_akhir($o['id'])['kelas'],
                    "nama_kelas"=>cek_kelas(cek_kelas_akhir($o['id'])['kelas_id'])['name'],
                );
            }
        // }else{
        //     echo'asdasd';
        // }

        // echo json_encode($show);
        
    }


}
