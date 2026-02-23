<?php

namespace App\Http\Controllers;

use App\Models\Tabarruat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TabarruatController extends Controller
{
    /**
     * Display the tabarruat form page
     */
    public function index()
    {
        return inertia('Tabarruat/Index');
    }

    /**
     * Store the tabarruat submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemohon' => 'required|string|max:255',
            'pasti' => 'required|string',
            'nama_arwah' => 'required|array|min:1',
            'nama_arwah.*' => 'required|string|max:255',
            'sudah_sumbangan' => 'required|boolean',
        ], [
            'nama_pemohon.required' => 'Sila masukkan nama pemohon',
            'pasti.required' => 'Sila pilih PASTI',
            'nama_arwah.required' => 'Sila masukkan sekurang-kurangnya satu nama arwah',
            'nama_arwah.min' => 'Sila masukkan sekurang-kurangnya satu nama arwah',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Tabarruat::create([
            'nama_pemohon' => $request->nama_pemohon,
            'pasti' => $request->pasti,
            'nama_arwah' => $request->nama_arwah,
            'sudah_sumbangan' => $request->sudah_sumbangan,
        ]);

        return redirect()->route('tabarruat.success')->with('data', [
            'nama_pemohon' => $request->nama_pemohon,
            'pasti' => $request->pasti,
            'nama_arwah' => $request->nama_arwah,
        ]);
    }

    /**
     * Display the success page
     */
    public function success()
    {
        return inertia('Tabarruat/Success');
    }

    /**
     * Get list of PASTI options
     */
    public function getPastiOptions()
    {
        return response()->json([
            'pasti' => [
                'PASTI Al Amin',
                'PASTI Al Fateh',
                'PASTI Al Furqan 1',
                'PASTI Al Furqan 2',
                'PASTI Al Ikhlas',
                'PASTI Al Khawarizmi',
                'PASTI Al Quds',
                'PASTI An Najah',
                'PASTI Ar Rahmah',
                'PASTI Ar Raudhah 1',
                'PASTI Ar Raudhah 2',
                'PASTI As Syakirin',
                'PASTI Az Zahrah 1',
                'PASTI Az Zahrah 2',
                'PASTI Az Zahrah 3',
                'PASTI Az Zahrah 4',
                'PASTI Baitul Ikhwah 1',
                'PASTI Baitul Ikhwah 2',
                'PASTI Cahaya Al Hamimi',
                'PASTI Darul Ulum 3',
                'PASTI Ibnu Sina',
                'PASTI Khaleef',
                'PASTI Lt. Imtiyaz',
                'PASTI Lt. Mukmin',
                'PASTI Lt. Muttaqin',
                'PASTI Nurul Iman',
                'PASTI Smart One',
                'PASTI Little Ikhwah',
            ],
        ]);
    }
}
