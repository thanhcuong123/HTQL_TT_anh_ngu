<?php

use App\Http\Controllers\Admin\CoSoController;
use App\Http\Controllers\Admin\GiaoVienController;
use App\Http\Controllers\Admin\HocPhiController;
use App\Http\Controllers\Admin\HocVienController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KhoaHocController;
use App\Http\Controllers\Admin\KynangController;
use App\Http\Controllers\Admin\LopHocController;
use App\Http\Controllers\Admin\PhongHocController;
use App\Http\Controllers\Admin\TrinhdoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaHocController;
use App\Http\Controllers\PaginationController;
use App\Models\CaHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;

Route::get('/', function () {
    return view('index');
});



//login
Route::get('/show/login', [AuthController::class, 'showloginform'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');





Route::get('/pagination', [PaginationController::class, 'index'])->name('pagination');
//khoahoc
Route::get('/admin/khoahoc', [KhoaHocController::class, 'index'])->name('khoahoc.index');
Route::post('/admin/khoahoc/store', [KhoaHocController::class, 'store'])->name('khoahoc.store');
Route::put('/admin/khoahoc/update/{id}', [KhoaHocController::class, 'update'])->name('khoahoc.update');
Route::delete('/admin/khoahoc/delete/{ma}', [KhoaHocController::class, 'destroy'])->name('khoahoc.destroy');
//search

Route::get('/khoahoc/search', [KhoaHocController::class, 'search'])->name('khoahoc.search');
// trinhdo
Route::get('/admin/trinhdo/test', [TrinhdoController::class, 'test'])->name('trinhdo.test');
Route::get('/admin/trinhdo', [TrinhdoController::class, 'index'])->name('trinhdo.index');
Route::get('admin/trinhdo/search', [TrinhdoController::class, 'search'])->name('trinhdo.search');
Route::post('/admin/trinhdo/add', [TrinhdoController::class, 'store'])->name('trinhdo.store');
Route::put('/admin/trinhdo/update/{id}', [TrinhdoController::class, 'update'])->name('trinhdo.update');
Route::delete('/admin/trinhdo/delete/{ma}', [TrinhdoController::class, 'destroy'])->name('trinhdo.destroy');
//kynang
Route::get('/admin/kynang', [KynangController::class, 'index'])->name('kynang.index');
Route::get('admin/kynang/search', [KynangController::class, 'search'])->name('kynang.search');
Route::delete('/admi/kynang/destroy/{id}', [KynangController::class, 'destroy'])->name('kynang.destroy');
//hocvien
Route::get('admin/hocvien', [HocVienController::class, 'index'])->name('hocvien.index');
Route::get('/admin/hocvien/search', [HocVienController::class, 'search'])->name('hocvien.search');
Route::post('/admin/hocvien/store', [HocVienController::class, 'store'])->name('hocvien.store');
Route::put('admin/hocvien/update/{hocvien}', [HocVienController::class, 'update'])->name('hocvien.update');
Route::delete('/admin/hocvien/destroy/{id}', [HocVienController::class, 'destroy'])->name('hocvien.destroy');
//lophoc
Route::get('/admin/lophoc', [LopHocController::class, 'index'])->name('lophoc.index');
Route::get('/admin/lophoc/chitiet/{id}', [LopHocController::class, 'show'])->name('lophoc.show');
Route::post('/admin/lophoc/store', [LopHocController::class, 'store'])->name('lophoc.store');
Route::get('/lophoc/search', [LopHocController::class, 'search'])->name('lophoc.search');
Route::delete('/admin/lophoc/delete/{id}', [LopHocController::class, 'destroy'])->name('lophoc.destroy');
Route::post('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'storeGiaoVien'])
    ->name('lophoc.storegiaovien');
Route::put('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'updateGiaovien'])
    ->name('lophoc.updateGiaovien');
Route::post('admin/lophoc/{id}/addhocvien', [LopHocController::class, 'addHocVien'])->name('lophoc.addhocvien');
Route::post('/admin/lophoc/{id}/addlichhoc', [LopHocController::class, 'addlichoc'])->name('lophoc.addlichhoc');

//cahoc
Route::get('/admin/cahoc', [CaHocController::class, 'index'])->name('cahoc.index');
Route::post('/admin/cahoc/store', [cahoccontroller::class, 'store'])->name('cahoc.store');
Route::put('/admin/cahoc/update/{id}', [CaHocController::class, 'update'])->name('cahoc.update');
route::delete('admin/cahoc/destroy/{id}', [cahoccontroller::class, 'destroy'])->name('cahoc.destroy');
//coso
Route::get('/admin/coso', [CoSoController::class, 'index'])->name('coso.index');
route::post('/admin/coso/store', [CoSoController::class, 'store'])->name('coso.store');
Route::put('/admin/coso/update/{id}', [CoSoController::class, 'update'])->name('coso.update');
route::delete('admin/coso/destroy/{id}', [CoSoController::class, 'destroy'])->name('coso.destroy');
//phonghoc
Route::get('/admin/phonghoc', [PhongHocController::class, 'index'])->name('phonghoc.index');
route::post('/admin/phonghoc/store', [PhongHocController::class, 'store'])->name('phonghoc.store');
Route::put('/admin/phonghoc/update/{id}', [PhongHocController::class, 'update'])->name('phonghoc.update');
//giaovien
ROute::get('/admin/giaovien', [GiaoVienController::class, 'index'])->name('giaovien.index');
Route::post('/admin/giaovien/store', [GiaoVienController::class, 'store'])->name('giaovien.store');
Route::put('/giaovien/update/{id}', [GiaovienController::class, 'update'])->name('giaovien.update');
//hocphi
Route::get('/admin/hocphi', [HocPhiController::class, 'index'])->name('hocphi.index');
