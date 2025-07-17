<?php


use App\Http\Controllers\Admin\CoSoController;
use App\Http\Controllers\Admin\GiaoVienController;
use App\Http\Controllers\Admin\HocPhiController;
use App\Http\Controllers\Admin\HocVienController;
use App\Http\Controllers\Users\Homecontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KhoaHocController;
use App\Http\Controllers\Admin\KynangController;
use App\Http\Controllers\Admin\LopHocController;
use App\Http\Controllers\Admin\PhongHocController;
use App\Http\Controllers\Admin\TrinhdoController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaHocController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffHocvienController;
use App\Http\Controllers\Staff\StaffLophocController;
use App\Http\Controllers\Users\AboutController;
use App\Http\Controllers\Users\AllCoursesController;
use App\Http\Controllers\Users\ClassController;
use App\Http\Controllers\Users\ContactController;
use App\Http\Controllers\Users\CoursesController;
use App\Http\Controllers\Users\TuVanController;

use App\Models\CaHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;


Route::get('/demo-calendar', function () {
    return view('test');
});

Route::get('/trangchu', [Homecontroller::class, 'index'])->name('trangchu');


//login
Route::get('/show/login', [AuthController::class, 'showloginform'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', [CoursesController::class, 'index'])->name('home');
Route::get('/trangchu', [Homecontroller::class, 'index'])->name('trangchu');
Route::get('/chitietkhoahoc/{id}', [CoursesController::class, 'courses_detail'])->name('courses_detail');
Route::get('/search', [CoursesController::class, 'search'])->name('search');
Route::get('/lophoc/{id}', [ClassController::class, 'show'])->name('class.show');
Route::get('/gioithieu', [AboutController::class, 'index'])->name('gioithieu');
Route::get('/khoahoc', [AllCoursesController::class, 'index'])->name('courses');
Route::get('/lienhe', [ContactController::class, 'index'])->name('contact');
Route::post('tuvan/store', [TuVanController::class, 'store'])->name('tuvan.store');



Route::get('/pagination', [PaginationController::class, 'index'])->name('pagination');

Route::middleware(['role:admin'])->group(function () {
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
    Route::get('/admin/lophoc/search', [LopHocController::class, 'search'])->name('lophoc.search');
    Route::delete('/admin/lophoc/delete/{id}', [LopHocController::class, 'destroy'])->name('lophoc.destroy');
    Route::post('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'storeGiaoVien'])
        ->name('lophoc.storegiaovien');
    Route::put('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'updateGiaovien'])
        ->name('lophoc.updateGiaovien');
    Route::post('admin/lophoc/{id}/addhocvien', [LopHocController::class, 'addHocVien'])->name('lophoc.addhocvien');
    Route::post('/admin/lophoc/{id}/addlichhoc', [LopHocController::class, 'addlichoc'])->name('lophoc.addlichhoc');
    Route::put('/admin/lophoc/{lophoc}/update', [LopHocController::class, 'update'])->name('lophocdetail.update');
    Route::delete('/admin/lophoc/{lophoc}/remove-hocvien/{hocvien}', [LopHocController::class, 'removeHocVien'])->name('lophoc.removeHocVien');
    Route::post('/hocvien/transfer-lop', [LopHocController::class, 'transferLop'])->name('hocvien.transferLop');
    Route::delete('/lophoc/{lophoc}/thoikhoabieu/{thoikhoabieu}', [LopHocController::class, 'destroylichhoc'])->name('lichhoc.destroy');

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
    Route::get('/giaovien/search', [GiaoVienController::class, 'search'])->name('giaovien.search');
    Route::post('/admin/giaovien/destroy', [GiaoVienController::class, 'destroy'])->name('giaovien.destroy');
    //hocphi
    Route::get('/admin/hocphi', [HocPhiController::class, 'index'])->name('hocphi.index');
    Route::get('/tuition-management', [HocPhiController::class, 'index'])->name('tuition.index');
    Route::get('/classes/{classId}/students', [HocPhiController::class, 'getStudentsByClass']);
    Route::get('/hocphi/get-tuition-info/{classId}/{studentId}', [HocPhiController::class, 'getTuitionInfo']);
    Route::post('/hocphi/process-payment', [HocPhiController::class, 'processPayment']);
    // Route để in biên lai
    Route::get('/phieuthu/print/{studentId}/{classId}', [HocPhiController::class, 'printReceipt'])->name('phieuthu.print');
    Route::post('/hocphi/send-reminders', [HocPhiController::class, 'sendTuitionReminders']);
    //tu van
    Route::get('/admin/tuvan', [TuVanController::class, 'index'])->name('tuvan');
    Route::put('/admin/tuvan/update/{tuvan}', [TuVanController::class, 'update'])->name('tuvan.update');
    Route::delete('/admin/tuvan/destroy/{id}', [TuVanController::class, 'destroy'])->name('tuvan.destroy');
    //dasboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    //thoikhoabieu
    Route::get('/api/calendar-events', [CalendarController::class, 'getCalendarEvents'])->name('calendar.events');
    Route::get('/calender', [CalendarController::class, 'index'])->name('general.calendar');
});
Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboar');



//stafflophoc
Route::get('/staff/lophoc', [StaffLophocController::class, 'index'])->name('staff.lophoc');
Route::get('/staff/lophoc/chitiet/{id}', [StaffLophocController::class, 'show'])->name('staff.lophoc.show');
Route::get('/staff/lophoc/search', [StaffLophocController::class, 'search'])->name('staff.lophoc.search');
// Route::delete('/admin/lophoc/delete/{id}', [LopHocController::class, 'destroy'])->name('lophoc.destroy');
// Route::post('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'storeGiaoVien'])
//     ->name('lophoc.storegiaovien');
// Route::put('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'updateGiaovien'])
// ->name('lophoc.updateGiaovien');
Route::post('staff/lophoc/{id}/addhocvien', [StaffLophocController::class, 'addHocVien'])->name('staff.lophoc.addhocvien');
Route::post('/staff/lophoc/{id}/addlichhoc', [StaffLophocController::class, 'addlichoc'])->name('staff.lophoc.addlichhoc');
Route::put('/staff/lophoc/{lophoc}/update', [StaffLophocController::class, 'update'])->name('staff.lophocdetail.update');
Route::delete('/staff/lophoc/{lophoc}/remove-hocvien/{hocvien}', [StaffLophocController::class, 'removeHocVien'])->name('staff.lophoc.removeHocVien');
Route::post('/hocvien/transfer-lop', [StaffLophocController::class, 'transferLop'])->name('staff.hocvien.transferLop');
Route::delete('/lophoc/{lophoc}/thoikhoabieu/{thoikhoabieu}', [StaffLophocController::class, 'destroylichhoc'])->name('staff.lichhoc.destroy');
//staff hocvien
Route::get('staff/hocvien', [StaffHocvienController::class, 'index'])->name('staff.hocvien');
Route::get('/staff/hocvien/search', [StaffHocvienController::class, 'search'])->name('staff.hocvien.search');
Route::post('/staff/hocvien/store', [StaffHocvienController::class, 'store'])->name('staff.hocvien.store');
Route::put('staff/hocvien/update/{hocvien}', [StaffHocvienController::class, 'update'])->name('staff.hocvien.update');
Route::delete('/staff/hocvien/destroy/{id}', [StaffHocvienController::class, 'destroy'])->name('staff.hocvien.destroy');
