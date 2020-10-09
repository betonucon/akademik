<ul class="sidebar-menu" data-widget="tree">
    
    <li><a href="{{url('/')}}"><i class="fa fa-home"></i>Halaman Utama</a></li>
    @if(Auth::user()['role_id']==1)
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Pengaturan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('master/title')}}"><i class="fa fa-gear"></i> Judul Halaman</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Master</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('master/')}}"><i class="fa fa-calendar"></i> Tahun Ajaran</a></li>
                <li><a href="{{url('master/kelas')}}"><i class="fa fa-mortar-board"></i> Kelas</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Registrasi Online</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('siswa/online')}}"><i class="fa fa-users"></i> Registrasi</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Siswa</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('siswa')}}"><i class="fa fa-users"></i> Data Siswa</a></li>
                <li><a href="{{url('siswa/kelas')}}"><i class="fa fa-users"></i> Data Kelas</a></li>
            </ul>
        </li>
    @endif  

    @if(Auth::user()['role_id']==0)
        <li class="header" style="font-size:15px;color:#c1c189">Data Diri</li>
        <li><a href="{{url('siswa/profil')}}"><i class="fa fa-user"></i> Profil Siswa</a></li>
    @endif 

    @if(Auth::user()['role_id']==3)
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Pengaturan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('beasiswa')}}"><i class="fa fa-gear"></i> SPP</a></li>
                <li><a href="{{url('beasiswa_daftar')}}"><i class="fa fa-gear"></i> Daftar Ulang</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Master Pembayaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('spp/')}}"><i class="fa fa-building"></i> SPP</a></li>
                <li><a href="{{url('daftarulang/')}}"><i class="fa fa-building-o"></i> Dafar Ulang</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Transaksi Pembayaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('pembayaran_spp/')}}"><i class="fa fa-building"></i> SPP</a></li>
                <li><a href="{{url('pembayaran/')}}"><i class="fa fa-building-o"></i> Dafar Ulang</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Transaksi Keuangan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('keuangan/')}}"><i class="fa fa-building"></i> Keuangan</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Rekapan Pembayaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                
                <li><a href="{{url('rekapan_daftar_ulang/')}}"><i class="fa fa-building-o"></i> Dafar Ulang Siswa</a></li>
                <li><a href="{{url('rekapan_spp_all/')}}"><i class="fa fa-building-o"></i> SPP Siswa</a></li>
                <li><a href="{{url('rekapan_spp/pembayaran_spp/')}}"><i class="fa fa-building"></i>Rincian SPP</a></li>
                <!-- <li><a href="{{url('rekapan/pembayaran/')}}"><i class="fa fa-building-o"></i> Dafar Ulang</a></li> -->
            </ul>
        </li>
        <!-- <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Rekapan SPP</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('rekapan_spp/pembayaran_spp/')}}"><i class="fa fa-building"></i> SPP</a></li>
                <li><a href="{{url('rekapan_spp_all/')}}"><i class="fa fa-building-o"></i> Semua SPP</a></li>
            </ul>
        </li> -->
        <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Laporan Keuangan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('rekapan_keuangan')}}"><i class="fa fa-building"></i> Semua Pembayaran</a></li>
                <li><a href="{{url('rekapan_keuangan_spp')}}"><i class="fa fa-building"></i> SPP</a></li>
                <li><a href="{{url('rekapan_keuangan_daftar')}}"><i class="fa fa-building"></i> Daftar Ulang</a></li>
            </ul>
        </li>
    @endif

    @if(Auth::user()['role_id']==2)
        <li class="header" style="font-size:15px;color:#c1c189">Data Diri</li>
        <li><a href="{{url('siswa/profil')}}"><i class="fa fa-user"></i> Profil Siswa</a></li>
        <li class="header" style="font-size:15px;color:#c1c189">Akademik</li>
        <li><a href="{{url('siswa/view/'.cek_siswa(Auth::user()['nik'])['id'])}}"><i class="fa fa-graduation-cap"></i> Riwayat Kelas</a></li>
        <li class="header" style="font-size:15px;color:#c1c189">Pembayaran</li>
        <li><a href="{{url('riwayat_pembayaran')}}"><i class="fa fa-money"></i> Riwayat Pembayaran</a></li>
    @endif 

</ul>