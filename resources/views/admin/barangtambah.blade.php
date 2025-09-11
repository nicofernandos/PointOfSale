@extends('layouts.admintemplate')

@section('title','Tambah Kamar')

@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Barang</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('kamartambahsimpan') }}" method="POST" enctype="multipart/form-data">
          @csrf
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Kamar</label>
            <div class="col-sm-10">
              <input type="text" name="namakamar" class="form-control" placeholder="Contoh: Beras Pulen" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Harga</label>
            <div class="col-sm-10">
              <input type="number" name="harga" class="form-control" placeholder="Contoh: 500000" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
              <textarea name="deskripsi" class="form-control" placeholder="Tulis deskripsi barang..." rows="3" required></textarea>
            </div>
          </div>
          
          <!-- Section Upload Foto -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Foto Barang</label>
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
                  
                  <div class="row">
                    <div class="col-sm-12">
                      <button type="button" class="btn btn-outline-primary btn-sm add-foto">
                        <i class="bx bx-plus"></i> Tambah Foto Lainnya
                      </button>
                      <small class="text-muted ms-2">Maksimal 5 foto per kamar</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-save"></i> Simpan Barang
              </button>
              <a href="{{ url('barang') }}" class="btn btn-secondary">
                <i class="bx bx-x"></i> Batal
              </a>
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
    // Tambah foto baru
    if(e.target.classList.contains('add-foto') || e.target.closest('.add-foto')) {
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
          <div class="preview-container">
            <img id="preview-${fotoIndex}" src="" alt="Preview" style="display: none; width: 100px; height: 80px; object-fit: cover; border-radius: 8px;">
          </div>
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
    
    // Hapus foto
    if(e.target.classList.contains('remove-foto') || e.target.closest('.remove-foto')) {
      e.preventDefault();
      const fotoItems = document.querySelectorAll('.foto-item');
      if(fotoItems.length > 1) {
        e.target.closest('.foto-item').remove();
      } else {
        alert('Minimal harus ada 1 input foto');
      }
    }
  });
  
  // Function untuk preview gambar
  function previewImage(input, index) {
    const file = input.files[0];
    const preview = document.getElementById(`preview-${index}`);
    
    if (file) {
      // Validasi ukuran file (2MB = 2048KB)
      if (file.size > 2048 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 2MB');
        input.value = '';
        preview.style.display = 'none';
        return;
      }
      
      // Validasi tipe file
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG');
        input.value = '';
        preview.style.display = 'none';
        return;
      }
      
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      }
      reader.readAsDataURL(file);
    } else {
      preview.style.display = 'none';
    }
  }
  document.querySelector('form').addEventListener('submit', function(e) {
    const fotoInputs = document.querySelectorAll('input[type="file"]');
    let hasPhoto = false;
    
    fotoInputs.forEach(input => {
      if (input.files && input.files.length > 0) {
        hasPhoto = true;
      }
    });
    
    if (!hasPhoto) {
      e.preventDefault();
      alert('Minimal harus mengupload 1 foto kamar');
      return false;
    }
  });
</script>
@endsection