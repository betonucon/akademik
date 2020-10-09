@extends('layouts.app_admin')

@section('content')
<style>
    
    @media only screen and (min-width: 650px) {
        #list-list{
            font-size:14px;
        }
        th{font-size:14px;}
        td{font-size:14px;}
        
    }
    @media only screen and (max-width: 649px) {
        #list-list{
            font-size:12px;
        }
        th{font-size:12px;}
        td{font-size:12px;}
       
    }
</style>
<section class="content">
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Profil</h3>
        </div>
        <div class="box-body">
        @if(total_daftar(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'])>0)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-bullhorn"></i> Peringatan Pembayaran Daftar Ulang</h4>
                Silahkan melunasi pembayaran daftar ulang Sebesar <b>Rp.{{uang(total_daftar(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas']))}}</b>
            </div>
        @endif
        <?php
            $bln=bl(date('m'));
        ?>
        @for($x=1;$x<=$bln;$x++)
            @if(total_spp_persiswa(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'],$x)>0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-bullhorn"></i> Peringatan Pembayaran SPP</h4>
                    Silahkan melunasi pembayaran SPP bulan {{bulan(bln($x))}} Sebesar <b>Rp.{{uang(total_spp_persiswa(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'],$x))}}</b>
                </div>
            @endif
        @endfor
            <div class="col-md-4">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{url('public/_file_upload/'.foto(Auth::user()['nik']))}}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{Auth::user()['name']}}</h3>

                    <p class="text-muted text-center">{{Auth::user()['nik']}}</p>

                    <ul class="list-group list-group-unbordered" id="list-list">
                        <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{Auth::user()['email']}}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Tempat Lahir</b> <a class="pull-right">{{cek_siswa(Auth::user()['nik'])['tempat_lahir']}}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Tanggal Lahir</b> <a class="pull-right">{{cek_siswa(Auth::user()['nik'])['tgl_lahir']}}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Kelas</b> <a class="pull-right">{!!cek_kelas_akhir(cek_siswa(Auth::user()['nik'])['id'])!!}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Tahun Ajaran</b> <a class="pull-right">{!!cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran']!!}</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Ubah Profil</b></a>
                </div>

            </div>
            <div class="col-md-8" style="background: #f1f1f9;min-height: 400px;padding:0px">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Pembayaran SPP</a></li>
                        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Daftar Ulang</a></li>
                    </ul>
                    <div class="tab-content" >
                        <div class="active tab-pane" id="activity" style="overflow-x:scroll">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="7%">No</th>
                                        <th>Bulan</th>
                                        <th width="20%">Dibayar</th>
                                        <th width="20%">Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($x=1;$x<13;$x++)
                                        <tr>
                                            <td>{{$x}}</td>
                                            <td>{{bulan(bln($x))}}</td>
                                            <td>{{nilai_sisa_pembayaran_spp($x,cek_siswa(Auth::user()['nik'])['id'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'])}}</td>
                                            <td>{{uang(cek_spp(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas']))}}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="timeline" style="overflow-x:scroll">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="7%">No</th>
                                        <th>Nama Pembayaran </th>
                                        <th width="20%">Biaya</th>
                                        <th width="20%">Tagihan</th>
                                        <th width="20%">Belum Dibayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(get_daftar_ulang(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas']) as $no=>$get_daftar_ulang )
                                        <tr>
                                            <td>{{$no+1}}</td>
                                            <td>{{$get_daftar_ulang['name']}}</td>
                                            <td>{{nilai_sisa_pembayaran_daftar_siswa($get_daftar_ulang['id'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas'])}}</td>
                                            <td>{{uang($get_daftar_ulang['biaya'])}}</td>
                                            <td>{{uang($get_daftar_ulang['biaya']-nilai_sisa_pembayaran_daftar($get_daftar_ulang['id'],cek_siswa(Auth::user()['nik'])['id'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas']))}}</td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="settings">

                        </div>
                    </div>
                </div>
            </div>
        <!-- /.box-body -->
    </div>      
</section>

<section class="content">
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Riwayat Pembayaran</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaksi </th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach(get_keuangan(cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['tahun_ajaran'],cek_kelas_sekarang(cek_siswa(Auth::user()['nik'])['id'])['kelas']) as $no=>$get_keuangan )
                        <tr>
                            <td><b><u>{{$get_keuangan['tanggal']}}</u></b><br>{{$get_keuangan['name']}} <br><b>Rp.{{uang($get_keuangan['biaya'])}}</b></td>
                        </tr>
                @endforeach
                </tbody>
            </table>
            
        </div>      
    </div>      
</section>
@endsection

@push('datatable')
    <script>
        
        $(document).ready(function() {
            
            if($(window).width()>='600'){
                var table = $('#tabeldata').DataTable({
                    responsive: true,
                    oLanguage: {"sSearch": "<span class='btn btn-default btn sm'><i class='fa fa-search'></i></span>" },
                    "ajax": {
                        "type": "GET",
                        "url": "{{url('api/user')}}",
                        "timeout": 120000,
                        "dataSrc": function (json) {
                            if(json != null){
                                return json
                            } else {
                                return "No Data";
                            }
                        }
                    },
                    "sAjaxDataProp": "",
                    "width": "100%",
                    "order": [[ 0, "asc" ]],
                    "aoColumns": [
                        {
                            "mData": null,
                            "width":"5%",
                            "title": "No",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Nik",
                            "render": function (data, row, type, meta) {
                                return data.nik;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Nama",
                            "render": function (data, row, type, meta) {
                                return data.name;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Role",
                            "render": function (data, row, type, meta) {
                                return data.role;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Aksi",
                            "width":"8%",
                            "sortable": false,
                            "render": function (data, row, type, meta) {
                                let btn = '';

                                
                                        btn += '<span class="btn btn-success btn-sm" onclick="edit('+data.id+')"><i class="fa fa-pencil"></i></span>_'
                                                +'<span class="btn btn-danger btn-sm" onclick="hapus('+data.id+')"><i class="fa fa-remove"></i></span>';
                                    

                                return btn;
                            }
                        }
                    ]
                });

            }

            if($(window).width()<'600'){
                var table = $('#tabeldata').DataTable({
                    responsive: true,
                    searching   : true,
                    ordering   : false,
                    paging   : false,
                    info   : false,
                    oLanguage: {"sSearch": "<span class='btn btn-default btn sm'><i class='fa fa-search'></i></span>" },
                    "ajax": {
                        "type": "GET",
                        "url": "{{url('api/user')}}",
                        "timeout": 120000,
                        "dataSrc": function (json) {
                            if(json != null){
                                return json
                            } else {
                                return "No Data";
                            }
                        }
                    },
                    "sAjaxDataProp": "",
                    "width": "100%",
                    "order": [[ 0, "asc" ]],
                    "aoColumns": [
                        
                        {
                            "mData": null,
                            "title": "Nama",
                            "render": function (data, row, type, meta) {
                                let isi = '';
                                    isi+='<b>NIK :</b> '+data.nik+'<br>'
                                        +'<b>Nama :</b> '+data.name+'<br>'
                                        +'<br>'
                                        +'<span class="label label-success" onclick="edit('+data.id+')">Ubah</span>_'
                                        +'<span class="label label-danger" onclick="hapus('+data.id+')">Hapus</span>';
                                         
                                return isi;
                            }
                        
                        }
                    ]
                });
            }
        });

        
    </script>
@endpush
