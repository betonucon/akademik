<?php

namespace App\Http\Controllers;

use App\Tahunajaran;
use App\Kelas;
use App\Title;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    public function index(request $request){
        if(Auth::user()['role_id']==1){
            
            $halaman='Tahun Ajaran';
            $link='master';
            return view('master.index_tahun',compact('halaman','link'));
        }else{
            return view('error.404');
        }
    }

    public function tambah(request $request){
        if(Auth::user()['role_id']==1){
            $halaman='Tambah Tahun Ajaran';
            $link='master/tambah';
            $batal='master';
            return view('master.tambah_tahun',compact('halaman','link','batal'));
        }else{
            return view('error');
        }
    }

    public function ubah(request $request,$id){
        if(Auth::user()['role_id']==1){
            $halaman='Tambah Tahun Ajaran';
            $link='master/tambah';
            $batal='master';
            $data=Tahunajaran::where('id',$id)->first();
            return view('master.ubah_tahun',compact('halaman','link','data','batal'));
        }else{
            return view('error');
        }
    }

    public function simpan(request $request){
        if(Auth::user()['role_id']==1){
            if (trim($request->name) == '') {$error[] = '- Tahun Ajaran  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
               
                $cek=Tahunajaran::where('name',$request->name)->count();
                    if($cek>0){
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Tahun Ajaran Sudah terdaftar</p>';
                    }else{
                        
                            $data                   = new Tahunajaran;
                            $data->name             = $request->name;    
                            $data->save(); 

                            echo'ok';
                    }
            }
        }else{
            
        }
    }

    public function simpan_ubah(request $request,$id){
        if(Auth::user()['role_id']==1){
            if (trim($request->name) == '') {$error[] = '- Tahun Ajaran  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
               
                $cek=Tahunajaran::where('name',$request->name)->count();
                    if($cek>1){
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Tahun Ajaran Sudah terdaftar</p>';
                    }else{
                        
                            $data                   = Tahunajaran::find($id);
                            $data->name             = $request->name;    
                            $data->save(); 

                            echo'ok';
                    }
            }
        }else{
            
        }
    }

    public function hapus(request $request){
        if(Auth::user()['role_id']==1){
            $jum=count($request->id);

            for($x=0;$x<$jum;$x++){
                $data=Tahunajaran::where('id',$request->id[$x])->delete();
            }
           
        }
    }

    public function api_tahun(){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            
            $data=Tahunajaran::orderBy('name','Asc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "name" =>$o['name']
                );
            }
        }

        echo json_encode($show);
        
    }

    //========================================kelas========================

    public function index_kelas(request $request){
        if(Auth::user()['role_id']==1){
            
            $halaman='Master Kelas';
            $link='master/kelas';
            return view('master.index_kelas',compact('halaman','link'));
        }else{
            return view('error.404');
        }
    }

    public function tambah_kelas(request $request){
        if(Auth::user()['role_id']==1){
            $halaman='Tambah Kelas';
            $link='master/kelas/tambah';
            $batal='master/kelas';
            return view('master.tambah_kelas',compact('halaman','link','batal'));
        }else{
            return view('error');
        }
    }

    public function ubah_kelas(request $request,$id){
        if(Auth::user()['role_id']==1){
            $halaman='Ubah Kelas';
            $link='master/ubah_kelas';
            $batal='master/kelas';
            $data=Kelas::where('id',$id)->first();
            return view('master.ubah_kelas',compact('halaman','link','data','batal'));
        }else{
            return view('error');
        }
    }

    public function simpan_kelas(request $request){
        if(Auth::user()['role_id']==1){
            if (trim($request->kelas) == '') {$error[] = '- Kelas  harus diisi';}
            if (trim($request->name) == '') {$error[] = '- Tahun Ajaran  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
               
                $cek=Kelas::where('name',$request->name)->count();
                    if($cek>0){
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Nama Kelas Sudah terdaftar</p>';
                    }else{
                        
                            $data                   = new Kelas;
                            $data->kelas             = $request->kelas;    
                            $data->name             = $request->name;    
                            $data->save(); 

                            echo'ok';
                    }
            }
        }else{
            
        }
    }

    public function simpan_ubah_kelas(request $request,$id){
        if(Auth::user()['role_id']==1){
            if (trim($request->kelas) == '') {$error[] = '- Kelas  harus diisi';}
            if (trim($request->name) == '') {$error[] = '- Tahun Ajaran  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
               
                $cek=Kelas::where('name',$request->name)->count();
                    if($cek>1){
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Tahun Ajaran Sudah terdaftar</p>';
                    }else{
                        
                            $data                   = Kelas::find($id);
                            $data->kelas             = $request->kelas;    
                            $data->name             = $request->name;    
                            $data->save(); 

                            echo'ok';
                    }
            }
        }else{
            
        }
    }

    public function hapus_kelas(request $request){
        if(Auth::user()['role_id']==1){
            $jum=count($request->id);

            for($x=0;$x<$jum;$x++){
                $data=Kelas::where('id',$request->id[$x])->delete();
            }
           
        }
    }

    public function api_kelas(){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            
            $data=Kelas::orderBy('kelas','Asc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "kelas" =>'Kelas '.$o['kelas'],
                    "name" =>$o['name'],
                );
            }
        }

        echo json_encode($show);
        
    }

    //========================================title========================
    
    public function index_title(request $request){
        if(Auth::user()['role_id']==1){
            
            $halaman='Master Judul';
            $link='master/title';
            return view('master.index_title',compact('halaman','link'));
        }else{
            return redirect('/home');
        }
    }

    public function ubah_title(request $request,$id){
        if(Auth::user()['role_id']==1){
            $halaman='Ubah Judul';
            $link='master/ubah/title';
            $batal='master/title';
            $data=title::where('id',$id)->first();
            return view('master.ubah_title',compact('halaman','link','data','batal'));
        }else{
            return view('error');
        }
    }

    
    public function simpan_ubah_title(request $request,$id){
        if(Auth::user()['role_id']==1){
            if (trim($request->title) == '') {$error[] = '- Judul  harus diisi';}
            if (trim($request->isi) == '') {$error[] = '- Keterangan  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
               
               
                $data                   = Title::find($id);
                $data->isi             = $request->isi;    
                $data->title             = $request->title;    
                $data->save(); 

                echo'ok';
                   
            }
        }else{
            
        }
    }

    

    public function api_title(){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            
            $data=Title::orderBy('name','Asc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "title" =>$o['title'],
                    "isi" =>$o['isi'],
                    "name" =>$o['name']
                );
            }
        }

        echo json_encode($show);
        
    }
}
