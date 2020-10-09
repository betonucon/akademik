<?php

namespace App\Http\Controllers;
use App\Spp;
use App\Siswa;
use App\Kelassiswa;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class SppController extends Controller
{
    public function index(request $request,$act=null,$id=null){
        if(Auth::user()['role_id']==3){
            $halaman='Daftar spp';
            $link='spp';
            if($act==null){
                $aksi='new';
                $id='new';
            }
            else{
                $aksi=$act;
                $id=$id;
            }
            $data=Spp::where('id',$id)->first();
            return view('spp.index',compact('halaman','link','aksi','id','data'));
        }else{
            return view('error');
        }
    }

    public function simpan(request $request){
        if(Auth::user()['role_id']==3){
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->kelas) == '') {$error[] = '- Pilih Kelas';} 
            if (trim($request->biaya) == '') {$error[] = '- Biaya  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $cek=Spp::where('tahun_ajaran',$request->tahun_ajaran)->where('kelas',$request->kelas)->count();
                if($cek>0){
                    echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Biaya ini sudah terdaftar</p>';
                }else{ 
                    $data                   = new Spp;
                    $data->tahun_ajaran     = $request->tahun_ajaran;    
                    $data->kelas            = $request->kelas;    
                    $data->biaya            = preg_replace($patr,'',$request->biaya);    
                    $data->save(); 
                    if($data){
                        echo'ok';
                    }
                }           
            }
        }else{
            
        }
    }

    public function simpan_ubah(request $request,$id){
        if(Auth::user()['role_id']==3){
            if (trim($request->biaya) == '') {$error[] = '- Biaya  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                
                    $data                   = Spp::find($id);
                    $data->biaya            = preg_replace($patr,'',$request->biaya);    
                    $data->save(); 
                    if($data){
                        echo'ok';
                    }
                          
            }
        }else{
            
        }
    }

    public function api(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
           
            $data=Spp::orderBy('tahun_ajaran','Asc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "tahun_ajaran" =>$o['tahun_ajaran'],
                    "kelas" =>'Kelas '.$o['kelas'],
                    "biaya" =>uang($o['biaya'])
                );
            }
        }

        echo json_encode($show);
        
    }

    public function hapus($id){
        $data=Spp::where('id',$id)->delete();
    }
}
