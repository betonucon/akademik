@extends('layouts.app_admin')

@section('content')
<style>
    #tambah{margin-left:2px;}
    #reload{margin-left:2px;}
    #cetak{margin-left:2px;}
</style>
<section class="content">

      <!-- SELECT2 EXAMPLE -->
      {!!title('tambah_tahun')!!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">&nbsp;</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        
        <div class="box-body">
          
          <form method="post" id="mysimpan_data" enctype="multipart/form-data">
             @csrf
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input class="form-control item"  id="phone" placeholder="xxxx-xxxx" type="text" name="name">
                  </div>
                  
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                  
              </div>
              <!-- /.col -->
            </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <span class="btn btn-primary" id="simpan"><i class="fa fa-save text-yellow"></i> Simpan</span>
            <span class="btn btn-success" id="batal"><i class="fa fa-undo text-yellow"></i> Batal</span>
        </div>
      </div>
      

      
      
</section>

  <div class="modal fade" id="modalnotif" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <div id="notifikasi"></div>
            </div>
            
        </div>
    </div>
  </div>
  <div class="modal fade" id="modalloading" >
      <div class="modal-dialog">
          <div class="modal-content" >
              <div class="modal-header">
                  <button type="button" class="close" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">Wait </h4>
              </div>
              <div class="modal-body" style="text-align:center">
                  <img src="{{url('public/img/loading.gif')}}" style="width:30%"></img>
              </div>
              
          </div>
      </div>
  </div>
  
@endsection

@push('datatable')
    <script>
        $(document).ready(function(){
          $(":input").inputmask();



          $("#phone").inputmask({
            mask: '9999-9999',
            placeholder: ' ',
            showMaskOnHover: false,
            showMaskOnFocus: false,
            onBeforePaste: function (pastedValue, opts) {
            var processedValue = pastedValue;

            //do something with it

            return processedValue;
            }
          });
        });
        $(document).ready(function() {
            
            $('#batal').click(function(){
                window.location.assign("{{url($batal)}}");
            });

            $('#simpan').click(function(){
                
                var form=document.getElementById('mysimpan_data');
                
                $.ajax({
                    type: 'POST',
                    url: "{{url('/'.$batal.'/simpan')}}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('#modalloading').modal('show');
                    },
                    success: function(msg){
                        
                        if(msg=='ok'){
                            window.location.assign("{{url($batal)}}")
                        }else{
                            $('#modalloading').modal('hide');
                            $('#modalnotif').modal('show');
                            $('#notifikasi').html(msg);
                        }
                        
                        
                    }
                });

            });
        });

        function hanyaAngka(evt) {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode > 31 && (charCode < 48 || charCode > 57))
    
            return false;
          return true;
        }
        


        function uploadfile(){
          var fileInput = document.getElementById('file');
          var filesize = fileInput.files[0].size;
          var filePath = fileInput.value;
          var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
          if(!allowedExtensions.exec(filePath)){
              $('#modalnotif').modal('show');
              $('#notifikasi').html('<font style="color:red;font-style:italic">Fromat upload harus gambar</font>')
              fileInput.value = '';
              document.getElementById('fileupload').innerHTML = '';
              return false;
          }else{
              //Image preview
              if(filesize>3000000){
                $('#modalnotif').modal('show');
                $('#notifikasi').html('<font style="color:red;font-style:italic">Ukuran file max 200kb</font>')
                fileInput.value = '';
                document.getElementById('fileupload').innerHTML = '';
              }else{
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('fileupload').innerHTML = '<img src="'+e.target.result+'" style="width:80%;height:150px;margin-left:10%"/>';
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
              }
              
          }
        }
    </script>
@endpush
