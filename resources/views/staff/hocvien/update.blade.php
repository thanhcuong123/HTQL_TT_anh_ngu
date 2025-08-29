 <div class="popup-overlay" id="editHocVienPopup">
     <div class="sidebar-popup">
         <div class="popup-header">
             <h4>Chỉnh Sửa Học Viên</h4>
             <button type="button" class="popup-close" data-target="editHocVienPopup">&times;</button>
         </div>
         <form id="editHocVienForm" method="POST">
             @csrf
             @method('PUT') {{-- Sử dụng phương thức PUT cho cập nhật --}}
             <input type="hidden" id="edit_hoc_vien_id" name="id"> {{-- Để lưu ID học viên cần chỉnh sửa --}}
             <div class="form-group mb-3">
                 <label for="edit_ma_hoc_vien">Mã học viên:</label>
                 <input type="text" class="form-control" id="edit_ma_hoc_vien" name="mahocvien" disabled>
                 <input type="hidden" name="ma_sua" id="ma_sua">


             </div>
             <div class="form-group mb-3">
                 <label for="edit_ten_hoc_vien">Tên học viên:</label>
                 <input type="text" class="form-control" id="edit_ten_hoc_vien" name="ten" reqired>
             </div>
             <div class="form-group mb-3">
                 <label for="edit_email_user">Email :</label>
                 <input type="email" class="form-control" id="edit_email_user" name="email">
             </div>
             <div class="form-group mb-3">
                 <label for="edit_sdt_hoc_vien">Số điện thoại:</label>
                 <input type="text" class="form-control" id="edit_sdt_hoc_vien" name="sdt">
             </div>
             <div class="form-group mb-3">
                 <label for="edit_diachi_hoc_vien">Địa chỉ:</label>
                 <input type="text" class="form-control" id="edit_diachi_hoc_vien" name="diachi">
             </div>
             <div class="form-group mb-3">
                 <label for="edit_ngaysinh_hoc_vien">Ngày sinh:</label>
                 <input type="date" class="form-control" id="edit_ngaysinh_hoc_vien" name="ngaysinh" reqired>
             </div>
             <div class="form-group mb-3">
                 <label for="edit_gioitinh_hoc_vien">Giới tính:</label>
                 <select class="form-control" id="edit_gioitinh_hoc_vien" name="gioitinh">
                     <option value="">Chọn giới tính</option>
                     <option value="Nam">Nam</option>
                     <option value="Nữ">Nữ</option>
                     <option value="Khác">Khác</option>
                 </select>
             </div>
             <!-- <div class="form-group mb-3">
                 <label for="lophoc_id">Lớp học đăng kí:</label>
                 <select class="form-control @error('lophoc_id') is-invalid @enderror" id="lophoc_id" name="lophoc_id">
                     <option value="">-- Chọn Lớp Học --</option>
                     @foreach ($allLopHoc as $lh)
                     @php
                     $soToiDa = $lh->soluonghocvientoida;
                     $soHienTai = $lh->soluonghocvienhientai;
                     $soConLai = $soToiDa - $soHienTai;
                     @endphp
                     <option

                         value="{{ $lh->id }}"
                         data-toida="{{ $soToiDa }}"
                         data-hientai="{{ $soHienTai }}"
                         {{ old('lophoc_id') == $lh->id ? 'selected' : '' }}>

                         {{ $lh->tenlophoc }} (Còn {{ $soConLai }} chỗ)
                     </option>
                     @endforeach
                 </select>
                 @error('lophoc_id')
                 <div class="text-danger mt-1">{{ $message }}</div>
                 @enderror
             </div> -->


             <div class="form-group mb-3">
                 <label for="edit_trangthai_hoc_vien">Trạng thái:</label>
                 <select class="form-control" id="edit_trangthai_hoc_vien" name="trangthai" required>
                     <option value="Đang học">Đang học</option>
                     <option value="Đã tốt nghiệp">Đã tốt nghiệp</option>
                     <option value="Bảo lưu">Bảo lưu</option>
                 </select>
             </div>
             <button type="submit" class="btn btn-primary btn-save-hocvien">Cập nhật Học Viên</button>
         </form>
     </div>
 </div>