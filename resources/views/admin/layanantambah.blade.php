@extends('layouts.admintemplate')
@section('title','Tambah Layanan Tambahan')
@section('content')

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Layanan Tambahan</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('tambahlayanansimpan') }}" method="POST">
          @csrf
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Layanan</label>
            <div class="col-sm-10">
              <input type="text" name="namalayanantambahan" class="form-control" placeholder="Contoh: Laundry, Spa" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Harga Layanan</label>
            <div class="col-sm-10">
              <input type="number" name="hargalayanantambahan" class="form-control" placeholder="Contoh: 100000" required>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ url('layanan') }}" class="btn btn-secondary">Batal</a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
@endsection