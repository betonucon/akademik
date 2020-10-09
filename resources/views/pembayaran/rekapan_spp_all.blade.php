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
        {!!title('rekapan_spp')!!}
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            
            
            <div class="box-body" >
                <div class="margin" style="padding:1%;">
                    <div class="btn-group" style="width:100%;">
                        <select class="form-control" id="tahun" style="display:inline;width:20%;margin-left:2px;float:left;margin-bottom:50px">
                            <option value="all">Pilih All---</option>
                            {!!cek_tahun_ajaran($tahun)!!}
                        </select>
                        <select class="form-control" id="kelas_id" style="display:inline;width:20%;margin-left:2px;float:left">
                            <option value="all" @if($kelas=='all') selected @endif>Pilih Kelas---</option>
                            @foreach(get_kelas() as $get_kelas)
                                <option value="{{$get_kelas['kelas']}}" @if($kelas ==$get_kelas['kelas']) selected @endif>Kelas {{$get_kelas['kelas']}}</option>
                            @endforeach
                        </select>
                        
                    
                        <button type="button" class="btn btn-success btn-flat" id="cari"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-success btn-flat" id="cetak"><i class="fa fa-print"></i> Cetak</button>
                        
                    </div>
                    
                    <table class="table table-striped" width="130%" id="tabeldata">
                        
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
                    scrollX: true,
                    scrollCollapse: true,
                    ordering   : false,
                    paging   : true,
                    info   : false,
                    oLanguage: {"sSearch": "<span class='btn btn-default btn sm'><i class='fa fa-search'></i></span>" },
                    "ajax": {
                        "type": "GET",
                        "url": "{{url('/api_rekapan_spp_all/'.$tahun.'/'.$kelas)}}",
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
                            "title": "Nama Siswa",
                            "render": function (data, row, type, meta) {
                                return data.name;
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
                            "title": "Jan",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Jan;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Feb",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Feb;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Mar",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Mar;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Apr",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Apr;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Mei",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Mei;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Jun",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Jun;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Jul",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Jul;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Ags",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Ags;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Sep",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Sep;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Okt",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Okt;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Nov",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Nov;
                            }
                        },
                        {
                            "mData": null,
                            "title": "Des",
                            "width":"6%",
                            "render": function (data, row, type, meta) {
                                return data.Des;
                            }
                        }
                    ]
                });

            

            

            $('#cari').click(function(){
                var tahun_ajaran_cari=$('#tahun').val();
                var kelas_cari=$('#kelas_id').val();
                window.location.assign("{{url('rekapan_spp_all')}}?tahun="+tahun_ajaran_cari+"&kelas="+kelas_cari);
                        
            });
            $('#cetak').click(function(){
                var tahun_ajaran_cari=$('#tahun').val();
                var kelas_cari=$('#kelas_id').val();
                window.open("{{url('pdf_rekapan_spp_all')}}/"+tahun_ajaran_cari+"/"+kelas_cari,"_blank");
                        
            });

            $('#tambah').click(function(){
                window.location.assign("{{url($link.'/tambah')}}");
            });
        });

        
        
        
    </script>
@endpush
