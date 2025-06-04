<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="col-lg-7">
                <h5>Absensi Harian</h5>
            </div>
            <div class="col-lg-5">
                <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}"
                    max="{{ date('Y-m-d') }}" />
            </div>
        </div>
        <div class="card-body">
            <form id="form-absensi">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Hadir</th>
                                <th>Lembur</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $k)
                                <tr>
                                    <td>{{ $k->name }}</td>
                                    <td><input type="checkbox" name="absen[{{ $k->id }}][hadir]" /></td>
                                    <td><input type="checkbox" name="absen[{{ $k->id }}][lembur]" /></td>
                                    <td><input type="text" name="absen[{{ $k->id }}][keterangan]"
                                            class="form-control" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Fungsi untuk load data absensi dan isi form
                function loadAbsensi(tanggal) {
                    $.ajax({
                        url: '{{ route('absensi.data') }}', // pastikan route sudah dibuat
                        type: 'GET',
                        data: {
                            tanggal: tanggal
                        },
                        success: function(data) {
                            $('input[type=checkbox]').prop('checked', false);
                            $('input[type=text]').val('');

                            data.forEach(function(absen) {
                                let userId = absen.user_id;
                                let hadir = absen.hadir;
                                let lembur = absen.lembur;
                                let keterangan = absen.keterangan;

                                $(`input[name='absen[${userId}][hadir]']`).prop('checked', hadir);
                                $(`input[name='absen[${userId}][lembur]']`).prop('checked', lembur);
                                $(`input[name='absen[${userId}][keterangan]']`).val(keterangan);
                            });
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Gagal mengambil data absensi', 'error');
                        }
                    });
                }

                // Load data absensi untuk tanggal default saat halaman dibuka
                let tanggalAwal = $('#tanggal').val();
                loadAbsensi(tanggalAwal);

                // Ketika tanggal berubah, load data absensi untuk tanggal baru
                $('#tanggal').on('change', function() {
                    let tanggal = $(this).val();
                    loadAbsensi(tanggal);
                });

                // Submit form tetap seperti biasa
                $('#form-absensi').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize() + '&tanggal=' + $('#tanggal').val();

                    $.ajax({
                        url: '{{ route('absensi.store') }}',
                        type: 'POST',
                        data: formData,
                        success: function(res) {
                            Swal.fire('Berhasil', res.message, 'success');
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
