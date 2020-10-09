<?php

namespace App\Http\Controllers;
use App\Daftarulang;
use App\Detaildaftarulang;
use App\Siswa;
use App\Kelassiswa;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class DaftarulangController extends Controller
{
    public function index(request $request,$act=null,$id=null){
        if(Auth::user()['role_id']==3){
            $halaman='Daftar daftarulang';
            $link='daftarulang';
            if($act==null){
                $aksi='new';
                $id='new';
            }
            else{
                $aksi=$act;
                $id=$id;
            }
            $data=Daftarulang::where('id',$id)->first();
            $detail=Detaildaftarulang::where('daftar_ulang_id',$id)->get();
            $totdet=Detaildaftarulang::where('daftar_ulang_id',$id)->count();
            $sisa=$totdet+1;
            return view('daftarulang.index',compact('halaman','link','aksi','id','data','detail','sisa'));
        }else{
            return view('error');
        }
    }

    public function simpan(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->kelas) == '') {$error[] = '- Pilih Kelas';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $cek=Daftarulang::where('tahun_ajaran',$request->tahun_ajaran)->where('kelas',$request->kelas)->count();
                if($cek>0){
                    echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Biaya ini sudah terdaftar</p>';
                }else{ 
                    $jum=count($request->rincian);
                    if($jum>0){
                        $data                   = new Daftarulang;
                        $data->tahun_ajaran     = $request->tahun_ajaran;    
                        $data->kelas            = $request->kelas;    
                        $data->biaya            = preg_replace($patr,'',$request->totalbiaya);    
                        $data->save(); 

                        
                        if($data){
                            
                            for($x=0;$x<$jum;$x++){
                                $detail                   = new Detaildaftarulang;
                                $detail->daftar_ulang_id  = $data['id'];    
                                $detail->name             = $request->name[$x];    
                                $detail->tahun_ajaran     = $request->tahun_ajaran;    
                                $detail->kelas            = $request->kelas;    
                                $detail->biaya            = preg_replace($patr,'',$request->biaya[$x]);    
                                $detail->save(); 
                            }

                            echo'ok';
                        }

                    }else{
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Rincian pembayaran tidak boleh kosong</p>';
                    }
                }           
            }
        }else{
            
        }
    }

    public function simpan_ubah(request $request,$id){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->totalbiaya) == '') {$error[] = '- Biaya  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $jum=count($request->rincian);
                // $hapus=Detaildaftarulang::where('daftar_ulang_id',$id)->delete();
                
                $data                   = Daftarulang::find($id);
                $data->biaya            = preg_replace($patr,'',$request->totalbiaya);    
                $data->save(); 

                        
                if($data){
                    if($jum>0){
                        for($x=0;$x<$jum;$x++){
                            if($request->id[$x]==''){
                                $detail                   = new Detaildaftarulang;
                                $detail->daftar_ulang_id  = $id;    
                                $detail->name             = $request->name[$x];    
                                $detail->tahun_ajaran     = $request->tahun_ajaran;    
                                $detail->kelas            = $request->kelas;    
                                $detail->biaya            = preg_replace($patr,'',$request->biaya[$x]);    
                                $detail->save();
                            }else{
                                $detail                   = Detaildaftarulang::find($request->id[$x]);
                                $detail->name             = $request->name[$x];    
                                $detail->biaya            = preg_replace($patr,'',$request->biaya[$x]);    
                                $detail->save();
                            }
                             
                        }

                        echo'ok';
                    }else{
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Rincian pembayaran tidak boleh kosong</p>';
                    }
                }
                         
            }
        }else{
            
        }
    }

    public function api(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
           
            $data=Daftarulang::orderBy('tahun_ajaran','Asc')->get();
            
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
        $data=Daftarulang::where('id',$id)->delete();
        $detail=Detaildaftarulang::where('daftar_ulang_id',$id)->delete();
    }
}
