<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="col-lg-7">
                <h5>Absensi Harian</h5>
            </div>
            <div class="col-lg-5">
                <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="card-body">
            <form id="form-absensi">
                @csrf
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
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#form-absensi').on('submit', function(e) {
                    e.preventDefault();

                    let formData = $(this).serialize();
                    let tanggal = $('#tanggal').val();

                    $.ajax({
                        url: '{{ route('absensi.store') }}',
                        type: 'POST',
                        data: formData + '&tanggal=' + tanggal,
                        success: function(res) {
                            Swal.fire('Berhasil', res.message, 'success');
                        },
                        error: function(err) {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
