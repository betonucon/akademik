@extends('layouts.app_admin')

@section('content')
<style>
    #tambah{margin-left:2px;}
    #reload{margin-left:2px;}
    #cetak{margin-left:2px;}
</style>
<section class="content">

      <!-- SELECT2 EXAMPLE -->
      {!!title('tambah_siswa')!!}
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
             <input type="hidden" value="{{$data['kode']}}" name="kode">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label>No Register</label>
                    <input class="form-control" disabled value="{{Auth::user()['nik']}}" type="text" name="name">
                  </div>
                  <div class="form-group">
                    <label>Nama</label>
                    <input class="form-control" disabled value="{{Auth::user()['name']}}" type="text" name="name">
                  </div>
                  <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="3">{{$data['alamat']}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input class="form-control"  value="{{$data['tempat_lahir']}}"  placeholder="Tempat Lahir" type="text" name="tempat_lahir" >
                  </div>
                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input class="form-control"  value="{{$data['tgl_lahir']}}"  placeholder="yyyy-mm-dd" type="text" name="tgl_lahir" id="datepicker" >
                  </div>
                  <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control select2 select2-hidden-accessible" name="jenis_kelamin" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" @if($data['jenis_kelamin']=='Laki-laki') selected @endif>Laki-Laki</option>
                        <option value="Perempuan" @if($data['jenis_kelamin']=='Perempuan') selected @endif>Perempuan</option>
                       
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Agama</label>
                    <select class="form-control select2 select2-hidden-accessible" name="agama" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                        <option value="">Pilih Agama</option>
                        @foreach(agama() as $agama)
                          <option value="{{$agama['name']}}" @if($data['agama']==$agama['name']) selected @endif>{{$agama['name']}}</option>
                        @endforeach
                    </select>
                  </div>
                  
              </div>
              <!-- /.col -->
              @if($data['file']=='')
                <div class="col-md-4">
                    <div class="form-group" style="height:150px">
                      <label>Foto</label>
                      <input class="form-control" id="file" name="file" onchange="return uploadfile(event)" type="file">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="fileupload" style="height:150px;background:aqua">
                      
                    </div>
                </div>

              @else
                <div class="col-md-4">
                    <div class="form-group" style="height:150px">
                      <label>Foto</label><br>
                      <span class="btn btn-danger btn-sm" id="hapus_file"><i class="fa fa-remove"></i> Hapus Foto</span>
                      <input class="form-control" id="file" name="fileedit" value="{{$data['file']}}" onchange="return uploadfile(event)" type="hidden">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group"  style="height:150px;background:aqua">
                      <img src="{{url('public/_file_upload/'.$data['file'])}}" style="width:80%;height:150px;margin-left:10%"/>
                    </div>
                </div>

              @endif
                
              <div class="col-md-6">
                  <div class="form-group">
                    <label>Kewarganegaraan {{$data['id']}}</label>
                    <select class="form-control select2 select2-hidden-accessible" name="kewarganegaraan" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">
                        <option value="">Pilih</option>
                        <option value="WNI"  @if($data['kewarganegaraan']=='WNI') selected @endif>WNI</option>
                        <option value="WNA"  @if($data['kewarganegaraan']=='WNA') selected @endif>WNA</option>
                       
                    </select>
                  </div>
                  <div class="form-group" style="">
                    <label>Tahun Masuk</label>
                    <input class="form-control" value="{{$data['tahun_angkatan']}}"  placeholder="2xxx" onkeypress="return hanyaAngka(event)" type="text" name="tahun_angkatan">
                  </div>
                  <div class="form-group" style="">
                    <label>Anak Ke</label>
                    <input class="form-control" value="{{$data['anak_ke']}}"  placeholder="Anak Ke" onkeypress="return hanyaAngka(event)" type="text" name="anak_ke">
                  </div>
                  <div class="form-group" style="">
                    <label>Jumlah Sodara Kandung</label>
                    <input class="form-control" value="{{$data['jumlah_sodara']}}"  placeholder="Jumlah Sodara" onkeypress="return hanyaAngka(event)" type="text" name="jumlah_sodara">
                  </div>
                  
                  
                  
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
        
        $(document).ready(function() {
            
            $('#batal').click(function(){
                window.location.assign("{{url($batal)}}");
            });

            $('#hapus_file').click(function(){
                
                $.ajax({
                    type: 'GET',
                    url: "{{url('/'.$batal.'/hapus_file/'.$data['id'])}}",
                    data: "id=1",
                    beforeSend: function(){
                        $('#modalloading').modal('show');
                    },
                    success: function(msg){
                        location.reload();
                    }
                });
            });

            $('#simpan').click(function(){
                
                var form=document.getElementById('mysimpan_data');
                
                $.ajax({
                    type: 'POST',
                    url: "{{url('/'.$batal.'/simpan_ubah/'.$data['id'])}}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('#modalloading').modal('show');
                    },
                    success: function(msg){
                        
                        if(msg=='ok'){
                            window.location.assign("{{url('/')}}")
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
