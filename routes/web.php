<?php

use App\Exports\RevenueByClassSheet;
use App\Http\Controllers\Admin\CoSoController;
use App\Http\Controllers\Admin\GiaoVienController;
use App\Http\Controllers\Admin\HocPhiController;
use App\Http\Controllers\Admin\HocVienController;
use App\Http\Controllers\Staff\StaffCalendarController;
use App\Http\Controllers\Users\Homecontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KhoaHocController;
use App\Http\Controllers\Admin\KynangController;
use App\Http\Controllers\Admin\LopHocController;
use App\Http\Controllers\Admin\PhongHocController;
use App\Http\Controllers\Admin\TrinhdoController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AcountController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ChucDanhController;
use App\Http\Controllers\Admin\ChuyenMonController;
use App\Http\Controllers\Admin\DongiaController;
use App\Http\Controllers\Admin\HocViController;
use App\Http\Controllers\Admin\ReportAdminCOntroller;
use App\Http\Controllers\Admin\RevenueReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaHocController;
use App\Http\Controllers\Admin\NhanVienController;
use App\Http\Controllers\Admin\ReportDiemdanhController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\Staff\Dangkycontroller;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffGiaovienController;
use App\Http\Controllers\Staff\StaffHocphiController;
use App\Http\Controllers\Staff\StaffHocvienController;
use App\Http\Controllers\Staff\StaffLophocController;
use App\Http\Controllers\Staff\StaffTuvanController;
use App\Http\Controllers\Staff\TinTucController;
use App\Http\Controllers\Student\STHocphiController;
use App\Http\Controllers\Student\STIndexCotroller;
use App\Http\Controllers\Student\STLophocController;
use App\Http\Controllers\Student\STMaterialController;
use App\Http\Controllers\Student\STTimeTableController;
use App\Http\Controllers\Student\StudentrNotificationController;
use App\Http\Controllers\Teacher\TeacherAttendanceController;
use App\Http\Controllers\Teacher\TeacherClassesController;

use App\Http\Controllers\Teacher\TeacherNotificationControlle;
use App\Http\Controllers\Teacher\TeacherTimetableController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherMaterialController;

use App\Http\Controllers\Users\AboutController;
use App\Http\Controllers\Users\AllCoursesController;
use App\Http\Controllers\Users\ClassController;
use App\Http\Controllers\Users\ContactController;
use App\Http\Controllers\Users\CoursesController;
use App\Http\Controllers\Users\NewsController;
use App\Http\Controllers\Users\TuVanController;

use App\Models\CaHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;


Route::get('/demo-calendar', function () {
    return view('test');
});

Route::get('/trangchu', [Homecontroller::class, 'index'])->name('trangchu');


//login
Route::get('/login', [AuthController::class, 'showloginform'])->name('login');
Route::post('/log/login', [AuthController::class, 'login'])->name('login.post');
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
route::get('/tintuc', [NewsController::class, 'index'])->name('user.tintuc');
Route::get('/tintuc/{id}', [NewsController::class, 'show'])->name('tintuc.show');
// Route::get('/lop-hoc/{khoaHocId}', [Homecontroller::class, 'byKhoaHoc'])->name('lop-hoc.byKhoaHoc');
Route::get('/lop-hoc/khoa-hoc/{khoaHocId}/trinh-do/{trinhDoId}', [Homecontroller::class, 'byKhoaHoc'])->name('lop-hoc.byKhoaHoc');


// route::get()



Route::get('/pagination', [PaginationController::class, 'index'])->name('pagination');

Route::middleware(['role:chutt'])->group(function () {
    //chuyemmon
    Route::get('/admin/chuyenmon', [ChuyenMonController::class, 'index'])->name('chuyenmon.index');
    Route::post('/chuyenmon/store', [ChuyenMonController::class, 'store'])->name('chuyenmon.store');
    Route::put('/chuyenmon/update/{id}', [ChuyenMonController::class, 'update'])->name('chuyenmon.update');
    Route::delete('/chuyenmon/delete/{id}', [ChuyenMonController::class, 'destroy'])->name('chuyenmon.destroy');
    //hocvi
    Route::get('/admin/hocvi', [HocViController::class, 'index'])->name('hocvi.index');
    Route::post('/hocvi/store', [HocViController::class, 'store'])->name('hocvi.store');
    Route::put('/hocvi/update/{id}', [HocViController::class, 'update'])->name('hocvi.update');
    Route::delete('/hocvi/delete/{id}', [HocViController::class, 'destroy'])->name('hocvi.destroy');
    //chucdanh
    Route::get('/admin/chucdanh', [ChucDanhController::class, 'index'])->name('chucdanh.index');
    Route::post('/chucdanh/store', [ChucDanhController::class, 'store'])->name('chucdanh.store');
    Route::put('/chucdanh/update/{id}', [ChucDanhController::class, 'update'])->name('chucdanh.update');
    Route::delete('/chucdanh/delete/{id}', [ChucDanhController::class, 'destroy'])->name('chucdanh.destroy');
    //khoahoc
    Route::get('/admin/khoahoc', [KhoaHocController::class, 'index'])->name('khoahoc.index');
    Route::post('/admin/khoahoc/store', [KhoaHocController::class, 'store'])->name('khoahoc.store');
    Route::put('/admin/khoahoc/update/{id}', [KhoaHocController::class, 'update'])->name('khoahoc.update');
    Route::delete('/admin/khoahoc/delete/{ma}', [KhoaHocController::class, 'destroy'])->name('khoahoc.destroy');
    route::get('/admin/khoahoc/create', [KhoaHocController::class, 'create'])->name('khoahoc.create');
    route::get('/admin/khoahoc/edit/{id}', [khoahoccontroller::class, 'edit'])->name('khoahoc.edit');
    route::put('/admin/khoahoc/update/{id}', [khoahoccontroller::class, 'update'])->name('khoahoc.update');
    route::get('/api/get-khoahoc-info', [khoahoccontroller::class, 'get-khoahoc-info']);
    route::post('/admin/khoahoc/autoCreate', [KhoaHocController::class, 'autoCreate'])->name('lophoc.autoCreate');
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
    Route::get('/admin/hocvien/search', [HocVienController::class, 'search'])->name('admin.hocvien.search');
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
    Route::post('/admin/lophoc/{id}/addhocvien', [LopHocController::class, 'addHocVien'])->name('lophoc.addhocvien');
    Route::post('/admin/lophoc/{id}/addlichhoc', [LopHocController::class, 'addlichoc'])->name('lophoc.addlichhoc');
    Route::put('/admin/lophoc/{lophoc}/update', [LopHocController::class, 'update'])->name('lophocdetail.update');
    Route::delete('/admin/lophoc/{lophoc}/remove-hocvien/{hocvien}', [LopHocController::class, 'removeHocVien'])->name('lophoc.removeHocVien');
    Route::post('/admin/hocvien/transfer-lop', [LopHocController::class, 'transferLop'])->name('hocvien.transferLop');
    Route::delete('/admin/lophoc/{lophoc}/thoikhoabieu/{thoikhoabieu}', [LopHocController::class, 'destroylichhoc'])->name('lichhoc.destroy');

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
    Route::put('/admin/giaovien/update/{id}', [GiaovienController::class, 'update'])->name('giaovien.update');
    Route::get('/giaovien/search', [GiaoVienController::class, 'search'])->name('giaovien.search');
    Route::post('/admin/giaovien/destroy', [GiaoVienController::class, 'destroy'])->name('giaovien.destroy');
    Route::post('/admin/check-giaovien', [LopHocController::class, 'getGiaovienBusy']);


    //hocphi
    Route::get('/admin/hocphi', [HocPhiController::class, 'index'])->name('hocphi.index');
    Route::get('/admin/tuition-management', [HocPhiController::class, 'index'])->name('tuition.index');
    Route::get('/admin/classes/{classId}/students', [HocPhiController::class, 'getStudentsByClass']);
    Route::get('/admin/hocphi/get-tuition-info/{classId}/{studentId}', [HocPhiController::class, 'getTuitionInfo']);
    Route::post('/admin/hocphi/process-payment', [HocPhiController::class, 'processPayment']);
    // Route để in biên lai
    Route::get('admin/phieuthu/print/{studentId}/{classId}', [HocPhiController::class, 'printReceipt'])->name('phieuthu.print');
    Route::post('/admin/hocphi/send-reminders', [HocPhiController::class, 'sendTuitionReminders']);
    //tu van
    Route::get('/admin/tuvan', [TuVanController::class, 'index'])->name('tuvan');
    Route::put('/admin/tuvan/update/{tuvan}', [TuVanController::class, 'update'])->name('tuvan.update');
    Route::delete('/admin/tuvan/destroy/{id}', [TuVanController::class, 'destroy'])->name('tuvan.destroy');
    //dasboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    //thoikhoabieu
    Route::get('/admin/api/calendar-events', [CalendarController::class, 'getCalendarEvents'])->name('calendar.events');
    Route::get('/admin/calender', [CalendarController::class, 'index'])->name('general.calendar');

    //\nhanvien
    Route::get('/admin/nhanvien/index', [NhanVienController::class, 'index'])->name('admin.nhanvien');
    Route::post('/admin/nhanvien/store', [NhanVienController::class, 'store'])->name('nhanvien.store');
    Route::post('/admin/nhanvien/destoy', [NhanVienController::class, 'destroy'])->name('nhanvien.destroy');
    Route::put('/admin/nhanvien/update', [NhanVienController::class, 'update'])->name('nhanvien.update');


    //report
    Route::get('/admin/report/class_student', [ReportAdminCOntroller::class, 'classStudentReport'])->name('report.class_student');
    Route::get('/admin/reports/class-student/export', [ReportAdminCOntroller::class, 'exportClassStudentReport'])->name('reports.class_student.export');


    Route::get('/admin/reports/unpaid-students', [ReportAdminCOntroller::class, 'unpaidStudentsReport'])->name('reports.unpaid-students');
    Route::get('/admin/reports/unpaid-students/export', [ReportAdminCOntroller::class, 'exportUnpaidStudentsReport'])->name('reports.unpaid_students.export');

    // NEW: Route cho báo cáo học viên đã đóng học phí
    Route::get('/admin/reports/paid-students', [ReportAdminCOntroller::class, 'paidStudentsReport'])->name('reports.paid-students');
    Route::get('/admin/reports/paid-students/export', [ReportAdminCOntroller::class, 'exportpaidStudentsReport'])->name('reports.paid_students.export');

    Route::get('/admin/reports/revenue', [RevenueReportController::class, 'index'])->name('admin.reports.revenue');
    Route::get('/admin/report/revenue/report', [RevenueReportController::class, 'exportRevenueToExcel'])->name('admin.reports.revenue.export');
    //diemdanh
    Route::get('/admin/diemdanh/report', [ReportDiemdanhController::class, 'index'])->name('diemdanh.report');
    //đongia
    route::get('/admin/dongia', [DongiaController::class, 'index'])->name('dongia.index');
    route::post('/admin/dongia/store', [DongiaController::class, 'store'])->name('dongia.store');
    Route::put('/admin/dongia/update/{id}', [DonGiaController::class, 'update'])->name('dongia.update');
    Route::delete('/admin/dongia/{id}', [DonGiaController::class, 'destroy'])->name('dongia.destroy');
    Route::get('/get-dongia/{trinhdo_id}', [LopHocController::class, 'getDonGiaTheoTrinhDo'])->name('dongia.theo_trinhdo');

    // account
    // routes/web.php
    Route::get('/admin/quan-ly-tai-khoan/hocvien', [AcountController::class, 'accountIndex'])->name('admin.hocvien.accountIndex');
    Route::post('/admin/hocvien/create-account', [AcountController::class, 'createAccount'])->name('hocvien.createAccount');
    Route::put('/admin/hocvien/{id}/lock-account', [AcountController::class, 'lockAccount'])->name('hocvien.lockAccount');
    //giaovein
    Route::get('/admin/giaovien/account', [AcountController::class, 'accountIndexGV'])->name('admin.giaovien.accountIndex');
    Route::post('/admin/giaovien/create-account', [AcountController::class, 'createAccountGV'])->name('giaovien.createAccount');
    Route::put('/admin/giaovien/{id}/lock-account', [AcountController::class, 'lockAccountGV'])->name('giaovien.lockAccount');
    //nhanvien
    Route::get('/admin/nhanvien/account', [AcountController::class, 'accountIndexNV'])->name('admin.nhanvien.accountIndex');
    Route::post('/admin/nhanvien/create-account', [AcountController::class, 'createAccountNV'])->name('nhanvien.createAccount');
    Route::put('/admin/nhanvien/{id}/lock-account', [AcountController::class, 'lockAccountNV'])->name('nhanvien.lockAccount');
});

Route::middleware(['role:nhanvien'])->group(function () {

    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    // //tuvan
    // Route::get('/staff/tuvan', [TuVanController::class, 'index'])->name('staff.tuvan');
    // Route::put('/staff/tuvan/update/{tuvan}', [TuVanController::class, 'update'])->name('staff.tuvan.update');
    // Route::delete('/staff/tuvan/destroy/{id}', [TuVanController::class, 'destroy'])->name('staff.tuvan.destroy');
    //stafflophoc
    Route::get('/staff/lophoc', [StaffLophocController::class, 'index'])->name('staff.lophoc');
    Route::get('/staff/lophoc/chitiet/{id}', [StaffLophocController::class, 'show'])->name('staff.lophoc.show');
    Route::get('/staff/lophoc/search', [StaffLophocController::class, 'search'])->name('staff.lophoc.search');
    // Route::delete('/admin/lophoc/delete/{id}', [LopHocController::class, 'destroy'])->name('lophoc.destroy');
    // Route::post('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'storeGiaoVien'])
    //     ->name('lophoc.storegiaovien');
    // Route::put('/admin/lophoc/{id}/giaovien', [LopHocController::class, 'updateGiaovien'])
    // ->name('lophoc.updateGiaovien');
    Route::post('/staff/lophoc/{id}/addhocvien', [StaffLophocController::class, 'addHocVien'])->name('staff.lophoc.addhocvien');
    Route::post('/staff/lophoc/{id}/addlichhoc', [StaffLophocController::class, 'addlichoc'])->name('staff.lophoc.addlichhoc');
    Route::put('/staff/lophoc/{lophoc}/update', [StaffLophocController::class, 'update'])->name('staff.lophocdetail.update');
    Route::delete('/staff/lophoc/{lophoc}/remove-hocvien/{hocvien}', [StaffLophocController::class, 'removeHocVien'])->name('staff.lophoc.removeHocVien');
    Route::post('/hocvien/transfer-lop', [StaffLophocController::class, 'transferLop'])->name('staff.hocvien.transferLop');
    Route::delete('/lophoc/{lophoc}/thoikhoabieu/{thoikhoabieu}', [StaffLophocController::class, 'destroylichhoc'])->name('staff.lichhoc.destroy');
    //staff hocvien
    Route::get('staff/hocvien', [StaffHocvienController::class, 'index'])->name('staff.hocvien');
    Route::get('/staff/hocvien/search', [StaffHocvienController::class, 'search'])->name('hocvien.search');
    Route::post('/staff/hocvien/store', [StaffHocvienController::class, 'store'])->name('staff.hocvien.store');
    Route::put('staff/hocvien/update/{hocvien}', [StaffHocvienController::class, 'update'])->name('staff.hocvien.update');
    Route::delete('/staff/hocvien/destroy/{id}', [StaffHocvienController::class, 'destroy'])->name('staff.hocvien.destroy');
    //staff giaovien
    ROute::get('/staff/giaovien', [StaffGiaovienController::class, 'index'])->name('staff.giaovien');
    Route::post('/staff/giaovien/store', [StaffGiaovienController::class, 'store'])->name('staff.giaovien.store');
    Route::put('/giaovien/update/{id}', [StaffGiaovienController::class, 'update'])->name('staff.giaovien.update');
    Route::get('/giaovien/search', [StaffGiaovienController::class, 'search'])->name('staff.giaovien.search');
    Route::post('/staff/giaovien/destroy', [StaffGiaovienController::class, 'destroy'])->name('staff.giaovien.destroy');
    //staff hocphi
    Route::get('/staff/hocphi', [StaffHocphiController::class, 'index'])->name('staff.hocphi');
    Route::get('/staff/tuition-management', [StaffHocphiController::class, 'index'])->name('satff.tuition.index');
    Route::get('/staff/classes/{classId}/students', [StaffHocphiController::class, 'getStudentsByClass']);
    Route::get('/staff/hocphi/get-tuition-info/{classId}/{studentId}', [StaffHocphiController::class, 'getTuitionInfo']);
    Route::post('/staff/hocphi/process-payment', [StaffHocphiController::class, 'processPayment']);
    Route::get('/staff/phieuthu/print/{studentId}/{classId}', [StaffHocphiController::class, 'printReceipt'])->name('phieuthu.print');
    Route::post('/staff/hocphi/send-reminders', [HocPhiController::class, 'sendTuitionReminders']);
    //staff tuvan
    Route::get('/staff/tuvan', [StaffTuvanController::class, 'index'])->name('staff.tuvan');
    Route::put('/staff/tuvan/update/{tuvan}', [StaffTuvanController::class, 'update'])->name('staff.tuvan.update');
    Route::delete('/staff/tuvan/destroy/{id}', [StaffTuvanController::class, 'destroy'])->name('staff.tuvan.destroy');
    ///tkb
    Route::get('/api/calendar-events', [StaffCalendarController::class, 'getCalendarEvents'])->name('staff.calendar.events');
    Route::get('/calender', [StaffCalendarController::class, 'index'])->name('staff.general.calendar');
    Route::post('/tkb/store', [StaffCalendarController::class, 'storeSchedule'])->name('staff.tkb.store');
    // Route::post('/calendar/store', [StaffCalendarController::class, 'storeSchedule']);

    // routes/web.php
    Route::get('/staff/lophoc/{lophocId}/kynang', [StaffCalendarController::class, 'getKyNangByLopHoc'])
        ->name('staff.lophoc.kynang');

    //tintuc
    route::get('/staff/tintuc', [TinTucController::class, 'index'])->name('tintuc');
    route::post('/staff/tintuc/store', [Tintuccontroller::class, 'store'])->name('staff.tintuc.store');
    route::put('/staff/tintuc/{id}', [Tintuccontroller::class, 'update'])->name('tintuc.update');
    route::delete('/staff/destroy/{id}', [TintucController::class, 'destroy'])->name('tintuc.destroy');

    // dangky
    Route::get('/staff/dangky', [Dangkycontroller::class, 'create'])->name('staff.registrations.create');
    Route::post('/registrations', [Dangkycontroller::class, 'store'])->name('staff.registrations.store');
    Route::get('/check-thoi-khoa-bieu/{lopHocId}', [Dangkycontroller::class, 'checkThoiKhoaBieu']);

    // AJAX routes
    Route::get('/registrations/search-students', [Dangkycontroller::class, 'searchStudents'])->name('staff.registrations.searchStudents');
    Route::get('/registrations/search-classes', [Dangkycontroller::class, 'searchClasses'])->name('staff.registrations.searchClasses');
    Route::get('/registrations/get-student-history', [Dangkycontroller::class, 'getStudentHistory'])->name('staff.registrations.getStudentHistory');
    Route::get('/hocphi/get-tuition-info/{classId}/{studentId}', [Dangkycontroller::class, 'getTuitionInfo'])->name('staff.hocphi.getTuitionInfo');
    Route::post('/hocphi/process-payment', [Dangkycontroller::class, 'processPayment'])->name('staff.hocphi.processPayment');
});


Route::middleware(['role:hocvien'])->group(function () {

    Route::get('/student/dashboard', [STIndexCotroller::class, 'index'])->name('student.dashboard');
    Route::get('/student/classes', [STLophocController::class, 'index'])->name('student.classes');
    Route::get('/student/classes/{lophoc}', [STLophocController::class, 'show'])->name('student.lophoc.show');
    // Route cho trang thời khóa biểu đầy đủ của học viên
    Route::get('/student/timetable', [STTimeTableController::class, 'index'])->name('student.timetable');

    // Route API để FullCalendar lấy dữ liệu sự kiện lịch của học viên
    Route::get('/api/student/calendar-events', [STTimeTableController::class, 'getCalendarEvents'])->name('student.timetable.events');

    Route::get('/student/profile', [STIndexCotroller::class, 'profile'])->name('student.profile');
    Route::get('/student/profile/edit', [STIndexCotroller::class, 'editprofile'])->name('student.profile.edit');
    route::put('/student/profile/update', [STIndexCotroller::class, 'updateprofile'])->name('student.profile.update');
    Route::get('/student/payments', [STHocphiController::class, 'index'])->name('student.payments.index');
    // Route để xử lý thanh toán (sử dụng POST request)
    Route::post('/student/payments/process/{lophoc}', [STHocphiController::class, 'processPayment'])->name('student.payments.process');
    Route::get('/student/notifications', [StudentrNotificationController::class, 'index'])->name('student.notifications.index');
    Route::get('/student/materials', [STMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/download/{material}', [STMaterialController::class, 'download'])->name('student.materials.download');
});

Route::middleware(['role:giaovien'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/teacher/classes', [TeacherClassesController::class, 'index'])->name('teacher.classes.index');
    // Route cho trang chi tiết lớp học của giáo viên (nếu bạn muốn có)
    Route::get('/teacher/classes/{lophoc}', [TeacherClassesController::class, 'show'])->name('teacher.classes.show');

    Route::get('/teacher/timetable', [TeacherTimetableController::class, 'index'])->name('teacher.timetable.index');

    // Route API để FullCalendar lấy dữ liệu sự kiện lịch của giáo viên
    Route::get('/api/teacher/calendar-events', [TeacherTimetableController::class, 'getCalendarEvents'])->name('teacher.timetable.events');
    Route::get('/teacher/profile', [TeacherDashboardController::class, 'profile'])->name('teacher.profile');
    Route::get('/teacher/profile/edit', [TeacherDashboardController::class, 'editprofile'])->name('teacher.profile.edit');
    route::put('/teacher/profile/update', [TeacherDashboardController::class, 'updateprofile'])->name('teacher.profile.update');

    Route::get('/teacher/attendance', [TeacherAttendanceController::class, 'index'])->name('teacher.attendance.index');
    Route::get('/teacher/attendance/{lophoc}/{thoikhoabieu}/create', [TeacherAttendanceController::class, 'create'])->name('teacher.attendance.create');
    Route::post('/teacher/attendance/{lophoc}/{thoikhoabieu}/store', [TeacherAttendanceController::class, 'store'])->name('teacher.attendance.store');
    Route::get('/teacher/notifications/create', [TeacherNotificationControlle::class, 'create'])->name('teacher.notifications.create');
    Route::post('/teacher/notifications', [TeacherNotificationControlle::class, 'store'])->name('teacher.notifications.store');
    Route::get('/teacher/notifications', [TeacherNotificationControlle::class, 'index'])->name('teacher.notifications.index'); // Route mới
    Route::get('/teacher/attendance/{lophoc}/{thoikhoabieu}/{ngayDiemDanhString}/report', [TeacherAttendanceController::class, 'showReport'])->name('teacher.attendance.report');
    Route::get('/teacher/materials', [TeacherMaterialController::class, 'index'])->name('teacher.materials.index');
    Route::post('/teacher/materials', [TeacherMaterialController::class, 'store'])->name('teacher.materials.store');
    Route::delete('/teacher/materials/{material}', [TeacherMaterialController::class, 'destroy'])->name('teacher.materials.destroy');
});
