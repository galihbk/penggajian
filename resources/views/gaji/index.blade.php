<x-app-layout>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
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
                        @for ($y = date('Y'); $y >= 2025; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" id="btn-filter">Tampilkan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

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
             <form id="form-kirim-${row.id}" method="POST" action="/send-slip" style="display:inline;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id_user" value="${row.id}">
                <input type="hidden" name="bulan" value="${$('#bulan').val()}">
                <input type="hidden" name="tahun" value="${$('#tahun').val()}">
                <button type="submit" class="btn btn-sm btn-success">Kirim Email</button>
            </form>
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
