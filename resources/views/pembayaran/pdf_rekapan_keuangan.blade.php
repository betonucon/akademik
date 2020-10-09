<html>
    <head>
        <title>KEUANGAN MASUK DAN KELUAR_{{$tahun}}_{{$bulan}}</title>
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
            .tth{
                border:solid 1px #fff;
                padding:1px;
                background:#fff;
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
        <center><h3><u>KEUANGAN MASUK DAN KELUAR PERIODE {{$tahun}}<br>{{$kelas}}</u></h3></center>
        <table width="100%" style="border-collapse:collapse">
            <tr>
                <td class="tth" width="15%"><b>Nilai Keuangan Masuk</b></td>
                <td class="tth" width="30%"><b>:</b> {{uang($masuk)}}</td>
                <td class="tth" width="15%"><b>Periode Bulan</b></td>
                <td class="tth"><b>:</b> {{$bulan}}</td>
                
            </tr>
            <tr>
                <td class="tth" ><b>Nilai Keuangan Keluar</b></td>
                <td class="tth"><b>:</b> {{uang($keluar)}}</td>
                <td class="tth"><b>Periode Tahun</b></td>
                <td class="tth"><b>:</b> {{$tahun}}</td>
            </tr>
            <tr>
                <td class="tth" ><b>Total</b></td>
                <td class="tth"><b>:</b> {{uang($masuk-$keluar)}}</td>
                <td class="tth"></td>
                <td class="tth"></td>
            </tr>
        </table><hr style="border:dashed 1px #000">
        <table width="100%" style="border-collapse:collapse">
            <tr>
                <th width="5%">No</th>
                <th width=15%">Tanggal</th>
                <th>Nama Transaksi</th>
                <th width=15%">Nilai</th>
                
            </tr>
            <tr>
                <th colspan="4" style="text-align:left">Masuk</th>
            </tr>
                @foreach($datamasuk as $no=>$datamasuk)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{$datamasuk['tanggal']}}</td>
                        <td>{{$datamasuk['name']}}</td>
                        <td>{{uang($datamasuk['biaya'])}}</td>
                     </tr>
                    
                    
                @endforeach
            <tr>
                <th colspan="4" style="text-align:left">Keluar</th>
            </tr>
                @foreach($datakeluar as $no=>$datakeluar)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{$datakeluar['tanggal']}}</td>
                        <td>{{$datakeluar['name']}}</td>
                        <td>{{uang($datakeluar['biaya'])}}</td>
                     </tr>
                    
                    
                @endforeach
        </table>
    </body>
</html>