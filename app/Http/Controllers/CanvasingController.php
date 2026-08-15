<?php

namespace App\Http\Controllers;

use App\Models\Canvasing;
use Illuminate\Http\Request;

class CanvasingController extends Controller
{
    public function index()
    {
        $items = Canvasing::latest()->paginate(20);
        return view('pages.canvasing.index', compact('items'));
    }

    public function create()
    {
        return view('pages.canvasing.create');
    }

    public function store(Request $request)
    {
        $this->hapusDataLama();

        $validated = $request->validate([
            'kantor' => 'required|string|max:100',
            'tanggal' => 'required|date|before_or_equal:' . now()->setTimezone('Asia/Jakarta'),
            'jenis_canvasing' => 'required|string',
            'keterangan' => 'required|string',
            'foto.*' => 'required|image|mimes:jpg,jpeg,png|max:10240', // ✅ max 10MB
        ], [
            'kantor.required' => 'Kantor wajib diisi.',
            'kantor.max' => 'Kantor maksimal 100 karakter.',

            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal harus berupa format tanggal yang valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi waktu saat ini.',

            'jenis_canvasing.required' => 'Jenis canvasing wajib dipilih.',

            'keterangan.required' => 'Keterangan wajib diisi.',

            'foto.*.required' => 'Foto wajib diupload.',
            'foto.*.image' => 'File harus berupa gambar.',
            'foto.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'foto.*.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        $validated['foto'] = json_encode($this->uploadFoto($request, $validated['kantor']));
        Canvasing::create($validated);
        return redirect()->route('dashboard')->with('success','Data Canvasing <strong>' . $validated['kantor'] . '</strong> berhasil disimpan!');
    }


    public function show(Canvasing $canvasing)
    {
        return view('pages.canvasing.show', compact('canvasing'));
    }

    public function edit($id)
    {
        $item = Canvasing::findOrFail($id);
        return view('pages.canvasing.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Canvasing::findOrFail($id);

        $fotoLama = json_decode($item->foto ?? '[]', true);
        $deletedPhotos = $this->parseDeletedPhotos($request->deleted_photos ?? []);

        // Hapus foto lama dari array
        foreach ($deletedPhotos as $fotoPath) {
            $fotoLama = array_values(array_filter($fotoLama, fn($f) => $f !== $fotoPath));
        }

        $isFotoRequired = empty($fotoLama);


        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:' . now()->setTimezone('Asia/Jakarta'),
            'jenis_canvasing' => 'required|string',
            'keterangan' => 'nullable|string',
            'foto.*' => ($isFotoRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png|max:10240', // ✅ max 10MB
        ], [
            'foto.*.max' => 'Ukuran gambar maksimal 10MB.',
            'foto.*.required' => 'Foto wajib diupload jika semua foto lama dihapus.',
        ]);

       // Hapus file fisik yang dihapus user
        foreach ($deletedPhotos as $fotoPath) {
            $fullPath = public_path($fotoPath);
            if (file_exists($fullPath)) @unlink($fullPath);
        }

        // Foto baru → resize + kompresi GD < 2MB
        $fotoBaru = $this->uploadFoto($request, $item->kantor);

        // Update data
        $item->update([
            'tanggal' => $validated['tanggal'] ?? $item->tanggal,
            'jenis_canvasing' => $validated['jenis_canvasing'] ?? $item->jenis_canvasing,
            'keterangan' => $validated['keterangan'] ?? $item->keterangan,
            'foto' => json_encode(array_merge($fotoLama, $fotoBaru)),
        ]);

        return redirect()->route('dashboard')
            ->with(
                'success',
                'Data Canvasing <strong>' . $item->kantor . '</strong> berhasil diperbarui!'
            );
    }


    public function destroy($id)
    {
        try {
            $item = Canvasing::findOrFail($id);
            $kantor = $item->kantor ?? 'Data';

            foreach (json_decode($item->foto ?? '[]', true) as $fotoPath) {
                $fullPath = public_path($fotoPath);
                if (file_exists($fullPath)) @unlink($fullPath);
            }

            $item->delete();

            return redirect()->route('dashboard')
                ->with('success', 'Data Canvasing <strong>' . $kantor . '</strong> berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function destroyFoto(Request $request)
    {
        $fotoPath = $request->foto;
        $item = Canvasing::where('foto', 'like', "%$fotoPath%")->first();

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

   private function uploadFoto(Request $request, string $namaKantor): array
    {
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            $destination = public_path('images/canvasing');
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
                $fotoPaths[] = 'images/canvasing/' . $namaFile;
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
        $dataLama = Canvasing::where('tanggal', '<', $batasTanggal)->get();

        foreach ($dataLama as $item) {
            foreach (json_decode($item->foto ?? '[]', true) as $fotoPath) {
                $fullPath = public_path($fotoPath);
                if (file_exists($fullPath)) unlink($fullPath);
            }
            $item->delete();
        }
    }
}
