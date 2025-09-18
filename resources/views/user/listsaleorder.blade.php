<h2>Sale Order {{ $saleorder->noso }}</h2>
<p>Tanggal: {{ $saleorder->tgl }}</p>
<p>Total: Rp {{ number_format($saleorder->tot, 0, ',', '.') }}</p>

<h3>Detail Barang</h3>
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($saleorder->salesorderdetails as $item)
        <tr>
            <td>{{ $item->nam }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->har, 0, ',', '.') }}</td>
            <td>{{ number_format($item->jum, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
