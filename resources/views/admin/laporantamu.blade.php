@extends('layouts.admintemplate')
@section('title','Laporan Tamu')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Laporan Tamu</h5>
        <a href="{{ url('cetaklaporantamu') }}" target="_blank" class="btn btn-sm btn-primary">
            <i class="bx bx-printer"></i> Cetak
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tamu as $pelanggan)
                        <tr>
                            <td>{{ $pelanggan->idpelanggan }}</td>
                            <td>{{ $pelanggan->namapelanggan }}</td>
                            <td>{{ $pelanggan->nohp }}</td>
                            <td>{{ $pelanggan->alamat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pelanggan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script')
@endsection
