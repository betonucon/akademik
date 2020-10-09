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
        {!!title('rekapan_keuangan')!!}
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            
            
            <div class="box-body" >
                <div class="margin" style="padding:1%;">
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
                    </div>
                    <div class="alert alert-dismissible" style="color:#615757;background:#fafafd;margin-top:10px">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i>Rekapan Pembayaran Daftar Ulang</h4>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background: none;padding:2px">
                                <b>Daftar Ulang :</b> {{uang(total_keuangan_daftar($bulan,$tahun))}}</a>
                            </li>
                           
                        </ul>
                    </div>
                    <table id="tabeldata" class="table table-bordered table-striped">
                        
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
                    "order": [[ 8, "desc" ]],
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
                            "title": "Kode Siswa",
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
                            "title": "Nama Pembayaran",
                            "render": function (data, row, type, meta) {
                                return data.nama_pembayaran;
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
                            "title": "",
                            "width":"5%",
                            "sortable": false,
                            "render": function (data, row, type, meta) {
                                let btn = '';

                                
                                        btn += '<i class="fa fa-check"></i>';
                                    

                                return btn;
                            }
                        }
                    ]
                });

            

            

            $('#cari').click(function(){
                var bulan=$('#bulan').val();
                var tahun=$('#tahun').val();
                window.location.assign("{{url($link)}}?bulan="+bulan+"&tahun="+tahun);
                        
            });

            $('#tambah').click(function(){
                window.location.assign("{{url($link.'/tambah')}}");
            });
        });

        
        
        
    </script>
@endpush
