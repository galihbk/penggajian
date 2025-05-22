<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="bulan" class="form-control">
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}">{{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="tahun" class="form-control">
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" id="btn-filter">Tampilkan</button>
                </div>
            </div>
            <table class="table table-bordered" id="gaji-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Hadir</th>
                        <th>Honor Harian</th>
                        <th>Honor Lembur</th>
                        <th>Potongan</th>
                        <th>Total Gaji</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            const table = $('#gaji-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ route('gaji.data') }}',
                    data: function(d) {
                        d.bulan = $('#bulan').val();
                        d.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'nama'
                    },
                    {
                        data: 'jabatan'
                    },
                    {
                        data: 'hadir'
                    },
                    {
                        data: 'honor_harian',
                        render: d => 'Rp ' + formatRupiah(d)
                    },
                    {
                        data: 'honor_lembur',
                        render: d => 'Rp ' + formatRupiah(d)
                    },
                    {
                        data: 'potongan',
                        render: d => 'Rp ' + formatRupiah(d)
                    },
                    {
                        data: 'total',
                        render: d => 'Rp ' + formatRupiah(d)
                    }
                ]
            });

            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        </script>
    @endpush
</x-app-layout>
