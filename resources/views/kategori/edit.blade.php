@empty($kategori)
    <!-- Modal error jika data tidak ditemukan -->
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle-fill"></i> Data tidak ditemukan</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
            </div>
        </div>
    </div>
@else
    <form id="formEditKategori" data-id="{{ $kategori->id }}">
        @csrf
        @method('PUT')

        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Kategori</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_kategori" class="form-control" name="nama_kategori"
                            value="{{ $kategori->nama_kategori }}" placeholder="Masukkan Nama Kategori" required
                            maxlength="255">
                        <div class="invalid-feedback" id="edit_nama_kategori_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#formEditKategori').submit(function(e) {
                e.preventDefault();

                const kategoriId = $(this).data('id');
                const editUrl = "{{ route('kategori.update', ':id') }}".replace(':id', kategoriId);

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                $.ajax({
                    url: editUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#myModal').modal('hide');
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
    </script>
@endempty
