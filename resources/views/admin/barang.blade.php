@extends('layouts.admintemplate')
@section('title','Data Barang')
@section('content')

<div class="card">
    <h5 class="card-header">Data Barang</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kamars as $kamar)
                        <tr>
                            <td>{{ $kamar->idkamar }}</td>
                            <td>
                                @if($kamar->fotos->count() > 0)
                                    <img src="{{ asset('storage/kamar_photos/'.$kamar->fotos->first()->foto) }}" 
                                         alt="Foto Kamar" 
                                         class="rounded" 
                                         style="width: 100px; height: 80px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Belum ada foto</span>
                                @endif
                            </td>
                            <td>{{ $kamar->namakamar }}</td>
                            <td>{{ $kamar->tipe ?? '-' }}</td>
                            <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                            <td>{{ $kamar->deskripsi }}</td>
                            <td>
                                <a href="{{ url('kamaredit', $kamar->idkamar) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ url('kamarhapus/'.$kamar->idkamar) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data kamar</td>
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
