<?php

function bulan($bulan)
{
   Switch ($bulan){
      case '01' : $bulan="Januari";
         Break;
      case '02' : $bulan="Februari";
         Break;
      case '03' : $bulan="Maret";
         Break;
      case '04' : $bulan="April";
         Break;
      case '05' : $bulan="Mei";
         Break;
      case '06' : $bulan="Juni";
         Break;
      case '07' : $bulan="Juli";
         Break;
      case '08' : $bulan="Agustus";
         Break;
      case '09' : $bulan="September";
         Break;
      case 10 : $bulan="Oktober";
         Break;
      case 11 : $bulan="November";
         Break;
      case 12 : $bulan="Desember";
         Break;
      }
   return $bulan;
}

function bln($id){
   if($id>9){
      $data=$id;
   }else{
      $data='0'.$id; 
   }

   return $data;
}
function detail_kelas($id){
   $data=App\Kelas::where('kelas',$id)->get();
   return $data;
}
function foto($id){
   $data=App\Siswa::where('kode',$id)->first();

   if($data['file']==''){
      $foto='akun.png';
   }else{
      $foto=$data['file'];
   }
   return $foto;
}

function uang($id){
   $data=number_format($id,0);

   return $data;
}

function kode($id){
   $data=App\User::where('tahun_angkatan',$id)->orderBy('id','desc')->firstOrFail();
   $urutan = (int) substr($data['nik'],4,5);
   $urutan++;
   $max=$id. sprintf("%05s", $urutan);
   return $max;
}



function title($id){
   $data=App\Title::where('name',$id)->first();
   $val='<div class="alert alert-dismissible" style="color:#615757;background:#e6e6ec">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i>'.$data['title'].'</h4>
            '.$data['isi'].'
         </div>';
   return $val;
}

function agama(){
   $data=App\Agama::all();
   return $data;
}

function kelas(){
   $data=App\Kelas::orderBy('kelas','Asc')->get();
   return $data;
}

function cek_kelas($id){
   $data=App\Kelas::where('id',$id)->first();
   return $data;
}

function cek_siswa($id){
   $data=App\Siswa::where('id',$id)->orWhere('kode',$id)->first();
   return $data;
}

function bulan_beasiswa($id,$tahun){
   $data=App\Beasiswa::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->get();
   $tam='';
   foreach($data as $o){
      $tam.=bulan(bln($o['bulan'])).',';
   }
    return $tam;     
}

function cek_bulan_beasiswa($id,$tahun,$bulan){
   $data=App\Beasiswa::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('bulan',$bulan)->count();
   
    return $data;     
}
function cek_spp($tahun,$kelas){
   $data=App\Spp::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->first();
   return $data['biaya'];
}

function siswa(){
   $data=App\Siswa::where('sts_kelas',1)->get();
   return $data;
}

function cari_siswa($id){
   $data=App\Kelassiswa::where('tahun_ajaran',$id)->get();
   return $data;
}

function jumlah_siswa($tahun,$kelas){
   $data=App\Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->count();
   return $data;
}

function sts($id){
   if($id==1){
      $data='Masuk';
   }
   if($id==2){
      $data='Keluar';
   }

   return $data;
}
function cek_kelas_akhir($id){
   $cek=App\Kelassiswa::where('siswa_id',$id)->count();
   if($cek>0){
      $data=App\Kelassiswa::where('siswa_id',$id)->orderBy('id','desc')->firstOrFail();
      $tam='<b>'.$data['kelas'].'</b> '. cek_kelas($data['kelas_id'])['name'];
   }else{
      $tam='<i>Murid Baru</i>';
   }
   
   return $tam;
}

function beasiswa($id,$tahun,$bulan){
   $cek=App\Beasiswa::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('bulan',$bulan)->count();
   $data=App\Beasiswa::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('bulan',$bulan)->first();

   if($cek>0){
      $bes=$data['persen'];
   }else{
      $bes=0;
   }

   return $bes;
}
function total_spp_siswa($id,$tahun,$bulan){
  
  
   $data=App\Pembayaranspp::where('siswa_id',$id)->where('bulan',$bulan)->where('tahun_ajaran',$tahun)->sum('biaya');
  
  return $data;
}
function total_keuangan_spp($bulan,$tahun){
   if($bulan=='all'){
      $data=App\Pembayaranspp::whereYear('tanggal',$tahun)->sum('biaya');
  }else{
      $data=App\Pembayaranspp::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->sum('biaya');
  }

  return $data;
}
function total_keuangan_donasi($bulan,$tahun){
   if($bulan=='all'){
      $data=App\Pembayarandonasi::whereYear('tanggal',$tahun)->sum('biaya');
  }else{
      $data=App\Pembayarandonasi::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->sum('biaya');
  }

  return $data;
}

function total_keuangan_daftar($bulan,$tahun){
   if($bulan=='all'){
      $data=App\Pembayarandaftar::whereYear('tanggal',$tahun)->sum('biaya');
  }else{
      $data=App\Pembayarandaftar::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->sum('biaya');
  }

  return $data;
}

function cek_kelas_sekarang($id){
   $cek=App\Kelassiswa::where('siswa_id',$id)->count();
   if($cek>0){
      $data=App\Kelassiswa::where('siswa_id',$id)->orderBy('id','desc')->firstOrFail();
      $tam=$data;
   }else{
      $tam='<i>Murid Baru</i>';
   }
   
   return $tam;
}

function cek_pembayaran_daftar($id){
   $data=App\Detaildaftarulang::where('id',$id)->first();

   return $data;
}
function cek_beasiswa_daftar($id,$tahun,$daftar){
   $cek=App\Beasiswadaftar::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('detail_daftar_ulang_id',$daftar)->count();
   if($cek>0){
      $data=App\Beasiswadaftar::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('detail_daftar_ulang_id',$daftar)->first();
      $nilai=$data['persen'];
   }else{
      $nilai=0;
   }
   return $nilai;
}
function tagihan_tahun_ajaran($tahun,$kelas){
   $data=App\Detaildaftarulang::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->sum('biaya');

   return $data;
}
function tagihan_tahun_ajaran_dibayar($id,$tahun,$kelas){
   $data=App\Pembayarandaftar::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('kelas',$kelas)->sum('biaya');

   return $data;
}
function tagihan_tahun_ajaran_potongan($id,$tahun,$kelas){
   $data=App\Pembayarandaftar::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('kelas',$kelas)->sum('potongan');

   return $data;
}

function total_daftar($tahun,$kelas){
   $data=App\Daftarulang::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->first();
   $bayar=App\Pembayarandaftar::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->where('siswa_id',cek_siswa(Auth::user()['nik'])['id'])->sum('biaya');
   $tagihan=$data['biaya']-$bayar;
   return $tagihan;
}

function siswa_non_aktif($id,$tahun){
   $aktif=App\Kelassiswa::where('tahun_ajaran',$tahun)->where('siswa_id',$id)->where('sts',1)->count();
   $tanggal=App\Kelassiswa::where('tahun_ajaran',$tahun)->where('siswa_id',$id)->where('sts',1)->first();
   if($aktif>0){
      $not=explode("-",$tanggal['tgl_sts']);
      $tot=$not[1];
   }else{
      $tot=13;
   }

   return $tot;
}
function total_spp_persiswa($tahun,$kelas,$x){
   $data=App\Spp::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->first();
   $bayar=App\Pembayaranspp::where('tahun_ajaran',$tahun)->where('bulan',$x)->where('kelas',$kelas)->where('siswa_id',cek_siswa(Auth::user()['nik'])['id'])->sum('biaya');
   $bea=beasiswa(cek_siswa(Auth::user()['nik'])['id'],$tahun,$x);
   $potongan=($data['biaya']*$bea)/100;
   $total=($data['biaya']-$potongan);
   return ($total-$bayar);
}
function get_daftar_ulang($tahun,$kelas){
   $data=App\Detaildaftarulang::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->get();

   return $data;
}

function bl($id){
   if($id>9){
      $data=$id;
   }else{
      $data=substr($id,1);
   }

   return $data;
}
function get_keuangan($tahun,$kelas){
   $data=App\Keuangan::where('tahun_ajaran',$tahun)->where('siswa_id',cek_siswa(Auth::user()['nik'])['id'])->orderBy('tanggal','Desc')->get();

   return $data;
}

function get_pembayaran_daftar_ulang($id,$tahun,$kelas){
   $data=App\Pembayarandaftar::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('kelas',$kelas)->orderby('tanggal','asc')->get();

   return $data;
}

function get_pembayaran_spp($id,$tahun,$kelas){
   $data=App\Pembayaranspp::where('siswa_id',$id)->where('tahun_ajaran',$tahun)->where('kelas',$kelas)->orderby('tanggal','asc')->get();

   return $data;
}

function nilai_sisa_pembayaran_daftar($id,$siswa_id,$tahun_ajaran,$kelas){
   $data=App\Pembayarandaftar::where('detail_daftar_ulang_id',$id)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun_ajaran)->where('kelas',$kelas)->sum('biaya');
   return $data;
}
function nilai_sisa_pembayaran_daftar_siswa($id,$tahun_ajaran,$kelas){
   $data=App\Pembayarandaftar::where('detail_daftar_ulang_id',$id)->where('siswa_id',cek_siswa(Auth::user()['nik'])['id'])->where('tahun_ajaran',$tahun_ajaran)->where('kelas',$kelas)->sum('biaya');
   return $data;
}

function nilai_sisa_pembayaran_spp($id,$siswa_id,$tahun_ajaran,$kelas){
   $data=App\Pembayaranspp::where('bulan',$id)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun_ajaran)->where('kelas',$kelas)->sum('biaya');
   return $data;
}

function sisa_pembayaran_daftar($id,$siswa_id,$tahun_ajaran,$kelas){
   $cek=App\Pembayarandaftar::where('detail_daftar_ulang_id',$id)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun_ajaran)->where('kelas',$kelas)->count();
   if($cek==0){
      $biaya=0;
   }else{
      $data=App\Pembayarandaftar::where('detail_daftar_ulang_id',$id)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun_ajaran)->where('kelas',$kelas)->sum('biaya');
      $biaya=$data;
   }

   return $biaya;
}
function sisa_pembayaran_spp($bulan,$siswa_id,$tahun,$kelas){
   $cek=App\Pembayaranspp::where('bulan',$bulan)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun)->count();
   if($cek==0){
      $biaya=0;
   }else{
      $data=App\Pembayaranspp::where('bulan',$bulan)->where('siswa_id',$siswa_id)->where('tahun_ajaran',$tahun)->sum('biaya');
      $biaya=$data;
   }

   return $biaya;
}

function get_tahun_ajaran(){
   $data=App\Tahunajaran::orderBy('name','Asc')->get();

   return $data;
}

function tahun_ajaran(){
   $data=App\Tahunajaran::orderBy('name','Asc')->get();
   
   foreach($data as $dat){
      
               echo'<option value="'.$dat['name'].'">'.$dat['name'].'</option>';
           
   }
}

function cek_tahun_ajaran($id=null){
   $data=App\Tahunajaran::orderBy('name','Asc')->get();
   
   foreach($data as $dat){
      if($dat['name']==$id){
         $cek='selected';
      }else{
         $cek='';
      }
      
      echo'<option value="'.$dat['name'].'" '.$cek.'>'.$dat['name'].'</option>';
           
   }
}

function get_kelas(){
   $data=App\Kelas::get()->unique('kelas');

   return $data;
}
function cek_get_kelas(){
   $data=App\Kelas::get()->unique('kelas');

   return $data;
}

function cek_kelas_all($id){
   
   $data=App\Kelas::get()->unique('kelas');
   
   foreach($data as $dat){
      echo'<optgroup label="Kelas '.$dat['kelas'].'" data-max-options="2">';
            foreach(detail_kelas($dat['kelas']) as $det){
               if($dat['name']==$id){
                  $cek='selected';
               }else{
                  $cek='';
               }
               echo'<option value="'.$det['id'].'" '.$cek.'>Kelas '.$det['kelas'].'  '.$det['name'].'</option>';
            }
      echo'</optgroup>';
   }
   
}

function kelas_add(){
   $data=App\Kelas::get()->unique('kelas');
   
   foreach($data as $dat){
      echo'<optgroup label="Kelas '.$dat['kelas'].'" data-max-options="2">';
            foreach(detail_kelas($dat['kelas']) as $det){
               echo'<option value="'.$det['id'].'">Kelas '.$det['kelas'].'  '.$det['name'].'</option>';
            }
      echo'</optgroup>';
   }
   
}

function tahun_angkatan(){
   $data=App\Siswa::where('tahun_angkatan','!=',null)->get()->unique('tahun_angkatan');
   
   foreach($data as $dat){
      
        echo'<option value="'.$dat['tahun_angkatan'].'">'.$dat['tahun_angkatan'].'</option>';
           
   }
   
}

function simpan_keuangan($id,$name,$biaya,$kategori,$sts,$siswa,$tahun){
   $data                 = new App\Keuangan;
   $data->name           =  $name;
   $data->biaya          =  $biaya;
   $data->kategori       =  $kategori;
   $data->siswa_id       =  $siswa;
   $data->tahun_ajaran   =  $tahun;
   $data->transaksi_id   =  $id;
   $data->sts            =  $sts;
   $data->tanggal        =  date('Y-m-d H:i:s');
   $data->save();

   if($data){
      $not='ok';
   }

   
}

?>