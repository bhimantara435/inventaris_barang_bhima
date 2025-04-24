@empty($barang)
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
    <form id="formEditBarang" data-id="{{ $barang->id }}">
        @csrf
        @method('PUT')

        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Barang</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_barang" class="form-control" name="nama_barang"
                            value="{{ $barang->nama_barang }}" placeholder="Masukkan Nama Barang" required
                            maxlength="255">
                        <div class="invalid-feedback" id="edit_nama_barang_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori_id">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_kategori_id" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id }}" {{ $barang->kategori == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="edit_kategori_id_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga <span class="text-danger">*</span></label>
                        <input type="number" id="edit_harga" class="form-control" name="harga"
                            value="{{ $barang->harga }}" placeholder="Masukkan Harga" required>
                        <div class="invalid-feedback" id="edit_harga_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_stok">Stok <span class="text-danger">*</span></label>
                        <input type="number" id="edit_stok" class="form-control" name="stok"
                            value="{{ $barang->stok }}" placeholder="Masukkan Stok" required>
                        <div class="invalid-feedback" id="edit_stok_error"></div>
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
@endempty
