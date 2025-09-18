<?php
namespace App\Http\Controllers;
use App\Models\Kamar;
use App\Models\Layanantambahan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Booking;
use App\Models\Tsaleorder;
use App\Models\Tsaleorder1;
use App\Models\Kamarfoto;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahbooking = Booking::count();
        $jumlahpelanggan = Pelanggan::count();
        $layanantambahan = Layanantambahan::count();
        $jumlahkamar = Kamar::count();

        $bookingData = Booking::select(
                FacadesDB::raw('MONTH(tanggalcheckin) as month'), // ganti sesuai field tanggal
                FacadesDB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Inisialisasi array 12 bulan dengan 0
        $bookingByMonth = array_fill(1, 12, 0);

        foreach ($bookingData as $data) {
            $bookingByMonth[$data->month] = $data->total;
        }

        $bookingLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $bookingCounts = array_values($bookingByMonth);

        return view('admin.dashboard', compact(
            'jumlahbooking',
            'jumlahpelanggan',
            'layanantambahan',
            'jumlahkamar',
            'bookingLabels',
            'bookingCounts'
        ));
    }


    public function kategori(){
        return view('admin.kategori');
    }

    public function tambahkategori(){
        return view('admin.tambahkategori');
    }

    public function stok(){
        $layanans = Layanantambahan::all();
        return view('admin.stok', compact('layanans'));
    }


    public function tambahstok()
    {
        return view('admin.stoktambah');
    }

    public function tambahlayanansimpan(Request $request)
    {
        $request->validate([
            'namalayanantambahan' => 'required|string|max:255',
            'hargalayanantambahan' => 'required|numeric',
        ]);

        Layanantambahan::create($request->all());

        return redirect('layanan')->with('success', 'Data Layanan Tambahan Berhasil Ditambahkan');
    }

    public function tambahlayananedit($id)
    {
        $layanan = Layanantambahan::findOrFail($id);
        return view('admin.layanantambahedit', compact('layanan'));
    }

    public function tambahlayananeditsimpan(Request $request, $id){ 
        $request->validate([
            'namalayanantambahan' => 'required|string|max:255',
            'hargalayanantambahan' => 'required|numeric',
        ]);

        $layanan = Layanantambahan::findOrFail($id);
        $layanan->update($request->all());

        return redirect('layanan')->with('success', 'Data Layanan Tambahan Berhasil Diubah');
    }


    public function stokhapus($id)
    {
        Layanantambahan::destroy($id);
        return redirect('stok')->with('success', 'Data Stok Berhasil Dihapus');
    }

    public function Pelanggan()
    {
        $pelanggans = Pelanggan::all();
        return view('admin.pelanggan', compact('pelanggans'));
    }

    public function tambahpelanggan()
    {
        return view('admin.tambahpelanggan');
    }

    public function tambahpelanggansimpan(Request $request)
    {
        $request->validate([
            'namapelanggan' => 'required|string|max:255',
            'nohp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        Pelanggan::create($request->all());

        return redirect('pelanggan')->with('success', 'Data Pelanggan Berhasil Ditambahkan');
    }

    public function pelangganedit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.pelangganedit', compact('pelanggan'));
    }

    public function pelangganeditsimpan(Request $request, $id)
    {
        $request->validate([
            'namapelanggan' => 'required|string|max:255',
            'nohp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        return redirect('pelanggan')->with('success', 'Data Pelanggan Berhasil Diubah');
    }  

    public function pelangganhapus($id){
        Pelanggan::destroy($id);
        return redirect('pelanggan')->with('success', 'Data Pelanggan Berhasil Dihapus');
    }


    //Sales Order
    public function saleorder(){
        // $tsaleorder = Tsaleorder::with('salesorderdetails')
        // ->orderBy('id','desc')
        // ->limit(100)
        // ->get();

        // $test = Tsaleorder::with('salesorderdetails')->first();
        // dd($test->toArray());

        // $tsaleorder = Tsaleorder::with('salesorderdetails')
        // ->orderBy('id','desc')
        // ->limit(100)
        // ->get();

        // foreach ($tsaleorder as $so) {
        //     dump($so->id, $so->salesorderdetails->count());
        // }
        // dd('done');

        $tsaleorder = Tsaleorder::with('salesorderdetails')
        ->whereHas('salesorderdetails')
        ->orderBy('id','desc')
        ->limit(100)
        ->get();

        return view('admin.salesorder', compact('tsaleorder'));
    }

    public function saleordertambah()
    {
        $barangs = FacadesDB::connection('maisecgc')
            ->table('tbarang')
            ->join('thargajual', 'tbarang.id', '=', 'thargajual.idbar')
            ->leftJoin('tbarangfoto', 'tbarang.id', '=', 'tbarangfoto.idbar')
            ->select(
                'tbarang.*',
                'thargajual.har as hargajual',
                'tbarangfoto.img as foto'
            )
            ->get();

        return view('admin.saleordertambah', compact('barangs'));
    }


    public function saleordertambahsimpan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nohp' => 'required|string|max:20',
                'tglinput' => 'required|date',
                'items' => 'required|json',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dipesan.');
            }

            $items = json_decode($request->input('items'), true);

            \Log::info('Data barang yang ingin dipesan ', [
                'raw_items' => $request->items,
                'decoded_items' => $items,
                'nohp' => $request->nohp,
            ]);

            if (empty($items) || !is_array($items)) {
                return redirect()->back()
                    ->withErrors(['items' => 'Tidak ada barang yang dipilih.'])
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dipesan.');
            }

            // Mulai transaksi
            FacadesDB::beginTransaction();

            $totalOrder = 0;
            foreach ($items as $item) {
                $totalOrder += $item['price'] * $item['quantity'];
            }

            $nomorSO = 'SO-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Simpan ke tabel tsaleorder
            $saleorderId = FacadesDB::table('tsaleorder')->insertGetId([
                'noso'   => $nomorSO,
                'tgl'    => $request->input('tglinput'),
                'tot'    => $totalOrder,
                'iduse'  => auth()->id() ?? 1, // default 1 kalau belum ada login
            ]);

            // Simpan ke tabel tsaleorder1
            foreach ($items as $item) {
                if (
                    !isset($item['id']) ||
                    !isset($item['quantity']) ||
                    !isset($item['price']) ||
                    !isset($item['name'])
                ) {
                    throw new \Exception("Data item tidak lengkap!");
                }

                FacadesDB::table('tsaleorder1')->insert([
                    'idso'   => $saleorderId,
                    'tglinp' => $request->input('tglinput'),
                    'nam'    => $item['name'],
                    'qty'    => $item['quantity'],
                    'sat'    => $item['satuan'] ?? null,
                    'idbar'  => $item['id'],
                    'har'    => $item['price'],
                    'jum'    => $item['price'] * $item['quantity'],
                ]);
            }

            FacadesDB::commit();

            return redirect('saleorder')
                ->with('success', "Sales Order berhasil! Nomor: {$nomorSO}");

        } catch (\Exception $e) {
            FacadesDB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    public function saleorderdetail($id){
        $saleorder = Tsaleorder::with('salesorderdetails')->findOrFail($id);
        return view('admin.salesorderdetail', compact('saleorder'));
    }

    


    //Penjualan
    public function penjualan(){
        $penjualan = Penjualan::with('penjualanDetails')
        ->orderBy('id', 'desc')
        ->limit(100)
        ->get();
        return view('admin.penjualan', compact('penjualan'));
    }


    public function penjualantambah(){
        $barangs = FacadesDB::table('tbarang')
        ->join('thargajual','tbarang.id', '=', 'thargajual.idbar')
        ->leftjoin('tbarangfoto','tbarang.id', '=', 'tbarangfoto.idbar')
        ->select('tbarang.*','thargajual.har as hargajual',
        'tbarangfoto.img as foto')
        ->get();        
        return view('admin.penjualantambah', compact('barangs'));
    }

    public function penjualantambahsimpan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nohp' => 'required|string|max:20',
                'tglinput' => 'required|date',
                'items' => 'required|json',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dicheckout.');
            }

            $items = json_decode($request->input('items'), true);

            \Log::info('Data barang yang ingin dipesan ', [
                'raw_items' => $request->items,
                'decoded_items' => $items,
                'nohp' => $request->nohp,
            ]);

            if (empty($items) || !is_array($items)) {
                return redirect()->back()
                    ->withErrors(['items' => 'Tidak ada barang yang dipilih.'])
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dicheckout.');
            }

            // Mulai transaksi
            FacadesDB::beginTransaction();

            $totalPenjualan = 0;
            foreach ($items as $item) {
                $totalPenjualan += $item['price'] * $item['quantity'];
            }

            $nomorTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $penjualanId = FacadesDB::table('tjual')->insertGetId([
                'nofp'   => $nomorTransaksi,
                'tglfp'  => $request->input('tglinput'),
                'wakfp' => now()->format('H:i:s'), 
                'totnet' => $totalPenjualan,
                'nohp'   => $request->input('nohp'),
            ]);

            foreach ($items as $item) {
                if (!isset($item['id']) || !isset($item['quantity']) || !isset($item['price']) || !isset($item['name'])) {
                    throw new \Exception("Data item tidak lengkap!");
                }


                FacadesDB::table('tjual1')->insert([
                    'idj'    => $penjualanId,
                    'tglinp' => $request->input('tglinput'),
                    'nam'    => $item['name'],
                    'qty'    => $item['quantity'],
                    'sat'   => $item['satuan'],
                    'idbar' => $item['id'],
                    'har'    => $item['price'],
                    'subtot' => $item['price'] * $item['quantity'],
                ]);
            }
            FacadesDB::commit();

            return redirect('penjualan')
                ->with('success', "Transaksi berhasil! Nomor: {$nomorTransaksi}");

        } catch (\Exception $e) {
            FacadesDB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function penjualandetail($id)
    {
        $penjualan = Penjualan::with('penjualanDetails')->findOrFail($id);
        return view('admin.penjualandetail', compact('penjualan'));
    }

    //Pembelian 
    public function pembelian(){
        $bookings = Booking::orderBy('tanggalbooking','desc')->get();
        return view('admin.pembelian', compact('bookings'));
    }

    public function pembeliantambah(){
        $pelanggans = Pelanggan::all();
        $kamars = Kamar::all();
        $layanans = Layanantambahan::all();
        return view('admin.pembeliantambah', compact('pelanggans', 'kamars', 'layanans'));
    }



    

    // public function bookinghapus($id){
    //     FacadesDB::table('booking')->where('idbooking', $id)->delete();
    //     return redirect('booking')->with('success', 'Booking Berhasil Dihapus');
    // }


    // Pengguna
    public function penggunadaftar()
    {
        $data['pengguna'] = FacadesDB::table('users')->where('id', '!=', Auth::user()->id)->get();
        return view('admin.penggunadaftar', $data);
    }

    public function penggunatambah()
    {
        return view('admin.penggunatambah');
    }

    public function penggunatambahsimpan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'username' => 'required',
        ]);

        FacadesDB::table('users')->insert([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
        ]);

        return redirect('penggunadaftar')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function penggunaedit($id)
    {
        $data['pengguna'] = FacadesDB::table('users')->where('id', $id)->first();
        return view('admin.penggunaedit', $data);
    }

    public function penggunaeditsimpan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'username' => 'required',
        ]);

        $data = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ];
        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        FacadesDB::table('users')->where('id', $id)->update($data);
        return redirect('penggunadaftar')->with('success', 'Data Berhasil Diubah');
    }

    public function penggunahapus($id)
    {
        FacadesDB::table('users')->where('id', $id)->delete();
        return redirect('penggunadaftar')->with('success', 'Data Berhasil Dihapus');
    }

    // Profile
    public function profile()
    {
        $data['profile'] = FacadesDB::table('users')->where('id', Auth::user()->id)->first();
        return view('admin.profile', $data);
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);
        $data = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
        ];
        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        FacadesDB::table('users')->where('id', Auth::user()->id)->update($data);

        return redirect('profile')->with('success', 'Data Berhasil Diubah');
    }

    //Laporan Tamu

    public function laporantamu()
    {
        $tamu = Pelanggan::all();
        return view('admin.laporantamu', compact('tamu'));
    }

    public function cetaklaporantamu(){
        $tamu = Pelanggan::all();
        $pdf = FacadePdf::loadView('admin.cetaklaporantamu', compact('tamu'))->setPaper('a4', 'Potrait');
        return $pdf->stream('Laporan_Tamu.pdf');
    }

    public function laporanpembelian(Request $request)
    { 
        $query = Booking::query();

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tanggal_booking', [$request->tanggal_awal, $request->tanggal_akhir]);
            }

            if ($request->filled('cari')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%'.$request->cari.'%')
                    ->orWhere('email', 'like', '%'.$request->cari.'%')
                    ->orWhere('no_hp', 'like', '%'.$request->cari.'%');
                });
            }

            $data = $query->paginate(10);
        
        return view('admin.laporanpembelian', compact('data'));
    }

    public function cetaklaporankunjungan(Request $request)
    {
        $query = FacadesDB::table('booking')
                    ->join('pelanggan', 'booking.idpelanggan', '=', 'pelanggan.idpelanggan')
                    ->select('booking.*', 'pelanggan.namapelanggan', 'pelanggan.nohp', 'pelanggan.alamat');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('booking.tanggalbooking', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('pelanggan.namapelanggan', 'like', '%'.$request->cari.'%')
                ->orWhere('pelanggan.nohp', 'like', '%'.$request->cari.'%')
                ->orWhere('booking.noinvoice', 'like', '%'.$request->cari.'%');
            });
        }

        $data = $query->get();

        $pdf = FacadePdf::loadView('admin.cetaklaporankunjungan', compact('data'))
                        ->setPaper('a4', 'landscape');
        return $pdf->download('laporan_kunjungan.pdf');
    }

    public function laporanpenjualan()
    {
        return view('admin.laporanpenjualan'); 
    }



}