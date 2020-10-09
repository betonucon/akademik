<?php

namespace App\Http\Controllers;
use App\Siswa;
use App\Beasiswa;
use App\Beasiswadaftar;
use App\Detaildaftarulang;
use App\Kelassiswa;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class SiswaController extends Controller
{
    public function index(request $request){
        if(Auth::user()['role_id']==1){
            if($request->kat==''){
                $kat='all';
            }else{
                $kat=$request->kat;
            }
            $halaman='Daftar siswa';
            $link='siswa';
            return view('siswa.index',compact('halaman','link','kat'));
        }else{
            return view('error');
        }
    }

    public function index_beasiswa(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            $halaman='Daftar Beasiswa Siswa';
            $link='beasiswa';
            if($request->act==null){
                $aksi='new';
                $data=0; 
            }
            else{
                $aksi=$request->act;
                $data=Beasiswa::where('siswa_id',$request->id)->where('tahun_ajaran',$request->tahun)->orderBy('id','desc')->firstOrFail();
            }
            
            return view('siswa.index_beasiswa',compact('halaman','link','aksi','data'));
        }else{
            return view('error');
        }
    }

    public function cari_daftar_ulang($tahun){
        $rinci=Detaildaftarulang::where('tahun_ajaran',$tahun)->get();
        
        echo'<tr>
                <th width="7%">No</th>
                <th>Nama Pembayaran </th>
                <th width="20%">Persen</th>
            </tr>';
        foreach($rinci as $no=>$rin){
            echo'
                    <tr>
                        <td class="ttdd" style="display:inline;padding:5px;"> <input  type="hidden"  name="id[]" value="'.$rin['id'].'"></td>
                        <td class="ttdd">'.$rin['name'].'</td>
                        <td class="ttdd">
                            <input class="form-cont"  type="number"   value="0" name="persen[]">
                        </td>
                    </tr>';
            

        }
    }

    public function index_beasiswa_daftar(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            $halaman='Daftar Beasiswa Siswa Daftar Ulang';
            $link='beasiswa_daftar';
            if($request->act==null){
                $aksi='new';
                $data=0; 
            }
            else{
                $aksi=$request->act;
                $data=Beasiswadaftar::where('siswa_id',$request->id)->where('tahun_ajaran',$request->tahun)->orderBy('id','desc')->firstOrFail();
            }
            
            return view('siswa.index_beasiswa_daftar',compact('halaman','link','aksi','data'));
        }else{
            return view('error');
        }
    }
    public function index_online(request $request){
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

    public function index_kelas(request $request){
        if(Auth::user()['role_id']==1){
            
            if($request->kelas==''){
                $kelas='all';
                $tahun=date('Y').'-'.(date('Y')+1);
            }else{
                $kelas=$request->kelas;
                $tahun=$request->tahun;
            }
            $halaman='Daftar siswa';
            $link='siswa/kelas';
            return view('siswa.index_kelas',compact('halaman','link','kelas','tahun'));
        }else{
            return view('error');
        }
    }

    public function view($id){
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==2 ){
            
            $halaman='Riwayat Kelas';
            $link='siswa/view';
            if(Auth::user()['role_id']==1){
                $batal='siswa';
            }else{
                $batal='siswa/profil';
            }
            
            $data=Siswa::where('id',$id)->first();
            return view('siswa.index_riwayat_kelas',compact('halaman','link','batal','data'));
        }else{
            return view('error');
        }
    }

    
    public function tambah(request $request){
        if(Auth::user()['role_id']==1){
            $halaman='Tambah Siswa';
            $link='siswa/tambah';
            $batal='siswa';
            return view('siswa.tambah',compact('halaman','link','batal'));
        }else{
            return view('error');
        }
    }

    public function ubah(request $request,$id){
        if(Auth::user()['role_id']==1){
            $halaman='Tambah Siswa';
            $link='siswa/tambah';
            $batal='siswa';
            $data=Siswa::where('id',$id)->first();
            return view('siswa.ubah',compact('halaman','link','data','batal'));
        }else{
            return view('error');
        }
    }

    

    public function cari_siswa($id){
        echo'<option value="">Pilih</option>';
        foreach(cari_siswa($id) as $o){
            echo'<option value="'.$o['siswa_id'].'">['.cek_siswa($o['siswa_id'])['kode'].'] '.cek_siswa($o['siswa_id'])['name'].'</option>';
        }
    }
    public function profil(){
        if(Auth::user()['role_id']==0 || Auth::user()['role_id']==2){
            $halaman='Profil';
            $link='siswa/profil';
            $batal='siswa/profil';
            $data=Siswa::where('kode',Auth::user()['nik'])->first();
            return view('siswa.profil',compact('halaman','link','data','batal'));
        }else{
            return view('error');
        }
    }

    public function hapus_file($id){
        $data           =Siswa::find($id);
        $data->file     =null;
        $data->save();
    }

    public function hapus(request $request){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            $jum=count($request->id);

            for($x=0;$x<$jum;$x++){
                $data=Kelassiswa::where('id',$request->no[$x])->delete();
            }
           
        }
    }

    public function simpan(request $request){
        if(Auth::user()['role_id']==1){
            if (trim($request->name) == '') {$error[] = '- Nama  harus diisi';}
            if (trim($request->alamat) == '') {$error[] = '- Alamat  harus diisi';} 
            if (trim($request->tempat_lahir) == '') {$error[] = '- Tempat lahir  harus diisi';} 
            if (trim($request->tgl_lahir) == '') {$error[] = '- Tanggal lahir  harus diisi';} 
            if (trim($request->jenis_kelamin) == '') {$error[] = '- Jenis Kelamin  harus diisi';} 
            if (trim($request->agama) == '') {$error[] = '- Agama  harus diisi';} 
            if (trim($request->tahun_angkatan) == '') {$error[] = '- Tahun Masuk  harus diisi';} 
            if (trim($request->no_hp) == '') {$error[] = '- Nomor Handphone  harus diisi';} 
            if (trim($request->file) == '') {$error[] = '- Upload foto siswa';} 
            if (trim($request->kewarganegaraan) == '') {$error[] = '- Pilih Kewarganegaraan';} 
            if (trim($request->anak_ke) == '') {$error[] = '- Isi keterangan Anak Keberapa';} 
            if (trim($request->jumlah_sodara) == '') {$error[] = '- Isi keterangan Jumlah Sodara';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $cek=explode('/',$_FILES['file']['type']);
                $file_tmp=$_FILES['file']['tmp_name'];
                $file=explode('.',$_FILES['file']['name']);
                $filename=kode($request->tahun_angkatan).'.'.$cek[1];
                $lokasi='public/_file_upload/';
                $cek=User::where('email',$request->email)->count();
                    if($cek>0){
                        echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />- Email Sudah terdaftar</p>';
                    }else{
                        if(move_uploaded_file($file_tmp, $lokasi.$filename)){
                            $data                   = new Siswa;
                            $data->tempat_lahir     = $request->tempat_lahir;    
                            $data->name             = $request->name;    
                            $data->jenis_kelamin    = $request->jenis_kelamin;    
                            $data->tgl_lahir        = $request->tgl_lahir;    
                            $data->jumlah_sodara    = $request->jumlah_sodara;    
                            $data->anak_ke          = $request->anak_ke;    
                            $data->agama            = $request->agama;    
                            $data->alamat           = $request->alamat;    
                            $data->file             = $filename;    
                            $data->tahun_angkatan   = $request->tahun_angkatan;    
                            $data->kode             = kode($request->tahun_angkatan);    
                            $data->kewarganegaraan  = $request->kewarganegaraan;    
                            $data->sts              = 1;    
                            $data->sts_penerimaan   = 1;    
                            $data->sts_kelas   = 0;    
                            $data->save(); 

                            if($data){
                                $user                   = new User;
                                $user->nik              = kode($request->tahun_angkatan);    
                                $user->name             = $request->name;    
                                $user->no_hp             = $request->no_hp;    
                                $user->email            = $request->email;    
                                $user->role_id          = 2;    
                                $user->tahun_angkatan   = $request->tahun_angkatan;    
                                $user->password         = Hash::make(kode($request->tahun_angkatan));    
                                $user->save(); 
                                if($user){
                                    echo'ok';
                                }
                            }
                        }
                
                    }
            }
        }else{
            
        }
    }

    public function simpan_beasiswa(request $request){
        if(Auth::user()['role_id']==3){
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih tahun ajaran';}
            if (trim($request->siswa_id) == '') {$error[] = '- Nama Siswa  harus diisi';}
            if (trim($request->persen) == '') {$error[] = '- Persen  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $hapus=Beasiswa::where('siswa_id',$request->siswa_id)->where('tahun_ajaran',$request->tahun_ajaran)->delete();        
                $jum=count($request->bulan);
                for($x=0;$x<$jum;$x++){
                        
                    $data                       = new Beasiswa;
                    $data->siswa_id             = $request->siswa_id;    
                    $data->persen               = $request->persen;    
                    $data->tahun_ajaran         = $request->tahun_ajaran;    
                    $data->bulan                = $request->bulan[$x];    
                    $data->save(); 
                }
                 
                echo'ok';
                 
                            
                
            }
        }else{
            
        }
    }
    public function simpan_beasiswa_daftar(request $request){
        if(Auth::user()['role_id']==3){
            if (trim($request->tahun_ajaran) == '') {$error[] = '- Pilih tahun ajaran';}
            if (trim($request->siswa_id) == '') {$error[] = '- Nama Siswa  harus diisi';}
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $hapus=Beasiswadaftar::where('siswa_id',$request->siswa_id)->where('tahun_ajaran',$request->tahun_ajaran)->delete();        
                $jum=count($request->id);
                for($x=0;$x<$jum;$x++){
                    if(is_null($request->persen[$x]) || $request->persen[$x]==0){

                    }else{
                        $data                       = new Beasiswadaftar;
                        $data->siswa_id             = $request->siswa_id;    
                        $data->persen               = $request->persen[$x];    
                        $data->tahun_ajaran         = $request->tahun_ajaran;    
                        $data->detail_daftar_ulang_id                = $request->id[$x];    
                        $data->save(); 
                    }
                    
                }
                
                 
                echo'ok';
                 
                            
                
            }
        }else{
            
        }
    }

    public function simpan_ubah_beasiswa(request $request,$id){
        if(Auth::user()['role_id']==3){
            if (trim($request->persen) == '') {$error[] = '- Persen  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                
                    
                $hapus=Beasiswa::where('siswa_id',$request->siswa_id)->where('tahun_ajaran',$request->tahun_ajaran)->delete();        
                $jum=count($request->bulan);
                for($x=0;$x<$jum;$x++){
                     
                      
                        $data                       = new Beasiswa;
                        $data->siswa_id             = $request->siswa_id;    
                        $data->persen               = $request->persen;    
                        $data->tahun_ajaran         = $request->tahun_ajaran;    
                        $data->bulan                = $request->bulan[$x];    
                        $data->save(); 
                      
                }
                 
                echo'ok';
                            
                
                    
            }
        }else{
            
        }
    }

    public function simpan_ubah(request $request,$id){
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==0){
            if (trim($request->name) == '') {$error[] = '- Nama  harus diisi';}
            if (trim($request->alamat) == '') {$error[] = '- Alamat  harus diisi';} 
            if (trim($request->tempat_lahir) == '') {$error[] = '- Tempat lahir  harus diisi';} 
            if (trim($request->tgl_lahir) == '') {$error[] = '- Tanggal lahir  harus diisi';} 
            if (trim($request->jenis_kelamin) == '') {$error[] = '- Jenis Kelamin  harus diisi';} 
            if (trim($request->agama) == '') {$error[] = '- Agama  harus diisi';} 
            if (trim($request->tahun_angkatan) == '') {$error[] = '- Tahun Masuk  harus diisi';} 
            if (trim($request->kewarganegaraan) == '') {$error[] = '- Pilih Kewarganegaraan';} 
            if (trim($request->anak_ke) == '') {$error[] = '- Isi keterangan Anak Keberapa';} 
            if (trim($request->jumlah_sodara) == '') {$error[] = '- Isi keterangan Jumlah Sodara';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                if($request->fileedit==''){
                    if($request->file==''){
                            echo'sdasdasd';
                    }else{
                        $cek=explode('/',$_FILES['file']['type']);
                        $file_tmp=$_FILES['file']['tmp_name'];
                        $file=explode('.',$_FILES['file']['name']);
                        $filename=$request->kode.'.'.$cek[1];
                        $lokasi='public/_file_upload/';
                        
                        if(move_uploaded_file($file_tmp, $lokasi.$filename)){
                            $data                   = Siswa::find($id);
                            $data->tempat_lahir     = $request->tempat_lahir;    
                            $data->jenis_kelamin    = $request->jenis_kelamin;    
                            $data->tgl_lahir        = $request->tgl_lahir;    
                            $data->jumlah_sodara    = $request->jumlah_sodara;    
                            $data->anak_ke          = $request->anak_ke;    
                            $data->agama            = $request->agama;    
                            $data->alamat           = $request->alamat;    
                            $data->file             = $filename;    
                            $data->tahun_angkatan   = $request->tahun_angkatan;    
                            $data->kewarganegaraan  = $request->kewarganegaraan;    
                            $data->save(); 

                            if($data){
                                
                                    echo'ok';
                                
                            }
                        }
                    }
                        
                }else{
                        $data                   = Siswa::find($id);
                        $data->tempat_lahir     = $request->tempat_lahir;    
                        $data->jenis_kelamin    = $request->jenis_kelamin;    
                        $data->tgl_lahir        = $request->tgl_lahir;    
                        $data->jumlah_sodara    = $request->jumlah_sodara;    
                        $data->anak_ke          = $request->anak_ke;    
                        $data->agama            = $request->agama;    
                        $data->alamat           = $request->alamat;    
                        $data->tahun_angkatan   = $request->tahun_angkatan;    
                        $data->kewarganegaraan  = $request->kewarganegaraan;    
                        
                        $data->save(); 

                        if($data){
                            
                                echo'ok';
                        
                        }
                }
            }
        }else{
            
        }
    }

    public function simpan_ubah_profil(request $request,$id){
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==0){
            if (trim($request->alamat) == '') {$error[] = '- Alamat  harus diisi';} 
            if (trim($request->tempat_lahir) == '') {$error[] = '- Tempat lahir  harus diisi';} 
            if (trim($request->tgl_lahir) == '') {$error[] = '- Tanggal lahir  harus diisi';} 
            if (trim($request->jenis_kelamin) == '') {$error[] = '- Jenis Kelamin  harus diisi';} 
            if (trim($request->agama) == '') {$error[] = '- Agama  harus diisi';} 
            if (trim($request->tahun_angkatan) == '') {$error[] = '- Tahun Masuk  harus diisi';} 
            if (trim($request->kewarganegaraan) == '') {$error[] = '- Pilih Kewarganegaraan';} 
            if (trim($request->anak_ke) == '') {$error[] = '- Isi keterangan Anak Keberapa';} 
            if (trim($request->jumlah_sodara) == '') {$error[] = '- Isi keterangan Jumlah Sodara';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                if($request->fileedit==''){
                    if($request->file==''){
                            echo'sdasdasd';
                    }else{
                        $cek=explode('/',$_FILES['file']['type']);
                        $file_tmp=$_FILES['file']['tmp_name'];
                        $file=explode('.',$_FILES['file']['name']);
                        $filename=$request->kode.'.'.$cek[1];
                        $lokasi='public/_file_upload/';
                        
                        if(move_uploaded_file($file_tmp, $lokasi.$filename)){
                            $data                   = Siswa::find($id);
                            $data->tempat_lahir     = $request->tempat_lahir;    
                            $data->jenis_kelamin    = $request->jenis_kelamin;    
                            $data->tgl_lahir        = $request->tgl_lahir;    
                            $data->jumlah_sodara    = $request->jumlah_sodara;    
                            $data->anak_ke          = $request->anak_ke;    
                            $data->agama            = $request->agama;    
                            $data->alamat           = $request->alamat;    
                            $data->file             = $filename;    
                            $data->tahun_angkatan   = $request->tahun_angkatan;    
                            $data->kewarganegaraan  = $request->kewarganegaraan;  
                            $data->sts              = 1;      
                            $data->save(); 

                            if($data){
                                
                                    echo'ok';
                                
                            }
                        }
                    }
                        
                }else{
                        $data                   = Siswa::find($id);
                        $data->tempat_lahir     = $request->tempat_lahir;    
                        $data->jenis_kelamin    = $request->jenis_kelamin;    
                        $data->tgl_lahir        = $request->tgl_lahir;    
                        $data->jumlah_sodara    = $request->jumlah_sodara;    
                        $data->anak_ke          = $request->anak_ke;    
                        $data->agama            = $request->agama;    
                        $data->alamat           = $request->alamat;    
                        $data->tahun_angkatan   = $request->tahun_angkatan;    
                        $data->kewarganegaraan  = $request->kewarganegaraan; 
                        $data->sts              = 1;       
                        $data->save(); 

                        if($data){
                            
                                echo'ok';
                        
                        }
                }
            }
        }else{
            
        }
    }

    public function simpan_kelas(request $request,$tahun,$kelas){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
           
            if (trim($tahun) == '0') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($kelas) == '0') {$error[] = '- Pilih Kelas Tujuan';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $jum=count($request->id);
                if($jum==0){
                    echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br /> - Pilih siswa yang akan dirposes</p>';
                }else{
                    for($x=0;$x<$jum;$x++){
                        $cekdata=Kelassiswa::where('siswa_id',$request->id[$x])->where('tahun_ajaran',$tahun)->where('kelas_id',$kelas)->where('kelas',cek_kelas($kelas)['kelas'])->count();
                        if($cekdata>0){

                        }else{

                            $data                   = new Kelassiswa;
                            $data->siswa_id         = $request->id[$x];    
                            $data->tahun_angkatan   = $request->tahun_angkatan[$x];    
                            $data->tahun_ajaran     = $tahun;    
                            $data->kelas_id         = $kelas;    
                            $data->kelas            = cek_kelas($kelas)['kelas'];    
                            $data->save();
                            
                            if($data){
                                $sis                   = Siswa::find($request->id[$x]);
                                $sis->sts_kelas        = 1;    
                                $sis->save();
                            }
                        }
                    }

                    echo'ok';
                }
                       
            }
        }else{
           echo'asdasda'; 
        }
    }

    public function simpan_approve(request $request,$sts){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
           
            if (trim($tahun) == '0') {$error[] = '- Pilih Tahun Ajaran';}
            if (trim($kelas) == '0') {$error[] = '- Pilih Kelas Tujuan';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $jum=count($request->id);
                
                    for($x=0;$x<$jum;$x++){
                        
                            $data                   = Siswa::find($request->id[$x]);
                            $data->sts_penerimaan   = $sts;    
                            $data->save(); 

                            if($sts==1){
                                $sst=2;
                            }else{
                                $sst=0; 
                            }
                            if($data){
                                $usr                   = User::where('nik',$request->kode[$x])->first();
                                $usr->role_id              = $sst;    
                                $usr->save(); 
                            }
                        
                    }

                    echo'ok';
                
                       
            }
        }else{
           
        }
    }

    public function api($kat){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            if($kat=='all'){
                $data=Siswa::where('sts_penerimaan',1)->where('sts_kelas',0)->orderBy('name','Asc')->get();
            }else{
                $data=Siswa::where('sts_penerimaan',1)->where('sts_kelas',0)->where('tahun_angkatan',$kat)->orderBy('name','Asc')->get();
            }
            
            foreach($data as $o){
            
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
                    "kelas"=>cek_kelas_akhir($o['id']),
                );
            }
        }

        echo json_encode($show);
        
    }

    public function api_beasiswa(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            
            $data=Beasiswa::select('siswa_id','tahun_ajaran','persen')->groupBy('siswa_id')->groupBy('persen')->groupBy('tahun_ajaran')->get();
            
            foreach($data as $o){
            
                $show[]=array(
                    "siswa_id" =>$o['siswa_id'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "persen" =>$o['persen'],
                    "tahun_ajaran" =>$o['tahun_ajaran'],
                    "bulan" =>bulan_beasiswa($o['siswa_id'],$o['tahun_ajaran']),
                );
            }
        }

        echo json_encode($show);
        
    }
    public function api_beasiswa_daftar(){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            
            $data=Beasiswadaftar::all();
            
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "siswa_id" =>$o['siswa_id'],
                    "tahun_ajaran" =>$o['tahun_ajaran'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "nama_pembayaran" =>cek_pembayaran_daftar($o['detail_daftar_ulang_id'])['name'],
                    "persen" =>$o['persen']
                );
            }
        }

        echo json_encode($show);
        
    }

    
    public function api_online($kat){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            if($kat=='all'){
                $data=Siswa::where('sts',1)->where('sts_penerimaan',0)->orderBy('name','Asc')->get();
            }else{
                $data=Siswa::where('sts',1)->where('sts_penerimaan',0)->where('tahun_angkatan',$kat)->orderBy('name','Asc')->get();
            }
            
            foreach($data as $o){
            
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
                    "kelas"=>cek_kelas_akhir($o['id'])
                );
            }
        }else{
            echo'asdasd';
        }

        echo json_encode($show);
        
    }

    public function api_keluar(){
        error_reporting(0);
        if(Auth::user()['role_id']==1){
            $data=Anggota::where('sts',2)->orderBy('nama','Asc')->get();
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "nik" =>$o['nik'],
                    "nama" =>$o['nama'],
                    "alamat"=>$o['alamat'],
                    "tgl_lahir"=>$o['tgl_lahir'],
                    "email"=>cek_user($o['nik'])['email'],
                    "tgl_anggota"=>$o['tgl_anggota'],
                    "tgl_keluar"=>$o['update']
                );
            }
        }

        echo json_encode($show);
        
    }

    public function api_kelas($tahun,$kelas){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($kelas=='all'){
                $data=Kelassiswa::where('tahun_ajaran',$tahun)->orderBy('id','Asc')->get();
            }else{
                $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas_id',$kelas)->orderBy('id','Asc')->get();
            }
            foreach($data as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "siswa_id" =>$o['siswa_id'],
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "tahun_angkatan" =>cek_siswa($o['siswa_id'])['tahun_angkatan'],
                    "kelas"=>$o['kelas'],
                    "sts"=>$o['sts'],
                    "tahun_ajaran"=>$o['tahun_ajaran'],
                    "nama_kelas"=>cek_kelas($o['kelas_id'])['name']
                );
            }
        }

        echo json_encode($show);
        
    }

    public function api_riwayat($id){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==2){
           
            $cek=Kelassiswa::where('siswa_id',$id)->count();
            $data=Kelassiswa::where('siswa_id',$id)->orderBy('id','Asc')->get();
            if($cek>0){
                foreach($data as $no=>$o){
                
                    $show[]=array(
                        "no" =>($no+1),
                        "id" =>$o['id'],
                        "kode" =>cek_siswa($o['siswa_id'])['kode'],
                        "name" =>cek_siswa($o['siswa_id'])['name'],
                        "tahun_angkatan" =>cek_siswa($o['siswa_id'])['tahun_angkatan'],
                        "kelas"=>$o['kelas'],
                        "tahun_ajaran"=>$o['tahun_ajaran'],
                        "nama_kelas"=>cek_kelas($o['kelas_id'])['name']
                    );
                }
            }else{
                $show[]=array(
                    "no" =>'',
                    "id" =>'',
                    "kode" =>'',
                    "name" =>'',
                    "tahun_angkatan" =>'',
                    "kelas"=>'',
                    "tahun_ajaran"=>'',
                    "nama_kelas"=>''
                );
            }
        }

        echo json_encode($show);
        
    }

    public function pdf(request $request){
        $jum=count($request->id);
        
        for($x=0;$x<$jum;$x++){
                
                   echo $request->id[$x].',';
               
        }
        
    }

    public function cetak(request $request){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        $data=Anggota::whereIn('id',$in)->get();

        $pdf = PDF::loadView('anggota.pdf', ['data'=>$data]);
        return $pdf->stream();
        
    }

    public function cetak_kelas($tahun,$kelas){
       
        error_reporting(0);
        if($kelas=='all'){
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->orderBy('id','Asc')->get();
        }else{
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas_id',$kelas)->orderBy('id','Asc')->get();
        }
        $pdf = PDF::loadView('siswa.kelas_pdf', ['data'=>$data,'tahun'=>$tahun,'kelas'=>$kelas]);
        return $pdf->stream();
        
    }
    
    public function cetak_display(request $request){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        $data=Anggota::whereIn('id',$in)->orderBy('jabatan_id','asc')->get();

        $pdf = PDF::loadView('anggota.pdf_display', ['data'=>$data]);
        return $pdf->stream();
        
    }
    public function cetak_keluar(request $request){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        $data=Anggota::whereIn('id',$in)->orderBy('jabatan_id','asc')->get();

        $pdf = PDF::loadView('anggota.pdf_keluar', ['data'=>$data]);
        return $pdf->stream();
        
    }

    public function hapus_beasiswa($id){
        $data=Beasiswa::where('id',$id)->delete();
    }
    public function hapus_beasiswa_daftar($id){
        $data=Beasiswadaftar::where('id',$id)->delete();
    }
    public function ubah_status($id){
        $data       =Kelassiswa::find($id);
        $data->sts  =1;
        $data->tgl_sts  =date('Y-m-d');
        $data->save();
    }
    public function ubah_status_aktif($id){
        $data       =Kelassiswa::find($id);
        $data->sts  =null;
        $data->tgl_sts  =null;
        $data->save();
    }
}
