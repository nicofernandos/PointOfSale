@extends('layouts.admintemplate')
@section('title','Data Booking')
@section('content')

<div class="card">
    <h5 class="card-header">Data Booking</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Invoice</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Tanggal Pembelian</th>
                        <th>Checkout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $booking->noinvoice }}</td>
                            <td>{{ $booking->pelanggan->namapelanggan ?? '-' }}</td>
                            <td>{{ $booking->nohp }}</td>
                            <td>{{ $booking->tanggalbooking }}</td>
                            <td>{{ $booking->tanggalcheckin }} {{ $booking->waktucheckin }}</td>
                            <td>{{ $booking->tanggalcheckout }} {{ $booking->waktucheckout }}</td>
                            <td>
                                <a href="{{ url('bookingedit', $booking->idbooking) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ url('bookinghapus/'.$booking->idbooking) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus booking ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data Pembelian</td>
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
