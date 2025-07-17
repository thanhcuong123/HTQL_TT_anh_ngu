<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

    public function showloginform()
    {
        return view('auth.login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'email' => 'required|email', // Đảm bảo sử dụng 'email'
            'password' => 'required',
        ]);

        // Lấy dữ liệu đăng nhập
        $credentials = $request->only('email', 'password'); // Sử dụng 'email'

        // Thử đăng nhập
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Điều hướng theo vai trò
            switch ($role) {
                case 'admin':
                    return redirect()->route('trangchu')->with('msg', 'Xin chào Admin');
                case 'giaovien':
                    return redirect()->route('trangchu')->with('msg', 'Xin chào Giáo viên');
                case 'hocvien':
                    return redirect()->route('trangchu')->with('msg', 'Xin chào Học viên');
                case 'nhanvien':
                    return redirect()->route('trangchu')->with('msg', 'Xin chào Nhân viên');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Vai trò người dùng không hợp lệ');
            }
        }

        // Thất bại đăng nhập
        return back()->with('error', 'Email hoặc mật khẩu không đúng');
    }
}
