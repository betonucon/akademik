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
        {!!title('rekapan_spp')!!}
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            
            
            <div class="box-body" >
                <div class="margin" style="padding:1%;">
                    <div class="btn-group" style="width:100%;margin-bottom:50px">
                        <button type="button" class="btn btn-success btn-flat" data-toggle="modal" id="cek" data-target="#modalcek"><i class="fa fa-filter"></i> Cek Pembayaran</button>
                        <button type="button" class="btn btn-default btn-flat" id="reload"><i class="fa fa-history"></i></button>
                        
                    </div>
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i>{{$tot}} Rekapan Pembayaran SPP tahun ajaran : {{$tahun}} kelas: kelas {{$kelas}}.
                        <small class="pull-right"><b>{{cek_siswa($id)['kode']}}</b>/{{cek_siswa($id)['name']}}</small>
                    </h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="7%">No</th>
                                <th>Bulan</th>
                                <th width="20%">Dibayar</th>
                                <th width="20%">Tagihan</th>
                                <th width="20%">Belum Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($x=1;$x<13;$x++)
                                @if($x>$tot)
                                    <tr>
                                        <td>{{$x}}</td>
                                        <td>{{bulan(bln($x))}}</td>
                                        <td><i>No</i></td>
                                        <td><i>No</i></td>
                                        <td><i>No</i></td>
                                    </tr>
                                @else
                                <?php
                                    $tagihan=(cek_spp($tahun,$kelas)*beasiswa($id,$tahun,$x))/100;
                                ?>
                                    <tr>
                                        <td>{{$x}}</td>
                                        <td>{{bulan(bln($x))}} </td>
                                        <td>{{nilai_sisa_pembayaran_spp($x,$id,$tahun,$kelas)}}</td>
                                        <td>{{uang(cek_spp($tahun,$kelas)-$tagihan)}}</td>
                                        <td>{{uang((cek_spp($tahun,$kelas)-$tagihan)-nilai_sisa_pembayaran_spp($x,$id,$tahun,$kelas))}}</td>
                                    </tr>
                                @endif
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="margin" style="padding:1%;">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> Rincian Pembayaran SPP tahun ajaran : {{$tahun}} kelas: kelas {{$kelas}}.
                        <small class="pull-right"><b>{{cek_siswa($id)['kode']}}</b>/{{cek_siswa($id)['name']}}</small>
                    </h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="7%">No</th>
                                <th>Nama Pembayaran </th>
                                <th width="20%">Dibayar</th>
                                <th width="20%">Tagihan</th>
                                <th width="20%">Sisa</th>
                                <th width="15%">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(get_pembayaran_spp($id,$tahun,$kelas) as $no=>$get_pembayaran_spp )
                                <tr>
                                    <td>{{$no+1}}</td>
                                    <td>{{bulan(bln($get_pembayaran_spp['bulan']))}}</td>
                                    <td>{{uang($get_pembayaran_spp['biaya'])}}</td>
                                    <td>{{uang($get_pembayaran_spp['tagihan'])}}</td>
                                    <td>{{uang($get_pembayaran_spp['sisa'])}}</td>
                                    <td>{{$get_pembayaran_spp['tanggal']}}</td>
                                </tr>
                        @endforeach
                        </tbody>
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

    <div class="modal fade" id="modalcek" >
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 30%;">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Pencarian Pembayaran SPP</h4>
                </div>
                <div class="modal-body" >
                    
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <select class="form-control select2 select2-hidden-accessible" onchange="cari_siswa(this.value)" id="tahun_ajaran" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                            <option value="">Pilih</option>
                            @foreach(get_tahun_ajaran() as $get_tahun_ajaran)
                                <option value="{{$get_tahun_ajaran['name']}}">{{$get_tahun_ajaran['name']}}</option>
                            @endforeach
                        
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kode Siswa</label>
                        <select class="form-control select2 select2-hidden-accessible siswa_id" id="tampilsiswa" onchange="cari_kode(this.value)" name="siswa_id" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                            <option value="">Pilih</option>
                            
                        </select>
                    </div>
                    <div class="form-group" style="">
                        <label>Kelas</label>
                        <input class="form-control" readony name="kelas" type="text" id="kelas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success pull-left" id="cari_data">Cari Data</button>
                    
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

        function cari_siswa(a){
            $.ajax({
                type: 'GET',
                url: "{{url('/cari_siswa')}}/"+a,
                data: "id="+a,
                success: function(msg){
                    
                    $('#tampilsiswa').html(msg);
                    
                }
            });
        }

        function cari_kode(a){
            // var nik=$('#nik').val();
            
            $.ajax({
                type: 'GET',
                url: "{{url('/cari_nik/pembayaran')}}/"+a,
                data: "id="+a,
                success: function(msg){
                    var tam = msg.split("@");
                    var det = tam[0].split("_");
                    $('#rinciannya').html(tam[1]);
                    $('#kelas').val(det[0]);
                    $('#tahun_ajaran').val(det[1]);
                }
            });
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
                        "url": "{{url('/api/'.$link.'/'.$id.'/'.$tahun.'/'.$kelas)}}",
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
                                return data.nama_biaya;
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

                                
                                        btn += '<span class="btn btn-danger btn-xs" onclick="hapus('+data.id+')"><i class="fa fa-remove"></i></span>';
                                    

                                return btn;
                            }
                        }
                    ]
                });

            

            

            $('#cari_data').click(function(){
                var siswa_id=$('.siswa_id').val();
                var tahun_ajaran_cari=$('#tahun_ajaran').val();
                var kelas_cari=$('#kelas').val();
                window.location.assign("{{url($link.'/')}}/"+siswa_id+"/"+tahun_ajaran_cari+"/"+kelas_cari);
                        
            });

            $('#tambah').click(function(){
                window.location.assign("{{url($link.'/tambah')}}");
            });
        });

        
        
        
    </script>
@endpush
