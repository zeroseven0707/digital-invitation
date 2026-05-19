<x-user-layout>
    <x-slot name="title">Panduan Pengguna</x-slot>
    <x-slot name="header">Panduan Pengguna</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Panduan</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <!-- Hero Guide Section -->
            <div class="guide-hero mb-4">
                <div class="guide-hero-content text-center text-md-left">
                    <h2><i class="fas fa-graduation-cap mr-2"></i>Panduan Lengkap Nikahin</h2>
                    <p class="mb-0">Ikuti langkah-langkah di bawah ini untuk membuat undangan pernikahan digital Anda secara mandiri dengan hasil yang maksimal.</p>
                </div>
            </div>

            <!-- Modern Step Timeline Layout -->
            <div class="card card-outline card-primary shadow-sm mb-4" style="border-radius: 15px; border-top: 3px solid #6b4ce6;">
                <div class="card-header bg-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h3 class="card-title m-0" style="font-weight: 700; color: #1a1a2e; font-size: 1.2rem;">
                        <i class="fas fa-route mr-2" style="color: #6b4ce6;"></i>Alur Pembuatan Undangan
                    </h3>
                </div>
                <div class="card-body" style="padding: 40px 30px;">
                    <div class="guide-timeline">
                        <!-- Step 1 -->
                        <div class="timeline-step">
                            <div class="step-badge step-1">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 1: Pilih Desain / Template</h4>
                                <p>Telusuri berbagai macam pilihan template premium beranimasi di halaman <strong>Template</strong>. Anda bisa langsung mencoba melihat tampilannya secara real-time sebelum menetapkan pilihan.</p>
                                <div class="step-tip">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i><strong>Tips:</strong> Cari template yang memiliki karakter warna yang selaras dengan tema dekorasi pernikahan Anda.
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="timeline-step">
                            <div class="step-badge step-2">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 2: Isi Kelengkapan Informasi</h4>
                                <p>Lengkapi formulir detail data pernikahan Anda. Ini mencakup informasi krusial seperti:</p>
                                <ul class="step-list">
                                    <li><strong>Informasi Mempelai:</strong> Nama lengkap dan sapaan kedua mempelai, nama orang tua, serta foto profil.</li>
                                    <li><strong>Acara Akad & Resepsi:</strong> Tanggal, waktu pengerjaan acara, alamat lengkap venue, dan koordinat Google Maps.</li>
                                    <li><strong>Backsound Musik:</strong> Unggah atau tentukan backsound lagu romantis pilihan Anda dalam format MP3.</li>
                                </ul>
                                <div class="step-tip">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i><strong>Tips:</strong> Pastikan ejaan nama orang tua dan detail waktu acara sudah diteliti dengan cermat sebelum menyimpan.
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="timeline-step">
                            <div class="step-badge step-3">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 3: Aktivasi & Pembayaran</h4>
                                <p>Setelah menyimpan data draft undangan, klik tombol <strong>Bayar Sekarang</strong> pada detail undangan untuk melakukan aktivasi fitur premium:</p>
                                <ol class="step-sub-steps">
                                    <li>Lakukan scan pembayaran menggunakan QRIS yang tertera.</li>
                                    <li>Masukkan nominal pembayaran minimal <strong>Rp 35.000</strong> (atau lebih).</li>
                                    <li>Lakukan screenshot bukti pembayaran, kemudian kirim konfirmasi Anda langsung ke tim admin kami via WhatsApp.</li>
                                </ol>
                                <div class="step-tip">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i><strong>Tips:</strong> Pembayaran yang disertai bukti screenshot biasanya akan langsung divalidasi oleh admin dalam waktu kurang dari 30 menit.
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="timeline-step">
                            <div class="step-badge step-4">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 4: Upload Galeri & Cerita Cinta</h4>
                                <p>Unggah koleksi foto pre-wedding Anda ke dalam album galeri. Anda dapat menyusun urutan penayangan foto dengan mudah secara drag-and-drop agar tampilannya di halaman undangan terlihat estetik.</p>
                                <div class="step-tip">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i><strong>Tips:</strong> Kompres ukuran gambar terlebih dahulu agar loading undangan terasa ringan saat diakses oleh para tamu.
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="timeline-step">
                            <div class="step-badge step-5">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 5: Manajemen Buku Tamu</h4>
                                <p>Manfaatkan menu <strong>Kelola Tamu</strong> untuk mendaftarkan nama-nama tamu undangan Anda. Sistem akan menghasilkan tautan personal yang unik untuk dikirimkan secara khusus kepada setiap tamu.</p>
                                <div class="step-tip">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i><strong>Tips:</strong> Gunakan fitur Import Excel/CSV jika Anda ingin memasukkan ratusan nama tamu sekaligus dalam satu ketukan.
                                </div>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="timeline-step">
                            <div class="step-badge step-6">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="step-content">
                                <h4>Langkah 6: Publikasikan & Sebar Undangan</h4>
                                <p>Sekali status pembayaran divalidasi dan dikonfirmasi aktif oleh admin, tombol <strong>Publikasikan</strong> akan terbuka. Tekan tombol tersebut untuk meluncurkan undangan ke domain publik, dan bagikan tautannya ke media sosial.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Accordion Section -->
            <div class="card card-outline card-primary shadow-sm mb-4" style="border-radius: 15px; border-top: 3px solid #6b4ce6;">
                <div class="card-header bg-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h3 class="card-title m-0" style="font-weight: 700; color: #1a1a2e; font-size: 1.2rem;">
                        <i class="fas fa-question-circle mr-2" style="color: #6b4ce6;"></i>Tanya Jawab Seputar Layanan (FAQ)
                    </h3>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="faq-grid">
                        <div class="faq-card">
                            <h5>Q: Berapa lama waktu yang dibutuhkan untuk proses aktivasi?</h5>
                            <p class="text-muted">A: Proses aktivasi instan berkisar antara 10-60 menit setelah Anda mengirimkan bukti transfer pembayaran via WhatsApp ke nomor CS kami.</p>
                        </div>
                        <div class="faq-card">
                            <h5>Q: Apakah data undangan masih bisa diedit setelah dipublikasikan?</h5>
                            <p class="text-muted">A: Sangat bisa! Anda dapat mengubah detail acara, mengganti foto galeri, atau menambah nama tamu kapan saja tanpa batasan.</p>
                        </div>
                        <div class="faq-card">
                            <h5>Q: Berapa batas jumlah nama tamu yang bisa didaftarkan?</h5>
                            <p class="text-muted">A: Tidak ada batasan (Unlimited)! Anda dibebaskan untuk mendaftarkan ribuan tamu undangan sesuka hati secara gratis.</p>
                        </div>
                        <div class="faq-card">
                            <h5>Q: Bagaimana cara mendapatkan notifikasi kehadiran RSVP tamu?</h5>
                            <p class="text-muted">A: Semua pesan ucapan dan rekap konfirmasi kehadiran RSVP akan langsung terekam di dashboard admin Anda secara real-time.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support Callout -->
            <div class="guide-contact-card text-center text-md-left">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <h4><i class="fas fa-headset mr-2"></i>Butuh Bantuan Lebih Lanjut?</h4>
                        <p class="mb-0 text-white-50">Jika Anda mendapati kendala saat merancang undangan atau memerlukan bantuan aktivasi cepat, silakan hubungi WhatsApp Support kami.</p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="https://wa.me/6282342742787" target="_blank" class="btn btn-light btn-lg font-weight-bold shadow-sm px-4" style="color: #25d366; border-radius: 12px;">
                            <i class="fab fa-whatsapp mr-1" style="font-size: 1.3rem; vertical-align: middle;"></i> Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Guide Hero */
        .guide-hero {
            background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%);
            border-radius: 15px;
            padding: 35px;
            color: white;
            box-shadow: 0 10px 30px rgba(107, 76, 230, 0.15);
        }

        .guide-hero h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .guide-hero p {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Timeline Step Styles */
        .guide-timeline {
            position: relative;
            padding-left: 50px;
        }

        .guide-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 20px;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-step {
            position: relative;
            margin-bottom: 40px;
        }

        .timeline-step:last-child {
            margin-bottom: 0;
        }

        .step-badge {
            position: absolute;
            left: -50px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: white;
            border: 3px solid #6b4ce6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b4ce6;
            font-size: 1.1rem;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .step-badge.step-1 { border-color: #6b4ce6; color: #6b4ce6; }
        .step-badge.step-2 { border-color: #3b82f6; color: #3b82f6; }
        .step-badge.step-3 { border-color: #f59e0b; color: #f59e0b; }
        .step-badge.step-4 { border-color: #ec4899; color: #ec4899; }
        .step-badge.step-5 { border-color: #10b981; color: #10b981; }
        .step-badge.step-6 { border-color: #14b8a6; color: #14b8a6; }

        .step-content h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .step-content p {
            font-size: 0.95rem;
            color: #4a5568;
            line-height: 1.6;
        }

        .step-tip {
            background: #fffbef;
            border: 1px solid #fef3c7;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #b45309;
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
        }

        .step-list {
            padding-left: 20px;
            margin-bottom: 12px;
        }

        .step-list li {
            font-size: 0.9rem;
            color: #4a5568;
            margin-bottom: 6px;
        }

        .step-sub-steps {
            padding-left: 20px;
            margin-bottom: 12px;
        }

        .step-sub-steps li {
            font-size: 0.9rem;
            color: #4a5568;
            margin-bottom: 6px;
        }

        /* FAQ Layout Styles */
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .faq-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .faq-card:hover {
            border-color: #6b4ce6;
            background: white;
            box-shadow: 0 8px 20px rgba(107, 76, 230, 0.05);
        }

        .faq-card h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .faq-card p {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* Contact Support Card */
        .guide-contact-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            box-shadow: 0 10px 35px rgba(16, 185, 129, 0.15);
            margin-top: 30px;
        }

        .guide-contact-card h4 {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .guide-contact-card p {
            font-size: 0.95rem;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    </style>
</x-user-layout>
