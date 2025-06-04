<x-app-layout>
    @php
        $currentMonth = date('n'); // 1-12
        $currentYear = date('Y');
    @endphp

    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex">
                <div class="col-lg-4 me-2">
                    <select id="bulan" class="form-control">
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $b == $currentMonth ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 me-2">
                    <select id="tahun" class="form-control">
                        @for ($y = $currentYear; $y >= 2025; $y--)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-primary" id="btn-filter">Tampilkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="gaji-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Hadir</th>
                            <th>Lembur</th>
                            <th>Honor Harian</th>
                            <th>Honor Lembur</th>
                            <th>Potongan</th>
                            <th>Total Gaji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
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
                        data: 'hadir',
                        render: d => d + ' hari'
                    },
                    {
                        data: 'lembur',
                        render: d => d + ' hari'
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
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
            <a href="/rekap-gaji/print-slip?id_user=${row.id}&bulan=${row.bulan}&tahun=${row.tahun}" 
               target="_blank" class="btn btn-sm btn-secondary">Cetak</a>
        `;
                        }
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
