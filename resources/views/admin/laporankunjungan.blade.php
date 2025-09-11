@extends('layouts.admintemplate')
@section('title','Laporan Kunjungan')
@section('content')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Laporan Kunjungan</h5>
      <a href="{{ url('cetaklaporankunjungan', request()->all()) }}" target="_blank" class="btn btn-sm btn-primary">
          <i class="bx bx-printer"></i> Cetak
      </a>
  </div>

  <div class="card-body">
      <!-- Filter Form -->
      <form method="GET" action="{{ url('laporankunjungan') }}" class="row g-3 mb-3">
          <div class="col-md-3">
              <label class="form-label">Tanggal Awal</label>
              <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
          </div>
          <div class="col-md-3">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
          </div>
          <div class="col-md-3">
              <label class="form-label">Cari</label>
              <input type="text" name="cari" class="form-control" placeholder="Nama / No. HP / Invoice" value="{{ request('cari') }}">
          </div>
          <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-primary me-2">Filter</button>
              <a href="{{ url('laporankunjungan') }}" class="btn btn-secondary">Reset</a>
          </div>
      </form>

      <!-- Table -->
      <div class="table-responsive text-nowrap">
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>No Invoice</th>
                      <th>Nama Pelanggan</th>
                      <th>No. HP</th>
                      <th>Tanggal Booking</th>
                      <th>Tanggal Checkin</th>
                      <th>Tanggal Checkout</th>
                      <th>Jumlah Orang</th>
                      <th>Grand Total</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($data as $i => $row)
                      <tr>
                          <td>{{ $i + $data->firstItem() }}</td>
                          <td>{{ $row->noinvoice }}</td>
                          <td>{{ $row->pelanggan->namapelanggan ?? '-' }}</td>
                          <td>{{ $row->nohp }}</td>
                          <td>{{ $row->tanggalbooking }}</td>
                          <td>{{ $row->tanggalcheckin }}</td>
                          <td>{{ $row->tanggalcheckout }}</td>
                          <td>{{ $row->jumlahorang }}</td>
                          <td>Rp {{ number_format($row->grandtotal, 0, ',', '.') }}</td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="9" class="text-center">Tidak ada data</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3">
          {{ $data->links() }}
      </div>
  </div>
</div>

@endsection

@section('script')
@endsection
