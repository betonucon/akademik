@extends('layouts.app_admin')

@section('content')
<section class="content">
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tag"></i> Perhatian</h3>
        </div>
        <div class="box-body">
            @if(cek_siswa(Auth::user()['nik'])['sts']==0)
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> Lengkapi Profil</h4>
                    Silangkan klik dibawah ini untuk melengkapi profil <br><span class="btn btn-success" id="profil">Profil</span>
                </div>
            @else
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Selesai</h4>
                    Anda sudah selesai melengkapi data diri calon siswa
                </div>

                @if(cek_siswa(Auth::user()['nik'])['sts_penerimaan']==0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Verifikasi Data</h4>
                        Data anda dalam proses verifikasi, Mohon bersabar
                    </div>
                @endif
                @if(cek_siswa(Auth::user()['nik'])['sts_penerimaan']==1)
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success</h4>
                            Data anda telah lulus verifikasi
                    </div>
                @endif
                @if(cek_siswa(Auth::user()['nik'])['sts_penerimaan']==2)
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Gagal</h4>
                            Data anda tidak lulus verifikasi
                    </div>
                @endif
            @endif
            
        </div>
        <!-- /.box-body -->
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

        $('#profil').click(function(){
            window.location.assign("{{url('siswa/profil')}}");
        });
    </script>
@endpush
