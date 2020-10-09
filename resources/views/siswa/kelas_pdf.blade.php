<html>
    <head>
        <title>DAFTAR PRODUK</title>
        <style>
            html{
                margin:10px;
            }
            th{
                border:solid 1px #fff;
                background:aqua;
                padding-left:10px;
                font-size:12px;
            }
            td{
                border-bottom:solid 1px aqua;
                padding-left:10px;
                border-bottom-style: dotted;
                font-size:12px;
            }
            .ttd{
                border-bottom:solid 1px #fff;
                padding-left:0px;
                font-size:12px;
            }
        </style>
    </head>
    <body>
        <table width="100%" border="0">
            <tr>
                <td class="ttd" width="15%"><b>Kelas</b></td>
                <td class="ttd" width="40%"><b>:</b>{{$kelas}}</td>
                <td class="ttd"  width="15%"><b>Tahun Ajaran</b></td>
                <td class="ttd"><b>:</b>{{$tahun}}</td>
            </tr>
            <tr>
                <td class="ttd"><b>Nama Kelas</b></td>
                <td class="ttd"><b>:</b>{{cek_kelas($kelas)['name']}}</td>
                <td class="ttd"><b>Tanggal</b></td>
                <td class="ttd"><b>:</b>{{date('d-m-Y')}}</td>
                
            </tr>
        </table>

        <table width="100%" style="border-collapse:collapse">
            <tr>
                <th width="5%">No</th>
                <th width="8%">Kode</th>
                <th>Nama</th>
                <th width="15%">Angkatan</th>
                <th width="10%">Kelas</th>
                <th width="16%">Tahun Ajaran</th>
            </tr>
            
                @foreach($data as $no=>$data)
                    
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{cek_siswa($data['siswa_id'])['kode']}}</td>
                        <td>{{cek_siswa($data['siswa_id'])['name']}}</td>
                        <td>{{cek_siswa($data['siswa_id'])['tahun_angkatan']}}</td>
                        <td><b>{{$data['kelas']}}</b> {{cek_kelas($data['kelas_id'])['name']}}</td>
                        <td>{{$data['tahun_ajaran']}}</td>
                    </tr>
                    
                @endforeach
            
        </table>
    </body>
</html>