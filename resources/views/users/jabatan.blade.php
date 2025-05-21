<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-6">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"><i class="bx bxs-plus-square"></i>Tambah Jabatan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="jabatan-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Jabatan</th>
                            <th>Honor Harian</th>
                            <th>Honor Lembur</th>
                            <th>Terakhir Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-tambah-jabatan" method="POST" action="{{ route('users.store-jabatan') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="honor_harian" class="form-label">Honor Harian</label>
                            <input type="text" class="form-control rupiah" id="honor_harian" name="honor_harian"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="honor_lembur" class="form-label">Honor Lembur</label>
                            <input type="text" class="form-control rupiah" id="honor_lembur" name="honor_lembur"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-edit-jabatan" method="POST">
                @csrf
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Jabatan</label>
                            <input type="text" class="form-control" name="nama_jabatan" id="edit_nama_jabatan">
                        </div>
                        <div class="mb-3">
                            <label>Honor Harian</label>
                            <input type="text" class="form-control rupiah" name="honor_harian"
                                id="edit_honor_harian">
                        </div>
                        <div class="mb-3">
                            <label>Honor Lembur</label>
                            <input type="text" class="form-control rupiah" name="honor_lembur"
                                id="edit_honor_lembur">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#form-tambah-jabatan').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let btn = form.find('button[type="submit"]');
                    btn.prop('disabled', true).text('Menyimpan...');
                    let formData = {
                        _token: '{{ csrf_token() }}',
                        nama_jabatan: $('#nama_jabatan').val(),
                        honor_harian: $('#honor_harian').val().replace(/[^0-9]/g, ''),
                        honor_lembur: $('#honor_lembur').val().replace(/[^0-9]/g, '')
                    };

                    $.ajax({
                        url: '{{ route('users.store-jabatan') }}',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#exampleModal').modal('hide');
                            $('#jabatan-table').DataTable().ajax.reload();

                            form[0].reset();

                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses!',
                                text: response.message || 'Jabatan berhasil ditambahkan.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            let errMsg = 'Terjadi kesalahan.';

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                errMsg = Object.values(errors).join(' ');
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errMsg
                            });
                        },
                        complete: function() {
                            btn.prop('disabled', false).text('Simpan');
                        }
                    });
                });

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

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    return prefix + rupiah;
                }
                $(document).on('keyup', '.rupiah', function() {
                    this.value = formatRupiah(this.value);
                });
                $('#jabatan-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('users.data-jabatan') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'honor_harian',
                            name: 'honor_harian'
                        },
                        {
                            data: 'honor_lembur',
                            name: 'honor_lembur'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
                $(document).on('click', '.btn-edit', function() {
                    $('#edit_id').val($(this).data('id'));
                    $('#edit_nama_jabatan').val($(this).data('nama'));
                    $('#edit_honor_harian').val(formatRupiah($(this).data('harian')));
                    $('#edit_honor_lembur').val(formatRupiah($(this).data('lembur')));
                    $('#modalEdit').modal('show');
                });

                // Submit Edit
                $('#form-edit-jabatan').submit(function(e) {
                    e.preventDefault();
                    let id = $('#edit_id').val();
                    $.ajax({
                        url: `/users/jabatan/${id}`,
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            $('#modalEdit').modal('hide');
                            $('#jabatan-table').DataTable().ajax.reload();
                            Swal.fire('Berhasil', 'Data berhasil diperbarui', 'success');
                        }
                    });
                });

                $(document).on('click', '.btn-hapus', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: "Data akan dicek terlebih dahulu.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/users/jabatan/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    if (res.status == 'fail') {
                                        Swal.fire('Gagal', res.message, 'error');
                                    } else {
                                        $('#jabatan-table').DataTable().ajax.reload();
                                        Swal.fire('Berhasil', res.message, 'success');
                                    }
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
