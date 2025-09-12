<?php
namespace App\Http\Controllers;
use App\Models\Kamar;
use App\Models\Layanantambahan;
use App\Models\Pelanggan;
use App\Models\Booking;
use App\Models\Kamarfoto;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;

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


    public function barang()
    {
        $kamars = Kamar::with('fotos')->get(); // ambil kamar dengan fotonya
        return view('admin.barang', compact('kamars'));
    }

    public function tambahbarang()
    {
        return view('admin.barangtambah');
    }
    
    public function barangtambahsimpan(Request $request)
    {
        // Validasi input
        $request->validate([
            'namakamar' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'foto' => 'required|array|min:1|max:5',
            'foto.*' => 'required|file|image|mimes:jpeg,jpg,png|max:2048', // max 2MB
        ], [
            'namakamar.required' => 'Nama kamar wajib diisi',
            'harga.required' => 'Harga kamar wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'deskripsi.required' => 'Deskripsi kamar wajib diisi',
            'foto.required' => 'Minimal harus mengupload 1 foto',
            'foto.min' => 'Minimal harus mengupload 1 foto',
            'foto.max' => 'Maksimal 5 foto per kamar',
            'foto.*.required' => 'File foto wajib dipilih',
            'foto.*.image' => 'File harus berupa gambar',
            'foto.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.*.max' => 'Ukuran foto maksimal 2MB',
        ]);

        try {
            // Mulai database transaction
            \DB::beginTransaction();

            // Simpan data kamar
            $kamar = Kamar::create([
                'namakamar' => $request->namakamar,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
            ]);

            // Proses upload foto
            if ($request->hasFile('foto')) {
                $uploadedFiles = [];
                
                foreach ($request->file('foto') as $index => $foto) {
                    if ($foto->isValid()) {
                        // Generate nama file unik
                        $originalName = $foto->getClientOriginalName();
                        $extension = $foto->getClientOriginalExtension();
                        $fileName = 'kamar_' . $kamar->idkamar . '_' . ($index + 1) . '_' . time() . '.' . $extension;
                        
                        // Simpan file ke storage/app/public/kamar_photos
                        $filePath = $foto->storeAs('kamar_photos', $fileName, 'public');
                        
                        // Simpan informasi foto ke database
                        Kamarfoto::create([
                            'idkamar' => $kamar->idkamar,
                            'foto' => $fileName, // Simpan nama file saja
                        ]);
                        
                        $uploadedFiles[] = $fileName;
                    }
                }
                
                // Log untuk debugging
                \Log::info('Foto kamar berhasil diupload', [
                    'kamar_id' => $kamar->idkamar,
                    'files' => $uploadedFiles
                ]);
            }

            // Commit transaction
            \DB::commit();
            
            return redirect('kamar')->with('success', 'Data Kamar dan Foto Berhasil Ditambahkan');
            
        } catch (\Exception $e) {
            // Rollback jika ada error
            \DB::rollback();
            
            // Hapus file yang sudah terupload jika ada error
            if (isset($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    Storage::disk('public')->delete('kamar_photos/' . $file);
                }
            }
            
            \Log::error('Error upload foto kamar: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])
                ->withInput();
        }
    }

     public function barangedit($id)
    {
        $kamar = Kamar::with('fotos')->findOrFail($id);
        return view('admin.barangedit', compact('kamar'));
    }

    public function barangeditupdate(Request $request, $id)
    {
        $request->validate([
            'namakamar' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'foto.*'    => 'nullable|file|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        FacadesDB::beginTransaction();
        try {
            $kamar = Kamar::findOrFail($id);
            $kamar->update([
                'namakamar' => $request->namakamar,
                'harga'     => $request->harga,
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $foto) {
                    if ($foto->isValid()) {
                        $fileName = 'kamar_' . $kamar->idkamar . '_' . time() . '_' . $index . '.' . $foto->getClientOriginalExtension();
                        $foto->storeAs('kamar_photos', $fileName, 'public');
                        Kamarfoto::create([
                            'idkamar' => $kamar->idkamar,
                            'foto'    => $fileName,
                        ]);
                    }
                }
            }

            FacadesDB::commit();
            return redirect('kamar')->with('success', 'Data kamar berhasil diperbarui');
        } catch (\Exception $e) {
            FacadesDB::rollBack();
            return back()->withErrors(['error' => 'Gagal update kamar: '.$e->getMessage()]);
        }
    }



    public function kamarhapus($id)
    {
        FacadesDB::beginTransaction();
        try {
            $kamar = Kamar::findOrFail($id);

            // Hapus foto dari storage
            $fotos = Kamarfoto::where('idkamar', $id)->get();
            foreach ($fotos as $foto) {
                Storage::disk('public')->delete('kamar_photos/'.$foto->foto);
                $foto->delete();
            }

            // Hapus kamar
            $kamar->delete();

            FacadesDB::commit();
            return redirect('kamar')->with('success', 'Data kamar berhasil dihapus');
        } catch (\Exception $e) {
            FacadesDB::rollBack();
            return back()->withErrors(['error' => 'Gagal hapus kamar: '.$e->getMessage()]);
        }
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

        return redirect('tamu')->with('success', 'Data Tamu Berhasil Ditambahkan');
    }

    public function tamuedit($id)
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

        return redirect('tamu')->with('success', 'Data Tamu Berhasil Diubah');
    }  

    public function pelangganhapus($id){
        Pelanggan::destroy($id);
        return redirect('Tamu')->with('success', 'Data Tamu Berhasil Dihapus');
    }


    //Penjualan
    public function penjualan(){
        return view('admin.penjualan');
    }

    public function penjualantambah(){
        return view('admin.penjualantambah');
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

    public function bookingtambahsimpan(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'idpelanggan' => 'required|exists:pelanggan,idpelanggan',
            'idkamar' => 'required|exists:kamar,idkamar',
            'noinvoice' => 'required|string|max:50|unique:booking,noinvoice',
            'tanggalbooking' => 'required|date',
            'tanggalcheckin' => 'required|date|after_or_equal:today',
            'tanggalcheckout' => 'required|date|after:tanggalcheckin',
            'waktucheckin' => 'nullable|date_format:H:i',
            'waktucheckout' => 'nullable|date_format:H:i',
            'jumlahorang' => 'required|integer|min:1',
            'nohp' => 'required|string|max:20',
            'fotoidentitas' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layanan' => 'nullable|array',
            'layanan.*.idlayanantambahan' => 'required_with:layanan.*.jumlah|exists:layanantambahan,idlayanantambahan',
            'layanan.*.jumlah' => 'required_with:layanan.*.idlayanantambahan|integer|min:1'
        ], [
            'idpelanggan.required' => 'Pelanggan harus dipilih',
            'idkamar.required' => 'Kamar harus dipilih',
            'noinvoice.unique' => 'No. Invoice sudah digunakan',
            'tanggalcheckin.after_or_equal' => 'Tanggal checkin tidak boleh kurang dari hari ini',
            'tanggalcheckout.after' => 'Tanggal checkout harus setelah tanggal checkin',
            'jumlahorang.min' => 'Jumlah orang minimal 1',
            'nohp.max' => 'No. HP maksimal 20 karakter',
            'fotoidentitas.image' => 'File harus berupa gambar',
            'fotoidentitas.max' => 'Ukuran file maksimal 2MB'
        ]);

        FacadesDB::beginTransaction();

        try {
            $namaFile = null;

            // Memproses upload file fotoidentitas ke public/identitas
            if ($request->hasFile('fotoidentitas')) {
                $file = $request->file('fotoidentitas');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                
                // Pastikan folder identitas ada
                $destinationPath = 'identitas';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                // Pindahkan file ke public/identitas
                $file->move($destinationPath, $namaFile);
            }

            // Menggunakan Eloquent untuk mendapatkan data kamar
            $kamar = Kamar::find($validatedData['idkamar']);
            if (!$kamar) {
                throw new \Exception('Kamar tidak ditemukan.');
            }

            // Menghitung jumlah hari dan harga kamar
            $tanggalCheckin = Carbon::parse($validatedData['tanggalcheckin']);
            $tanggalCheckout = Carbon::parse($validatedData['tanggalcheckout']);
            $jumlahHari = $tanggalCheckin->diffInDays($tanggalCheckout);
            $hargaKamar = $kamar->harga * $jumlahHari;

            $totalHargaLayanan = 0;
            $layananDetails = [];

            // Memproses layanan tambahan
            if (isset($validatedData['layanan']) && is_array($validatedData['layanan'])) {
                foreach ($validatedData['layanan'] as $layanan) {
                    if (!empty($layanan['idlayanantambahan']) && !empty($layanan['jumlah'])) {
                        $dataLayanan = LayananTambahan::find($layanan['idlayanantambahan']);
                        if ($dataLayanan) {
                            $subtotal = $dataLayanan->hargalayanantambahan * $layanan['jumlah'];
                            $totalHargaLayanan += $subtotal;

                            $layananDetails[] = [
                                'idlayanantambahan' => $layanan['idlayanantambahan'],
                                'jumlah' => $layanan['jumlah'],
                                'harga' => $dataLayanan->hargalayanantambahan,
                                'subtotal' => $subtotal
                            ];
                        }
                    }
                }
            }

            $grandTotal = $hargaKamar + $totalHargaLayanan;

            // Membuat entri booking baru
            $booking = Booking::create([
                'idpelanggan' => $validatedData['idpelanggan'],
                'idkamar' => $validatedData['idkamar'],
                'noinvoice' => $validatedData['noinvoice'],
                'tanggalbooking' => $validatedData['tanggalbooking'],
                'tanggalcheckin' => $validatedData['tanggalcheckin'],
                'waktucheckin' => $validatedData['waktucheckin'],
                'tanggalcheckout' => $validatedData['tanggalcheckout'],
                'waktucheckout' => $validatedData['waktucheckout'],
                'jumlahorang' => $validatedData['jumlahorang'],
                'nohp' => $validatedData['nohp'],
                'fotoidentitas' => $namaFile,
                'hargakamar' => $hargaKamar,
                'denda' => 0.00,
                'grandtotal' => $grandTotal
            ]);
            
            // Menyimpan detail layanan tambahan
            foreach ($layananDetails as $detail) {
                FacadesDB::table('bookingdetail')->insert([
                    'idbooking' => $booking->idbooking,
                    'idlayanantambahan' => $detail['idlayanantambahan'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal']
                ]);
            }

            FacadesDB::commit();

            return redirect('pembelian')->with('success', 'Booking berhasil ditambahkan dengan No. Invoice: ' . $validatedData['noinvoice']);
            
        } catch (\Exception $e) {
            FacadesDB::rollback();
            
            // Hapus file jika ada error (sesuaikan path untuk public/identitas)
            if ($namaFile && file_exists(public_path('identitas/' . $namaFile))) {
                unlink(public_path('identitas/' . $namaFile));
            }

            return redirect('booking')->withInput()->with('error', 'Gagal menyimpan booking: ' . $e->getMessage());
        }
    }
    
    public function bookingedit($id)
    {
        $booking = FacadesDB::table('booking')
            ->join('pelanggan', 'booking.idpelanggan', '=', 'pelanggan.idpelanggan')
            ->join('kamar', 'booking.idkamar', '=', 'kamar.idkamar')
            ->where('booking.idbooking', $id)
            ->first();
        
        if (!$booking) {
            return redirect('booking')->with('error', 'Data booking tidak ditemukan');
        }
        $bookingDetails = FacadesDB::table('bookingdetail')
            ->join('layanantambahan', 'bookingdetail.idlayanantambahan', '=', 'layanantambahan.idlayanantambahan')
            ->where('bookingdetail.idbooking', $id)
            ->get();
        $booking->layanantambahan = $bookingDetails;

        $pelanggans = Pelanggan::all();
        $kamars = Kamar::all();
        $layanans = Layanantambahan::all();
        
        return view('admin.bookingedit', compact('booking', 'pelanggans', 'kamars', 'layanans'));
    }

        public function bookingeditsimpan(Request $request, $id)
    {
        // Log request data
        \Log::info('Booking Edit Request Data:', $request->all());

        $request->validate([
            'idpelanggan' => 'required|exists:pelanggan,idpelanggan',
            'idkamar' => 'required|exists:kamar,idkamar',
            'noinvoice' => 'required|string|max:50|unique:booking,noinvoice,' . $id . ',idbooking',
            'tanggalbooking' => 'required|date',
            'tanggalcheckin' => 'required|date',
            'tanggalcheckout' => 'required|date|after:tanggalcheckin',
            'waktucheckin' => 'nullable',
            'waktucheckout' => 'nullable',
            'jumlahorang' => 'required|integer|min:1',
            'nohp' => 'required|string|max:20',
            'fotoidentitas' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layanan' => 'nullable|array',
            'layanan.*.idlayanantambahan' => 'required_with:layanan.*.jumlah|exists:layanantambahan,idlayanantambahan',
            'layanan.*.jumlah' => 'required_with:layanan.*.idlayanantambahan|integer|min:1'
        ], [
            'idpelanggan.required' => 'Pelanggan harus dipilih',
            'idkamar.required' => 'Kamar harus dipilih',
            'noinvoice.unique' => 'No. Invoice sudah digunakan',
            'tanggalcheckout.after' => 'Tanggal checkout harus setelah tanggal checkin',
            'jumlahorang.min' => 'Jumlah orang minimal 1',
            'nohp.max' => 'No. HP maksimal 20 karakter',
            'fotoidentitas.image' => 'File harus berupa gambar',
            'fotoidentitas.max' => 'Ukuran file maksimal 2MB'
        ]);

        // Cek apakah booking exists
        $bookingExists = Booking::find($id);
        if (!$bookingExists) {
            return redirect('booking')->with('error', 'Data booking tidak ditemukan');
        }

        FacadesDB::beginTransaction();
        
        $namaFile = $bookingExists->fotoidentitas;
        $folderPath = 'identitas';

        try {
            // Catatan: Menggunakan path absolut seperti ini tidak disarankan untuk
            // produksi karena tidak portabel. Sebaiknya gunakan public_path()
            // atau konfigurasi Filesystem.

            // 2. Handle upload foto baru dan hapus foto lama
            if ($request->hasFile('fotoidentitas')) {
                // Hapus foto lama jika ada
                if ($bookingExists->fotoidentitas) {
                    $oldFilePath = $folderPath . '/' . $bookingExists->fotoidentitas;
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                }

                // Simpan foto baru ke path yang ditentukan
                $file = $request->file('fotoidentitas');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move($folderPath, $namaFile);
            }

            // 3. Hitung ulang harga kamar
            $kamar = Kamar::find($request->idkamar);
            if (!$kamar) {
                throw new \Exception('Kamar tidak ditemukan');
            }
            
            $tanggalCheckin = Carbon::parse($request->tanggalcheckin);
            $tanggalCheckout = Carbon::parse($request->tanggalcheckout);
            $jumlahHari = max(1, $tanggalCheckin->diffInDays($tanggalCheckout));
            $hargaKamar = $kamar->harga * $jumlahHari;

            // 4. Hitung total harga layanan tambahan
            $totalHargaLayanan = 0;
            $layananDetails = [];

            if ($request->has('layanan') && is_array($request->layanan)) {
                foreach ($request->layanan as $layanan) {
                    if (isset($layanan['idlayanantambahan']) && !empty($layanan['idlayanantambahan']) && 
                        isset($layanan['jumlah']) && !empty($layanan['jumlah']) && $layanan['jumlah'] > 0) {
                        
                        $dataLayanan = LayananTambahan::find($layanan['idlayanantambahan']);
    
                        if ($dataLayanan) {
                            $subtotal = $dataLayanan->hargalayanantambahan * $layanan['jumlah'];
                            $totalHargaLayanan += $subtotal;

                            $layananDetails[] = [
                                'idlayanantambahan' => $layanan['idlayanantambahan'],
                                'jumlah' => $layanan['jumlah'],
                                'harga' => $dataLayanan->hargalayanantambahan,
                                'subtotal' => $subtotal
                            ];
                        }
                    }
                }
            }

            // Pastikan denda tidak null
            $denda = $bookingExists->denda ?? 0;
            $grandTotal = $hargaKamar + $totalHargaLayanan + $denda;

            // 5. Prepare data untuk update
            $updateData = [
                'idpelanggan' => $request->idpelanggan,
                'idkamar' => $request->idkamar,
                'noinvoice' => $request->noinvoice,
                'tanggalbooking' => $request->tanggalbooking,
                'tanggalcheckin' => $request->tanggalcheckin,
                'tanggalcheckout' => $request->tanggalcheckout,
                'jumlahorang' => $request->jumlahorang,
                'nohp' => $request->nohp,
                'fotoidentitas' => $namaFile,
                'hargakamar' => $hargaKamar,
                'grandtotal' => $grandTotal
            ];

            // Tambahkan waktu checkin/checkout jika ada
            if ($request->waktucheckin) {
                $updateData['waktucheckin'] = $request->waktucheckin;
            }
            if ($request->waktucheckout) {
                $updateData['waktucheckout'] = $request->waktucheckout;
            }

            // 6. Update data booking
            $updated = $bookingExists->update($updateData);

            // Log apakah update berhasil
            \Log::info('Booking Update Result:', ['updated' => $updated, 'id' => $id]);

            // Hapus semua booking detail yang lama
            FacadesDB::table('bookingdetail')->where('idbooking', $id)->delete();

            // Insert booking detail yang baru
            if (!empty($layananDetails)) {
                // Tambahkan idbooking ke setiap item di layananDetails sebelum insert
                $layananDetailsWithBookingId = array_map(function($detail) use ($id) {
                    $detail['idbooking'] = $id;
                    return $detail;
                }, $layananDetails);
                FacadesDB::table('bookingdetail')->insert($layananDetailsWithBookingId);
            }

            FacadesDB::commit();

            return redirect('booking')->with('success', 'Booking berhasil diupdate dengan No. Invoice: ' . $request->noinvoice);

        } catch (\Exception $e) {
            FacadesDB::rollback();
            
            // Log error untuk debugging
            \Log::error('Booking Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            // Hapus foto baru yang diunggah jika terjadi error
            if ($request->hasFile('fotoidentitas') && $namaFile !== $bookingExists->fotoidentitas) {
                $newFilePath = $folderPath . '/' . $namaFile;
                if (File::exists($newFilePath)) {
                    File::delete($newFilePath);
                }
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate booking: ' . $e->getMessage());
        }
    }

    public function bookinghapus($id){
        FacadesDB::table('booking')->where('idbooking', $id)->delete();
        return redirect('booking')->with('success', 'Booking Berhasil Dihapus');
    }



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