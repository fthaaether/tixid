<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Model::all() => mengambil semua data di model
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        //compact()
        return view('admin.user.index', compact('users'));
    }

    public function datatables()
    {
        $users = User::query();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('action', function ($user)  {
            $btnEdit = ' <a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-primary me-2">Edit</a>';
            $btnDelete = '<form action="' . route('admin.users.delete', $user->id) . '" method="POST">
            ' .@csrf_field() . method_field('DELETE') . '<button class="btn btn-danger">Hapus</button>
                        </form>';
                        return '<div class="d-flex justify-content-center align-items-center gap-2">'
                        . $btnEdit . $btnDelete .
                        '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'required|min:8',
        ], [
            'first_name.required' => 'First name wajib di isi',
            'first_name.min' => 'First name minimal 3 karakter',
            'last_name.required' => 'Last name wajib di isi',
            'last_name.min' => 'Last name minimal 3 karakter',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'password wajib di isi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $createData = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'

        ]);

        if ($createData) {
            return redirect()->route('login')->with('success', 'Berhasil membuat akun, silahkan login!');
        } else {
            return redirect()->route('signup')->with('failed', 'gagal memproses data!, silahkan coba lagi!');
        }
    }

    public function authentication(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi'
        ]);
        //data yg akan digunakan untuk verifikasi
        // $data = $request->only(['email', 'password']);
        // //Auth::attempt() -> mencocokan data (email-pw / username-pw)
        // if (Auth::attempt($data)) {
        //     //jika data email-pw cocok
        //     if (Auth::user()->role == 'admin') { {
        //         //di cek lagi rolenya, selain admin ke dashboard
        //         return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
        //     } elseif (Auth::user()->role == 'staff') {
        //         return redirect()->route('staff.dashboard')->with('success', 'Berhasil login!');
        //     } else {
        //         //selain admin ke home
        //         return redirect()->route('home')->with('success', 'Berhasil login');
        //     }
        // } else {
        //     return redirect()->back()->with('error', 'Gagal! pastikan email dan password benar');
        // }

        $data = $request->only(['password', 'email']);
        if (Auth::attempt($data)) {
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('Success', 'Berhasil Login!');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Berhasil Login!');
            } else {
                return redirect()->route('home')->with('Success', 'Berhasil Login!');
            }
        } else {
            return redirect()->back()->with('Error', 'Gagal! Pastikan Email dan Password Benar');
        }
    }

    public function logout()
    {
        //menghapus sesi login
        Auth::logout();
        return redirect()->route('home')->with('logout', "anda telah logout! Silahkan login kembali untuk akses lengkap");
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.user.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:10'
        ], [
            'name.required' => 'Nama Petugas harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email telah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password diisi minimal 10 karakter',
        ]);
        $createDate = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'staff',
            'password' => Hash::make($request->password)
        ]);
        if ($createDate) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('Error', 'Silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit($id)
    {
        //edit($id) => $id dari {$id} di route edit
        //Cinema::find() => mencari data di table users berdasarkan id
        $user = User::find($id);
        //dd() => cek data
        // dd($users->toArray());
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //(Request $request, $id) : Request $reqest (ambil data forum), $id ambil parameter placeholder {$id} dari route
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:10' // gk wajib diisi
        ], [
            'name.required' => 'Nama Petugas harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'password.min' => 'Password minimal 10 karakter', // gk wajib diisi
        ]);
        //
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        //jika password ada isinya hanya meng update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        //where("id", $id) -> sebelum diupdate wajib cari datanya, untuk mencarinya salah satunya dengan where
        //format-> where('field_di_fillable', $sumber data)
        $updateData = User::where('id', $id)->update($data);

        if ($updateData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', "Silahkan coba lagi");
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data!');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-user.xlsx";
        // prosese download
        return Excel::download(new UserExport,$fileName);
    }

    public function trash()
    {
        // onlyTrashed() -> filter darta yang dihapus, delete_at BUKAN NULL
        $userTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        // restore() -> mengembaliukan data yagn sudah dihapus (menghaps=us nilai tanggal pada delete_at)
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        // forceDelete() -> menghapus data secara permanen, data hilang bahkan dari db nya
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
