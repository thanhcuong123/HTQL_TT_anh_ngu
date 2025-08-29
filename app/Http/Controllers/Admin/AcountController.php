<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\NhanVien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AcountController extends Controller
{


    // app/Http/Controllers/HocVienController.php

    public function accountIndex(Request $request)
    {
        $query = HocVien::query()->with('user');

        // Lọc theo từ khóa
        if ($request->filled('tu_khoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('mahocvien', 'like', '%' . $request->tu_khoa . '%')
                    ->orWhere('ten', 'like', '%' . $request->tu_khoa . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->trangthai == 'active') {
            $query->whereHas('user', function ($q) {
                $q->where('trangthai', '1');
            });
        } elseif ($request->trangthai == 'locked') {
            $query->whereHas('user', function ($q) {
                $q->where('trangthai', '0');
            });
        } elseif ($request->trangthai == 'no_account') {
            $query->doesntHave('user');
        }

        $dshocvien = $query->paginate(20);

        return view('admin.account.hocvien.index', compact('dshocvien'));
    }


    public function createAccount(Request $request)
    {
        $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Tạo user
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'hocvien',
            'trangthai' => '1'
        ]);

        // Liên kết với học viên
        $hocvien = HocVien::findOrFail($request->hocvien_id);
        $hocvien->user_id = $user->id;
        $hocvien->save();

        // Gửi email thông báo
        Mail::send('email.account_hv', [
            'hocvien' => $hocvien,
            'user' => $user,
            'password' => $request->password // gửi mật khẩu gốc
        ], function ($message) use ($hocvien) {
            $message->to($hocvien->email_hv)
                ->subject('Thông tin tài khoản học viên');
        });

        return back()->with('success', 'Tạo tài khoản thành công và đã gửi email thông báo!');
    }

    public function lockAccount($id)
    {
        $user = User::findOrFail($id);
        $user->trangthai = '0';
        $user->save();

        return back()->with('success', 'Đã khoá tài khoản.');
    }

    //giáo viên
    public function accountIndexGV(Request $request)
    {
        $query = GiaoVien::query();

        if ($request->has('tu_khoa') && $request->tu_khoa) {
            $kw = $request->tu_khoa;
            $query->where(function ($q) use ($kw) {
                $q->where('magiaovien', 'like', "%$kw%")
                    ->orWhere('ten', 'like', "%$kw%");
            });
        }

        // $query->orderBy('id', 'desc');
        $dshocvien = $query->with('user')->paginate(10);

        return view('admin.account.giaovien.index', compact('dshocvien'));
    }
    public function createAccountGV(Request $request)
    {


        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'giaovien',
            'trangthai' => '1'
        ]);

        $giaovien = GiaoVien::findOrFail($request->hocvien_id);
        $giaovien->user_id = $user->id;
        $giaovien->save();

        return back()->with('success', 'Tạo tài khoản thành công!');
    }
    public function lockAccountGV($id)
    {
        $user = User::findOrFail($id);
        $user->trangthai = '0';
        $user->save();

        return back()->with('success', 'Đã khoá tài khoản.');
    }

    public function accountIndexNV(Request $request)
    {
        $query = NhanVien::query();

        if ($request->has('tu_khoa') && $request->tu_khoa) {
            $kw = $request->tu_khoa;
            $query->where(function ($q) use ($kw) {
                $q->where('manhanvien', 'like', "%$kw%")
                    ->orWhere('ten', 'like', "%$kw%");
            });
        }
        $query->orderByRaw('user_id IS NULL DESC')
            ->orderBy('id', 'desc');
        $dshocvien = $query->with('user')->paginate(10);

        return view('admin.account.nhanvien.index', compact('dshocvien'));
    }
    public function lockAccountNV($id)
    {
        $user = User::findOrFail($id);
        $user->trangthai = '0';
        $user->save();

        return back()->with('success', 'Đã khoá tài khoản.');
    }
    public function createAccountNV(Request $request)
    {


        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'nhanvien',
            'trangthai' => '1'
        ]);

        $nhanvien = NhanVien::findOrFail($request->hocvien_id);
        $nhanvien->user_id = $user->id;
        $nhanvien->save();

        return back()->with('success', 'Tạo tài khoản thành công!');
    }
}
