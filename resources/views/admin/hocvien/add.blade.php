 <div class="popup-overlay" id="addHocVienPopup">
     <div class="sidebar-popup">
         <div class="popup-header">
             <h4>Thêm Học Viên Mới</h4>
             <button type="button" class="popup-close">&times;</button>
         </div>
         <form action="{{ route('hocvien.store') }}" method="POST">
             @csrf
             <div class="form-group mb-3">
                 <label for="ma_hoc_vien">Mã học viên:</label>
                 <input type="text" name="mahocvien" class="form-control" id="ma_hoc_vien" value="{{ $newMa }}" disabled>
                 <input type="hidden" name="mahocvien" value="{{ $newMa }}">
             </div>
             <div class="form-group mb-3">
                 <label for="ten_hoc_vien">Tên học viên:</label>
                 <input type="text" class="form-control" id="ten_hoc_vien" name="ten" required>
             </div>
             <div class="form-group mb-3">
                 <label for="email_user">Email (liên kết tài khoản người dùng):</label>
                 <input type="email" class="form-control" id="email_user" name="email" required>
             </div>
             <div class="form-group mb-3">
                 <label for="sdt_hoc_vien">Số điện thoại:</label>
                 <input type="text" class="form-control" id="sdt_hoc_vien" name="sdt" required>
             </div>
             <div class="form-group mb-3">
                 <label for="diachi_hoc_vien">Địa chỉ:</label>
                 <input type="text" class="form-control" id="diachi_hoc_vien" name="diachi" required>
             </div>
             <div class="form-group mb-3">
                 <label for="ngaysinh_hoc_vien">Ngày sinh:</label>
                 <input type="date" class="form-control" id="ngaysinh_hoc_vien" name="ngaysinh" required>
             </div>
             <div class="form-group mb-3">
                 <label for="gioitinh_hoc_vien">Giới tính:</label>
                 <select class="form-control" id="gioitinh_hoc_vien" name="gioitinh" required>
                     <option value="">Chọn giới tính</option>
                     <option value="Nam">Nam</option>
                     <option value="Nữ">Nữ</option>
                     <option value="Khác">Khác</option>
                 </select>
             </div>
             <div class="form-group mb-3">
                 <label for="ngaydangki_hoc_vien">Ngày đăng kí:</label>
                 <input type="date" class="form-control" id="ngaydangki_hoc_vien" name="ngaydangki" required>
             </div>
             <div class="form-group mb-3">
                 <label for="trangthai_hoc_vien">Trạng thái:</label>
                 <select class="form-control" id="trangthai_hoc_vien" name="trangthai" required>
                     <option value="Đang học">Đang học</option>
                     <option value="Đã tốt nghiệp">Đã tốt nghiệp</option>
                     <option value="Bảo lưu">Bảo lưu</option>

                 </select>
             </div>
             <button type="submit" class="btn btn-primary btn-save-hocvien">Lưu Học Viên</button>
         </form>
     </div>
 </div>