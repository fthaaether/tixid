<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\PromoExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('staff.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code',
            'type'       => 'required|in:percent,rupiah',
            'discount'   => 'required|numeric|min:1',
        ]);


        if ($request->type === 'percent' && $request->discount > 100) {
            return back()->withErrors(['discount' => 'Diskon dalam persen tidak boleh lebih dari 100'])->withInput();
        }

        if ($request->type === 'rupiah' && $request->discount < 500) {
            return back()->withErrors(['discount' => 'Diskon dalam rupiah minimal Rp 500'])->withInput();
        }

        Promo::create([
            'promo_code' => $request->promo_code,
            'type'       => $request->type,
            'discount'   => $request->discount,
            'actived'    => 1
        ]);

        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil ditambahkan');
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('staff.promo.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code,' . $id,
            'discount'   => 'required|numeric|min:1',
            'type'       => 'required|in:percent,rupiah',
        ]);

        if ($request->type === 'percent' && $request->discount > 100) {
            return back()->withErrors(['discount' => 'Diskon dalam persen tidak boleh lebih dari 100'])->withInput();
        }

        if ($request->type === 'rupiah' && $request->discount < 500) {
            return back()->withErrors(['discount' => 'Diskon dalam rupiah minimal Rp 500'])->withInput();
        }

        $promo = Promo::findOrFail($id);
        $promo->update([
            'promo_code' => $request->promo_code,
            'discount'   => $request->discount,
            'type'       => $request->type,
        ]);

        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil diperbarui');
    }


    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil dihapus');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-promo.xlsx";
        // prosese download
        return Excel::download(new PromoExport,$fileName);
    }

    public function trash()
    {
        // onlyTrashed() -> filter darta yang dihapus, delete_at BUKAN NULL
        $promoTrash = Promo::onlyTrashed()->get();
        return view('staff.promo.trash', compact('promoTrash'));
    }

    public function restore($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        // restore() -> mengembaliukan data yagn sudah dihapus (menghaps=us nilai tanggal pada delete_at)
        $promo->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        // forceDelete() -> menghapus data secara permanen, data hilang bahkan dari db nya
        $promo->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

        public function datatables()
    {
        $promos = Promo::query();
        return DataTables::of($promos)
        ->addIndexColumn()
        ->addColumn('action', function ($promo)  {
            $btnEdit = ' <a href="' . route('staff.promos.edit', $promo->id) . '" class="btn btn-primary me-2">Edit</a>';
            $btnDelete = '<form action="' . route('staff.promos.delete',    $promo->id) . '" method="POST">
            ' .@csrf_field() . method_field('DELETE') . '<button class="btn btn-danger">Hapus</button>
                        </form>';
                        return '<div class="d-flex justify-content-center align-items-center gap-2">'
                        . $btnEdit . $btnDelete .
                        '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}

