@extends('layouts.admintemplate')
@section('title','Edit Kamar')
@section('content')

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Edit Kamar</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('kamareditupdate/'.$kamar->idkamar) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <!-- Nama -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Kamar</label>
            <div class="col-sm-10">
              <input type="text" name="namakamar" class="form-control" 
                     value="{{ old('namakamar', $kamar->namakamar) }}" required>
            </div>
          </div>

          <!-- Harga -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Harga</label>
            <div class="col-sm-10">
              <input type="number" name="harga" class="form-control" 
                     value="{{ old('harga', $kamar->harga) }}" required>
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
              <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
            </div>
          </div>

          <!-- Foto Lama -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Foto Lama</label>
            <div class="col-sm-10">
              <div class="row">
                @forelse($kamar->fotos as $foto)
                  <div class="col-md-3 mb-3 text-center">
                    <img src="{{ asset('storage/kamar_photos/'.$foto->foto) }}" 
                         class="img-thumbnail mb-2" style="width: 150px; height: 120px; object-fit: cover;">
                    <div>
                      <a href="{{ url('kamar/foto/delete/'.$foto->id) }}" 
                         class="btn btn-sm btn-danger" 
                         onclick="return confirm('Yakin ingin hapus foto ini?')">
                        <i class="bx bx-trash"></i> Hapus
                      </a>
                    </div>
                  </div>
                @empty
                  <p class="text-muted">Belum ada foto</p>
                @endforelse
              </div>
            </div>
          </div>

          <!-- Upload Foto Baru -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tambah Foto Baru</label>
            <div class="col-sm-10">
              <div class="card border-primary">
                <div class="card-body">
                  <div id="foto-wrapper">
                    <div class="row mb-3 foto-item">
                      <div class="col-sm-8">
                        <input type="file" name="foto[]" class="form-control" accept="image/*" onchange="previewImage(this, 0)">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                      </div>
                      <div class="col-sm-4">
                        <div class="preview-container">
                          <img id="preview-0" src="" alt="Preview" style="display: none; width: 100px; height: 80px; object-fit: cover; border-radius: 8px;">
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-outline-primary btn-sm add-foto">
                    <i class="bx bx-plus"></i> Tambah Foto Lainnya
                  </button>
                  <small class="text-muted ms-2">Maksimal total 5 foto</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol -->
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="{{ url('kamar') }}" class="btn btn-secondary">Batal</a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  let fotoIndex = 1;
  const maxFoto = 5;

  document.addEventListener('click', function(e) {
    if(e.target.closest('.add-foto')) {
      e.preventDefault();

      const currentFotoCount = document.querySelectorAll('.foto-item').length;
      if(currentFotoCount >= maxFoto) {
        alert('Maksimal 5 foto per kamar');
        return;
      }

      let wrapper = document.getElementById('foto-wrapper');
      let newRow = document.createElement('div');
      newRow.classList.add('row','mb-3','foto-item');
      newRow.innerHTML = `
        <div class="col-sm-8">
          <input type="file" name="foto[]" class="form-control" accept="image/*" onchange="previewImage(this, ${fotoIndex})">
          <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
        </div>
        <div class="col-sm-3">
          <img id="preview-${fotoIndex}" src="" style="display: none; width: 100px; height: 80px; object-fit: cover; border-radius: 8px;">
        </div>
        <div class="col-sm-1">
          <button type="button" class="btn btn-danger btn-sm remove-foto">
            <i class="bx bx-trash"></i>
          </button>
        </div>
      `;
      wrapper.appendChild(newRow);
      fotoIndex++;
    }

    if(e.target.closest('.remove-foto')) {
      e.preventDefault();
      const fotoItems = document.querySelectorAll('.foto-item');
      if(fotoItems.length > 1) {
        e.target.closest('.foto-item').remove();
      } else {
        alert('Minimal harus ada 1 input foto');
      }
    }
  });

  function previewImage(input, index) {
    const file = input.files[0];
    const preview = document.getElementById(`preview-${index}`);
    if(file) {
      if(file.size > 2048 * 1024) {
        alert('Ukuran file maksimal 2MB');
        input.value = '';
        preview.style.display = 'none';
        return;
      }
      const allowed = ['image/jpeg','image/jpg','image/png'];
      if(!allowed.includes(file.type)) {
        alert('Format file tidak valid');
        input.value = '';
        preview.style.display = 'none';
        return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }
</script>
@endsection
