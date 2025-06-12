<x-app-layout>
    @php
        $currentMonth = date('n'); // 1-12
        $currentYear = date('Y');
    @endphp

    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex align-items-center">
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
                <div class="col-lg-4 d-flex">
                    <button class="btn btn-primary me-2" id="btn-filter">Tampilkan</button>
                    <a href="#" class="btn btn-success" id="btn-export">Export Excel</a>
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
                            <th>Nama Bank</th>
                            <th>Nomor Rekening</th>
                            <th>Nama Penerima</th>
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
                buttons: [{
                        extend: 'copy',
                        text: 'Salin'
                    },
                    {
                        extend: 'excel',
                        title: 'Data Gaji',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'Data Gaji',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Data Gaji',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                columns: [{
                        data: 'nama'
                    },
                    {
                        data: 'jabatan'
                    },
                    {
                        data: 'nama_bank',
                        name: 'nama_bank'
                    },
                    {
                        data: 'nomor_rekening',
                        name: 'nomor_rekening'
                    },
                    {
                        data: 'nama_penerima',
                        name: 'nama_penerima'
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
            table.buttons().container()
                .appendTo('#gaji-table_wrapper .col-md-6:eq(0)');

            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });
            $('#btn-export').on('click', function(e) {
                e.preventDefault();
                let bulan = $('#bulan').val();
                let tahun = $('#tahun').val();
                let url = `{{ route('gaji.export') }}?bulan=${bulan}&tahun=${tahun}`;
                window.location.href = url;
            });

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        </script>
    @endpush
</x-app-layout>
