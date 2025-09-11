@extends('layouts.admintemplate')
@section('title','Tambah Booking')
@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Booking</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('bookingtambahsimpan') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Pilih Pelanggan --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Pelanggan</label>
            <div class="col-sm-10">
              <select name="idpelanggan" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggans as $pelanggan)
                  <option value="{{ $pelanggan->idpelanggan }}">{{ $pelanggan->namapelanggan }} - {{ $pelanggan->nohp }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Pilih Kamar --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Kamar</label>
            <div class="col-sm-10">
              <select name="idkamar" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                @foreach($kamars as $kamar)
                  <option value="{{ $kamar->idkamar }}">{{ $kamar->namakamar }} - Rp{{ number_format($kamar->harga,0,',','.') }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- No Invoice --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. Invoice</label>
            <div class="col-sm-10">
              <input type="text" name="noinvoice" class="form-control" value="INV{{ date('YmdHis') }}" readonly>
            </div>
          </div>

          {{-- Tanggal Booking --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Booking</label>
            <div class="col-sm-10">
              <input type="date" name="tanggalbooking" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>

          {{-- Checkin --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Checkin</label>
            <div class="col-sm-5">
              <input type="date" name="tanggalcheckin" class="form-control" required>
            </div>
            <div class="col-sm-5">
              <input type="time" name="waktucheckin" class="form-control">
            </div>
          </div>

          {{-- Checkout --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Checkout</label>
            <div class="col-sm-5">
              <input type="date" name="tanggalcheckout" class="form-control" required>
            </div>
            <div class="col-sm-5">
              <input type="time" name="waktucheckout" class="form-control">
            </div>
          </div>

          {{-- Jumlah Orang --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Jumlah Orang</label>
            <div class="col-sm-10">
              <input type="number" name="jumlahorang" class="form-control" min="1" required>
            </div>
          </div>

          {{-- No HP --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. HP</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" required>
            </div>
          </div>

          {{-- Foto Identitas --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Foto Identitas</label>
            <div class="col-sm-10">
              <input type="file" name="fotoidentitas" class="form-control">
            </div>
          </div>

          <hr>

          {{-- Layanan Tambahan --}}
          <h5 class="mb-3">Layanan Tambahan</h5>
          <div id="layanan-wrapper">
            <div class="row mb-3 layanan-item">
              <div class="col-sm-6">
                <select name="layanan[0][idlayanantambahan]" class="form-control">
                  <option value="">-- Pilih Layanan --</option>
                  @foreach($layanans as $layanan)
                    <option value="{{ $layanan->idlayanantambahan }}">{{ $layanan->namalayanantambahan }} - Rp{{ number_format($layanan->hargalayanantambahan,0,',','.') }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-4">
                <input type="number" name="layanan[0][jumlah]" class="form-control" placeholder="Jumlah">
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-success add-layanan">+</button>
              </div>
            </div>
          </div>

          <hr>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
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
  let layananIndex = 1;
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
          <input type="number" name="layanan[${layananIndex}][jumlah]" class="form-control" placeholder="Jumlah">
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
</script>
@endsection
