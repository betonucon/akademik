@extends('layouts.app_admin')

@section('content')
<style>
    #tambah{margin-left:2px;}
    #reload{margin-left:2px;}
    #proses{margin-left:2px;}
    #cetak{margin-left:2px;}
    #cek{margin-left:2px;}
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
        {!!title('transaksi_keuangan')!!}
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            
            <!-- /.box-header -->
            <div class="margin" style="padding:1%;background:#deefef">
                @if($aksi=='new')
                    <div class="row">
                   
                        <form method="post" id="mydatatambah" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group" style="">
                                    <label>Nama Transaksi</label>
                                    <input class="form-control"  name="name" type="text" id="name">
                                </div>
                                <div class="form-group" style="">
                                    <label>Status</label>
                                    <select class="form-control"  name="sts" >
                                        <option value="">Pilih Status</option>
                                        <option value="1">Masuk</option>
                                        <option value="2">Keluar</option>
                                    </select>
                                </div>
                               
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group" style="">
                                    <label>Nilai </label>
                                    <input class="form-control"  type="text" id="rupiah" name="biaya">
                                </div>
                            </div>
                        </form>
                    
                    </div>
                
                    <div class="btn-group" style="width:100%;">
                        <button type="button" class="btn btn-primary btn-flat" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                        
                    </div>
                @endif

                @if($aksi=='edit')
                    <div class="row">
                   
                        <form method="post" id="mydataubah" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group" style="">
                                    <label>Nama Transaksi</label>
                                    <input class="form-control"  name="name" value="{{$data['name']}}" type="text" id="name">
                                </div>
                                <div class="form-group" style="">
                                    <label>Status</label>
                                    <select class="form-control"  name="sts" >
                                        <option value="">Pilih Status</option>
                                        <option value="1" @if($data['sts']==1) selected @endif>Masuk</option>
                                        <option value="2" @if($data['sts']==2) selected @endif>Keluar</option>
                                    </select>
                                </div>
                               
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group" style="">
                                    <label>Nilai </label>
                                    <input class="form-control"  type="text" id="rupiah" value="{{$data['biaya']}}" name="biaya">
                                </div>
                            </div>
                        </form>
                    
                    </div>
                
                    <div class="btn-group" style="width:100%;">
                        <button type="button" class="btn btn-primary btn-flat" id="simpan_ubah"><i class="fa fa-save"></i> Simpan</button>
                        
                    </div>
                @endif
            </div>
            <hr style="border:dotted  #6b7373 1px">
            <div class="box-body" >
                <div class="btn-group" style="width:100%;">
                    <button type="button" class="btn btn-default btn-flat" id="reload"><i class="fa fa-history"></i></button>
                    <select class="form-control" id="tahun" style="display:inline;width:20%;margin-left:2px;float:left" >
                        @for($th=2019;$th<2030;$th++)
                            <option value="{{$th}}" @if($tahun==$th) selected @endif>{{$th}}</option>
                        
                        @endfor
                    </select>
                    <select class="form-control" id="bulan" style="display:inline;width:20%;margin-left:2px;float:left">
                        <option value="all" @if($bulan=='all') selected @endif>Semua Bulan</option>
                        @for($bl=1;$bl<13;$bl++)
                            <option value="{{bln($bl)}}" @if($bulan==bln($bl)) selected @endif>{{bulan(bln($bl))}}</option>
                        
                        @endfor
                    </select>
                    <button type="button" class="btn btn-success btn-flat" style="float:left" id="cari"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-success btn-flat" id="cetak"><i class="fa fa-print"></i> Cetak</button>
                        
                </div>
                <div class="alert alert-dismissible" style="color:#615757;background:#fafafd;margin-top:10px">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i>Rekapan Keuangan</h4>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="background: none;padding:2px">
                            <b>Masuk &nbsp&nbsp;:&nbsp&nbsp</b> {{uang($masuk)}}</a>
                        </li>
                        <li class="list-group-item" style="background: none;padding:2px">
                            <b>Keluar &nbsp&nbsp;:&nbsp&nbsp</b> {{uang($keluar)}}</a>
                        </li>
                        <li class="list-group-item" style="background: none;padding:2px">
                            <b>Total &nbsp&nbsp&nbsp&nbsp&nbsp;:&nbsp&nbsp</b> {{uang($masuk-$keluar)}}</a>
                        </li>
                       
                    </ul>
                </div>
                <form method="post" id="mydata" enctype="multipart/form-data">
                    @csrf
                    <table id="tabeldata" class="table table-bordered table-striped">
                        
                    </table>
                </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

    <div class="modal fade" id="modalloading" >
      <div class="modal-dialog">
          <div class="modal-content" style="margin-top: 30%;">
              <div class="modal-header">
                  <button type="button" class="close" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">Wait </h4>
              </div>
              <div class="modal-body" style="text-align:center">
                  <img src="{{url('public/img/loading.gif')}}" style="width:30%">
              </div>
              
          </div>
      </div>
    </div>
    <div class="modal fade" id="prosesdata" >
      <div class="modal-dialog">
          <div class="modal-content" style="margin-top: 30%;">
              <div class="modal-header">
                  <button type="button" class="close" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">Wait </h4>
              </div>
              <div class="modal-body" style="text-align:center">
                  <img src="{{url('public/img/loading.gif')}}" style="width:30%">
              </div>
              
          </div>
      </div>
    </div>

    <div class="modal fade" id="modalnotif" >
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 30%;">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Peringatan </h4>
                </div>
                <div class="modal-body" style="text-align:center">
                    <div id="notifikasi"></div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    
                </div> 
                
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="modalerror" >
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 30%;">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Peringatan </h4>
                </div>
                <div class="modal-body" style="text-align:center">
                    <div id="notifikasierror"></div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    
                </div> 
            </div>
        </div>
    </div>
@endsection

@push('datatable')
    <script>
        function hitung(x){
            var jum = 0;

            for(a=1; a<10; a++){
                biaya= eval($("#biaya"+a).val());

                if(biaya==''){
                    jum+=0;
                }else{
                    jum+=biaya; 
                }
            }
            $("#totalbiaya").val(jum);
        }

        function cek_biaya(no,a){
            var tagihan=$('#tagihan'+no).val();
            if(eval(a)>eval(tagihan)){
               
                $('#modalerror').modal('show');
                $('#notifikasierror').html('Pembayaran melebihi Tagihan');
                $('#biayatagihan'+no).val(tagihan);
            }else{

            }
            
        }
        $(document).ready(function() {
                
                   


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
                        "url": "{{url('/api/'.$link.'/'.$bulan.'/'.$tahun)}}",
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
                            "title": "Nama Transaksi",
                            "render": function (data, row, type, meta) {
                                return data.name;
                            }
                        },
                        
                        {
                            "mData": null,
                            "title": "Biaya",
                            "render": function (data, row, type, meta) {
                                return data.biaya;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Tanggal",
                            "render": function (data, row, type, meta) {
                                return data.tanggal;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Status",
                            "render": function (data, row, type, meta) {
                                return data.sts;
                            }
                        },
                        
                        {
                            "mData": null,
                            "title": "",
                            "width":"5%",
                            "sortable": false,
                            "render": function (data, row, type, meta) {
                                let btn = '';
                                    if(data.transaksi_di==''){
                                        btn += '<span class="btn btn-danger btn-xs" onclick="hapus('+data.id+')"><i class="fa fa-remove"></i></span>';
                                    }else{

                                    }
                                return btn;
                            }
                        },
                        {
                            "mData": null,
                            "title": "",
                            "width":"5%",
                            "sortable": false,
                            "render": function (data, row, type, meta) {
                                let btn = '';
                                    if(data.transaksi_id==null){
                                        btn += '<span class="btn btn-success btn-xs" onclick="ubah('+data.id+')"><i class="fa fa-pencil"></i></span>';
                                    }else{
                                       
                                    }
                                
                                        
                                    

                                return btn;
                            }
                        }
                    ]
                });

            

            $('#proses').click(function(){
                $('#prosesdata').modal('show');
            });

            $('#reload').click(function(){
                $('#tabeldata').load();
            });

            $('#pilihsemua').click(function(){
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]').not(this).prop('checked', this.checked);
                // $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $('#hapus').click(function(){
                var form=document.getElementById('mydata');
                
                $.ajax({
                    type: 'POST',
                    url: "{{url('/'.$link.'/hapus')}}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('#modalloading').modal({backdrop: 'static', keyboard: false});
                    },
                    success: function(msg){
                        // alert(msg);
                        location.reload();
                        
                    }
                });
            });

            $('#simpan').click(function(){
                var form=document.getElementById('mydatatambah');
                
                $.ajax({
                    type: 'POST',
                    url: "{{url('/simpan_keuangan/'.$link)}}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('#modalloading').modal({backdrop: 'static', keyboard: false});
                    },
                    success: function(msg){
                        if(msg=='ok'){
                            location.reload();
                        }else{
                            $('#modalloading').modal('hide');
                            $('#modalnotif').modal('show');
                            $('#notifikasi').html(msg);
                        }
                        
                        
                    }
                });
            });
            
            $('#simpan_ubah').click(function(){
                var form=document.getElementById('mydataubah');
                
                $.ajax({
                    type: 'POST',
                    url: "{{url('/simpan_ubah_keuangan/'.$link.'/'.$data['id'])}}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('#modalloading').modal({backdrop: 'static', keyboard: false});
                    },
                    success: function(msg){
                        if(msg=='ok'){
                            window.location.assign("{{url($link)}}");
                        }else{
                            $('#modalloading').modal('hide');
                            $('#modalnotif').modal('show');
                            $('#notifikasi').html(msg);
                        }
                        
                        
                    }
                });
            });


            $('#cetak').click(function(){
                var tahun=$('#tahun').val();
                var bulan=$('#bulan').val();
                window.open("{{url('pdf_rekapan_keuangan')}}/"+tahun+"/"+bulan, '_blank');
                        
            });

            $('#cari_data').click(function(){
                var siswa_id=$('#siswa_id').val();
                var tahun_ajaran_cari=$('#tahun_ajaran_cari').val();
                var kelas_cari=$('#kelas_cari').val();
                window.open("{{url('rekapan_spp/'.$link.'/')}}/"+siswa_id+"/"+tahun_ajaran_cari+"/"+kelas_cari, '_blank');
                $('#modalcek').modal('hide');        
            });

            $('#tambah').click(function(){
                window.location.assign("{{url($link.'/tambah')}}");
            });
        });

        $('#cari').click(function(){
            var bulan=$('#bulan').val();
            var tahun=$('#tahun').val();
            window.location.assign("{{url($link)}}?bulan="+bulan+"&tahun="+tahun);
     +"/"+tahun   });

        function cari_kode(){
            var nik=$('#nik').val();
            
            $.ajax({
                type: 'GET',
                url: "{{url('/cari_nik_spp/'.$link)}}/"+nik,
                data: "id="+nik,
                success: function(msg){
                    var tam = msg.split("@");
                    var det = tam[0].split("_");
                    $('#rinciannya').html(tam[1]);
                    $('#kelas').val(det[0]);
                    $('#tahun_ajaran').val(det[1]);
                }
            });
        }
        function ubah(a){
            window.location.assign("{{url($link.'/edit/')}}?id="+a);
        }

        function hapus(a){
            $.ajax({
                type: 'GET',
                url: "{{url('/hapus_donasi/'.$link)}}/"+a,
                data: "id="+a,
                beforeSend: function(){
                    $('#modalloading').modal({backdrop: 'static', keyboard: false});
                },
                success: function(msg){
                    
                        window.location.assign("{{url($link)}}");
                    
                    
                }
            });
        }
        
        
    </script>
@endpush
