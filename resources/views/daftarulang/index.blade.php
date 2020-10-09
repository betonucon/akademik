@extends('layouts.app_admin')

@section('content')
<style>
    #tambah{margin-left:2px;}
    #reload{margin-left:2px;}
    #proses{margin-left:2px;}
    #cetak{margin-left:2px;}
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
        {!!title('daftar_ulang')!!}
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
                                <div class="form-group">
                                    <label>Tahun Ajaran</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="tahun_ajaran" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                                        <option value="">Pilih</option>
                                        @foreach(get_tahun_ajaran() as $get_tahun_ajaran)
                                            <option value="{{$get_tahun_ajaran['name']}}">{{$get_tahun_ajaran['name']}}</option>
                                        @endforeach
                                    
                                    </select>
                                </div>
                                <div class="form-group" style="">
                                    <label>Kelas</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="kelas" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option value="">Pilih Kelas</option>
                                        @foreach(get_kelas() as $get_kelas)
                                            <option value="{{$get_kelas['kelas']}}">Kelas {{$get_kelas['kelas']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="">
                                    <label>Total Biaya</label>
                                    <input class="form-control" readony name="totalbiaya" type="text" id="totalbiaya">
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="">
                                    <label>Rincian Daftar Ulang</label>
                                    <table width="100%">
                                        <tr>
                                            <th width="7%">No</th>
                                            <th>Nama Pembayaran</th>
                                            <th width="30%">Biaya</th>
                                        </tr>
                                        @for($x=1;$x<10;$x++)
                                            <tr>
                                                <td class="ttdd" style="display:inline;padding:5px;"><input  type="checkbox" name="rincian[]"></td>
                                                <td class="ttdd"><input class="form-cont" type="text" name="name[]"></td>
                                                <td class="ttdd"><input class="form-cont" onkeyup="hitung({{$x}})" type="number"  name="biaya[]" id="biaya{{$x}}" value="0" ></td>
                                            </tr>
                                        @endfor
                                    </table>
                                </div>
                            </div>
                        </form>
                    
                    </div>
                
                    <div class="btn-group" style="width:100%;">
                        <button type="button" class="btn btn-primary btn-flat" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-default btn-flat" id="reload"><i class="fa fa-history"></i></button>
                        
                    </div>
                @endif

                @if($aksi=='edit')
                    <div class="row">
                   
                        <form method="post" id="mydataubah" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Ajaran</label>
                                    <select disabled class="form-control select2 select2-hidden-accessible" name="tahun_ajaran" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                                        <option value="">Pilih</option>
                                        @foreach(get_tahun_ajaran() as $get_tahun_ajaran)
                                            <option value="{{$get_tahun_ajaran['name']}}" @if($data['tahun_ajaran']==$get_tahun_ajaran['name']) selected @endif>{{$get_tahun_ajaran['name']}}</option>
                                        @endforeach
                                    
                                    </select>
                                    <input type="hidden" name="tahun_ajaran" value="{{$data['tahun_ajaran']}}">
                                    <input type="hidden" name="kelas" value="{{$data['kelas']}}">
                                </div>
                                <div class="form-group" style="">
                                    <label>Kelas</label>
                                    <select disabled class="form-control select2 select2-hidden-accessible" name="kelas" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option value="">Pilih Kelas</option>
                                        @foreach(get_kelas() as $get_kelas)
                                            <option value="{{$get_kelas['kelas']}}" @if($data['kelas']==$get_kelas['kelas']) selected @endif>Kelas {{$get_kelas['kelas']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="">
                                    <label>Total Biaya</label>
                                    <input class="form-control" readony value="{{$data['biaya']}}" type="text" id="totalbiaya" name="totalbiaya">
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="">
                                    <label>Rincian Daftar Ulang</label>
                                    <table width="100%">
                                        <tr>
                                            <th width="7%">No</th>
                                            <th>Nama Pembayaran </th>
                                            <th width="30%">Biaya</th>
                                        </tr>
                                        <?php $no=1; ?>
                                        @foreach($detail as $no=>$det)
                                            <tr>
                                                <td class="ttdd" style="display:inline;padding:5px;"><input  type="checkbox" checked name="rincian[]"> <input  type="hidden" name="id[]" value="{{$det['id']}}"></td>
                                                <td class="ttdd"><input class="form-cont" type="text" name="name[]" value="{{$det['name']}}"></td>
                                                <td class="ttdd"><input class="form-cont" onkeyup="hitung({{($no+1)}})" type="number"  name="biaya[]" id="biaya{{($no+1)}}" value="{{$det['biaya']}}" ></td>
                                            </tr>
                                        @endforeach
                                        @for($x=$sisa;$x<10;$x++)
                                            <tr>
                                                <td class="ttdd" style="display:inline;padding:5px;"><input  type="checkbox" name="rincian[]"></td>
                                                <td class="ttdd"><input class="form-cont" type="text" name="name[]"></td>
                                                <td class="ttdd"><input class="form-cont" onkeyup="hitung({{$x}})" type="number"  name="biaya[]" id="biaya{{$x}}" value="0" ></td>
                                            </tr>
                                        @endfor
                                    </table>
                                </div>
                            </div>
                        </form>
                    
                    </div>
                
                    <div class="btn-group" style="width:100%;">
                        <button type="button" class="btn btn-primary btn-flat" id="simpan_ubah"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-default btn-flat" id="reload"><i class="fa fa-history"></i></button>
                        
                    </div>
                @endif
            </div>
            <hr style="border:dotted  #6b7373 1px">
            <div class="box-body" >
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
            <div class="modal-content">
                <div class="modal-body" style="text-align:center">
                    <div id="notifikasi"></div>
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
                jum += eval($("#biaya"+a).val());
            }
            $("#totalbiaya").val(jum);
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
                        "url": "{{url('/api_daftarulang/'.$link)}}",
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
                            "title": "Tahun Ajaran",
                            "render": function (data, row, type, meta) {
                                return data.tahun_ajaran;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Kelas",
                            "render": function (data, row, type, meta) {
                                return data.kelas;
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
                            "title": "",
                            "width":"5%",
                            "sortable": false,
                            "render": function (data, row, type, meta) {
                                let btn = '';

                                
                                        btn += '<span class="btn btn-success btn-xs" onclick="ubah('+data.id+')"><i class="fa fa-pencil"></i></span>';
                                    

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

                                
                                        btn += '<span class="btn btn-danger btn-xs" onclick="hapus('+data.id+')"><i class="fa fa-remove"></i></span>';
                                    

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
                    url: "{{url('/simpan_daftarulang/'.$link)}}",
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
                    url: "{{url('/simpan_ubah_daftarulang/'.$link.'/'.$data['id'])}}",
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
                var kat=$('#kat').val();
                window.open("{{url($link.'/cetak')}}/"+kat, '_blank');
                        
            });

            $('#tambah').click(function(){
                window.location.assign("{{url($link.'/tambah')}}");
            });
        });

        $('#cari').click(function(){
            var kat=$('#kat').val();
            window.location.assign("{{url($link)}}?kat="+kat);
        });

        function ubah(a){
            window.location.assign("{{url($link.'/edit/')}}/"+a);
        }

        function hapus(a){
            $.ajax({
                type: 'GET',
                url: "{{url('/hapus_daftarulang/'.$link)}}/"+a,
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
