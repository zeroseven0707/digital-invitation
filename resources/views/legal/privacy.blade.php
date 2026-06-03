@extends('legal.layout')

@section('title', 'Kebijakan Privasi')
@section('meta_description', 'Kebijakan Privasi aplikasi Nikahin — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.')

@section('content')

<div class="hero">
  <div class="hero-label">Dokumen Hukum</div>
  <h1>Kebijakan Privasi</h1>
  <p class="hero-updated">Terakhir diperbarui: 1 Juni 2025</p>
</div>

<div class="container">

  <div class="toc">
    <div class="toc-title">Daftar Isi</div>
    <ol>
      <li><a href="#pendahuluan">Pendahuluan</a></li>
      <li><a href="#data">Data yang Kami Kumpulkan</a></li>
      <li><a href="#penggunaan">Cara Kami Menggunakan Data</a></li>
      <li><a href="#berbagi">Berbagi Data dengan Pihak Ketiga</a></li>
      <li><a href="#penyimpanan">Penyimpanan dan Keamanan Data</a></li>
      <li><a href="#hak">Hak Pengguna</a></li>
      <li><a href="#anak">Perlindungan Data Anak</a></li>
      <li><a href="#cookie">Cookie dan Teknologi Pelacakan</a></li>
      <li><a href="#perubahan-privacy">Perubahan Kebijakan</a></li>
      <li><a href="#kontak-privacy">Hubungi Kami</a></li>
    </ol>
  </div>

  {{-- 1 --}}
  <div class="section" id="pendahuluan">
    <div class="section-num">1</div>
    <h2>Pendahuluan</h2>
    <p>Nikahin ("kami", "kita") berkomitmen melindungi privasi pengguna. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat menggunakan aplikasi mobile dan layanan web Nikahin.</p>
    <p>Dengan menggunakan Nikahin, Anda menyetujui praktik yang dijelaskan dalam kebijakan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan layanan kami.</p>
  </div>

  {{-- 2 --}}
  <div class="section" id="data">
    <div class="section-num">2</div>
    <h2>Data yang Kami Kumpulkan</h2>
    <h3>2.1 Informasi Akun</h3>
    <ul>
      <li><strong>Nama lengkap</strong> — untuk identifikasi akun</li>
      <li><strong>Alamat email</strong> — untuk login dan komunikasi</li>
      <li><strong>Kata sandi</strong> — disimpan dalam bentuk terenkripsi (hash)</li>
    </ul>
    <h3>2.2 Informasi Undangan</h3>
    <ul>
      <li>Nama mempelai (pria dan wanita)</li>
      <li>Nama orang tua mempelai</li>
      <li>Tanggal, waktu, dan lokasi acara pernikahan</li>
      <li>Alamat lengkap dan koordinat lokasi acara</li>
      <li>File musik undangan (jika diupload)</li>
    </ul>
    <h3>2.3 Data Tamu</h3>
    <ul>
      <li>Nama tamu undangan</li>
      <li>Nomor telepon (opsional, untuk WA Blast)</li>
      <li>Kategori tamu (keluarga, teman, rekan kerja, dll.)</li>
      <li>Status RSVP dan jumlah tamu yang hadir</li>
      <li>Waktu check-in dan check-out (jika menggunakan QR code)</li>
    </ul>
    <h3>2.4 Konten yang Diupload</h3>
    <ul>
      <li>Foto galeri pernikahan</li>
      <li>File musik latar undangan</li>
    </ul>
    <h3>2.5 Informasi Transaksi</h3>
    <ul>
      <li>Riwayat pembayaran melalui Midtrans</li>
      <li>Nomor rekening bank (jika menggunakan fitur amplop digital)</li>
    </ul>
    <h3>2.6 Data Penggunaan dan Analytics</h3>
    <ul>
      <li>Alamat IP dan jenis perangkat</li>
      <li>Browser dan sistem operasi</li>
      <li>Waktu dan frekuensi kunjungan undangan</li>
      <li>Interaksi dengan fitur aplikasi</li>
    </ul>
    <h3>2.7 Izin Perangkat</h3>
    <ul>
      <li><strong>Kamera</strong> — untuk pemindaian QR code tamu</li>
      <li><strong>Galeri foto</strong> — untuk mengunggah foto ke galeri undangan</li>
      <li><strong>Internet</strong> — untuk sinkronisasi data dengan server</li>
    </ul>
  </div>

  {{-- 3 --}}
  <div class="section" id="penggunaan">
    <div class="section-num">3</div>
    <h2>Cara Kami Menggunakan Data</h2>
    <p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
    <ul>
      <li>Menyediakan, mengoperasikan, dan meningkatkan layanan Nikahin</li>
      <li>Membuat dan mengelola undangan pernikahan digital</li>
      <li>Memproses pembayaran melalui Midtrans</li>
      <li>Mengirim notifikasi terkait layanan (RSVP baru, check-in tamu, dll.)</li>
      <li>Memberikan dukungan pelanggan</li>
      <li>Menganalisis penggunaan layanan untuk peningkatan fitur</li>
      <li>Mencegah penyalahgunaan dan aktivitas ilegal</li>
      <li>Mematuhi kewajiban hukum</li>
    </ul>
    <div class="highlight">
      Kami tidak akan pernah menjual data pribadi Anda kepada pihak ketiga.
    </div>
  </div>

  {{-- 4 --}}
  <div class="section" id="berbagi">
    <div class="section-num">4</div>
    <h2>Berbagi Data dengan Pihak Ketiga</h2>
    <p>Kami hanya berbagi data Anda dalam kondisi berikut:</p>
    <h3>4.1 Penyedia Layanan</h3>
    <ul>
      <li><strong>Midtrans</strong> — untuk proses pembayaran (menerima informasi transaksi)</li>
      <li><strong>Hosting provider</strong> — untuk penyimpanan data di server cloud</li>
      <li><strong>WhatsApp Business API</strong> — jika menggunakan fitur WA Blast (nomor telepon tamu)</li>
    </ul>
    <h3>4.2 Kewajiban Hukum</h3>
    <p>Kami dapat mengungkapkan informasi jika diwajibkan oleh hukum, panggilan pengadilan, atau permintaan resmi dari aparat penegak hukum.</p>
    <h3>4.3 Persetujuan Pengguna</h3>
    <p>Kami dapat membagikan data dengan persetujuan eksplisit Anda.</p>
  </div>

  {{-- 5 --}}
  <div class="section" id="penyimpanan">
    <div class="section-num">5</div>
    <h2>Penyimpanan dan Keamanan Data</h2>
    <h3>5.1 Keamanan</h3>
    <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi untuk melindungi data Anda, termasuk:</p>
    <ul>
      <li>Enkripsi data sensitif (kata sandi, informasi pembayaran)</li>
      <li>Koneksi HTTPS/SSL untuk transmisi data</li>
      <li>Akses terbatas hanya untuk personel yang berwenang</li>
      <li>Backup rutin untuk mencegah kehilangan data</li>
    </ul>
    <h3>5.2 Penyimpanan Data</h3>
    <p>Data Anda disimpan di server cloud yang berlokasi di Indonesia atau wilayah Asia Tenggara dengan standar keamanan internasional.</p>
    <h3>5.3 Retensi Data</h3>
    <ul>
      <li><strong>Akun aktif</strong> — data disimpan selama akun masih aktif</li>
      <li><strong>Akun dihapus</strong> — data dihapus permanen dalam 30 hari setelah penghapusan akun</li>
      <li><strong>Riwayat transaksi</strong> — disimpan minimal 5 tahun sesuai peraturan perpajakan Indonesia</li>
    </ul>
  </div>

  {{-- 6 --}}
  <div class="section" id="hak">
    <div class="section-num">6</div>
    <h2>Hak Pengguna</h2>
    <p>Anda memiliki hak berikut terkait data pribadi Anda:</p>
    <ul>
      <li><strong>Akses</strong> — meminta salinan data pribadi yang kami simpan</li>
      <li><strong>Koreksi</strong> — memperbarui atau memperbaiki data yang tidak akurat</li>
      <li><strong>Penghapusan</strong> — meminta penghapusan data pribadi Anda</li>
      <li><strong>Portabilitas</strong> — meminta data dalam format yang dapat dipindahkan</li>
      <li><strong>Penarikan persetujuan</strong> — mencabut izin akses kamera atau galeri kapan saja</li>
      <li><strong>Keberatan</strong> — menolak pemrosesan data untuk tujuan tertentu</li>
    </ul>
    <p>Untuk menggunakan hak ini, hubungi kami di <a href="mailto:support@nikahin.id" style="color:#6b4ce6;font-weight:500;">support@nikahin.id</a>. Kami akan merespons permintaan Anda dalam 14 hari kerja.</p>
  </div>

  {{-- 7 --}}
  <div class="section" id="anak">
    <div class="section-num">7</div>
    <h2>Perlindungan Data Anak</h2>
    <p>Nikahin tidak ditujukan untuk anak di bawah usia 17 tahun. Kami tidak dengan sengaja mengumpulkan informasi pribadi dari anak-anak. Jika kami mengetahui bahwa seorang anak telah memberikan informasi pribadi, kami akan segera menghapus data tersebut.</p>
  </div>

  {{-- 8 --}}
  <div class="section" id="cookie">
    <div class="section-num">8</div>
    <h2>Cookie dan Teknologi Pelacakan</h2>
    <p>Website Nikahin menggunakan cookie dan teknologi serupa untuk:</p>
    <ul>
      <li>Mengingat preferensi login Anda</li>
      <li>Menganalisis traffic dan penggunaan website</li>
      <li>Meningkatkan performa dan pengalaman pengguna</li>
    </ul>
    <p>Anda dapat mengatur browser untuk menolak cookie, tetapi beberapa fitur mungkin tidak berfungsi dengan baik.</p>
  </div>

  {{-- 9 --}}
  <div class="section" id="perubahan-privacy">
    <div class="section-num">9</div>
    <h2>Perubahan Kebijakan</h2>
    <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu untuk mencerminkan perubahan praktik kami atau persyaratan hukum. Perubahan material akan diberitahukan melalui email atau notifikasi dalam aplikasi minimal 14 hari sebelum berlaku.</p>
    <p>Tanggal "Terakhir diperbarui" di bagian atas halaman menunjukkan kapan kebijakan ini terakhir direvisi.</p>
  </div>

  {{-- 10 --}}
  <div class="section" id="kontak-privacy">
    <div class="section-num">10</div>
    <h2>Hubungi Kami</h2>
    <p>Jika Anda memiliki pertanyaan, kekhawatiran, atau permintaan terkait privasi Anda, hubungi kami di:</p>
    <ul>
      <li><strong>Email:</strong> support@nikahin.id</li>
      <li><strong>Subjek:</strong> Pertanyaan Privasi</li>
    </ul>
    <p>Kami akan merespons permintaan Anda dalam waktu maksimal 14 hari kerja.</p>
  </div>

  <div class="contact-card">
    <h3>Masih Ada Pertanyaan?</h3>
    <p>Tim kami siap membantu menjawab pertanyaan Anda tentang bagaimana kami melindungi data pribadi Anda.</p>
    <a href="mailto:support@nikahin.id">Hubungi Kami</a>
  </div>

</div>
@endsection
