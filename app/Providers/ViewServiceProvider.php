<?php

namespace App\Providers;

use App\Models\KhoaHoc;
use App\Models\TrinhDo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Gắn dữ liệu $khoahocss cho view header
        View::composer('pages.layout.hearder', function ($view) {
            $khoahocss = DB::table('khoahoc')
                ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
                ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
                ->select(
                    'khoahoc.id as khoahoc_id',
                    'khoahoc.ma as khoahoc_ten',
                    'trinhdo.id as trinhdo_id', // Đã thêm trinhdo.id ở đây
                    'trinhdo.ten as trinhdo_ten'
                )
                ->distinct() // Đảm bảo lấy các cặp khóa học - trình độ duy nhất
                ->get();

            $view->with('khoahocss', $khoahocss);
        });
    }
}
