<html>
    <head>
        <title>DAFTAR_ULANG_{{$tahun}}_kelas_{{$kelas}}</title>
        <style>
            html{
                margin:10px;
            }
            th{
                border:solid 1px #000;
                background:aqua;
                padding-left:10px;
                font-size:12px;
            }
            td{
                border:solid 1px #000;
                padding-left:10px;
                font-size:12px;
            }
        </style>
    </head>
    <body>
        <center><h3>PEMBAYARAN DAFTAR ULANG PERIODE {{$tahun}}<br>{{$kelas}}</h3></center>
        <table width="100%" style="border-collapse:collapse">
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th width="10%">Tahun Ajaran</th>
                <th width="6%">Kelas</th>
                <th width="10%">Dibayar</th>
                <th width="10%">Tagihan</th>
                <th width="10%">Potongan</th>
                <th width="10%">Kurang</th>
                
            </tr>
            
                @foreach($data as $no=>$data)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>[{{cek_siswa($data['siswa_id'])['kode']}}] {{cek_siswa($data['siswa_id'])['name']}}</td>
                        <td>{{$data['tahun_ajaran']}}</td>
                        <td>{{$data['kelas']}}</td>
                        <td>{{uang(tagihan_tahun_ajaran_dibayar($data['siswa_id'],$data['tahun_ajaran'],$data['kelas']))}}</td>
                        <td>{{uang(tagihan_tahun_ajaran($data['tahun_ajaran'],$data['kelas']))}}</td>
                        <td>{{uang(tagihan_tahun_ajaran_potongan($data['siswa_id'],$data['tahun_ajaran'],$data['kelas']))}}</td>
                        <td>{{uang((tagihan_tahun_ajaran($data['tahun_ajaran'],$data['kelas'])-tagihan_tahun_ajaran_potongan($data['siswa_id'],$data['tahun_ajaran'],$data['kelas']))-tagihan_tahun_ajaran_dibayar($data['siswa_id'],$data['tahun_ajaran'],$data['kelas']))}}</td>
                       
                    </tr>
                    
                    
                @endforeach
            
        </table>
    </body>
</html>