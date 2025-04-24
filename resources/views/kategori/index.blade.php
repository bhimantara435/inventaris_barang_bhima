@extends('layouts.app')

@section('content')
    <!-- Section utama -->
    <section class="section">
        <div class="card">
            <!-- Header kartu dengan judul dan tombol tambah -->
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm text-start">
                        <!-- Judul halaman -->
                        <h5 class="card-title mb-0">{{ $page->title }}</h5>
                    </div>
                    <div class="col-sm text-end">
                        <!-- Tombol untuk membuka modal tambah kategori -->
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTambahKategori">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel kategori -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="table_kategori"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Form tambah kategori -->
                <form id="formTambahKategori">
                    @csrf
                    <div class="modal-body">
                        <!-- Input nama kategori -->
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                placeholder="Masukkan Nama Kategori" required>
                            <div class="invalid-feedback" id="nama_kategori_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Tombol tutup dan simpan -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal kosong untuk edit data -->
    <div id="myModal" class="modal fade text-left modal-borderless" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true"></div>
@endsection

@push('scripts')
<script>
    // Konfirmasi hapus kategori
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
                deleteKategori(id);
            }
        });
    }

    // Fungsi untuk menghapus kategori melalui AJAX
    function deleteKategori(id) {
        $.ajax({
            url: "{{ route('kategori.destroy', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Terhapus!', 'Data kategori berhasil dihapus.', 'success');
                    $('#table_kategori').DataTable().ajax.reload();
                }
            },
            error: function() {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    }

    // Menampilkan modal edit dan menangani form-nya
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $(this).modal('show');

            // Submit form edit kategori
            $('#formEditKategori').off('submit').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var kategoriId = $('#edit_nama_kategori').data('kategori-id');
                var url = "{{ route('kategori.update', ':id') }}".replace(':id', kategoriId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize() + '&_method=PUT',
                    success: function(response) {
                        if (response.success) {
                            $('#modalEditKategori').modal('hide');
                            $('#table_kategori').DataTable().ajax.reload();
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

    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#table_kategori').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('kategori.list') }}",
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nama_kategori', name: 'nama_kategori' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        // Submit form tambah kategori
        $('#formTambahKategori').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('kategori.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#modalTambahKategori').modal('hide');
                        $('#table_kategori').DataTable().ajax.reload();
                        $('#formTambahKategori')[0].reset();
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
        $('#modalTambahKategori, #modalEditKategori').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').text('');
        });
    });
</script>
@endpush
