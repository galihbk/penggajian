<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="gaji-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
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
            const bulanNama = [
                "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const table = $('#gaji-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('karyawan.riwayat-gaji-karyawan') }}",
                },
                buttons: [{
                        extend: 'excel',
                        title: 'Riwayat Gaji Karyawan',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'Riwayat Gaji Karyawan',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Riwayat Gaji Karyawan',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            return bulanNama[row.bulan] + ' ' + row.tahun;
                        }
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
                                <a href="/rekap-gaji/print-slip?id_user={{ auth()->user()->id }}&bulan=${row.bulan}&tahun=${row.tahun}" 
                                   target="_blank" class="btn btn-sm btn-secondary">Cetak</a>
                            `;
                        }
                    }
                ]
            });

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        </script>
    @endpush
</x-app-layout>
