@extends('layouts.admintemplate')
@section('title','Edit Booking')
@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Edit Booking</h5>
      </div>
      <div class="card-body">
        {{-- Tampilkan Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tampilkan Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('bookingeditsimpan/'.$booking->idbooking) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          {{-- Pilih Pelanggan --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Pelanggan <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <select name="idpelanggan" class="form-control @error('idpelanggan') is-invalid @enderror" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggans as $pelanggan)
                  <option value="{{ $pelanggan->idpelanggan }}" 
                    {{ (old('idpelanggan', $booking->idpelanggan) == $pelanggan->idpelanggan) ? 'selected' : '' }}>
                    {{ $pelanggan->namapelanggan }} - {{ $pelanggan->nohp }}
                  </option>
                @endforeach
              </select>
              @error('idpelanggan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Pilih Kamar --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Kamar <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <select name="idkamar" class="form-control @error('idkamar') is-invalid @enderror" required>
                <option value="">-- Pilih Kamar --</option>
                @foreach($kamars as $kamar)
                  <option value="{{ $kamar->idkamar }}" 
                    {{ (old('idkamar', $booking->idkamar) == $kamar->idkamar) ? 'selected' : '' }}>
                    {{ $kamar->namakamar }} - Rp{{ number_format($kamar->harga,0,',','.') }}
                  </option>
                @endforeach
              </select>
              @error('idkamar')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- No Invoice --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. Invoice <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" name="noinvoice" class="form-control @error('noinvoice') is-invalid @enderror" 
                value="{{ old('noinvoice', $booking->noinvoice) }}" required>
              @error('noinvoice')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Tanggal Booking --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Booking <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="date" name="tanggalbooking" class="form-control @error('tanggalbooking') is-invalid @enderror" 
                value="{{ old('tanggalbooking', $booking->tanggalbooking) }}" required>
              @error('tanggalbooking')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Checkin --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Checkin <span class="text-danger">*</span></label>
            <div class="col-sm-5">
              <input type="date" name="tanggalcheckin" class="form-control @error('tanggalcheckin') is-invalid @enderror" 
                value="{{ old('tanggalcheckin', $booking->tanggalcheckin) }}" required>
              @error('tanggalcheckin')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-sm-5">
              <input type="time" name="waktucheckin" class="form-control @error('waktucheckin') is-invalid @enderror" 
                value="{{ old('waktucheckin', $booking->waktucheckin) }}">
              @error('waktucheckin')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Checkout --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Checkout <span class="text-danger">*</span></label>
            <div class="col-sm-5">
              <input type="date" name="tanggalcheckout" class="form-control @error('tanggalcheckout') is-invalid @enderror" 
                value="{{ old('tanggalcheckout', $booking->tanggalcheckout) }}" required>
              @error('tanggalcheckout')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-sm-5">
              <input type="time" name="waktucheckout" class="form-control @error('waktucheckout') is-invalid @enderror" 
                value="{{ old('waktucheckout', $booking->waktucheckout) }}">
              @error('waktucheckout')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Jumlah Orang --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Jumlah Orang <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="number" name="jumlahorang" class="form-control @error('jumlahorang') is-invalid @enderror" 
                min="1" value="{{ old('jumlahorang', $booking->jumlahorang) }}" required>
              @error('jumlahorang')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- No HP --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. HP <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control @error('nohp') is-invalid @enderror" 
                value="{{ old('nohp', $booking->nohp) }}" required maxlength="20">
              @error('nohp')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Foto Identitas --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Foto Identitas</label>
            <div class="col-sm-10">
              @if($booking->fotoidentitas)
                  <div class="mb-2">
                      <img src="{{ asset('identitas/'.$booking->fotoidentitas) }}" 
                          alt="Foto Identitas" 
                          style="max-width: 200px; max-height: 200px;" 
                          class="img-thumbnail">
                      <p class="text-muted small mt-1">Foto saat ini</p>
                  </div>
              @endif
              <input type="file" name="fotoidentitas" class="form-control @error('fotoidentitas') is-invalid @enderror" 
                accept="image/jpeg,image/png,image/jpg,image/gif">
              <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, GIF. Maksimal 2MB</small>
              @error('fotoidentitas')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr>

          {{-- Layanan Tambahan --}}
          <h5 class="mb-3">Layanan Tambahan</h5>
          <div id="layanan-wrapper">
            @php
                $oldLayanan = old('layanan', []);
                $bookingLayanan = isset($booking->layanantambahan) ? $booking->layanantambahan->toArray() : [];
                $layananToShow = !empty($oldLayanan) ? $oldLayanan : $bookingLayanan;
            @endphp
            
            @if(!empty($layananToShow))
              @foreach($layananToShow as $index => $bookingLayananItem)
                <div class="row mb-3 layanan-item">
                  <div class="col-sm-6">
                    <select name="layanan[{{ $index }}][idlayanantambahan]" class="form-control">
                      <option value="">-- Pilih Layanan --</option>
                      @foreach($layanans as $layanan)
                        @php
                            $selectedValue = is_array($bookingLayananItem) ? 
                                ($bookingLayananItem['idlayanantambahan'] ?? '') : 
                                ($bookingLayananItem->idlayanantambahan ?? '');
                        @endphp
                        <option value="{{ $layanan->idlayanantambahan }}" 
                          {{ $layanan->idlayanantambahan == $selectedValue ? 'selected' : '' }}>
                          {{ $layanan->namalayanantambahan }} - Rp{{ number_format($layanan->hargalayanantambahan,0,',','.') }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-4">
                    @php
                        $jumlahValue = is_array($bookingLayananItem) ? 
                            ($bookingLayananItem['jumlah'] ?? '') : 
                            ($bookingLayananItem->jumlah ?? '');
                    @endphp
                    <input type="number" name="layanan[{{ $index }}][jumlah]" class="form-control" 
                           placeholder="Jumlah" value="{{ $jumlahValue }}" min="1">
                  </div>
                  <div class="col-sm-2">
                    @if($index == 0)
                      <button type="button" class="btn btn-success add-layanan">+</button>
                    @else
                      <button type="button" class="btn btn-danger remove-layanan">-</button>
                    @endif
                  </div>
                </div>
              @endforeach
            @else
              <div class="row mb-3 layanan-item">
                <div class="col-sm-6">
                  <select name="layanan[0][idlayanantambahan]" class="form-control">
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($layanans as $layanan)
                      <option value="{{ $layanan->idlayanantambahan }}">
                        {{ $layanan->namalayanantambahan }} - Rp{{ number_format($layanan->hargalayanantambahan,0,',','.') }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-4">
                  <input type="number" name="layanan[0][jumlah]" class="form-control" placeholder="Jumlah" min="1">
                </div>
                <div class="col-sm-2">
                  <button type="button" class="btn btn-success add-layanan">+</button>
                </div>
              </div>
            @endif
          </div>

          <hr>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Update Booking</button>
              <a href="{{ url('booking') }}" class="btn btn-secondary">Batal</a>
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
  // Hitung index layanan yang sudah ada
  @php
      $layananCount = !empty($layananToShow) ? count($layananToShow) : 1;
  @endphp
  let layananIndex = {{ $layananCount }};
  
  document.addEventListener('click', function(e) {
    if(e.target.classList.contains('add-layanan')) {
      e.preventDefault();
      let wrapper = document.getElementById('layanan-wrapper');
      let newRow = document.createElement('div');
      newRow.classList.add('row','mb-3','layanan-item');
      newRow.innerHTML = `
        <div class="col-sm-6">
          <select name="layanan[${layananIndex}][idlayanantambahan]" class="form-control">
            <option value="">-- Pilih Layanan --</option>
            @foreach($layanans as $layanan)
              <option value="{{ $layanan->idlayanantambahan }}">{{ $layanan->namalayanantambahan }} - Rp{{ number_format($layanan->hargalayanantambahan,0,',','.') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-4">
          <input type="number" name="layanan[${layananIndex}][jumlah]" class="form-control" placeholder="Jumlah" min="1">
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-danger remove-layanan">-</button>
        </div>
      `;
      wrapper.appendChild(newRow);
      layananIndex++;
    }

    if(e.target.classList.contains('remove-layanan')) {
      e.preventDefault();
      e.target.closest('.layanan-item').remove();
    }
  });

  // Validasi form sebelum submit
  document.querySelector('form').addEventListener('submit', function(e) {
    let tanggalCheckin = new Date(document.querySelector('[name="tanggalcheckin"]').value);
    let tanggalCheckout = new Date(document.querySelector('[name="tanggalcheckout"]').value);
    
    if (tanggalCheckout <= tanggalCheckin) {
      e.preventDefault();
      alert('Tanggal checkout harus setelah tanggal checkin!');
      return false;
    }
  });
</script>
@endsection