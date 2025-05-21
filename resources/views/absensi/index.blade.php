<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Absensi Harian</h5>
            <input type="date" class="form-control w-auto" id="tanggalAbsensi" />
        </div>
        <div class="card-body">
            <form id="form-absensi">
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
    @endpush
</x-app-layout>
