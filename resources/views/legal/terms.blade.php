@extends('legal.layout')

@section('title', 'Ketentuan Layanan')
@section('meta_description', 'Ketentuan Layanan aplikasi Nikahin — undangan pernikahan digital untuk pasangan modern Indonesia.')

@section('content')

<div class="hero">
  <div class="hero-label">Dokumen Hukum</div>
  <h1>Ketentuan Layanan</h1>
  <p class="hero-updated">Terakhir diperbarui: 1 Juni 2025</p>
</div>

<div class="container">

  <div class="toc">
    <div class="toc-title">Daftar Isi</div>
    <ol>
      <li><a href="#penerimaan">Penerimaan Ketentuan</a></li>
      <li><a href="#deskripsi">Deskripsi Layanan</a></li>
      <li><a href="#akun">Akun Pengguna</a></li>
      <li><a href="#penggunaan">Penggunaan yang Diizinkan</a></li>
      <li><a href="#pembayaran">Pembayaran</a></li>
      <li><a href="#konten">Konten Pengguna</a></li>
      <li><a href="#kekayaan">Kekayaan Intelektual</a></li>
      <li><a href="#privasi">Privasi</a></li>
      <li><a href="#penghentian">Penghentian Layanan</a></li>
      <li><a href="#tanggung-jawab">Batasan Tanggung Jawab</a></li>
      <li><a href="#perubahan">Perubahan Ketentuan</a></li>
      <li><a href="#kontak">Hubungi Kami</a></li>
    </ol>
  </div>

  {{-- 1 --}}
  <div class="section" id="penerimaan">
    <div class="section-num">1</div>
    <h2>Penerimaan Ketentuan</h2>
    <p>Dengan mengunduh, menginstal, atau menggunakan aplikasi <strong>Nikahin</strong> ("Aplikasi", "Layanan"), Anda menyatakan telah membaca, memahami, dan menyetujui untuk terikat oleh Ketentuan Layanan ini ("Ketentuan").</p>
    <p>Jika Anda tidak menyetujui Ketentuan ini, mohon untuk tidak menggunakan Aplikasi. Pengguna yang berusia di bawah 17 tahun tidak diperkenankan menggunakan Layanan ini.</p>
    <div class="highlight">
      Dengan menggunakan Nikahin, Anda menyetujui ketentuan ini dan Kebijakan Privasi kami yang tersedia di <a href="/privacy">/privacy</a>.
    </div>
  </div>

  {{-- 2 --}}
  <div class="section" id="deskripsi">
    <div class="section-num">2</div>
    <h2>Deskripsi Layanan</h2>
    <p>Nikahin adalah platform undangan pernikahan digital yang memungkinkan pengguna untuk:</p>
    <ul>
      <li>Membuat dan mempersonalisasi undangan pernikahan digital</li>
      <li>Mengelola daftar tamu dan sistem RSVP</li>
      <li>Melakukan check-in tamu melalui pemindaian QR code</li>
      <li>Mengunggah dan mengelola galeri foto pernikahan</li>
      <li>Mengirim undangan via WhatsApp kepada tamu</li>
      <li>Mengelola informasi rekening bank untuk amplop digital</li>
      <li>Melihat statistik kunjungan undangan</li>
    </ul>
    <p>Kami berhak mengubah, menangguhkan, atau menghentikan fitur layanan kapan pun dengan atau tanpa pemberitahuan sebelumnya.</p>
  </div>

  {{-- 3 --}}
  <div class="section" id="akun">
    <div class="section-num">3</div>
    <h2>Akun Pengguna</h2>
    <h3>3.1 Pendaftaran</h3>
    <p>Untuk menggunakan layanan, Anda harus membuat akun dengan memberikan informasi yang akurat, lengkap, dan terkini. Anda bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda.</p>
    <h3>3.2 Keamanan Akun</h3>
    <p>Anda wajib menjaga kerahasiaan kata sandi akun Anda. Segera hubungi kami jika Anda menyadari adanya penggunaan akun yang tidak sah. Kami tidak bertanggung jawab atas kerugian yang disebabkan oleh kegagalan Anda menjaga keamanan akun.</p>
    <h3>3.3 Satu Akun Per Pengguna</h3>
    <p>Setiap pengguna hanya diizinkan memiliki satu akun aktif. Pembuatan akun ganda dapat mengakibatkan penangguhan semua akun terkait.</p>
  </div>

  {{-- 4 --}}
  <div class="section" id="penggunaan">
    <div class="section-num">4</div>
    <h2>Penggunaan yang Diizinkan</h2>
    <p>Anda setuju untuk menggunakan Nikahin hanya untuk tujuan yang sah dan sesuai dengan ketentuan ini. Anda <strong>dilarang</strong> untuk:</p>
    <ul>
      <li>Menggunakan layanan untuk tujuan ilegal atau tidak sah</li>
      <li>Mengunggah konten yang melanggar hak cipta, bersifat cabul, mengandung kekerasan, atau diskriminatif</li>
      <li>Melakukan reverse engineering, dekompilasi, atau pembongkaran pada Aplikasi</li>
      <li>Mencoba mengakses data pengguna lain tanpa izin</li>
      <li>Menggunakan bot atau alat otomatis untuk mengakses layanan</li>
      <li>Menyebarkan virus, malware, atau kode berbahaya lainnya</li>
      <li>Melanggar hak privasi pihak ketiga dengan mengumpulkan data tamu tanpa persetujuan</li>
      <li>Menggunakan layanan untuk tujuan komersial selain yang diizinkan secara eksplisit</li>
    </ul>
  </div>

  {{-- 5 --}}
  <div class="section" id="pembayaran">
    <div class="section-num">5</div>
    <h2>Pembayaran</h2>
    <h3>5.1 Biaya Publikasi</h3>
    <p>Pembuatan dan pengelolaan undangan (draft) bersifat gratis. Untuk mempublikasikan undangan agar dapat diakses tamu, dikenakan biaya satu kali sebesar <strong>Rp 50.000</strong> per undangan.</p>
    <h3>5.2 Prosesor Pembayaran</h3>
    <p>Pembayaran diproses melalui <strong>Midtrans</strong>, penyedia layanan pembayaran tepercaya di Indonesia. Dengan melakukan pembayaran, Anda juga tunduk pada Syarat dan Ketentuan Midtrans.</p>
    <h3>5.3 Kebijakan Pengembalian Dana</h3>
    <p>Biaya publikasi tidak dapat dikembalikan setelah undangan berhasil dipublikasikan. Jika terdapat kendala teknis dari pihak kami yang mengakibatkan undangan tidak dapat dipublikasikan, kami akan memproses pengembalian dana dalam 7 hari kerja.</p>
    <h3>5.4 Perubahan Harga</h3>
    <p>Kami berhak mengubah harga layanan dengan pemberitahuan minimal 14 hari sebelumnya melalui email atau notifikasi dalam aplikasi.</p>
  </div>

  {{-- 6 --}}
  <div class="section" id="konten">
    <div class="section-num">6</div>
    <h2>Konten Pengguna</h2>
    <h3>6.1 Kepemilikan Konten</h3>
    <p>Anda mempertahankan hak atas konten yang Anda unggah (foto, nama, informasi acara). Dengan mengunggah konten, Anda memberikan Nikahin lisensi non-eksklusif untuk menampilkan dan menyimpan konten tersebut dalam rangka penyediaan layanan.</p>
    <h3>6.2 Tanggung Jawab Konten</h3>
    <p>Anda sepenuhnya bertanggung jawab atas konten yang Anda unggah. Anda menjamin bahwa konten tersebut tidak melanggar hak pihak ketiga dan sesuai dengan peraturan yang berlaku di Indonesia.</p>
    <h3>6.3 Penghapusan Konten</h3>
    <p>Kami berhak menghapus konten yang melanggar ketentuan ini tanpa pemberitahuan sebelumnya. Anda dapat menghapus konten Anda kapan saja melalui pengaturan akun.</p>
  </div>

  {{-- 7 --}}
  <div class="section" id="kekayaan">
    <div class="section-num">7</div>
    <h2>Kekayaan Intelektual</h2>
    <p>Nikahin dan semua konten, fitur, serta fungsionalitas dalam Aplikasi (termasuk desain, teks, grafik, template, logo) merupakan milik eksklusif Nikahin dan dilindungi oleh hukum kekayaan intelektual yang berlaku di Indonesia.</p>
    <p>Anda tidak diizinkan mereproduksi, mendistribusikan, memodifikasi, atau membuat karya turunan dari konten kami tanpa izin tertulis.</p>
  </div>

  {{-- 8 --}}
  <div class="section" id="privasi">
    <div class="section-num">8</div>
    <h2>Privasi</h2>
    <p>Pengumpulan dan penggunaan informasi pribadi Anda diatur oleh <a href="/privacy" style="color:#6b4ce6;font-weight:500;">Kebijakan Privasi</a> kami yang merupakan bagian yang tidak terpisahkan dari Ketentuan ini. Dengan menggunakan Nikahin, Anda menyetujui praktik pengumpulan data yang dijelaskan dalam Kebijakan Privasi.</p>
  </div>

  {{-- 9 --}}
  <div class="section" id="penghentian">
    <div class="section-num">9</div>
    <h2>Penghentian Layanan</h2>
    <h3>9.1 Oleh Pengguna</h3>
    <p>Anda dapat menghentikan penggunaan layanan kapan saja dengan menghapus akun melalui menu Pengaturan Akun di aplikasi.</p>
    <h3>9.2 Oleh Nikahin</h3>
    <p>Kami dapat menangguhkan atau menghentikan akses Anda tanpa pemberitahuan jika kami memiliki alasan untuk meyakini bahwa Anda telah melanggar Ketentuan ini, atau jika tindakan Anda dapat membahayakan pengguna lain, pihak ketiga, atau Nikahin.</p>
    <h3>9.3 Akibat Penghentian</h3>
    <p>Setelah akun dihapus, semua data undangan, daftar tamu, dan konten yang Anda buat akan dihapus secara permanen dalam 30 hari. Undangan yang telah dipublikasikan tidak akan dapat diakses oleh tamu setelah akun dihapus.</p>
  </div>

  {{-- 10 --}}
  <div class="section" id="tanggung-jawab">
    <div class="section-num">10</div>
    <h2>Batasan Tanggung Jawab</h2>
    <p>Nikahin disediakan "sebagaimana adanya" tanpa jaminan apa pun, baik tersurat maupun tersirat. Kami tidak menjamin bahwa layanan akan selalu tersedia tanpa gangguan atau bebas dari kesalahan.</p>
    <p>Dalam batas maksimum yang diizinkan hukum, Nikahin tidak bertanggung jawab atas:</p>
    <ul>
      <li>Kerugian tidak langsung, insidental, atau konsekuensial</li>
      <li>Kehilangan data akibat kegagalan teknis</li>
      <li>Gangguan layanan akibat force majeure (bencana alam, gangguan jaringan, dll.)</li>
      <li>Tindakan atau kelalaian pihak ketiga (WhatsApp, Midtrans, dll.)</li>
    </ul>
    <p>Tanggung jawab maksimum Nikahin kepada Anda terbatas pada jumlah yang Anda bayarkan kepada kami dalam 12 bulan terakhir.</p>
  </div>

  {{-- 11 --}}
  <div class="section" id="perubahan">
    <div class="section-num">11</div>
    <h2>Perubahan Ketentuan</h2>
    <p>Kami dapat memperbarui Ketentuan ini dari waktu ke waktu. Perubahan material akan diberitahukan melalui email atau notifikasi dalam aplikasi minimal 14 hari sebelum berlaku. Penggunaan berkelanjutan atas layanan setelah perubahan berlaku dianggap sebagai persetujuan Anda atas ketentuan yang diperbarui.</p>
  </div>

  {{-- 12 --}}
  <div class="section" id="kontak">
    <div class="section-num">12</div>
    <h2>Hukum yang Berlaku</h2>
    <p>Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Negara Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah mufakat, dan jika tidak tercapai, akan diselesaikan melalui Pengadilan Negeri Jakarta Selatan.</p>
  </div>

  <div class="contact-card">
    <h3>Pertanyaan tentang Ketentuan Layanan?</h3>
    <p>Tim kami siap membantu menjawab pertanyaan Anda seputar ketentuan penggunaan Nikahin.</p>
    <a href="mailto:support@nikahin.id">Hubungi Kami</a>
  </div>

</div>
@endsection
