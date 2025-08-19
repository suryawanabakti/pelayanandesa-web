<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan - {{ $permohonan->jenis_layanan }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.3;
            font-size: 10pt;
            color: #000;
        }
        .container {
            max-width: 21cm;
            margin: 0 auto;
            padding: 0.2cm;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: auto;
        }
        .header h2, .header h3 {
            margin: 2px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
        }
        .header h3 {
            font-size: 11pt;
        }
        .alamat {
            font-size: 9pt;
            margin-top: 2px;
        }
        .title {
            text-align: center;
            margin: 15px 0 10px;
        }
        .title h1 {
            font-size: 11pt;
            text-transform: uppercase;
            margin: 0;
            text-decoration: underline;
        }
        .title p {
            margin: 2px 0;
        }
        .content {
            margin-top: 5px;
        }
        .intro {
            margin-bottom: 0px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .data-table td:first-child {
            width: 140px;
        }
        .data-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .statement {
            text-align: justify;
            margin-bottom: 10px;
        }
        .signature {
            margin-top: 20px;
            text-align: right;
            width: 100%;
        }
        .signature-content {
            display: inline-block;
            text-align: center;
            width: 180px;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
            text-decoration: underline;
        }
        .stamp-area {
            height: 60px;
            position: relative;
        }
        .stamp {
            position: absolute;
            width: 100px;
            height: 100px;
            opacity: 0.8;
            top: -30px;
            left: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('logo.jpg') }}" alt="Logo Kabupaten Mamasa" class="logo">
            <h2>Pemerintah Kabupaten Mamasa</h2>
            <h3>Kecamatan Pana</h3>
            <h3>Desa Sapan</h3>
            <p class="alamat">Alamat: Sapan, Kecamatan Pana, Kabupaten Mamasa, Sulawesi Barat</p>
        </div>

        <div class="title">
            <h1>Surat Keterangan</h1>
            <p>Nomor: {{ $nomor_surat ?? 'SK/TDS-SPN/'.date('m').'/'.date('Y') }}</p>
        </div>

        <div class="content">
            <div class="intro"><p>Yang bertandatangan di bawah ini:</p></div>
            <table class="data-table">
                <tr><td>Nama</td><td>:</td><td>{{ $kepala_desa ?? 'BUTTU LEMBANG, S.Kom' }}</td></tr>
                <tr><td>Jabatan</td><td>:</td><td>Kepala Desa</td></tr>
                <tr><td>Alamat</td><td>:</td><td>{{ $alamat_desa ?? 'Desa Sapan' }}</td></tr>
            </table>

            <div class="intro"><p>Dengan ini menerangkan bahwa:</p></div>
            <table class="data-table">
                <tr><td>Nama</td><td>:</td><td>{{ $permohonan->nama }}</td></tr>
                <tr><td>Tempat/Tgl Lahir</td><td>:</td><td>{{ $permohonan->tempat_lahir }}, {{ date('d-m-Y', strtotime($permohonan->tanggal_lahir)) }}</td></tr>
                @if($permohonan->jenis_layanan == 'Surat Penyaluran BLT')
                <tr><td>Pekerjaan</td><td>:</td><td>{{ $permohonan->pekerjaan }}</td></tr>
                @else
                <tr><td>Nama Orang Tua</td><td>:</td><td>{{ $permohonan->nama_orang_tua }}</td></tr>
                @endif
                <tr><td>NIK</td><td>:</td><td>{{ $permohonan->nik }}</td></tr>
                <tr><td>Umur</td><td>:</td><td>{{ $permohonan->umur }} Tahun</td></tr>
                <tr><td>Alamat</td><td>:</td><td>{{ $permohonan->alamat }}</td></tr>
            </table>

            <div class="statement">
                @switch($permohonan->jenis_layanan)
                    @case('Surat keterangan tidak mampu')
                        <p>Benar yang tersebut di atas adalah keluarga tidak mampu yang berdomisili di Desa Sapan, Kecamatan Pana, Kabupaten Mamasa, Sulawesi Barat.</p>
                        @break
                    @case('Surat Izin Usaha')
                        <p>Benar yang tersebut di atas adalah warga Desa Sapan yang memiliki usaha dan telah memenuhi persyaratan untuk mendapatkan izin usaha di wilayah Desa Sapan.</p>
                        @break
                    @case('Surat Pindah Penduduk')
                        <p>Benar yang tersebut di atas adalah warga Desa Sapan yang akan pindah ke {{ $permohonan->keterangan }} terhitung mulai tanggal {{ date('d F Y', strtotime($permohonan->tanggal)) }}.</p>
                        @break
                    @case('Surat Penyaluran BLT')
                        <p>Benar yang tersebut di atas adalah keluarga penerima BLT berdasarkan hasil musyawarah dan kriteria kelayakan menerima Dana Desa.</p>
                        @break
                    @case('Surat Stanting')
                        <p>Benar yang tersebut di atas adalah anak ke {{ $permohonan->keterangan ?? '...' }} dari {{ $permohonan->nama_orang_tua }} yang termasuk Lokus Stunting di Desa kami.</p>
                        @break
                    @default
                        <p>{{ $permohonan->keterangan }}</p>
                @endswitch
                <p>Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class="signature">
                <div class="signature-content">
                    <p>Sapan, {{ date('d F Y') }}</p>
                    <p>Kepala Desa</p>
                    
                      
               
                    <div class="signature-name">  <img src="{{ public_path('ttd.png') }}" width="100" /> </br>  </br> {{ $kepala_desa ?? 'BUTTU LEMBANG, S.Kom' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
