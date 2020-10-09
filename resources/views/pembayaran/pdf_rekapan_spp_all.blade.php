<html>
    <head>
        <title>SPP_{{$tahun}}_kelas_{{$kelas}}</title>
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
        <center><h3>PEMBAYARAN SPP PERIODE {{$tahun}}<br>{{$kelas}}</h3></center>
        <table width="100%" style="border-collapse:collapse">
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th width="10%">Tahun Ajaran</th>
                <th width="6%">Kelas</th>
                @for($x=1;$x<13;$x++)
                    <th width="5%">{{substr(bulan(bln($x)),0,3)}}</th>
                @endfor
            </tr>
            
                @foreach($data as $no=>$data)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>[{{cek_siswa($data['siswa_id'])['kode']}}] {{cek_siswa($data['siswa_id'])['name']}}</td>
                        <td>{{$data['tahun_ajaran']}}</td>
                        <td>{{$data['kelas']}}</td>
                        @for($x=1;$x<13;$x++)
                            <td>{{uang(total_spp_siswa($data['siswa_id'],$data['tahun_ajaran'],$x))}}</td>
                        @endfor
                    </tr>
                    
                    
                @endforeach
            
        </table>
    </body>
</html>