@extends('layouts.app_admin')

@section('content')
<section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Table With Full Features</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="tabeldata" class="table table-bordered table-striped">
                
              </table>
              
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
            
            if($(window).width()>='600'){
                var table = $('#tabeldata').DataTable({
                    responsive: true,
                    oLanguage: {"sSearch": "<span class='btn btn-default btn sm'><i class='fa fa-search'></i></span>" },
                    "ajax": {
                        "type": "GET",
                        "url": "{{url('user/api')}}",
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
