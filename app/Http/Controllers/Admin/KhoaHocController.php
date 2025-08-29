<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonGia;
use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\TrinhDo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;



class KhoaHocController extends Controller
{
    public function autoCreate(Request $request)
    {
        $request->validate([
            'khoahoc_id' => 'required|exists:khoahoc,id',
            'trinhdo_id' => 'required|exists:trinhdo,id',
            'so_lop' => 'required|integer|min:1|max:20',
        ]);

        $khoahoc = KhoaHoc::with('lopHocs')->findOrFail($request->khoahoc_id);
        $trinhDo = TrinhDo::findOrFail($request->trinhdo_id);
        for ($i = 1; $i <= $request->so_lop; $i++) {

            // Đếm tổng số lớp đã có của KH này (hoặc tìm lớn nhất)
            $lastLop = LopHoc::where('khoahoc_id', $khoahoc->id)
                ->where('malophoc', 'like', $khoahoc->ma . 'A%')
                ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(malophoc, '-', 1), 'A', -1) AS UNSIGNED) DESC")
                ->first();

            $nextNumber = 1;

            if ($lastLop && preg_match('/A(\d+)/', $lastLop->malophoc, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }

            $maLop = $khoahoc->ma . 'A' . $nextNumber . '-' . $khoahoc->ten;
            $tenLop = 'Lớp' . ' ' .  $trinhDo->ten . ' - A' . $nextNumber;


            // $maLop = $khoahoc->ma . 'A' . $lSttFormatted . '-' . preg_replace('/\s+/', '', $khoahoc->ten);
            LopHoc::create([
                'malophoc' => $maLop,
                'tenlophoc' => $tenLop,
                'khoahoc_id' => $khoahoc->id,
                'trinhdo_id' => $request->trinhdo_id,
                'namhoc_id' => $khoahoc->namhoc_id,
                'ngaybatdau' => $khoahoc->ngaybatdau,
                'ngayketthuc' => $khoahoc->ngayketthuc,
                'soluonghocvientoida' => $request->soluonghocvientoida,
                // Các field khác...
            ]);
        }

        return redirect()->route('lophoc.index')->with('success', 'Tạo thành công ' . $request->so_lop . ' lớp mới!');
    }

    public function edit($id)
    {
        // Load khoá học + các quan hệ cần thiết
        $khoahoc = KhoaHoc::with([
            'lopHocs.trinhDo.dongias',
            'namHoc'
        ])->findOrFail($id);

        $nams = NamHoc::orderBy('nam', 'desc')->get();
        $trinhDos = TrinhDo::orderBy('ten')->get();

        // Tìm lớp đầu tiên (hoặc theo logic riêng)
        $lopHoc = $khoahoc->lopHocs->first();
        $dongia = null;

        if ($lopHoc && $lopHoc->trinhDo && $khoahoc->namhoc_id) {
            $dongia = $lopHoc->trinhDo->dongias
                ->where('namhoc_id', $khoahoc->namhoc_id)
                ->first();
        }

        return view('admin.khoahoc.edit', compact(
            'khoahoc',
            'nams',
            'trinhDos',
            'dongia' // <<<< đẩy ra view!
        ));
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'kh_ten'     => 'required|string|max:255',
        //     'nam'        => 'required|exists:namhoc,id',
        //     'ma_td'      => 'required|exists:trinhdo,id',
        //     'kh_ngaykg'  => 'required|date',
        //     'kh_ngaykt'  => 'required|date|after_or_equal:kh_ngaykg',
        //     'sobuoi'     => 'required|integer|min:1',
        //     'dg_hocphi'  => 'required|numeric',
        //     // 'soluonghv_toida' => 'required|integer|min:1',
        // ]);

        $khoahoc = KhoaHoc::findOrFail($id);
        $khoahoc->ten         = $request->kh_ten;
        $khoahoc->namhoc_id   = $request->nam;
        $khoahoc->ngaybatdau  = $request->ngaybatdau;
        $khoahoc->ngayketthuc = $request->ngayketthuc;
        $khoahoc->thoiluong   = $request->thoiluong;
        $khoahoc->sobuoi      = $request->sobuoi;
        $khoahoc->mota        = $request->mota;

        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $path = $file->store('khoahoc', 'public');
            $khoahoc->hinhanh = $path;
        }

        $khoahoc->save();

        // Xử lý lớp học: giả sử mỗi khoá 1 lớp duy nhất
        $lop = $khoahoc->lopHocs()->first();
        if ($lop) {
            // Update trình độ
            // $lop->trinhdo_id = $request->ma_td;

            // // Update số lượng học viên tối đa
            // $lop->soluonghocvien_toida = $request->soluonghv_toida;

            // // Ví dụ update số lượng hiện tại bằng 0 (hoặc tuỳ logic bạn)
            // if (is_null($lop->soluonghocvien_hientai)) {
            //     $lop->soluonghocvien_hientai = 0; // Hoặc tính lại số HV thực tế
            // }

            $lop->save();

            // Cập nhật đơn giá đúng với năm học
            $dongia = $lop->trinhDo->dongias()
                ->where('namhoc_id', $khoahoc->namhoc_id)
                ->first();

            if ($dongia) {
                $dongia->hocphi = $request->dg_hocphi;
                $dongia->save();
            } else {
                // Nếu chưa có thì tạo mới đơn giá
                $lop->trinhDo->dongias()->create([
                    'namhoc_id' => $khoahoc->namhoc_id,
                    'hocphi'    => $request->dg_hocphi,
                ]);
            }
        }

        return redirect()->route('khoahoc.index')
            ->with('success', 'Cập nhật khóa học thành công!');
    }

    public function create()
    {
        $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $trinhDos = TrinhDo::all();
        $nams = NamHoc::all();
        return view('admin.khoahoc.create', compact('trinhDos', 'nams', 'newMa'));
    }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        // Luôn eager load đầy đủ quan hệ
        $dsKhoaHoc = KhoaHoc::with([
            'lopHocs.trinhDo.dongias',
            'namHoc'
        ])->orderBy('ma', 'desc')->paginate($perPage)->appends($request->all());

        foreach ($dsKhoaHoc as $kh) {
            $lop = $kh->lopHocs->first(); // Hoặc quy định lấy lớp nào
            if ($lop && $lop->trinhDo && $kh->namHoc) {
                $dongia = $lop->trinhDo->dongias
                    ->where('namhoc_id', $kh->namhoc_id)
                    ->first();

                $kh->hocphi = $dongia ? $dongia->hocphi : null;
            } else {
                $kh->hocphi = null;
            }
        }


        // Xử lý tạo mã mới
        $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa =  str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        return view('admin.khoahoc.index', compact('dsKhoaHoc', 'newMa'));
    }


    public  function search(Request $request)
    {
        $tuKhoa = $request->input('tu_khoa');

        $dsKhoaHoc = KhoaHoc::where('ten', 'like', '%' . $tuKhoa . '%')
            ->orWhere('ma', 'like', '%' . $tuKhoa . '%')
            ->get();

        return view('admin.khoahoc.search_results', compact('dsKhoaHoc'));
    }

    // public function store(Request $request)
    // {
    //     // dd($request);
    //     // Phần tạo mã 'ma' không thay đổi
    //     $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();

    //     if ($lastCourse) {
    //         $lastNumber = (int) substr($lastCourse->ma, 2);
    //         $newNumber = $lastNumber + 1;
    //     } else {
    //         $newNumber = 1;
    //     }

    //     $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    //     // $request->validate([
    //     //     'ten' => 'required|string|max:255',
    //     //     'mota' => 'nullable|string',
    //     //     'thoiluong' => 'required|string|max:50',
    //     //     'sobuoi' => 'required|integer|min:1',
    //     //     // Validation cho ảnh vẫn giữ nguyên
    //     //     'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     // ]);

    //     $data = [
    //         'ma' => $newMa,
    //         'ten' => $request->ten,
    //         'mota' => $request->mota,
    //         'thoiluong' => $request->thoiluong,
    //         'sobuoi' => $request->sobuoi,
    //     ];
    //     $hinhAnhPath = null;
    //     if ($request->hasFile('hinhanh')) {
    //         $hinhAnhPath = $request->file('hinhanh')->store('khoahoc_images', 'public');
    //     }
    //     $data['hinhanh'] = $hinhAnhPath;
    //     try {
    //         KhoaHoc::create($data);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Lỗi khi lưu dữ liệu khóa học: ' . $e->getMessage());
    //     }

    //     return redirect()->route('khoahoc.index')->with('success', 'Thêm khóa học thành công!');
    // }



    // public function store(Request $request)
    // {
    //     // 1. Validate dữ liệu
    //     $request->validate([
    //         'kh_stt' => 'required|string|max:10|unique:khoahoc,ma',
    //         'kh_ten' => 'required|string|max:255',
    //         'mota' => 'nullable|string',
    //         'kh_ngaykg' => 'required|date',
    //         'kh_ngaykt' => 'nullable|date|after_or_equal:kh_ngaykg',
    //         'ma_td' => 'required|exists:trinhdo,id',
    //         'nam' => 'required|exists:namhoc,id',
    //         'dg_hocphi' => 'required|numeric|min:0',
    //         'thoiluong' => 'nullable|string|max:50',
    //         'sobuoi' => 'nullable|integer|min:1',
    //         'hinhanh' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //         'number_of_classes' => 'required|integer|min:1|max:99',
    //         'l_slmax' => 'required|integer|min:1|max:100',

    //     ], [
    //         'kh_stt.unique' => 'Mã khóa học đã tồn tại.',
    //         'kh_ngaykt.after_or_equal' => 'Ngày kết thúc khóa học không được nhỏ hơn này khai giảng.',
    //         'ma_td.exists' => 'Mã trình độ không hợp lệ.',
    //         'nam.exists' => 'Năm không hợp lệ.',
    //         'number_of_classes.max' => 'Số lượng lớp tối đa là 99.',
    //     ]);

    //     DB::transaction(function () use ($request) {

    //         // 2. Upload ảnh nếu có
    //         $hinhAnhPath = null;
    //         if ($request->hasFile('hinhanh')) {
    //             $hinhAnhPath = $request->file('hinhanh')->store('khoahoc_images', 'public');
    //         }

    //         // 3. Tạo hoặc cập nhật đơn giá
    //         DonGia::updateOrCreate(
    //             [
    //                 'trinhdo_id' => $request->ma_td,
    //                 'namhoc_id' => $request->nam,
    //             ],
    //             [
    //                 'hocphi' => $request->dg_hocphi,
    //             ]
    //         );
    //         $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();

    //         if ($lastCourse) {
    //             $lastNumber = (int) substr($lastCourse->ma, 2);
    //             $newNumber = $lastNumber + 1;
    //         } else {
    //             $newNumber = 1;
    //         }

    //         $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    //         // 4. Tạo Khóa học
    //         $khoaHoc = KhoaHoc::create([
    //             'ma' => $newMa,
    //             'ten' => $request->kh_ten,
    //             'namhoc_id' => $request->nam,
    //             'mota' => $request->mota,
    //             'ngaybatdau' => $request->kh_ngaykg,
    //             'ngayketthuc' => $request->kh_ngaykt,
    //             'thoiluong' => $request->thoiluong,
    //             'sobuoi' => $request->sobuoi,
    //             'hinhanh' => $hinhAnhPath,
    //             'solop' => $request->number_of_classes,
    //             // 'trinhdo_id' => $request->ma_td,

    //         ]);
    //         $trinhDo = TrinhDo::find($request->ma_td);
    //         // 5. Tạo các lớp học
    //         for ($i = 1; $i <= $request->number_of_classes; $i++) {
    //             $lSttFormatted = str_pad($i, 1,  STR_PAD_LEFT);
    //             $tenLop = 'Lớp' . ' ' .  $trinhDo->ten . ' - A' . $lSttFormatted;
    //             LopHoc::create([
    //                 'khoahoc_id' => $khoaHoc->id,
    //                 'malophoc' => $khoaHoc->ma . 'A' . $lSttFormatted . '-' .  $khoaHoc->ten,
    //                 'tenlophoc' => $tenLop,
    //                 'trinhdo_id' => $request->ma_td,
    //                 'ngaybatdau' => $request->kh_ngaykg,
    //                 'ngayketthuc' => $request->kh_ngaykt,
    //                 'soluonghocvientoida' => $request->l_slmax,
    //                 'trangthai' => 'sap_khai_giang'
    //             ]);
    //         }
    //     });

    //     return redirect()->route('khoahoc.index')->with('success', 'Khóa học và các lớp học đã được tạo thành công!');
    // }


    // public function update(Request $request, $ma)
    // {
    //     $khoahoc = KhoaHoc::where('ma', $ma)->firstOrFail();

    //     $khoahoc->ten = $request->input('ten_sua');
    //     $khoahoc->mota = $request->input('mota_sua');
    //     $khoahoc->thoiluong = $request->input('thoiluong_sua');
    //     $khoahoc->sobuoi = $request->input('sobuoi_sua');
    //     $khoahoc->save();

    //     return redirect()->route('khoahoc.index')->with('success', 'Cập nhật khóa học thành công!');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'kh_stt' => 'required|string|max:10|unique:khoahoc,ma',
    //         'kh_ten' => 'required|string|max:255',
    //         'mota' => 'nullable|string',
    //         'kh_ngaykg' => 'required|date',
    //         'kh_ngaykt' => 'nullable|date|after_or_equal:kh_ngaykg',
    //         'ma_td' => 'required|exists:trinhdo,id',
    //         'nam' => 'required|exists:namhoc,id',
    //         'dg_hocphi' => 'required|numeric|min:0',
    //         'thoiluong' => 'nullable|string|max:50',
    //         'sobuoi' => 'nullable|integer|min:1',
    //         'hinhanh' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //         'number_of_classes' => 'required|integer|min:1|max:99',
    //         'l_slmax' => 'required|integer|min:1|max:100',
    //     ], [
    //         'kh_stt.unique' => 'Mã khóa học đã tồn tại.',
    //         'kh_ngaykt.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
    //         'ma_td.exists' => 'Mã trình độ không hợp lệ.',
    //         'nam.exists' => 'Năm không hợp lệ.',
    //         'number_of_classes.max' => 'Số lượng lớp tối đa là 99.',
    //     ]);

    //     // ✅ Kiểm tra trùng ngày và trình độ
    //     $trungNgayVaTrinhDo = LopHoc::whereHas('khoaHoc', function ($query) use ($request) {
    //         $query->where('ngaybatdau', $request->kh_ngaykg);
    //     })
    //         ->where('trinhdo_id', $request->ma_td)
    //         ->exists();

    //     if ($trungNgayVaTrinhDo) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->withErrors(['kh_ngaykg' => 'Đã có lớp học thuộc trình độ này với ngày bắt đầu này. Vui lòng chọn ngày khác.']);
    //     }


    //     DB::transaction(function () use ($request) {

    //         // 2. Upload ảnh nếu có
    //         $hinhAnhPath = null;
    //         if ($request->hasFile('hinhanh')) {
    //             $hinhAnhPath = $request->file('hinhanh')->store('khoahoc_images', 'public');
    //         }

    //         // 3. Tạo hoặc cập nhật đơn giá
    //         DonGia::updateOrCreate(
    //             [
    //                 'trinhdo_id' => $request->ma_td,
    //                 'namhoc_id' => $request->nam,
    //             ],
    //             [
    //                 'hocphi' => $request->dg_hocphi,
    //             ]
    //         );
    //         $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();

    //         if ($lastCourse) {
    //             $lastNumber = (int) substr($lastCourse->ma, 2);
    //             $newNumber = $lastNumber + 1;
    //         } else {
    //             $newNumber = 1;
    //         }

    //         $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    //         // 4. Tạo Khóa học
    //         $khoaHoc = KhoaHoc::create([
    //             'ma' => $newMa,
    //             'ten' => $request->kh_ten,
    //             'namhoc_id' => $request->nam,
    //             'mota' => $request->mota,
    //             'ngaybatdau' => $request->kh_ngaykg,
    //             'ngayketthuc' => $request->kh_ngaykt,
    //             'thoiluong' => $request->thoiluong,
    //             'sobuoi' => $request->sobuoi,
    //             'hinhanh' => $hinhAnhPath,
    //             'solop' => $request->number_of_classes,
    //             // 'trinhdo_id' => $request->ma_td,

    //         ]);
    //         $trinhDo = TrinhDo::find($request->ma_td);
    //         // 5. Tạo các lớp học
    //         for ($i = 1; $i <= $request->number_of_classes; $i++) {
    //             $lSttFormatted = str_pad($i, 1,  STR_PAD_LEFT);
    //             $tenLop = 'Lớp' . ' ' .  $trinhDo->ten . ' - A' . $lSttFormatted;
    //             LopHoc::create([
    //                 'khoahoc_id' => $khoaHoc->id,
    //                 'malophoc' => $khoaHoc->ma . 'A' . $lSttFormatted . '-' .  $khoaHoc->ten,
    //                 'tenlophoc' => $tenLop,
    //                 'trinhdo_id' => $request->ma_td,
    //                 'ngaybatdau' => $request->kh_ngaykg,
    //                 'ngayketthuc' => $request->kh_ngaykt,
    //                 'soluonghocvientoida' => $request->l_slmax,
    //                 'trangthai' => 'sap_khai_giang'
    //             ]);
    //         }
    //     });

    //     return redirect()->route('khoahoc.index')
    //         ->with('success', 'Khóa học và các lớp học đã được tạo thành công!');
    // }


    public function store(Request $request)
    {
        $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa =  str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $request->validate([
            // 'ma' => 'required|string|max:50|unique:khoahoc,ma',
            'ngaybatdau' => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
        ], [
            'ngayketthuc.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
        ]);


        $khoahoc = new KhoaHoc();
        $khoahoc->ma = $newMa;
        $khoahoc->ngaybatdau = $request->ngaybatdau;
        $khoahoc->ngayketthuc = $request->ngayketthuc;
        $khoahoc->save();

        return redirect()->route('khoahoc.index')->with('success', 'Thêm khóa học thành công!');
    }

    public function destroy($ma)
    {
        $khoahoc = KhoaHoc::findOrFail($ma);
        $khoahoc->delete();
        return  redirect()->route('khoahoc.index')->with('success', 'Xóa khóa học thành công! ');
    }
}
