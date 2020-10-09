<?php

namespace App\Http\Controllers;
use App\Daftarulang;
use App\Pembayarandaftar;
use App\Pembayaranspp;
use App\Pembayarandonasi;
use App\Keuangan;

use App\Detaildaftarulang;
use App\Siswa;
use App\Spp;
use App\Kelassiswa;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class PembayaranController extends Controller
{
    public function index(request $request,$act=null,$id=null){
        if(Auth::user()['role_id']==3){
            $halaman='Pembayaran Daftar Ulang';
            $link='pembayaran';
            if($act==null){
                $aksi='new';
                $id='new';
            }
            else{
                $aksi=$act;
                $id=$id;
            }
            $data=Pembayarandaftar::where('id',$id)->first();
            return view('pembayaran.index',compact('halaman','link','aksi','id','data'));
        }else{
            return view('error');
        }
    }

    public function index_donasi(request $request,$act=null,$id=null){
        if(Auth::user()['role_id']==3){
            $halaman='Donasi';
            $link='pembayaran_donasi';
            if($act==null){
                $aksi='new';
                $id='new';
            }
            else{
                $aksi=$act;
                $id=$id;
            }
            $data=Pembayarandonasi::where('id',$id)->first();
            return view('pembayaran.index_donasi',compact('halaman','link','aksi','id','data'));
        }else{
            return view('error');
        }
    }

    public function index_spp(request $request,$act=null,$id=null){
        if(Auth::user()['role_id']==3){
            $halaman='Pembayaran Daftar SPP';
            $link='pembayaran_spp';
            if($act==null){
                $aksi='new';
                $id='new';
            }
            else{
                $aksi=$act;
                $id=$id;
            }
            $data=Pembayaranspp::where('id',$id)->first();
            return view('pembayaran.index_spp',compact('halaman','link','aksi','id','data'));
        }else{
            return view('error');
        }
    }
    
    public function rekapan($id=null,$tahun=null,$kelas=null){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Pembayaran Daftar Ulang';
            $link='rekapan/pembayaran';
            return view('pembayaran.rekapan',compact('halaman','link','id','tahun','kelas'));
        }else{
            return view('error');
        }
    }
    public function rekapan_spp($id=null,$tahun=null,$kelas=null){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Pembayaran SPP';
            $link='rekapan_spp/pembayaran_spp';
            $aktif=Kelassiswa::where('tahun_ajaran',$tahun)->where('siswa_id',$id)->where('kelas',$kelas)->where('sts',1)->count();
            $tanggal=Kelassiswa::where('tahun_ajaran',$tahun)->where('siswa_id',$id)->where('kelas',$kelas)->where('sts',1)->first();
            if($aktif>0){
                $not=explode("-",$tanggal['tgl_sts']);
                $tot=$not[1];
            }else{
                $tot=13;
            }
            return view('pembayaran.rekapan_spp',compact('halaman','link','id','tahun','kelas','tot'));
        }else{
            return view('error');
        }
    }

    public function simpan(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->siswa_id) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->kelas) == '') {$error[] = '- Pilih Kelas';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                    $jum=count($request->id);
                    if($jum>0){
                            
                            for($x=0;$x<$jum;$x++){
                                $biaya=preg_replace($patr,'',$request->biayatagihan[$x]);
                                if($biaya==0 || $biaya==null){

                                }else{
                                    $detail                   = new Pembayarandaftar;
                                    $detail->detail_daftar_ulang_id  = $request->id[$x];    
                                    $detail->tahun_ajaran     = $request->tahun_ajaran;    
                                    $detail->siswa_id         = $request->siswa_id;    
                                    $detail->kelas            = $request->kelas;    
                                    $detail->tanggal          = date('Y-m-d H:i:s');    
                                    $detail->biaya            = $biaya; 
                                    $detail->tagihan            = $request->tagihan[$x];     
                                    $detail->potongan            = $request->potongan[$x];     
                                    $detail->sisa               = ($request->tagihan[$x]-$biaya);     
                                    $detail->save(); 

                                    $name='['.cek_siswa($request->siswa_id)['kode'].' '.cek_siswa($request->siswa_id)['name'].'] Daftar Ulang ';
                                    $kategori=2;
                                    $sts=1;
                                    simpan_keuangan($detail['id'],$name,$biaya,$kategori,$sts,$request->siswa_id,$request->tahun_ajaran);
                                }
                            }

                            echo'ok';
                       
                    } 
                    else{
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Pembayaran anda sudah dilunaskan</p>';
                    }   
            }
        }else{
            
        }
    }

    public function simpan_donasi(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->name) == '') {$error[] = '- Isi nama pemberi donasi';}
            if (trim($request->biaya) == '') {$error[] = '- Masukan nilai yang didonasikan';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $biaya=preg_replace($patr,'',$request->biaya);

                $detail                   = new Pembayarandonasi;
                $detail->name             = $request->name;    
                $detail->tanggal          = date('Y-m-d H:i:s');    
                $detail->biaya            = $biaya; 
                $detail->save(); 
                if($detail){
                    echo'ok';
                }        
            }
        }else{
            
        }
    }

    public function simpan_spp(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->siswa_id) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($request->kelas) == '') {$error[] = '- Pilih Kelas';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                    $jum=count($request->bulan);
                    if($jum>0){
                            
                            for($x=0;$x<$jum;$x++){
                                $biaya=preg_replace($patr,'',$request->biayatagihan[$x]);
                                if($biaya==0 || $biaya=='null'){

                                }else{
                                    $detail                   = new Pembayaranspp;
                                    $detail->bulan            = $request->bulan[$x];    
                                    $detail->tahun_ajaran     = $request->tahun_ajaran;    
                                    $detail->siswa_id         = $request->siswa_id;    
                                    $detail->diskon           = $request->diskon[$x];    
                                    $detail->kelas            = $request->kelas;    
                                    $detail->tanggal          = date('Y-m-d H:i:s');    
                                    $detail->biaya            = $biaya; 
                                    $detail->tagihan            = $request->tagihan[$x];     
                                    $detail->sisa               = ($request->tagihan[$x]-$biaya);     
                                    $detail->save(); 

                                    $name='['.cek_siswa($request->siswa_id)['kode'].' '.cek_siswa($request->siswa_id)['name'].'] SPP '.bulan(bln($request->bulan[$x])).'';
                                    $kategori=1;
                                    $sts=1;
                                    simpan_keuangan($detail['id'],$name,$biaya,$kategori,$sts,$request->siswa_id,$request->tahun_ajaran);
                                }
                            }

                            echo'ok';
                       
                    } 
                    else{
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Pembayaran anda sudah dilunaskan</p>';
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
                $hapus=Detaildaftarulang::where('daftar_ulang_id',$id)->delete();
                
                $data                   = Daftarulang::find($id);
                $data->biaya            = preg_replace($patr,'',$request->totalbiaya);    
                $data->save(); 

                        
                if($data){
                    if($jum>0){
                        for($x=0;$x<$jum;$x++){
                            $detail                   = new Detaildaftarulang;
                            $detail->daftar_ulang_id  = $id;    
                            $detail->name             = $request->name[$x];    
                            $detail->tahun_ajaran     = $request->tahun_ajaran;    
                            $detail->kelas            = $request->kelas;    
                            $detail->biaya            = preg_replace($patr,'',$request->biaya[$x]);  
                            $detail->tagihan          = $request->tagihan[$x]-preg_replace($patr,'',$request->biaya[$x]);  
                            $detail->sisa             = $request->tagihan[$x]-preg_replace($patr,'',$request->biaya[$x]);  
                            $detail->save(); 
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

    public function simpan_ubah_donasi(request $request,$id){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            if (trim($request->name) == '') {$error[] = '- Isi nama pemberi donasi';}
            if (trim($request->biaya) == '') {$error[] = '- Masukan nilai yang didonasikan';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $biaya=preg_replace($patr,'',$request->biaya);

                $detail                   = Pembayarandonasi::find($id);
                $detail->name             = $request->name;    
                $detail->biaya            = $biaya; 
                $detail->save(); 

                if($detail){
                    echo'ok';
                }
                           
            }
        }else{
            
        }
    }
    public function api(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
           
            $data=Pembayarandaftar::orderBy('tahun_ajaran','Asc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "nama_biaya" =>cek_pembayaran_daftar($o['detail_daftar_ulang_id'])['name'],
                    "tahun_ajaran" =>$o['tahun_ajaran'],
                    "tanggal" =>$o['tanggal'],
                    "kelas" =>'Kelas '.$o['kelas'],
                    "biaya" =>uang($o['biaya'])
                );
            }
        }

        echo json_encode($show);
        
    }

    public function api_spp(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
           
            $data=Pembayaranspp::orderBy('id','Desc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "kode" =>'<b>['.cek_siswa($o['siswa_id'])['kode'].']</b> '.cek_siswa($o['siswa_id'])['name'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "bulan" =>bulan(bln($o['bulan'])),
                    "tahun_ajaran" =>$o['tahun_ajaran'],
                    "tanggal" =>$o['tanggal'],
                    "kelas" =>'Kelas '.$o['kelas'],
                    "biaya" =>uang($o['biaya'])
                );
            }
        }

        echo json_encode($show);
        
    }

    public function api_donasi(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
           
            $data=Pembayarandonasi::orderBy('id','Desc')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "name" =>$o['name'],
                    "tanggal" =>$o['tanggal'],
                    "biaya" =>uang($o['biaya'])
                );
            }
        }

        echo json_encode($show);
        
    }

    public function cari_nik($id){
        $data=Kelassiswa::where('siswa_id',$id)->orderBy('id','desc')->firstOrFail();

        echo $data['kelas'].'_'.$data['tahun_ajaran'].'@';
        $rinci=Detaildaftarulang::where('kelas',$data['kelas'])->where('tahun_ajaran',$data['tahun_ajaran'])->get();
        
        echo'<tr>
                <th width="7%">No</th>
                <th>Nama Pembayaran </th>
                <th width="20%">Biaya</th>
                <th width="20%">Tagihan</th>
            </tr>';
        foreach($rinci as $no=>$rin){
            $potongan=($rin['biaya']*cek_beasiswa_daftar($id,$data['tahun_ajaran'],$rin['id']))/100;
            if(($rin['biaya']-sisa_pembayaran_daftar($rin['id'],$id,$data['tahun_ajaran'],$data['kelas']))==0){
                echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;">'.($no+1).'</td>
                        <td class="ttdd">'.$rin['name'].'</td>
                        <td class="ttdd"><i>Lunas</i></td>
                        <td class="ttdd"><input class="form-cont"  type="number" readonly style="background:aqua" id="tagihan'.$no.'"  value="'.($rin['biaya']-sisa_pembayaran_daftar($rin['id'],$id,$data['tahun_ajaran'],$data['kelas'])).'"></td>
                    </tr>';
            }else{
                echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;">'.($no+1).'  <input  type="hidden"  name="id[]" value="'.$rin['id'].'"></td>
                        <td class="ttdd">'.$rin['name'].'</td>
                        <td class="ttdd"><input class="form-cont" onkeyup="cek_biaya('.$no.',this.value)" type="number"  name="biayatagihan[]" id="biayatagihan'.$no.'"  value="0"></td>
                        <td class="ttdd">
                            <input class="form-cont"  type="number" readonly style="background:aqua" id="tagihan'.$no.'" name="tagihan[]" value="'.(($rin['biaya']-sisa_pembayaran_daftar($rin['id'],$id,$data['tahun_ajaran'],$data['kelas']))-$potongan).'">
                            <input class="form-cont"  type="hidden" readonly style="background:aqua" id="potongan[]" name="potongan[]" value="'.$potongan.'">
                        </td>
                    </tr>';
            }

        }
    }

    public function cari_nik_spp($id){
        $data=Kelassiswa::where('siswa_id',$id)->orderBy('id','desc')->firstOrFail();

        echo $data['kelas'].'_'.$data['tahun_ajaran'].'@';
        
        
        echo'<tr>
                <th width="7%">No</th>
                <th>Bulan </th>
                <th width="20%">Biaya</th>
                <th width="20%">Tagihan</th>
            </tr>';
        $aktif=Kelassiswa::where('tahun_ajaran',$data['tahun_ajaran'])->where('siswa_id',$id)->where('kelas',$data['kelas'])->where('sts',1)->count();
        $tanggal=Kelassiswa::where('tahun_ajaran',$data['tahun_ajaran'])->where('siswa_id',$id)->where('kelas',$data['kelas'])->where('sts',1)->first();
        if($aktif>0){
            $not=explode("-",$tanggal['tgl_sts']);
            $tot=$not[1];
        }else{
            $tot=13;
        }
        for($x=1;$x<13;$x++){
            $rin=Spp::where('tahun_ajaran',$data['tahun_ajaran'])->where('kelas',$data['kelas'])->first();
            
            
            $tagihan=($rin['biaya']*beasiswa($id,$data['tahun_ajaran'],$x))/100;
            if((($rin['biaya']-sisa_pembayaran_spp($x,$id,$data['tahun_ajaran'],$data['kelas']))-$tagihan)==0){
                echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;">'.$x.'</td>
                        <td class="ttdd">'.bulan(bln($x)).'</td>
                        <td class="ttdd"><i>Lunas</i></td>
                        <td class="ttdd"><input class="form-cont"  type="number" readonly style="background:aqua"  value="'.(($rin['biaya']-sisa_pembayaran_spp($x,$id,$data['tahun_ajaran'],$data['kelas']))-$tagihan).'"></td>
                    </tr>';

            }else{
               if($x>=$tot){
                    echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;">'.$x.'</td>
                        <td class="ttdd">'.bulan(bln($x)).'</td>
                        <td class="ttdd"><i>Nonaktif</i></td>
                        <td class="ttdd"><input class="form-cont"  type="number" readonly style="background:aqua"  value="'.(($rin['biaya']-sisa_pembayaran_spp($x,$id,$data['tahun_ajaran'],$data['kelas']))-$tagihan).'"></td>
                    </tr>';
               }else{

               
                    echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;">'.$x.' <input  type="hidden"  name="diskon[]" value="'.beasiswa($id,$data['tahun_ajaran'],$x).'"><input  type="hidden"  name="bulan[]" value="'.$x.'"></td>
                        <td class="ttdd">'.bulan(bln($x)).'</td>
                        <td class="ttdd"><input class="form-cont" onkeyup="cek_biaya('.$x.',this.value)" type="number"  name="biayatagihan[]" id="biayatagihan'.$x.'"  value="0"></td>
                        <td class="ttdd"><input class="form-cont"  type="number" readonly style="background:aqua" id="tagihan'.$x.'" name="tagihan[]" value="'.(($rin['biaya']-$tagihan)-sisa_pembayaran_spp($x,$id,$data['tahun_ajaran'],$data['kelas'])).'"></td>
                    </tr>';
                }
            }
            
        }
    }
    public function hapus($id){
        $detail=Pembayarandaftar::where('id',$id)->delete();
    }
    public function hapus_spp($id){
        $detail=Pembayaranspp::where('id',$id)->delete();
        $keuangan=Keuangan::where('transaksi_id',$id)->where('kategori',1)->delete();
    }
    public function hapus_donasi($id){
        $detail=Pembayarandonasi::where('id',$id)->delete();
    }
}
