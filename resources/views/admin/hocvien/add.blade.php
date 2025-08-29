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
                 <input type="text" class="form-control" id="ten_hoc_vien" name="ten" required placeholder="Nhập tên học viên">
             </div>
             <div class="form-group mb-3">
                 <label for="email_user">Email:</label>
                 <input type="email" class="form-control" id="email_user" name="email_hv" required placeholder="Nhập email học viên">
                 <div id="email_error" class="text-danger mt-1"></div>
             </div>
             <div class="form-group mb-3">
                 <label for="sdt_hoc_vien">Số điện thoại:</label>
                 <input type="text" class="form-control" id="sdt_hoc_vien" name="sdt" required maxlength="10" placeholder="Nhập số điện thoại học viên">
                 <div id="sdt_error" class="text-danger mt-1"></div>
             </div>
             <div class="form-group mb-3">
                 <label for="diachi_hoc_vien">Địa chỉ:</label>
                 <input type="text" class="form-control" id="diachi_hoc_vien" name="diachi" required placeholder="Nhập địa chỉ">
             </div>
             <div class="form-group mb-3">
                 <label for="ngaysinh_hoc_vien">Ngày sinh:</label>
                 <input type="date" class="form-control" id="ngaysinh_hoc_vien" name="ngaysinh">
             </div>
             <div class="form-group mb-3">
                 <label>Giới tính:</label>
                 <div>
                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="gioitinh" id="gioitinh_nam" value="Nam" {{ old('gioitinh') == 'Nam' ? 'checked' : '' }}>
                         <label class="form-check-label" for="gioitinh_nam">Nam</label>
                     </div>
                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="gioitinh" id="gioitinh_nu" value="Nữ" {{ old('gioitinh') == 'Nữ' ? 'checked' : '' }}>
                         <label class="form-check-label" for="gioitinh_nu">Nữ</label>
                     </div>
                     <!-- <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="gioitinh" id="gioitinh_khac" value="Khác" {{ old('gioitinh') == 'Khác' ? 'checked' : '' }}>
                         <label class="form-check-label" for="gioitinh_khac">Khác</label>
                     </div> -->
                 </div>
                 @error('gioitinh')
                 <div class="text-danger mt-1">{{ $message }}</div>
                 @enderror
             </div>

             <div class="form-group mb-3" style="display: none;">
                 <label for="ngaydangki_hoc_vien">Ngày đăng kí:</label>
                 <input type="date" class="form-control" id="ngaydangki_hoc_vien" name="ngaydangki">
             </div>
             <div class="form-group mb-3">
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
             </div>
             <div class="form-group mb-3" style="display: none;">
                 <label for="trangthai_hoc_vien">Trạng thái:</label>
                 <select class="form-control" id="trangthai_hoc_vien" name="trangthai" required>
                     <option value="Đang học">Đang học</option>
                     <option value="Đã tốt nghiệp">Đã tốt nghiệp</option>
                     <option value="Bảo lưu">Bảo lưu</option>

                 </select>
             </div>
             <button type="submit" class="btn btn-primary btn-save-hocvien" style="margin-top: 30px;">Lưu Học Viên</button>
         </form>
     </div>
 </div>
 <script>
     const sdtInput = document.getElementById('sdt_hoc_vien');
     sdtInput.addEventListener('input', function() {
         // Xóa ký tự không phải số
         this.value = this.value.replace(/\D/g, '').slice(0, 11);
     });
     document.addEventListener('DOMContentLoaded', function() {
         const lopHocSelect = document.getElementById('lophoc_id');

         lopHocSelect.addEventListener('change', function() {
             const selectedOption = this.options[this.selectedIndex];
             const soToiDa = parseInt(selectedOption.dataset.toida || 0);
             const soHienTai = parseInt(selectedOption.dataset.hientai || 0);

             if (soHienTai >= soToiDa) {
                 alert(`Lớp " ${selectedOption.text}" đã đầy! Vui lòng chọn lớp khác.`);
                 this.value = ''; // Reset chọn lớp
             }
         });
     });



     const existingEmails = @json($dsEmail);
     const existingPhones = @json($dsSDT);

     const emailInput = document.getElementById('email_user');
     const phoneInput = document.getElementById('sdt_hoc_vien');
     const emailError = document.getElementById('email_error');
     const phoneError = document.getElementById('sdt_error');
     const form = document.querySelector('form');

     function checkEmail() {
         const val = emailInput.value.trim().toLowerCase();
         if (existingEmails.includes(val)) {
             emailError.textContent = 'Email này đã tồn tại.';
             return false;
         } else {
             emailError.textContent = '';
             return true;
         }
     }

     function checkPhone() {
         const val = phoneInput.value.trim();
         if (existingPhones.includes(val)) {
             phoneError.textContent = 'Số điện thoại này đã tồn tại.';
             return false;
         } else {
             phoneError.textContent = '';
             return true;
         }
     }

     emailInput.addEventListener('blur', checkEmail);
     phoneInput.addEventListener('blur', checkPhone);

     form.addEventListener('submit', function(e) {
         const isEmailValid = checkEmail();
         const isPhoneValid = checkPhone();

         if (!isEmailValid || !isPhoneValid) {
             e.preventDefault(); // 🚫 NGĂN SUBMIT
         }
     });
 </script>