<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-6">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"><i class="bx bxs-plus-square"></i>Tambah
                                Karyawan</button>
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
                            <th>Nama Pengguna</th>
                            <th>Jabatan</th>
                            <th>No. HP</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tgl Masuk</th>
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
            <form id="form-karyawan" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="karyawan_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6 mb-2">
                            <label>Nama Pengguna</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Nomor HP</label>
                            <input type="text" class="form-control" name="no_hp">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Tanggal Masuk</label>
                            <input type="date" class="form-control" name="tgl_masuk">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Jabatan</label>
                            <select name="jabatan_id" class="form-control" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"></textarea>
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
            function editKaryawan(id) {
                $.get('/users/karyawan/edit/' + id, function(data) {
                    $('#karyawan_id').val(data.id);
                    $('[name=username]').val(data.username).prop('readonly', true);
                    $('[name=name]').val(data.name);
                    $('[name=email]').val(data.email);
                    $('[name=no_hp]').val(data.no_hp);
                    $('[name=tgl_masuk]').val(data.tgl_masuk);
                    $('[name=jabatan_id]').val(data.jabatan_id);
                    $('[name=role]').val(data.role);
                    $('[name=alamat]').val(data.alamat);
                    $('#exampleModal').modal('show');
                });
            }

            function hapusKaryawan(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/users/karyawan/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                $('#karyawan-table').DataTable().ajax.reload();
                                Swal.fire('Terhapus!', res.message, 'success');
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            }
            $(document).ready(function() {
                $('#karyawan-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('users.data-karyawan') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'jabatan.nama_jabatan',
                            name: 'jabatan.nama_jabatan'
                        },
                        {
                            data: 'no_hp',
                            name: 'no_hp'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'role',
                            name: 'role'
                        },
                        {
                            data: 'tgl_masuk',
                            name: 'tgl_masuk'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
                $('#form-karyawan').on('submit', function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    let id = $('#karyawan_id').val();
                    let url = id ? 'karyawan/update/' + id : '{{ route('users.store-karyawan') }}';

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: res => {
                            $('#karyawan-table').DataTable().ajax.reload();
                            $('#exampleModal').modal('hide');
                            Swal.fire('Berhasil!', res.message, 'success');
                            this.reset();
                        },
                        error: err => {
                            Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                        }
                    });
                });

                // Show Modal Edit

            });
        </script>
    @endpush
</x-app-layout>
