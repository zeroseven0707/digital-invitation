@extends('legal.layout')

@section('title', 'Kebijakan Privasi')
@section('meta_description', 'Kebijakan Privasi Nikahin — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.')
@section('hero_title', 'Kebijakan Privasi')

@section('sidebar_nav')
  <a href="#pendahuluan"   class="sidebar-link active"><span class="sidebar-num">1</span>Pendahuluan</a>
  <a href="#data"          class="sidebar-link"><span class="sidebar-num">2</span>Data Dikumpulkan</a>
  <a href="#penggunaan"    class="sidebar-link"><span class="sidebar-num">3</span>Cara Kami Menggunakan</a>
  <a href="#berbagi"       class="sidebar-link"><span class="sidebar-num">4</span>Berbagi dengan Pihak Ketiga</a>
  <a href="#keamanan"      class="sidebar-link"><span class="sidebar-num">5</span>Keamanan Data</a>
  <a href="#hak"           class="sidebar-link"><span class="sidebar-num">6</span>Hak Pengguna</a>
  <a href="#anak"          class="sidebar-link"><span class="sidebar-num">7</span>Perlindungan Anak</a>
  <a href="#cookie"        class="sidebar-link"><span class="sidebar-num">8</span>Cookie</a>
  <a href="#perubahan"     class="sidebar-link"><span class="sidebar-num">9</span>Perubahan Kebijakan</a>
  <a href="#kontak"        class="sidebar-link"><span class="sidebar-num">10</span>Hubungi Kami</a>
@endsection

@section('sections')

{{-- 1 --}}
<div class="section" id="pendahuluan" data-section="pendahuluan">
  <div class="section-header">
    <div class="section-num">01</div>
    <div class="section-title-wrap">
      <h2>Pendahuluan</h2>
      <div class="section-subtitle">Komitmen kami terhadap privasi Anda</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin berkomitmen melindungi privasi pengguna. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat menggunakan aplikasi mobile dan layanan web Nikahin.</p>
    <div class="callout callout-success">
      <span class="callout-icon">🛡️</span>
      <strong>Janji kami:</strong> Kami tidak pernah menjual data pribadi Anda. Data Anda hanya digunakan untuk memberikan layanan terbaik kepada Anda.
    </div>
  </div>
</div>

{{-- 2 --}}
<div class="section" id="data" data-section="data">
  <div class="section-header">
    <div class="section-num">02</div>
    <div class="section-title-wrap">
      <h2>Data yang Kami Kumpulkan</h2>
      <div class="section-subtitle">Jenis informasi yang dikumpulkan saat Anda menggunakan Nikahin</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Informasi Akun</h3>
    <ul>
      <li><strong>Nama lengkap</strong> — identifikasi pengguna dalam sistem</li>
      <li><strong>Alamat email</strong> — untuk login dan komunikasi layanan</li>
      <li><strong>Kata sandi</strong> — disimpan dalam bentuk terenkripsi (bcrypt hash), tidak pernah dalam plain text</li>
    </ul>

    <h3>Informasi Undangan</h3>
    <ul>
      <li>Nama lengkap mempelai dan orang tua</li>
      <li>Tanggal, waktu, dan nama lokasi acara</li>
      <li>Alamat lengkap dan koordinat GPS lokasi acara</li>
      <li>File musik latar (jika diupload)</li>
    </ul>

    <h3>Data Tamu</h3>
    <ul>
      <li>Nama tamu undangan</li>
      <li>Nomor telepon (opsional, hanya untuk fitur WA Blast)</li>
      <li>Kategori tamu (keluarga, teman, rekan, dll.)</li>
      <li>Status RSVP, jumlah tamu hadir, dan catatan tamu</li>
      <li>Waktu check-in dan check-out melalui QR code</li>
    </ul>

    <h3>Konten yang Diupload</h3>
    <ul>
      <li>Foto galeri pernikahan</li>
      <li>File musik latar undangan (MP3, M4A, dll.)</li>
    </ul>

    <h3>Informasi Pembayaran</h3>
    <ul>
      <li>Riwayat transaksi (nomor order, status, jumlah)</li>
      <li>Nomor rekening bank (opsional, untuk fitur amplop digital)</li>
    </ul>
    <div class="callout callout-info">
      <span class="callout-icon">ℹ️</span>
      Data kartu kredit/debit <strong>tidak disimpan</strong> di server kami. Semua proses pembayaran ditangani langsung oleh Midtrans dengan standar keamanan PCI-DSS.
    </div>

    <h3>Data Penggunaan Aplikasi</h3>
    <ul>
      <li>Alamat IP dan jenis perangkat</li>
      <li>Versi browser dan sistem operasi</li>
      <li>Waktu dan frekuensi kunjungan halaman undangan</li>
      <li>Log interaksi fitur untuk keperluan debugging</li>
    </ul>

    <h3>Izin Perangkat yang Digunakan</h3>
    <div class="tag-row" style="margin-top: 4px">
      <div class="tag"><div class="tag-dot" style="background:#6b4ce6"></div> Kamera — scan QR code tamu</div>
      <div class="tag"><div class="tag-dot" style="background:#10b981"></div> Galeri Foto — upload foto undangan</div>
      <div class="tag"><div class="tag-dot" style="background:#3b82f6"></div> Internet — sinkronisasi data</div>
    </div>
  </div>
</div>

{{-- 3 --}}
<div class="section" id="penggunaan" data-section="penggunaan">
  <div class="section-header">
    <div class="section-num">03</div>
    <div class="section-title-wrap">
      <h2>Cara Kami Menggunakan Data</h2>
      <div class="section-subtitle">Tujuan pemrosesan informasi pribadi Anda</div>
    </div>
  </div>
  <div class="section-body">
    <p>Kami menggunakan informasi yang dikumpulkan hanya untuk tujuan berikut:</p>
    <ul>
      <li>Menyediakan, mengoperasikan, dan meningkatkan layanan Nikahin</li>
      <li>Membuat, menyimpan, dan menampilkan undangan pernikahan digital</li>
      <li>Memproses pembayaran secara aman melalui Midtrans</li>
      <li>Mengirim notifikasi layanan (RSVP baru, check-in tamu)</li>
      <li>Memberikan dukungan teknis kepada pengguna</li>
      <li>Menganalisis pola penggunaan untuk peningkatan fitur</li>
      <li>Mencegah penyalahgunaan dan aktivitas ilegal</li>
      <li>Memenuhi kewajiban hukum dan regulasi</li>
    </ul>
  </div>
</div>

{{-- 4 --}}
<div class="section" id="berbagi" data-section="berbagi">
  <div class="section-header">
    <div class="section-num">04</div>
    <div class="section-title-wrap">
      <h2>Berbagi Data dengan Pihak Ketiga</h2>
      <div class="section-subtitle">Kapan dan kepada siapa data Anda dibagikan</div>
    </div>
  </div>
  <div class="section-body">
    <div class="callout callout-success">
      <span class="callout-icon">✅</span>
      Kami <strong>tidak menjual</strong> data Anda. Berbagi data hanya dilakukan untuk keperluan operasional layanan.
    </div>
    <h3>Penyedia Layanan Tepercaya</h3>
    <ul>
      <li><strong>Midtrans</strong> — menerima data transaksi untuk proses pembayaran</li>
      <li><strong>Cloud Hosting</strong> — menyimpan data di server dengan enkripsi</li>
      <li><strong>WhatsApp</strong> — nomor telepon tamu dikirim saat menggunakan fitur WA Blast (dengan izin Anda)</li>
    </ul>
    <h3>Kewajiban Hukum</h3>
    <p>Kami dapat mengungkapkan informasi jika diwajibkan oleh hukum, perintah pengadilan, atau permintaan resmi dari aparat penegak hukum Indonesia.</p>
  </div>
</div>

{{-- 5 --}}
<div class="section" id="keamanan" data-section="keamanan">
  <div class="section-header">
    <div class="section-num">05</div>
    <div class="section-title-wrap">
      <h2>Penyimpanan dan Keamanan Data</h2>
      <div class="section-subtitle">Langkah-langkah teknis untuk melindungi data Anda</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Langkah Keamanan</h3>
    <ul>
      <li>Kata sandi dienkripsi menggunakan algoritma <strong>bcrypt</strong></li>
      <li>Semua transmisi data menggunakan <strong>HTTPS/TLS</strong></li>
      <li>Akses database terbatas hanya untuk sistem dan personel berwenang</li>
      <li>Backup data rutin untuk mencegah kehilangan data</li>
    </ul>
    <h3>Lokasi Penyimpanan</h3>
    <p>Data disimpan di server cloud di <strong>Indonesia atau Asia Tenggara</strong> dengan standar keamanan internasional.</p>
    <h3>Retensi Data</h3>
    <ul>
      <li><strong>Akun aktif</strong> — data disimpan selama akun masih aktif digunakan</li>
      <li><strong>Akun dihapus</strong> — data dihapus permanen dalam <strong>30 hari</strong></li>
      <li><strong>Riwayat transaksi</strong> — disimpan minimal <strong>5 tahun</strong> sesuai peraturan perpajakan Indonesia</li>
    </ul>
  </div>
</div>

{{-- 6 --}}
<div class="section" id="hak" data-section="hak">
  <div class="section-header">
    <div class="section-num">06</div>
    <div class="section-title-wrap">
      <h2>Hak Pengguna</h2>
      <div class="section-subtitle">Kontrol Anda atas data pribadi</div>
    </div>
  </div>
  <div class="section-body">
    <p>Anda memiliki hak berikut terkait data pribadi Anda:</p>
    <ul>
      <li><strong>Akses</strong> — meminta salinan data pribadi yang kami simpan tentang Anda</li>
      <li><strong>Koreksi</strong> — memperbarui atau memperbaiki data yang tidak akurat</li>
      <li><strong>Penghapusan</strong> — meminta penghapusan akun dan semua data terkait</li>
      <li><strong>Portabilitas</strong> — meminta data dalam format yang dapat digunakan kembali</li>
      <li><strong>Penarikan Persetujuan</strong> — mencabut izin akses kamera atau galeri kapan saja melalui pengaturan perangkat</li>
      <li><strong>Keberatan</strong> — menolak pemrosesan data untuk tujuan pemasaran</li>
    </ul>
    <div class="callout callout-info">
      <span class="callout-icon">📧</span>
      Kirimkan permintaan ke <a href="mailto:pamudanyiptakarya@gmail.com"><strong>pamudanyiptakarya@gmail.com</strong></a> dengan subjek <em>"Permintaan Data Pribadi"</em>. Kami akan merespons dalam <strong>14 hari kerja</strong>.
    </div>
  </div>
</div>

{{-- 7 --}}
<div class="section" id="anak" data-section="anak">
  <div class="section-header">
    <div class="section-num">07</div>
    <div class="section-title-wrap">
      <h2>Perlindungan Data Anak</h2>
      <div class="section-subtitle">Kebijakan khusus untuk pengguna di bawah umur</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin <strong>tidak ditujukan untuk anak di bawah usia 17 tahun</strong>. Kami tidak dengan sengaja mengumpulkan data pribadi dari anak-anak.</p>
    <p>Jika kami menemukan bahwa anak di bawah 17 tahun telah memberikan data pribadi tanpa izin orang tua, kami akan segera menghapus data tersebut. Orang tua yang mengetahui hal ini dapat menghubungi kami di <a href="mailto:pamudanyiptakarya@gmail.com">pamudanyiptakarya@gmail.com</a>.</p>
  </div>
</div>

{{-- 8 --}}
<div class="section" id="cookie" data-section="cookie">
  <div class="section-header">
    <div class="section-num">08</div>
    <div class="section-title-wrap">
      <h2>Cookie dan Teknologi Pelacakan</h2>
      <div class="section-subtitle">Penggunaan cookie di website Nikahin</div>
    </div>
  </div>
  <div class="section-body">
    <p>Website Nikahin menggunakan cookie untuk:</p>
    <ul>
      <li>Menyimpan sesi login agar Anda tidak perlu login berulang kali</li>
      <li>Menganalisis traffic dan pola penggunaan website</li>
      <li>Meningkatkan performa dan pengalaman pengguna</li>
    </ul>
    <p>Anda dapat mengatur browser untuk menolak cookie, namun beberapa fitur mungkin tidak berfungsi dengan optimal.</p>
  </div>
</div>

{{-- 9 --}}
<div class="section" id="perubahan" data-section="perubahan">
  <div class="section-header">
    <div class="section-num">09</div>
    <div class="section-title-wrap">
      <h2>Perubahan Kebijakan</h2>
      <div class="section-subtitle">Bagaimana kami memberi tahu Anda tentang pembaruan</div>
    </div>
  </div>
  <div class="section-body">
    <p>Kami dapat memperbarui Kebijakan Privasi ini untuk mencerminkan perubahan praktik atau persyaratan hukum. Perubahan material akan diberitahukan melalui:</p>
    <ul>
      <li>Email ke alamat yang terdaftar di akun Anda</li>
      <li>Notifikasi dalam aplikasi Nikahin</li>
      <li>Banner pengumuman di halaman ini</li>
    </ul>
    <p>Pemberitahuan akan dikirim minimal <strong>14 hari sebelum</strong> perubahan berlaku. Tanggal <em>"Terakhir diperbarui"</em> di bagian atas halaman selalu mencerminkan versi terkini.</p>
  </div>
</div>

{{-- 10 --}}
<div class="section" id="kontak" data-section="kontak">
  <div class="section-header">
    <div class="section-num">10</div>
    <div class="section-title-wrap">
      <h2>Hubungi Kami</h2>
      <div class="section-subtitle">Pertanyaan dan permintaan seputar privasi</div>
    </div>
  </div>
  <div class="section-body">
    <p>Jika Anda memiliki pertanyaan, kekhawatiran, atau permintaan terkait privasi, hubungi kami melalui:</p>
    <div class="tag-row">
      <div class="tag"><div class="tag-dot" style="background:#6b4ce6"></div> Email: pamudanyiptakarya@gmail.com</div>
      <div class="tag"><div class="tag-dot" style="background:#10b981"></div> Subjek: Pertanyaan Privasi</div>
      <div class="tag"><div class="tag-dot" style="background:#3b82f6"></div> Respons: 14 hari kerja</div>
    </div>
  </div>
</div>

@endsection
