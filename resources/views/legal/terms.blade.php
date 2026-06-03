@extends('legal.layout')

@section('title', 'Ketentuan Layanan')
@section('meta_description', 'Ketentuan Layanan aplikasi Nikahin — undangan pernikahan digital untuk pasangan modern Indonesia.')
@section('hero_title', 'Ketentuan Layanan')

@section('sidebar_nav')
  <a href="#penerimaan"    class="sidebar-link active"><span class="sidebar-num">1</span>Penerimaan Ketentuan</a>
  <a href="#deskripsi"     class="sidebar-link"><span class="sidebar-num">2</span>Deskripsi Layanan</a>
  <a href="#akun"          class="sidebar-link"><span class="sidebar-num">3</span>Akun Pengguna</a>
  <a href="#penggunaan"    class="sidebar-link"><span class="sidebar-num">4</span>Penggunaan Diizinkan</a>
  <a href="#pembayaran"    class="sidebar-link"><span class="sidebar-num">5</span>Pembayaran</a>
  <a href="#konten"        class="sidebar-link"><span class="sidebar-num">6</span>Konten Pengguna</a>
  <a href="#haki"          class="sidebar-link"><span class="sidebar-num">7</span>Kekayaan Intelektual</a>
  <a href="#privasi"       class="sidebar-link"><span class="sidebar-num">8</span>Privasi</a>
  <a href="#penghentian"   class="sidebar-link"><span class="sidebar-num">9</span>Penghentian</a>
  <a href="#tanggung"      class="sidebar-link"><span class="sidebar-num">10</span>Batasan Tanggung Jawab</a>
  <a href="#perubahan"     class="sidebar-link"><span class="sidebar-num">11</span>Perubahan Ketentuan</a>
  <a href="#hukum"         class="sidebar-link"><span class="sidebar-num">12</span>Hukum Berlaku</a>
@endsection

@section('sections')

{{-- 1 --}}
<div class="section" id="penerimaan" data-section="penerimaan">
  <div class="section-header">
    <div class="section-num">01</div>
    <div class="section-title-wrap">
      <h2>Penerimaan Ketentuan</h2>
      <div class="section-subtitle">Syarat penggunaan aplikasi Nikahin</div>
    </div>
  </div>
  <div class="section-body">
    <p>Dengan mengunduh, menginstal, atau menggunakan aplikasi <strong>Nikahin</strong> ("Aplikasi"), Anda menyatakan telah membaca, memahami, dan menyetujui untuk terikat oleh Ketentuan Layanan ini.</p>
    <div class="callout callout-info">
      <span class="callout-icon">💡</span>
      Dengan menggunakan Nikahin, Anda secara otomatis menyetujui ketentuan ini beserta <a href="/privacy">Kebijakan Privasi</a> kami. Jika tidak setuju, mohon berhenti menggunakan layanan.
    </div>
    <p>Pengguna yang berusia di bawah <strong>17 tahun</strong> tidak diperkenankan mendaftar atau menggunakan layanan ini tanpa pengawasan orang tua atau wali sah.</p>
  </div>
</div>

{{-- 2 --}}
<div class="section" id="deskripsi" data-section="deskripsi">
  <div class="section-header">
    <div class="section-num">02</div>
    <div class="section-title-wrap">
      <h2>Deskripsi Layanan</h2>
      <div class="section-subtitle">Fitur dan cakupan platform Nikahin</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin adalah platform undangan pernikahan digital yang memungkinkan pengguna untuk:</p>
    <ul>
      <li>Membuat dan mempersonalisasi undangan pernikahan digital dengan berbagai template</li>
      <li>Mengelola daftar tamu, RSVP, dan konfirmasi kehadiran</li>
      <li>Melakukan check-in tamu secara real-time melalui pemindaian QR code</li>
      <li>Mengunggah dan mengelola galeri foto pernikahan</li>
      <li>Mengirim undangan massal via WhatsApp kepada seluruh tamu</li>
      <li>Mengelola amplop digital melalui informasi rekening bank</li>
      <li>Memantau statistik kunjungan dan interaksi undangan</li>
    </ul>
    <div class="callout callout-warning">
      <span class="callout-icon">⚠️</span>
      Kami berhak mengubah, menangguhkan, atau menghentikan fitur tertentu kapan pun. Perubahan signifikan akan diberitahukan melalui email atau notifikasi aplikasi.
    </div>
  </div>
</div>

{{-- 3 --}}
<div class="section" id="akun" data-section="akun">
  <div class="section-header">
    <div class="section-num">03</div>
    <div class="section-title-wrap">
      <h2>Akun Pengguna</h2>
      <div class="section-subtitle">Pendaftaran, keamanan, dan tanggung jawab akun</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Pendaftaran Akun</h3>
    <p>Untuk menggunakan layanan, Anda wajib mendaftar dengan informasi yang <strong>akurat, lengkap, dan terkini</strong>. Anda bertanggung jawab penuh atas semua aktivitas yang terjadi di bawah akun Anda.</p>
    <h3>Keamanan Akun</h3>
    <p>Jagalah kerahasiaan kata sandi Anda. Segera hubungi kami jika menyadari akses tidak sah ke akun Anda. Kami tidak bertanggung jawab atas kerugian akibat kelalaian Anda menjaga keamanan akun.</p>
    <h3>Satu Akun Per Pengguna</h3>
    <p>Setiap pengguna hanya diizinkan memiliki satu akun aktif. Pembuatan akun duplikat dapat mengakibatkan penangguhan seluruh akun terkait.</p>
  </div>
</div>

{{-- 4 --}}
<div class="section" id="penggunaan" data-section="penggunaan">
  <div class="section-header">
    <div class="section-num">04</div>
    <div class="section-title-wrap">
      <h2>Penggunaan yang Diizinkan</h2>
      <div class="section-subtitle">Batasan dan larangan dalam menggunakan layanan</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin <strong>hanya</strong> boleh digunakan untuk keperluan undangan pernikahan yang sah. Anda <strong>dilarang</strong>:</p>
    <ul>
      <li>Menggunakan layanan untuk tujuan ilegal, penipuan, atau tidak etis</li>
      <li>Mengunggah konten yang melanggar hak cipta, bersifat cabul, mengandung kekerasan, atau diskriminatif</li>
      <li>Melakukan reverse engineering, dekompilasi, atau pembongkaran pada Aplikasi</li>
      <li>Mencoba mengakses data pengguna lain tanpa izin</li>
      <li>Menggunakan bot, scraper, atau alat otomatis untuk mengakses layanan</li>
      <li>Menyebarkan virus, malware, atau kode berbahaya lainnya</li>
      <li>Mengumpulkan data tamu tanpa persetujuan yang bersangkutan</li>
    </ul>
    <div class="callout callout-warning">
      <span class="callout-icon">🚫</span>
      Pelanggaran terhadap ketentuan ini dapat mengakibatkan penangguhan akun secara permanen tanpa pengembalian dana.
    </div>
  </div>
</div>

{{-- 5 --}}
<div class="section" id="pembayaran" data-section="pembayaran">
  <div class="section-header">
    <div class="section-num">05</div>
    <div class="section-title-wrap">
      <h2>Pembayaran</h2>
      <div class="section-subtitle">Biaya layanan, prosesor pembayaran, dan refund</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Biaya Publikasi</h3>
    <p>Pembuatan dan pengelolaan undangan dalam mode <em>draft</em> bersifat <strong>gratis</strong>. Untuk mempublikasikan undangan agar dapat diakses tamu, dikenakan biaya satu kali:</p>
    <div class="tag-row" style="margin: 4px 0 8px">
      <div class="tag"><div class="tag-dot" style="background:#6b4ce6"></div> Rp 50.000 / undangan</div>
      <div class="tag"><div class="tag-dot" style="background:#10b981"></div> Berlaku seumur hidup</div>
      <div class="tag"><div class="tag-dot" style="background:#f59e0b"></div> Bayar sekali saja</div>
    </div>
    <h3>Prosesor Pembayaran</h3>
    <p>Pembayaran diproses melalui <strong>Midtrans</strong>, penyedia layanan pembayaran terpercaya di Indonesia yang mendukung transfer bank, kartu kredit, e-wallet, dan QRIS.</p>
    <h3>Kebijakan Pengembalian Dana</h3>
    <p>Biaya publikasi <strong>tidak dapat dikembalikan</strong> setelah undangan berhasil dipublikasikan. Pengecualian berlaku jika terdapat kendala teknis dari pihak kami — refund akan diproses dalam <strong>7 hari kerja</strong>.</p>
    <h3>Perubahan Harga</h3>
    <p>Kami berhak mengubah harga dengan pemberitahuan minimal <strong>14 hari</strong> sebelumnya melalui email atau notifikasi dalam aplikasi.</p>
  </div>
</div>

{{-- 6 --}}
<div class="section" id="konten" data-section="konten">
  <div class="section-header">
    <div class="section-num">06</div>
    <div class="section-title-wrap">
      <h2>Konten Pengguna</h2>
      <div class="section-subtitle">Kepemilikan, lisensi, dan tanggung jawab konten</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Kepemilikan Konten</h3>
    <p>Anda mempertahankan hak penuh atas konten yang Anda unggah (foto, nama, informasi acara). Dengan mengunggah konten, Anda memberikan Nikahin lisensi <strong>non-eksklusif</strong> untuk menampilkan dan menyimpan konten tersebut dalam rangka penyediaan layanan.</p>
    <h3>Tanggung Jawab Konten</h3>
    <p>Anda sepenuhnya bertanggung jawab atas konten yang Anda unggah dan menjamin bahwa konten tersebut tidak melanggar hak pihak ketiga.</p>
    <h3>Penghapusan Konten</h3>
    <p>Kami berhak menghapus konten yang melanggar ketentuan ini tanpa pemberitahuan. Anda dapat menghapus konten Anda kapan saja melalui pengaturan akun.</p>
  </div>
</div>

{{-- 7 --}}
<div class="section" id="haki" data-section="haki">
  <div class="section-header">
    <div class="section-num">07</div>
    <div class="section-title-wrap">
      <h2>Kekayaan Intelektual</h2>
      <div class="section-subtitle">Hak cipta dan kepemilikan platform</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin dan seluruh konten, fitur, serta fungsionalitas dalam Aplikasi — termasuk desain, teks, grafik, template, logo, dan kode — merupakan <strong>milik eksklusif Nikahin</strong> dan dilindungi oleh hukum kekayaan intelektual yang berlaku di Indonesia.</p>
    <div class="callout callout-info">
      <span class="callout-icon">🔒</span>
      Mereproduksi, mendistribusikan, atau memodifikasi konten kami tanpa izin tertulis adalah pelanggaran hukum.
    </div>
  </div>
</div>

{{-- 8 --}}
<div class="section" id="privasi" data-section="privasi">
  <div class="section-header">
    <div class="section-num">08</div>
    <div class="section-title-wrap">
      <h2>Privasi</h2>
      <div class="section-subtitle">Perlindungan data pribadi pengguna</div>
    </div>
  </div>
  <div class="section-body">
    <p>Pengumpulan dan penggunaan informasi pribadi Anda diatur oleh <a href="/privacy"><strong>Kebijakan Privasi</strong></a> kami yang merupakan bagian tidak terpisahkan dari Ketentuan ini.</p>
    <div class="callout callout-success">
      <span class="callout-icon">✅</span>
      Kami berkomitmen untuk tidak menjual data pribadi Anda kepada pihak ketiga dalam bentuk apa pun.
    </div>
  </div>
</div>

{{-- 9 --}}
<div class="section" id="penghentian" data-section="penghentian">
  <div class="section-header">
    <div class="section-num">09</div>
    <div class="section-title-wrap">
      <h2>Penghentian Layanan</h2>
      <div class="section-subtitle">Cara menghapus akun dan konsekuensinya</div>
    </div>
  </div>
  <div class="section-body">
    <h3>Oleh Pengguna</h3>
    <p>Anda dapat menghentikan layanan kapan saja dengan menghapus akun melalui menu <strong>Pengaturan Akun</strong> di aplikasi.</p>
    <h3>Oleh Nikahin</h3>
    <p>Kami dapat menangguhkan atau menghentikan akses Anda tanpa pemberitahuan jika Anda melanggar Ketentuan ini atau jika tindakan Anda membahayakan pengguna lain atau sistem kami.</p>
    <h3>Akibat Penghentian</h3>
    <p>Setelah akun dihapus, semua data undangan dan konten akan <strong>dihapus permanen dalam 30 hari</strong>. Undangan yang dipublikasikan tidak akan dapat diakses tamu setelah akun dihapus.</p>
  </div>
</div>

{{-- 10 --}}
<div class="section" id="tanggung" data-section="tanggung">
  <div class="section-header">
    <div class="section-num">10</div>
    <div class="section-title-wrap">
      <h2>Batasan Tanggung Jawab</h2>
      <div class="section-subtitle">Cakupan dan batas kewajiban Nikahin</div>
    </div>
  </div>
  <div class="section-body">
    <p>Nikahin disediakan <em>"sebagaimana adanya"</em> tanpa jaminan ketersediaan 100%. Kami tidak bertanggung jawab atas:</p>
    <ul>
      <li>Kerugian tidak langsung, insidental, atau konsekuensial</li>
      <li>Kehilangan data akibat kegagalan teknis di luar kendali kami</li>
      <li>Gangguan layanan akibat force majeure (bencana alam, gangguan infrastruktur, dll.)</li>
      <li>Tindakan atau kelalaian pihak ketiga (WhatsApp, Midtrans, penyedia hosting)</li>
    </ul>
    <p>Tanggung jawab maksimum Nikahin kepada Anda terbatas pada jumlah yang Anda bayarkan dalam <strong>12 bulan terakhir</strong>.</p>
  </div>
</div>

{{-- 11 --}}
<div class="section" id="perubahan" data-section="perubahan">
  <div class="section-header">
    <div class="section-num">11</div>
    <div class="section-title-wrap">
      <h2>Perubahan Ketentuan</h2>
      <div class="section-subtitle">Proses pembaruan dan notifikasi</div>
    </div>
  </div>
  <div class="section-body">
    <p>Kami dapat memperbarui Ketentuan ini dari waktu ke waktu. Perubahan material akan diberitahukan melalui email atau notifikasi dalam aplikasi minimal <strong>14 hari sebelum berlaku</strong>.</p>
    <p>Penggunaan berkelanjutan atas layanan setelah perubahan berlaku dianggap sebagai <strong>persetujuan Anda</strong> atas ketentuan yang diperbarui.</p>
  </div>
</div>

{{-- 12 --}}
<div class="section" id="hukum" data-section="hukum">
  <div class="section-header">
    <div class="section-num">12</div>
    <div class="section-title-wrap">
      <h2>Hukum yang Berlaku</h2>
      <div class="section-subtitle">Yurisdiksi dan penyelesaian sengketa</div>
    </div>
  </div>
  <div class="section-body">
    <p>Ketentuan ini diatur dan ditafsirkan sesuai dengan <strong>hukum Negara Republik Indonesia</strong>. Setiap sengketa akan diselesaikan melalui musyawarah mufakat terlebih dahulu.</p>
    <p>Jika tidak tercapai kesepakatan, sengketa akan diselesaikan melalui <strong>Pengadilan Negeri Jakarta Selatan</strong> sebagai forum yang disepakati bersama.</p>
  </div>
</div>

@endsection
