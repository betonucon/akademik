<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Daftarulang;
use App\Pembayarandaftar;
use App\Pembayarandonasi;
use App\Pembayaranspp;
use App\Keuangan;
use Carbon\Carbon;
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
class RekapanController extends Controller
{
    public function index(request $request){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Semua Pembayaran';
            $link='rekapan_keuangan';
            if($request->bulan==null){
                $bulan='all';
                $tahun=date('Y');
            }
            else{
                $bulan=$request->bulan;
                $tahun=$request->tahun;
            }
            
            return view('pembayaran.rekapan_keuangan',compact('halaman','link','bulan','tahun'));
        }else{
            return view('error');
        }
    }
    public function index_keuangan(request $request,$aksi=null){
        error_reporting(0);
        if(Auth::user()['role_id']==3){
            $halaman='Transaksi Keuangan';
            $link='keuangan';
            
            if($request->bulan==null){
                $bulan='all';
                $tahun=date('Y');
                $masuk=Keuangan::whereYear('tanggal',$tahun)->where('sts',1)->sum('biaya');
                $keluar=Keuangan::whereYear('tanggal',$tahun)->where('sts',2)->sum('biaya');
            }
            else{
                $bulan=$request->bulan;
                $tahun=$request->tahun;
                $masuk=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',1)->sum('biaya');
                $keluar=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',2)->sum('biaya');
                
            }
            if($aksi==null){
                $aksi='new';
            }else{
                $aksi='edit';
            }
            $data=Keuangan::where('id',$request->id)->first();
            return view('pembayaran.index_keuangan',compact('halaman','link','bulan','tahun','aksi','data','masuk','keluar'));
        }else{
            return view('error');
        }
    }

    public function index_spp(request $request){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Semua SPP';
            $link='rekapan_keuangan_spp';
            if($request->bulan==null){
                $bulan='all';
                $tahun=date('Y');
            }
            else{
                $bulan=$request->bulan;
                $tahun=$request->tahun;
            }
            
            return view('pembayaran.rekapan_keuangan_spp',compact('halaman','link','bulan','tahun'));
        }else{
            return view('error');
        }
    }
    public function rekapan_spp_all(request $request){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Semua SPP';
            $link='rekapan_keuangan_spp';
            if($request->tahun==null){
                $kelas='all';
                $tahun=date('Y').'-'.(date('Y')+1);
            }
            else{
                $kelas=$request->kelas;
                $tahun=$request->tahun;
            }
            
            return view('pembayaran.rekapan_spp_all',compact('halaman','link','kelas','tahun'));
        }else{
            return view('error');
        }
    }

    public function rekapan_daftar_ulang(request $request){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Daftar Ulang';
            $link='rekapan_daftar_ulang';
            if($request->tahun==null){
                $kelas='all';
                $tahun=date('Y').'-'.(date('Y')+1);
            }
            else{
                $kelas=$request->kelas;
                $tahun=$request->tahun;
            }
            
            return view('pembayaran.rekapan_daftar_ulang',compact('halaman','link','kelas','tahun'));
        }else{
            return view('error');
        }
    }

    public function index_daftar(request $request){
        if(Auth::user()['role_id']==3){
            $halaman='Rekapan Semua Daftar Ulang';
            $link='rekapan_keuangan_daftar';
            if($request->bulan==null){
                $bulan='all';
                $tahun=date('Y');
            }
            else{
                $bulan=$request->bulan;
                $tahun=$request->tahun;
            }
            
            return view('pembayaran.rekapan_keuangan_daftar',compact('halaman','link','bulan','tahun'));
        }else{
            return view('error');
        }
    }


    public function api($bulan,$tahun){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($bulan=='all'){
                $cek=Pembayaranspp::whereYear('tanggal',$tahun)->count();
                $data=Pembayaranspp::whereYear('tanggal',$tahun)->orderBy('id','Asc')->get();
            }else{
                $cek=Pembayaranspp::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->count();
                $data=Pembayaranspp::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->orderBy('id','Asc')->get();
            }
            
            
            foreach($data as $no=>$o){
            
                $show[]=array(
                    "nama_pembayaran" =>'SPP '.bulan(bln($o['bulan'])),
                    "keterangan" =>bulan(bln($o['bulan'])),
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kelas"=>$o['kelas'].'&nbsp;&nbsp;['.cek_kelas($o['kelas'])['name'].']',
                    "tahun_ajaran"=>$o['tahun_ajaran'],
                    "nama_kelas"=>cek_kelas($o['kelas'])['name'],
                    "biaya"=>uang($o['biaya']),
                    "tanggal"=>$o['tanggal'],
                    
                );
            }

            $show[]=array(
                "nama_pembayaran" =>'Total SPP',
                "keterangan" =>'',
                "kode" =>'',
                "name" =>'',
                "kelas"=>'',
                "tahun_ajaran"=>'',
                "nama_kelas"=>'',
                "biaya"=>'<b>'.uang(total_keuangan_spp($bulan,$tahun)).'</b>',
                "tanggal"=>'',
                
            );
            
            if($bulan=='all'){
                $det=Pembayarandaftar::whereYear('tanggal',$tahun)->orderBy('id','Asc')->get();
            }else{
                $det=Pembayarandaftar::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->orderBy('id','Asc')->get();
            }
            
            
            foreach($det as $o){
            
                $show[]=array(
                    "nama_pembayaran" =>'Daftar Ulang',
                    "keterangan" =>'',
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kelas"=>$o['kelas'].'&nbsp;&nbsp;['.cek_kelas($o['kelas'])['name'].']',
                    "tahun_ajaran"=>$o['tahun_ajaran'],
                    "nama_kelas"=>cek_kelas($o['kelas'])['name'],
                    "biaya"=>uang($o['biaya']),
                    "tanggal"=>$o['tanggal'],
                    
                );
            }
            
            $show[]=array(
                "nama_pembayaran" =>'Total Daftar Ulang',
                "keterangan" =>'',
                "kode" =>'',
                "name" =>'',
                "kelas"=>'',
                "tahun_ajaran"=>'',
                "nama_kelas"=>'',
                "biaya"=>'<b>'.uang(total_keuangan_daftar($bulan,$tahun)).'</b>',
                "tanggal"=>'',
                
            );

            
        }

        echo json_encode($show);
        
    }

    public function api_rekapan_spp_all($tahun,$kelas){
        if($kelas=='all'){
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->orderBy('id','Desc')->get();
        }else{
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->orderBy('id','Desc')->get();
        }
        
        
        foreach($data as $o){
        
            $show[]=array(
                "id" =>$o['id'],
                "name" =>'['.cek_siswa($o['siswa_id'])['kode'].'] '.cek_siswa($o['siswa_id'])['name'],
                "kode" =>cek_siswa($o['siswa_id'])['kode'],
                "tahun_ajaran" =>$o['tahun_ajaran'],
                "kelas" =>$o['kelas'],
                "Jan" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],1)),
                "Feb" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],2)),
                "Mar" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],3)),
                "Apr" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],4)),
                "Mei" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],5)),
                "Jun" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],6)),
                "Jul" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],7)),
                "Ags" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],8)),
                "Sep" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],9)),
                "Okt" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],10)),
                "Nov" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],11)),
                "Des" =>uang(total_spp_siswa($o['siswa_id'],$o['tahun_ajaran'],12))
            );
        }

        for($x=1;$x<3;$x++){
            $show[]=array(
                "id" =>'',
                "name" =>'',
                "kode" =>'',
                "tahun_ajaran" =>'',
                "kelas" =>'',
                "Jan" =>'',
                "Feb" =>'',
                "Mar" =>'',
                "Apr" =>'',
                "Mei" =>'',
                "Jun" =>'',
                "Jul" =>'',
                "Ags" =>'',
                "Sep" =>'',
                "Okt" =>'',
                "Nov" =>'',
                "Des" =>''
            );
        }
        
        echo json_encode($show);
        
    }

    public function api_rekapan_daftar_ulang($tahun,$kelas){
        if($kelas=='all'){
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->orderBy('id','Desc')->get();
        }else{
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->orderBy('id','Desc')->get();
        }
        
        
        foreach($data as $o){
        
            $show[]=array(
                "id" =>$o['id'],
                "name" =>'['.cek_siswa($o['siswa_id'])['kode'].'] '.cek_siswa($o['siswa_id'])['name'],
                "kode" =>cek_siswa($o['siswa_id'])['kode'],
                "tahun_ajaran" =>$o['tahun_ajaran'],
                "kelas" =>$o['kelas'],
                "tagihan" =>uang(tagihan_tahun_ajaran($o['tahun_ajaran'],$o['kelas'])),
                "dibayar" =>uang(tagihan_tahun_ajaran_dibayar($o['siswa_id'],$o['tahun_ajaran'],$o['kelas'])),
                "potongan" =>uang(tagihan_tahun_ajaran_potongan($o['siswa_id'],$o['tahun_ajaran'],$o['kelas'])),
            );
        }

        
        
        echo json_encode($show);
        
    }

    public function api_spp($bulan,$tahun){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($bulan=='all'){
                $cek=Pembayaranspp::whereYear('tanggal',$tahun)->count();
                $data=Pembayaranspp::whereYear('tanggal',$tahun)->orderBy('id','Asc')->get();
            }else{
                $cek=Pembayaranspp::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->count();
                $data=Pembayaranspp::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->orderBy('id','Asc')->get();
            }
            
            
            foreach($data as $no=>$o){
            
                $show[]=array(
                    "nama_pembayaran" =>'SPP '.bulan(bln($o['bulan'])),
                    "keterangan" =>bulan(bln($o['bulan'])),
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kelas"=>$o['kelas'].'&nbsp;&nbsp;['.cek_kelas($o['kelas'])['name'].']',
                    "tahun_ajaran"=>$o['tahun_ajaran'],
                    "nama_kelas"=>cek_kelas($o['kelas'])['name'],
                    "biaya"=>uang($o['biaya']),
                    "tanggal"=>$o['tanggal'],
                    
                );
            }

            $show[]=array(
                "nama_pembayaran" =>'Total SPP',
                "keterangan" =>'',
                "kode" =>'',
                "name" =>'',
                "kelas"=>'',
                "tahun_ajaran"=>'',
                "nama_kelas"=>'',
                "biaya"=>'<b>'.uang(total_keuangan_spp($bulan,$tahun)).'</b>',
                "tanggal"=>'',
                
            );
            
        }
        echo json_encode($show);
        
    }

    public function api_daftar($bulan,$tahun){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($bulan=='all'){
                $det=Pembayarandaftar::whereYear('tanggal',$tahun)->orderBy('id','Asc')->get();
            }else{
                $det=Pembayarandaftar::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->orderBy('id','Asc')->get();
            }
            
            
            foreach($det as $o){
            
                $show[]=array(
                    "nama_pembayaran" =>'Daftar Ulang',
                    "keterangan" =>'',
                    "kode" =>cek_siswa($o['siswa_id'])['kode'],
                    "name" =>cek_siswa($o['siswa_id'])['name'],
                    "kelas"=>$o['kelas'].'&nbsp;&nbsp;['.cek_kelas($o['kelas'])['name'].']',
                    "tahun_ajaran"=>$o['tahun_ajaran'],
                    "nama_kelas"=>cek_kelas($o['kelas'])['name'],
                    "biaya"=>uang($o['biaya']),
                    "tanggal"=>$o['tanggal'],
                    
                );
            }
            
            $show[]=array(
                "nama_pembayaran" =>'Total Daftar Ulang',
                "keterangan" =>'',
                "kode" =>'',
                "name" =>'',
                "kelas"=>'',
                "tahun_ajaran"=>'',
                "nama_kelas"=>'',
                "biaya"=>'<b>'.uang(total_keuangan_daftar($bulan,$tahun)).'</b>',
                "tanggal"=>'',
                
            );
        }
        echo json_encode($show);
        
    }

    public function api_keuangan($bulan,$tahun){
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($bulan=='all'){
                $det=Keuangan::whereYear('tanggal',$tahun)->orderBy('id','Desc')->get();
            }else{
                $det=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->orderBy('id','Desc')->get();
            }
            
            
            foreach($det as $o){
            
                $show[]=array(
                    "id" =>$o['id'],
                    "name" =>$o['name'],
                    "transaksi_id" =>$o['transaksi_id'],
                    "biaya"=>uang($o['biaya']),
                    "tanggal"=>$o['tanggal'],
                    "sts"=>sts($o['sts']),
                    
                );
            }
            
            
        }
        echo json_encode($show);
        
    }

    public function pdf_rekapan_spp_all($tahun,$kelas){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        if($kelas=='all'){
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->get();
            $kelas='Semua Kelas';
        }else{
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->get();
            $kelas='Kelas '.$kelas;
        }
        $tahun=$tahun;

        $pdf = PDF::loadView('pembayaran.pdf_rekapan_spp_all', ['data'=>$data,'kelas'=>$kelas,'tahun'=>$tahun]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
        
    }
    public function pdf_rekapan_keuangan($tahun,$bulan){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        if($bulan=='all'){
            $datamasuk=Keuangan::whereYear('tanggal',$tahun)->where('sts',1)->orderBy('tanggal','Asc')->get();
            $datakeluar=Keuangan::whereYear('tanggal',$tahun)->where('sts',2)->orderBy('tanggal','Asc')->get();
            $masuk=Keuangan::whereYear('tanggal',$tahun)->where('sts',1)->sum('biaya');
            $keluar=Keuangan::whereYear('tanggal',$tahun)->where('sts',2)->sum('biaya');
            
            $bulan='Satu Tahun';
        }else{
            $datamasuk=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',1)->orderBy('tanggal','Asc')->get();
            $datakeluar=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',2)->orderBy('tanggal','Asc')->get();
            $masuk=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',1)->sum('biaya');
            $keluar=Keuangan::whereYear('tanggal',$tahun)->whereMonth('tanggal',$bulan)->where('sts',2)->sum('biaya');
            $bulan=bulan(bln($bulan));
        }
        $tahun=$tahun;

        $pdf = PDF::loadView('pembayaran.pdf_rekapan_keuangan', ['datamasuk'=>$datamasuk,'datakeluar'=>$datakeluar,'bulan'=>$bulan,'tahun'=>$tahun,'masuk'=>$masuk,'keluar'=>$keluar]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
        
    }
    public function pdf_rekapan_daftar_ulang($tahun,$kelas){
       
        error_reporting(0);
        $in=explode(',',$request->id);
        if($kelas=='all'){
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->get();
            $kelas='Semua Kelas';
        }else{
            $data=Kelassiswa::where('tahun_ajaran',$tahun)->where('kelas',$kelas)->get();
            $kelas='Kelas '.$kelas;
        }
        $tahun=$tahun;

        $pdf = PDF::loadView('pembayaran.pdf_rekapan_daftar_ulang', ['data'=>$data,'kelas'=>$kelas,'tahun'=>$tahun]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
        
    }

    public function simpan_keuangan(request $request){
        if(Auth::user()['role_id']==3){
            if (trim($request->name) == '') {$error[] = '- Nama  harus diisi';}
            if (trim($request->sts) == '') {$error[] = '- Status  harus diisi';} 
            if (trim($request->biaya) == '') {$error[] = '- Biaya  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                

                $data           = new Keuangan;
                $data->name     = $request->name;
                $data->biaya    = preg_replace($patr,'',$request->biaya);
                $data->sts      = $request->sts;
                $data->siswa_id = 0;
                $data->kategori = 3;
                $data->tanggal  = date('Y-m-d H:i:s');
                $data->save();

                echo'ok';
            }
        }
    }
    public function simpan_ubah_keuangan(request $request,$id){
        if(Auth::user()['role_id']==3){
            if (trim($request->name) == '') {$error[] = '- Nama  harus diisi';}
            if (trim($request->sts) == '') {$error[] = '- Status  harus diisi';} 
            if (trim($request->biaya) == '') {$error[] = '- Biaya  harus diisi';} 
            if (isset($error)) {echo '<p style="padding:5px;background:#d1ffae"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
            else{
                $patr='/([^0-9]+)/';
                $data           = Keuangan::find($id);
                $data->name     = $request->name;
                $data->biaya    = preg_replace($patr,'',$request->biaya);
                $data->sts      = $request->sts;
                $data->save();

                echo'ok';
            }
        }
    }
}
