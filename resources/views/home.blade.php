@extends('layouts.app_admin')

@section('content')
<style>
    #tambah{margin-left:2px;}
    #reload{margin-left:2px;}
    #proses{margin-left:2px;}
    #cetak{margin-left:2px;}
    #cek{margin-left:2px;}
    td{
        font-size:13px;
    }
    .ttdd{
        border-bottom:solid 1px #f5cdcd;
    }
    .form-cont{
        padding:3px;
        width:100%;
        border:solid 1px #f5cdcd;
        margin:1px;
    }
</style>
<section class="content">
    {!!title('home_admin')!!}
    <div class="row">
        <div class="margin" style="padding:0.4%;background: #fff;">
            <div class="btn-group" style="width:100%;">
                <select class="form-control" id="tahun" style="display:inline;width:20%;margin-left:2px;float:left;">
                    <option value="all">Pilih All---</option>
                    {!!cek_tahun_ajaran($tahun)!!}
                </select>
                <select class="form-control" id="kelas_id" style="display:inline;width:20%;margin-left:2px;float:left">
                    <option value="all" @if($kelas=='all') selected @endif>Pilih Kelas---</option>
                    @foreach(get_kelas() as $get_kelas)
                        <option value="{{$get_kelas['kelas']}}" @if($kelas ==$get_kelas['kelas']) selected @endif>Kelas {{$get_kelas['kelas']}}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-success btn-flat" style="float:left" id="cari"><i class="fa fa-search"></i></button>
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 1</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,1)}}</span>
                </div>
            </div>
        </div>
    
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 2</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,2)}}</span>
                </div>
            </div>
        </div>
        
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 3</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,3)}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 4</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,4)}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 5</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,5)}}</span>
                </div>
            </div>
        </div>
    
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kelas 6</span>
                    <span class="info-box-number">{{jumlah_siswa($tahun,6)}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            
            
            <div class="box-body" >
                <div class="margin" style="padding:1%;">
                
                   <table class="table table-striped"  id="tabeldata">
                        
                    </table>
                </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

    
@endsection

@push('datatable')
<script> 
    $(document).ready(function() {
            
            var kelas="{{$kelas}}";
            var tahun="{{$tahun}}";
            var table = $('#tabeldata').DataTable({
                responsive: true,
                scrollY: "450px",
                scrollCollapse: true,
                ordering   : false,
                paging   : false,
                info   : false,
                oLanguage: {"sSearch": "<span class='btn btn-default btn sm'><i class='fa fa-search'></i></span>" },
                "ajax": {
                    "type": "GET",
                    "url": "{{url('siswa/kelas/api')}}/"+tahun+"/"+kelas,
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
                "order": [[ 4, "asc" ]],
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
                        "title": "Kode",
                        "render": function (data, row, type, meta) {
                            return data.kode;
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
                        "title": "Angkatan",
                        "render": function (data, row, type, meta) {
                            return data.tahun_angkatan;
                        }
                    },
                    {
                        "mData": null,
                        "title": "Kelas",
                        "render": function (data, row, type, meta) {
                            return '<b>'+data.kelas+'</b> '+data.nama_kelas;
                        }
                    },
                    {
                        "mData": null,
                        "title": "Thn Ajaran",
                        "render": function (data, row, type, meta) {
                            return data.tahun_ajaran;
                        }
                    }
                ]
            });

        
    });

    $('#cari').click(function(){
        var tahun=$('#tahun').val();
        var kelas_id=$('#kelas_id').val();
        window.location.assign("{{url($link)}}?tahun="+tahun+"&kelas="+kelas_id);
     });  
</script>   
@endpush
