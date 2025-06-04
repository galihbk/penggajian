<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-6">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"><i class="bx bxs-plus-square"></i>Tambah
                                Potongan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="karyawan-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Keterangan</th>
                            <th>Jumlah Potongan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-potongan">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Potongan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-12 mb-2">
                            <label>Karyawan</label>
                            <select name="user_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Karyawan</option>
                                @foreach ($karyawan as $l)
                                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" name="tanggal">
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Potongan</label>
                            <input type="text" class="form-control rupiah" name="jumlah" id="jumlah">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                function formatRupiah(angka, prefix = 'Rp. ') {
                    var number_string = angka.toString().replace(/[^,\d]/g, ''),
                        split = number_string.split(','),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + '.' + split[1] : rupiah;
                    return prefix + rupiah;
                }
                $(document).on('keyup', '.rupiah', function() {
                    this.value = formatRupiah(this.value);
                });
                const table = $('#karyawan-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('potongan') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            data: 'nama_potongan',
                            name: 'nama_potongan'
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#form-potongan').submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ route('potongan.store') }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            Swal.fire('Berhasil', res.message, 'success');
                            $('#form-potongan')[0].reset();
                            $('#exampleModal').modal('hide');
                            table.ajax.reload();
                        },
                        error: function(err) {
                            Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                        }
                    });
                });


                // Hapus data
                $(document).on('click', '.btn-hapus', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/potongan/' + id,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    Swal.fire('Berhasil', res.message, 'success');
                                    table.ajax.reload();
                                },
                                error: function() {
                                    Swal.fire('Gagal', 'Tidak dapat menghapus data',
                                        'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
