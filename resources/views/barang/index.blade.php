@extends('layouts.app') {{-- Menggunakan layout utama bernama 'app' --}}

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm text-start">
                        {{-- Menampilkan judul halaman --}}
                        <h5 class="card-title mb-0">{{ $page->title }}</h5>
                    </div>
                    <div class="col-sm text-end">
                        {{-- Tombol untuk membuka modal tambah data --}}
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTambahBarang">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Data
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- Tabel untuk menampilkan data barang --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="table_barang"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody> {{-- Akan diisi secara dinamis oleh DataTables --}}
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Tambah Data --}}
    <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- Form tambah barang --}}
                <form id="formTambahBarang">
                    @csrf
                    <div class="modal-body">
                        {{-- Input Nama Barang --}}
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                placeholder="Masukkan Nama Barang" required>
                            <div class="invalid-feedback" id="nama_barang_error"></div>
                        </div>

                        {{-- Input Kategori --}}
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="kategori_error"></div>
                        </div>

                        {{-- Input Harga --}}
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Masukkan Harga" required>
                            <div class="invalid-feedback" id="harga_error"></div>
                        </div>

                        {{-- Input Stok --}}
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok"
                                placeholder="Masukkan Stok" required>
                            <div class="invalid-feedback" id="stok_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- Tombol untuk menutup atau menyimpan --}}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal dinamis untuk edit --}}
    <div id="myModal" class="modal fade text-left modal-borderless" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk membuka modal edit
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $(this).modal('show');

                // Submit form edit barang
                $('#formEditBarang').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var barangId = $(this).data('id');
                    var url = "{{ route('barang.update', ':id') }}".replace(':id', barangId);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: form.serialize() + '&_method=PUT',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                $('#table_barang').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#edit_' + key).addClass('is-invalid');
                                    $('#edit_' + key + '_error').text(value[0]);
                                });
                            }
                        }
                    });
                });
            });
        }

        // Menampilkan konfirmasi hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteBarang(id);
                }
            });
        }

        // AJAX hapus barang
        function deleteBarang(id) {
            $.ajax({
                url: "{{ route('barang.destroy', ':id') }}".replace(':id', id),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Terhapus!', 'Data barang berhasil dihapus.', 'success');
                        $('#table_barang').DataTable().ajax.reload();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
        }

        // Inisialisasi DataTables dan event handler
        $(document).ready(function() {
            // Load DataTable
            $('#table_barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('barang.list') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'kategori.nama_kategori', name: 'kategori.nama_kategori' },
                    {
                        data: 'harga',
                        name: 'harga',
                        render: function(data) {
                            return 'Rp ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
                    { data: 'stok', name: 'stok' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            // Submit form tambah barang
            $('#formTambahBarang').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('barang.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#modalTambahBarang').modal('hide');
                            $('#table_barang').DataTable().ajax.reload();
                            $('#formTambahBarang')[0].reset();
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').text('');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    }
                });
            });

            // Reset form saat modal ditutup
            $('#modalTambahBarang, #myModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');
            });
        });
    </script>
@endpush
