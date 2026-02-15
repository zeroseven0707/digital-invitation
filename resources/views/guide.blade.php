<x-user-layout>
    <x-slot name="title">Panduan Pengguna</x-slot>
    <x-slot name="header">Panduan Pengguna</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Panduan</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book"></i> Panduan Lengkap Menggunakan Nikahin
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Introduction -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Selamat Datang!</h5>
                        Panduan ini akan membantu Anda membuat undangan digital pernikahan dengan mudah dan cepat.
                    </div>

                    <!-- Step 1 -->
                    <div class="callout callout-success">
                        <h5><i class="fas fa-check-circle"></i> Langkah 1: Pilih Template</h5>
                        <ol>
                            <li>Klik menu <strong>"Template"</strong> di sidebar</li>
                            <li>Browse 18+ template yang tersedia</li>
                            <li>Klik <strong>"Preview"</strong> untuk melihat tampilan template</li>
                            <li>Klik <strong>"Pilih"</strong> pada template yang Anda suka</li>
                        </ol>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Pilih template yang sesuai dengan tema pernikahan Anda</small>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="callout callout-info">
                        <h5><i class="fas fa-edit"></i> Langkah 2: Isi Data Undangan</h5>
                        <p>Lengkapi informasi berikut:</p>
                        <ul>
                            <li><strong>Informasi Mempelai:</strong> Nama mempelai wanita & pria, nama orang tua</li>
                            <li><strong>Informasi Akad:</strong> Tanggal, waktu, dan lokasi akad nikah</li>
                            <li><strong>Informasi Resepsi:</strong> Tanggal, waktu, dan lokasi resepsi</li>
                            <li><strong>Lokasi:</strong> Alamat lengkap dan link Google Maps (opsional)</li>
                            <li><strong>Musik:</strong> URL musik latar (opsional)</li>
                        </ul>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Pastikan semua data sudah benar sebelum menyimpan</small>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="callout callout-warning">
                        <h5><i class="fas fa-credit-card"></i> Langkah 3: Lakukan Pembayaran</h5>
                        <ol>
                            <li>Setelah menyimpan undangan, klik tombol <strong>"Bayar Sekarang"</strong></li>
                            <li>Scan QRIS code dengan aplikasi mobile banking atau e-wallet Anda</li>
                            <li>Lakukan pembayaran minimal <strong>Rp 50.000</strong> (lebih? se-ikhlas-nya!)</li>
                            <li>Setelah bayar, klik <strong>"Konfirmasi Pembayaran via WhatsApp"</strong></li>
                            <li>Tunggu admin mengaktifkan undangan Anda (biasanya 1-24 jam)</li>
                        </ol>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Screenshot bukti pembayaran untuk mempercepat proses aktivasi</small>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="callout callout-primary">
                        <h5><i class="fas fa-images"></i> Langkah 4: Upload Galeri Foto (Opsional)</h5>
                        <ol>
                            <li>Buka detail undangan Anda</li>
                            <li>Scroll ke bagian <strong>"Galeri Foto"</strong></li>
                            <li>Klik <strong>"Upload Foto"</strong></li>
                            <li>Pilih foto-foto terbaik Anda (maksimal 10 foto)</li>
                            <li>Atur urutan foto dengan drag & drop</li>
                        </ol>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Gunakan foto berkualitas tinggi untuk hasil terbaik</small>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="callout callout-success">
                        <h5><i class="fas fa-users"></i> Langkah 5: Kelola Daftar Tamu</h5>
                        <ol>
                            <li>Klik <strong>"Kelola Tamu"</strong> pada undangan Anda</li>
                            <li>Tambah tamu satu per satu atau import dari file CSV/Excel</li>
                            <li>Isi nama tamu dan nomor WhatsApp (opsional)</li>
                            <li>Generate link undangan personal untuk setiap tamu</li>
                        </ol>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Gunakan fitur import untuk menambah banyak tamu sekaligus</small>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="callout callout-danger">
                        <h5><i class="fas fa-paper-plane"></i> Langkah 6: Publikasikan Undangan</h5>
                        <ol>
                            <li>Pastikan pembayaran sudah dikonfirmasi oleh admin</li>
                            <li>Preview undangan untuk memastikan semua data benar</li>
                            <li>Klik tombol <strong>"Publikasikan"</strong></li>
                            <li>Salin link undangan yang sudah dipublikasikan</li>
                            <li>Bagikan link ke tamu melalui WhatsApp, Instagram, atau media sosial lainnya</li>
                        </ol>
                        <div class="alert alert-light mt-2">
                            <small><i class="fas fa-lightbulb text-warning"></i> <strong>Tips:</strong> Anda bisa unpublish dan edit undangan kapan saja</small>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div class="callout callout-info">
                        <h5><i class="fas fa-chart-bar"></i> Langkah 7: Pantau Statistik</h5>
                        <p>Setelah undangan dipublikasikan, Anda bisa memantau:</p>
                        <ul>
                            <li><strong>Jumlah Views:</strong> Berapa kali undangan dilihat</li>
                            <li><strong>RSVP:</strong> Daftar tamu yang sudah konfirmasi kehadiran</li>
                            <li><strong>Ucapan & Doa:</strong> Pesan dari tamu untuk Anda</li>
                            <li><strong>Grafik Views:</strong> Statistik views per hari</li>
                        </ul>
                    </div>

                    <!-- FAQ -->
                    <div class="card card-outline card-primary mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-question-circle"></i> FAQ (Pertanyaan Umum)</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Q: Berapa lama proses aktivasi setelah pembayaran?</strong>
                                <p class="text-muted">A: Biasanya 1-24 jam. Pastikan Anda sudah konfirmasi via WhatsApp.</p>
                            </div>
                            <div class="mb-3">
                                <strong>Q: Apakah bisa edit undangan setelah dipublikasikan?</strong>
                                <p class="text-muted">A: Ya, Anda bisa unpublish, edit, lalu publikasikan lagi.</p>
                            </div>
                            <div class="mb-3">
                                <strong>Q: Berapa maksimal jumlah tamu yang bisa ditambahkan?</strong>
                                <p class="text-muted">A: Unlimited! Anda bisa menambahkan sebanyak yang Anda mau.</p>
                            </div>
                            <div class="mb-3">
                                <strong>Q: Apakah bisa ganti template setelah membuat undangan?</strong>
                                <p class="text-muted">A: Saat ini belum bisa. Pastikan pilih template yang tepat dari awal.</p>
                            </div>
                            <div class="mb-3">
                                <strong>Q: Bagaimana cara menghapus undangan?</strong>
                                <p class="text-muted">A: Buka detail undangan, scroll ke bawah, klik tombol "Hapus".</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="alert alert-success mt-4">
                        <h5><i class="fas fa-headset"></i> Butuh Bantuan?</h5>
                        <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami:</p>
                        <a href="https://wa.me/6281394454900" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>
