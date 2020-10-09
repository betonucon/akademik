<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home(request $request)
    {
        error_reporting(0);
        if(Auth::user()['role_id']==1 || Auth::user()['role_id']==3){
            if($request->tahun==''){
                $tahun=date('Y').'-'.(date('Y')+1);
            }else{
                $tahun=$request->tahun;
            }
            if($request->kelas==''){
                $kelas=1;
            }else{
                $kelas=$request->kelas;
            }
            $halaman='Halaman Utama';
            $link='home';
            return view('home',compact('halaman','link','tahun','kelas'));
        }
        if(Auth::user()['role_id']==0){
            $halaman='Halaman Utama';
            $link='home';
            return view('home_new',compact('halaman','link'));
        }
        if(Auth::user()['role_id']==2){
            $halaman='Halaman Utama';
            $link='home';
            return view('home_siswa',compact('halaman','link'));
        }
        
    }
    public function riwayat(request $request)
    {
        error_reporting(0);
        
        if(Auth::user()['role_id']==2){
            if($request->tahun==''){
                $tahun=date('Y').'-'.(date('Y')+1);
            }else{
                $tahun=$request->tahun;
            }
            if($request->kelas==''){
                $kelas=cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'];
            }else{
                $kelas=$request->kelas;
            }
            $halaman='Riwayat Pembayaran';
            $link='riwayat_pembayaran';
            return view('index_siswa',compact('halaman','link','tahun','kelas'));
        }
        
    }
    public function index()
    {
        $halaman='Data User';
        $link='user';
        return view('master.user',compact('halaman','link'));
    }

    public function api_user(){
        error_reporting(0);
        $data=User::orderBy('name','Asc')->get();
        foreach($data as $o){
           
            $show[]=array(
                "id" =>$o['id'],
                "nik" =>$o['nik'],
                "name" =>$o['name'],
                "role"=>$o['role_id']
            );
        }

        echo json_encode($show);
        
    }
}
