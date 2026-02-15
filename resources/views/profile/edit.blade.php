<x-user-layout>
    <x-slot name="title">Profil</x-slot>
    <x-slot name="header">Profil Saya</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profil</li>
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-info-tab" data-toggle="pill" href="#profile-info" role="tab" aria-controls="profile-info" aria-selected="true">
                                <i class="fas fa-user"></i> Informasi Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
                                <i class="fas fa-lock"></i> Update Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="delete-account-tab" data-toggle="pill" href="#delete-account" role="tab" aria-controls="delete-account" aria-selected="false">
                                <i class="fas fa-trash-alt"></i> Hapus Akun
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profile-tabContent">
                        <!-- Profile Information Tab -->
                        <div class="tab-pane fade show active" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">
                            <h5 class="mb-3">Informasi Profil</h5>
                            <p class="text-muted mb-4">Update informasi profil dan alamat email akun Anda.</p>
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <!-- Update Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <h5 class="mb-3">Update Password</h5>
                            <p class="text-muted mb-4">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
                            @include('profile.partials.update-password-form')
                        </div>

                        <!-- Delete Account Tab -->
                        <div class="tab-pane fade" id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
                            <h5 class="mb-3 text-danger">Hapus Akun</h5>
                            <p class="text-muted mb-4">Setelah akun dihapus, semua data akan hilang secara permanen.</p>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>
