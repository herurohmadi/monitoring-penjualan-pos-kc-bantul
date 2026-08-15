<?php

namespace App\Http\Controllers;

use App\Models\AktivasiSeller;
use Illuminate\Http\Request;

class AktivasiSellerController extends Controller
{
    public function index()
    {
        $items = AktivasiSeller::latest()->paginate(20);
        return view('pages.aktivasi_seller.index', compact('items'));
    }

    public function create()
    {
        return view('pages.aktivasi_seller.create');
    }

    public function store(Request $request)
    {
        $this->hapusDataLama();

        $validated = $request->validate([
            'kantor' => 'required|string|max:100',
            'tanggal' => 'required|date|before_or_equal:' . now()->setTimezone('Asia/Jakarta'),
            'nama_olshop' => 'required|string|max:255',
            'jenis_aktivasi_seller' => 'required|string|max:255',
            'link_toko' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'jenis_produk' => 'required|string|max:255',
            'pesaing' => 'nullable|string|max:255',
            'alamat_lengkap' => 'required|string',
            'keterangan_lainnya' => 'nullable|string',
            'foto.*' => 'required|image|mimes:jpg,jpeg,png|max:10240', // ✅ max 10MB
        ], [
            'kantor.required' => 'Kantor wajib diisi.',
            'kantor.string' => 'Kantor harus berupa teks.',
            'kantor.max' => 'Kantor maksimal 100 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal harus berupa format tanggal yang valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi waktu saat ini.',
            'nama_olshop.required' => 'Nama toko online wajib diisi.',
            'nama_olshop.string' => 'Nama toko online harus berupa teks.',
            'nama_olshop.max' => 'Nama toko online maksimal 255 karakter.',
            'jenis_aktivasi_seller.required' => 'Jenis aktivasi seller wajib diisi.',
            'jenis_aktivasi_seller.string' => 'Jenis aktivasi seller harus berupa teks.',
            'jenis_aktivasi_seller.max' => 'Jenis aktivasi seller maksimal 255 karakter.',
            'link_toko.required' => 'Link toko wajib diisi.',
            'link_toko.string' => 'Link toko harus berupa teks.',
            'link_toko.max' => 'Link toko maksimal 255 karakter.',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_pemilik.string' => 'Nama pemilik harus berupa teks.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 255 karakter.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.string' => 'Nomor HP harus berupa teks.',
            'nomor_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'jenis_produk.required' => 'Jenis produk wajib diisi.',
            'jenis_produk.string' => 'Jenis produk harus berupa teks.',
            'jenis_produk.max' => 'Jenis produk maksimal 255 karakter.',
            'pesaing.string' => 'Pesaing harus berupa teks.',
            'pesaing.max' => 'Pesaing maksimal 255 karakter.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'alamat_lengkap.string' => 'Alamat lengkap harus berupa teks.',
            'keterangan_lainnya.string' => 'Keterangan lainnya harus berupa teks.',
            'foto.*.max' => 'Ukuran gambar maksimal 10MB.', // pesan validasi
        ]);
        
        $validated['pesaing'] = $validated['pesaing'] ?? 'Tidak Disebutkan';
        $validated['jenis_aktivasi_seller'] = $validated['jenis_aktivasi_seller'] ?? 'Tidak Disebutkan';
        $validated['foto'] = json_encode($this->uploadFoto($request, $validated['kantor']));
           
        AktivasiSeller::create($validated);
        return redirect()->route('dashboard')->with('success', 'Data Aktivasi Seller <strong>' . ($validated['kantor'] ?? 'Data') . '</strong> berhasil disimpan!');
    }

    public function show(AktivasiSeller $aktivasiSeller)
    {
        return view('pages.aktivasi_seller.show', compact('aktivasiSeller'));
    }

    public function edit($id)
    {
        $item = AktivasiSeller::findOrFail($id);
        return view('pages.aktivasi_seller.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = AktivasiSeller::findOrFail($id);

        $fotoLama = json_decode($item->foto ?? '[]', true);
        $deletedPhotos = $this->parseDeletedPhotos($request->deleted_photos ?? []);

        // Hapus foto lama dari array
        foreach ($deletedPhotos as $fotoPath) {
            $fotoLama = array_values(array_filter($fotoLama, fn($f) => $f !== $fotoPath));
        }

        $isFotoRequired = empty($fotoLama);

        $validated = $request->validate([
            'tanggal' => 'nullable|date|before_or_equal:' . now()->setTimezone('Asia/Jakarta'),
            'nama_olshop' => 'nullable|string|max:255',
            'link_toko' => 'nullable|string|max:255',
            'nama_pemilik' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'jenis_produk' => 'nullable|string|max:255',
            'pesaing' => 'nullable|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'keterangan_lainnya' => 'nullable|string',
            'jenis_aktivasi_seller' => 'required|string|max:255',
            'jenis_aktivitas' => 'nullable|string|max:255',
            'foto.*' => ($isFotoRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png|max:10240', // ✅ max 10MB
        ], [
            'foto.*.max' => 'Ukuran gambar maksimal 10MB.',
            'foto.*.required' => 'Foto wajib diupload jika semua foto lama dihapus.',
            'jenis_aktivasi_seller.required' => 'Jenis aktivasi seller wajib diisi.',
            'jenis_aktivasi_seller.string' => 'Jenis aktivasi seller harus berupa teks.',
            'jenis_aktivasi_seller.max' => 'Jenis aktivasi seller maksimal 255 karakter.',
        ]);

        // Hapus file fisik yang dihapus user
        foreach ($deletedPhotos as $fotoPath) {
            $fullPath = public_path($fotoPath);
            if (file_exists($fullPath)) @unlink($fullPath);
        }

        // Foto baru → resize + kompresi GD < 2MB
        $fotoBaru = $this->uploadFoto($request, $item->kantor);

        // Update data
        $item->update(array_merge($validated, [
            'tanggal' => $validated['tanggal'] ?? $item->tanggal,
            'foto' => json_encode(array_merge($fotoLama, $fotoBaru)),
            'link_toko' => $validated['link_toko'] ?? $item->link_toko,
            'nama_pemilik' => $validated['nama_pemilik'] ?? $item->nama_pemilik,
            'nomor_hp' => $validated['nomor_hp'] ?? $item->nomor_hp,
            'jenis_produk' => $validated['jenis_produk'] ?? $item->jenis_produk,
            'pesaing' => $validated['pesaing'] ?? $item->pesaing,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? $item->alamat_lengkap,
            'keterangan_lainnya' => $validated['keterangan_lainnya'] ?? $item->keterangan_lainnya,
            'jenis_aktivasi_seller' => $validated['jenis_aktivasi_seller'] ?? $item->jenis_aktivasi_seller,
        ]));

        return redirect()->route('dashboard')
            ->with('success', 'Data Aktivasi Seller <strong>' . ($item->kantor ?? 'Data') . '</strong> berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        try {
            $item = AktivasiSeller::findOrFail($id);
            $kantor = $item->kantor ?? 'Data';

            foreach (json_decode($item->foto ?? '[]', true) as $fotoPath) {
                $fullPath = public_path($fotoPath);
                if (file_exists($fullPath)) @unlink($fullPath);
            }

            $item->delete();

            return redirect()->route('dashboard')
                ->with('success', 'Data Aktivasi Seller <strong>' . $kantor . '</strong> berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function destroyFoto(Request $request)
    {
        $fotoPath = $request->foto;
        $item = AktivasiSeller::where('foto', 'like', "%$fotoPath%")->first();

        if ($item && $fotoPath) {
            $fotoArray = json_decode($item->foto, true) ?? [];
            $fotoArray = array_filter($fotoArray, fn($f) => $f !== $fotoPath);
            $item->foto = json_encode(array_values($fotoArray));
            $item->save();

            if (file_exists(public_path($fotoPath))) unlink(public_path($fotoPath));

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    // ------------------------ Helper Functions ------------------------

    // ✅ Helper: uploadFoto
    private function uploadFoto(Request $request, string $namaKantor): array
    {
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            $destination = public_path('images/aktivasi_seller');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            foreach ($request->file('foto') as $index => $file) {
                $namaFile = preg_replace('/\s+/', '_', strtolower($namaKantor)) .
                    '-aktivasi-' . now()->format('YmdHis') .
                    '-foto' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                $path = $destination . '/' . $namaFile;
                $file->move($destination, $namaFile);

                // ✅ Kompresi manual dengan GD agar < 2MB
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $image = ($extension === 'png')
                        ? imagecreatefrompng($path)
                        : imagecreatefromjpeg($path);

                    $quality = 80;
                    do {
                        ob_start();
                        // PNG dikonversi ke JPEG agar lebih kecil
                        imagejpeg($image, null, $quality);
                        $compressed = ob_get_clean();
                        $size = strlen($compressed);
                        $quality -= 10;
                    } while ($size > 1 * 1024 * 1024 && $quality > 10);
                    file_put_contents($path, $compressed);
                    imagedestroy($image);
                }
                $fotoPaths[] = 'images/aktivasi_seller/' . $namaFile;
            }
        }
        return $fotoPaths;
    }

    private function parseDeletedPhotos($raw): array
    {
        $deleted = [];
        if (is_array($raw)) {
            foreach ($raw as $r) {
                if (is_string($r) && ($decoded = json_decode($r, true)) !== null) {
                    $deleted = array_merge($deleted, $decoded);
                } else {
                    $deleted[] = $r;
                }
            }
        } elseif (is_string($raw)) {
            if (($decoded = json_decode($raw, true)) !== null && is_array($decoded)) {
                $deleted = $decoded;
            } else {
                $deleted[] = $raw;
            }
        }
        return $deleted;
    }

    private function hapusDataLama()
    {
        $batasTanggal = now()->subDays(365); // Hapus data lebih dari 1 tahun
        $dataLama = AktivasiSeller::where('tanggal', '<', $batasTanggal)->get();
        foreach ($dataLama as $item) {
            foreach (json_decode($item->foto ?? '[]', true) as $fotoPath) {
                $fullPath = public_path($fotoPath);
                if (file_exists($fullPath)) unlink($fullPath);
            }
            $item->delete();
        }
    }
}
