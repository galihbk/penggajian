{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>

    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Karyawan</p>
                            <h4 class="my-1 text-info">{{ $karyawan }}</h4>
                            <p class="mb-0 font-13">Orang</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class="bx bxs-group"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Gaji Bulan Ini</p>
                            <h4 class="my-1 text-danger">Rp {{ number_format($totalGaji, 0, ',', '.') }}</h4>
                            <p class="mb-0 font-13">{{ now()->format('F Y') }}</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class="bx bxs-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Kehadiran Hari Ini</p>
                            <h4 class="my-1 text-success">{{ $totalHadir }}</h4>
                            <p class="mb-0 font-13">Orang</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bxs-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
    @endif
    @if (auth()->user()->role === 'karyawan')
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
            <!-- Gaji terakhir -->
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Gaji Terakhir</p>
                            <h4 class="my-1 text-success">Rp {{ number_format($gajiTotal, 0, ',', '.') }}</h4>
                            <p class="mb-0 font-13">{{ now()->format('F Y') }}</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bxs-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absensi bulan ini -->
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Absensi Bulan Ini</p>
                            <h4 class="my-1 text-info">{{ $hadir }} Hadir</h4>
                            <p class="mb-0 font-13">{{ now()->format('F Y') }}</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class="bx bxs-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
