<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index()
    {
        $khoahocss = DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma as khoahoc_ten',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();
        // Lấy danh sách tin tức, kèm thông tin tác giả nếu có
        $newsArticles = TinTuc::with('nhanVien.user') // eager load để truy cập tên tác giả
            ->where('trang_thai', 'da_dang') // Chỉ lấy tin đã đăng
            ->orderBy('ngaydang', 'desc')    // Mới nhất trước
            ->get()
            ->map(function ($news) {
                return (object)[
                    'id' => $news->id,
                    'title' => $news->tieude,
                    'description' => strip_tags($news->noidung),
                    'published_at' => $news->ngaydang,
                    'image_url' => $news->hinhanh,
                    'author' => optional(optional($news->tacgia)->user)->ten ?? 'Admin',
                ];
            });

        return view('pages.tintuc', compact('newsArticles', 'khoahocss'));
    }
    public function show($id)
    {
        $khoahocss = DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma as khoahoc_ten',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();
        $relatedNews = TinTuc::where('id', '!=', $id)
            ->where('trang_thai', 'da_dang')
            ->latest('ngaydang')
            ->limit(5)
            ->get();
        $news = TinTuc::with('tacgia')->findOrFail($id);
        return view('pages.tintuc_detail', compact('news', 'khoahocss', 'relatedNews'));
    }
}
