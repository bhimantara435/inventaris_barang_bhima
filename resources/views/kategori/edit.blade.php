@empty($kategori)
    <!-- Jika variabel $kategori kosong, tampilkan modal error -->
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Judul modal error -->
                <h5 class="modal-title">Kesalahan</h5>
                <!-- Tombol untuk menutup modal -->
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal dengan pesan kesalahan -->
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle-fill"></i> Data tidak ditemukan</h5>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Tombol tutup modal -->
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
            </div>
        </div>
    </div>
@else
    <!-- Form untuk mengedit kategori jika data tersedia -->
    <form id="formEditKategori" data-id="{{ $kategori->id }}">
        @csrf <!-- Token CSRF untuk keamanan -->
        @method('PUT') <!-- Method override untuk HTTP PUT -->

        <!-- Modal form edit kategori -->
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Judul modal -->
                    <h5 class="modal-title">Edit Data Kategori</h5>
                    <!-- Tombol tutup modal -->
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Input field untuk nama kategori -->
                    <div class="form-group">
                        <label for="edit_nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_kategori" class="form-control" name="nama_kategori"
                            value="{{ $kategori->nama_kategori }}" placeholder="Masukkan Nama Kategori" required
                            maxlength="255">
                        <div class="invalid-feedback" id="edit_nama_kategori_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Tombol batal -->
                    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <!-- Tombol simpan -->
                    <button type="submit" class="btn btn-primary ms-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Script JavaScript untuk menangani form edit -->
    <script>
        $(document).ready(function() {
            // Saat form dikirim
            $('#formEditKategori').submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman

                // Ambil ID kategori dari atribut data
                const kategoriId = $(this).data('id');
                // Bangun URL update dari route
                const editUrl = "{{ route('kategori.update', ':id') }}".replace(':id', kategoriId);

                // Reset error validasi
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Kirim data via Ajax
                $.ajax({
                    url: editUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Tutup modal dan reload tabel jika sukses
                            $('#myModal').modal('hide');
                            $('#table_kategori').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        // Tampilkan error validasi jika ada
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
    </script>
@endempty
