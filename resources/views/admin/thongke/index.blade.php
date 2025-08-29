@extends('index')

@section('title-content')
<title>Dashboard Thống Kê</title>
@endsection

@section('main-content')

{{-- Import Font Awesome cho icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- Chỉ cần nhúng Chart.js MỘT LẦN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- 
<style>
    /* CSS của bạn giữ nguyên để đảm bảo giao diện */
    .statistic-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease-in-out;
    }

    #monthlyRevenueChart {
        height: 300px;
        /* Set a fixed height for the canvas */
        width: 100% !important;
        /* height: 100%; */
        /* Ensure it takes full width of its container */
    }

    .statistic-card:hover {
        transform: translateY(-5px);
    }

    .statistic-card .icon {
        font-size: 3em;
        color: #007bff;
    }

    .statistic-card .content {
        text-align: right;
    }

    .statistic-card .content h4 {
        margin-bottom: 5px;
        color: #555;
    }

    .statistic-card .content p {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .statistic-card.bg-primary .icon {
        color: #fff;
    }

    .statistic-card.bg-success .icon {
        color: #fff;
    }

    .statistic-card.bg-warning .icon {
        color: #fff;
    }

    .statistic-card.bg-danger .icon {
        color: #fff;
    }

    .statistic-card.bg-primary {
        background-color: #007bff !important;
        color: #fff;
    }

    .statistic-card.bg-success {
        background-color: #28a745 !important;
        color: #fff;
    }

    .statistic-card.bg-info {
        background-color: #17a2b8 !important;
        color: #fff;
    }

    .statistic-card.bg-warning {
        background-color: #ffc107 !important;
        color: #212529;
    }

    .statistic-card.bg-danger {
        background-color: #dc3545 !important;
        color: #fff;
    }

    .statistic-card.bg-warning .content h4,
    .statistic-card.bg-warning .content p {
        color: #212529;
    }

    .chart-placeholder {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2em;
        color: #6c757d;
        text-align: center;
    }

    .section-title {
        margin-top: 30px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .time-filter-controls {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: center;
        flex-wrap: wrap;
    }

    .time-filter-controls select,
    .time-filter-controls input[type="number"] {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
    }

    .time-filter-controls button {
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
    }

    .time-filter-controls #month-selection,
    .time-filter-controls #quarter-selection,
    .time-filter-controls #year-selection {
        display: flex;
        gap: 10px;
        min-width: 250px;
        /* flex-wrap: wrap; */
    }

    .time-filter-controls select,
    .time-filter-controls input[type="number"] {
        /* flex: 1 1 auto; */
        /* thêm dòng này */
        min-width: 120px;
        /* thêm dòng này */
    }
</style> -->

<style>
    /* CSS của bạn giữ nguyên để đảm bảo giao diện */
    .statistic-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease-in-out;
    }

    #monthlyRevenueChart {
        height: 300px;
        /* Set a fixed height for the canvas */
        width: 100% !important;
        /* height: 100%; */
        /* Ensure it takes full width of its container */
    }

    .statistic-card:hover {
        transform: translateY(-5px);
    }

    .statistic-card .icon {
        font-size: 3em;
        color: #007bff;
    }

    .statistic-card .content {
        text-align: right;
    }

    .statistic-card .content h4 {
        margin-bottom: 5px;
        color: #555;
    }

    .statistic-card .content p {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .statistic-card.bg-primary .icon {
        color: #fff;
    }

    .statistic-card.bg-success .icon {
        color: #fff;
    }

    .statistic-card.bg-warning .icon {
        color: #fff;
    }

    .statistic-card.bg-danger .icon {
        color: #fff;
    }

    .statistic-card.bg-primary {
        background-color: #007bff !important;
        color: #fff;
    }

    .statistic-card.bg-success {
        background-color: #28a745 !important;
        color: #fff;
    }

    .statistic-card.bg-info {
        background-color: #17a2b8 !important;
        color: #fff;
    }

    .statistic-card.bg-warning {
        background-color: #ffc107 !important;
        color: #212529;
    }

    .statistic-card.bg-danger {
        background-color: #dc3545 !important;
        color: #fff;
    }

    .statistic-card.bg-warning .content h4,
    .statistic-card.bg-warning .content p {
        color: #212529;
    }

    .chart-placeholder {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2em;
        color: #6c757d;
        text-align: center;
    }

    .section-title {
        margin-top: 30px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .time-filter-controls {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: center;
        flex-wrap: wrap;
    }

    .time-filter-controls select,
    .time-filter-controls input[type="number"] {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
    }

    .time-filter-controls button {
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
    }

    .time-filter-controls #month-selection,
    .time-filter-controls #quarter-selection,
    .time-filter-controls #year-selection {
        display: flex;
        gap: 10px;
        min-width: 250px;
        /* flex-wrap: wrap; */
    }

    .time-filter-controls select,
    .time-filter-controls input[type="number"] {
        /* flex: 1 1 auto; */
        /* thêm dòng này */
        min-width: 120px;
        /* thêm dòng này */
    }

    /* --- NEW CSS FOR ENHANCED VISUAL SEPARATION --- */

    /* Style for the main content area to give it a slight background */
    #main-content {
        background-color: #f8f9fa;
        /* Light grey background for the entire content area */
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        /* Add some top margin to separate from potential header */
    }

    /* Enhanced styling for section titles */
    .section-title {
        font-size: 1.75em;
        /* Slightly larger font size */
        font-weight: 600;
        /* Bolder */
        color: #343a40;
        /* Darker text for titles */
        border-bottom: 3px solid black;
        /* Thicker, colored border */
        padding-bottom: 15px;
        margin-top: 40px;
        /* More space above sections */
        margin-bottom: 25px;
        /* More space below sections */
        position: relative;
        /* For potential pseudo-elements */
    }

    /* Optional: Add a subtle line above section titles for more separation */
    .section-title::before {
        content: '';
        position: absolute;
        top: -20px;
        /* Adjust as needed */
        left: 0;
        width: 100%;
        border-top: 1px solid #e9ecef;
    }

    /* Style for card containers to add more visual depth */
    .card {
        border: none;
        /* Remove default card border */
        border-radius: 12px;
        /* More rounded corners */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        /* Stronger shadow */
        margin-bottom: 30px;
        /* More space between cards/sections */
    }

    .card-header {
        background-color: #f1f5f9;
        /* Light background for card header */
        border-bottom: 1px solid #e9ecef;
        /* Subtle border at the bottom of the header */
        padding: 15px 20px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .chart-container-wrapper {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
        /* Consistent margin */
        min-height: 350px;
        /* Ensure chart area has enough space */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .chart-container-wrapper h5 {
        margin-bottom: 20px;
        /* Space between title and chart */
        color: #333;
        font-size: 1.25em;
        font-weight: 500;
    }

    /* Specific styling for table cards */
    .card .table-responsive {
        border-radius: 8px;
        /* Match card border-radius */
        overflow: hidden;
        /* Ensure table doesn't break rounded corners */
    }

    .card table {
        margin-bottom: 0;
        /* Remove default table margin */
    }

    .table thead th {
        background-color: #e9ecef;
        /* Light background for table headers */
        color: #495057;
        /* Darker text for headers */
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.03);
        /* Lighter stripe */
    }

    .text-muted.text-center {
        padding: 20px;
        /* Add padding for empty states */
        font-style: italic;
        color: #6c757d;
    }

    /* Spacing adjustments for filter controls */
    .time-filter-controls {
        padding: 15px;
        background-color: #e9f2ff;
        /* Light blue background for filters */
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        /* Inner shadow for depth */
    }

    /* Adjust button style within filters */
    .time-filter-controls .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.2s, border-color 0.2s;
    }

    .time-filter-controls .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    /* Ensure consistent padding within sections if needed */
    .row {
        margin-bottom: 20px;
        /* Add some space between rows of statistic cards */
    }
</style>
<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-4">Dashboard Thống Kê Trung Tâm Anh Ngữ</h3>

        {{-- Phần điều khiển chọn thời gian --}}
        <div class="time-filter-controls">
            <select id="period-selector" class="form-select">
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Theo tháng</option>
                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Theo quý</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Theo năm</option>
            </select>

            <div id="month-selection" class="{{ $period == 'month' ? '' : 'd-none' }}">
                <select id="month-selector" class="form-select">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                </select>
                <input type="number" id="month-year-input" class="form-control" value="{{ $year }}" min="2000" max="{{ \Carbon\Carbon::now()->year + 5 }}">
            </div>

            <div id="quarter-selection" class="time-selection {{ $period == 'quarter' ? '' : 'd-none' }}">
                <select id="quarter-selector" class="form-select">
                    <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Quý 1</option>
                    <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Quý 2</option>
                    <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Quý 3</option>
                    <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Quý 4</option>
                </select>
                <input type="number" id="quarter-year-input" class="form-control" value="{{ $year }}" min="2000" max="{{ \Carbon\Carbon::now()->year + 5 }}">
            </div>

            {{-- Đảm bảo chỉ có MỘT khối year-selection và nó là select --}}
            <div id="year-selection" class="{{ $period == 'year' ? '' : 'd-none' }}">
                <select name="year" id="year-selector" class="form-control">
                    @for ($y = \Carbon\Carbon::now()->year - 5; $y <= \Carbon\Carbon::now()->year + 4; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>Năm {{ $y }}</option>
                        @endfor
                </select>
            </div>
            <button id="apply-filter-btn" class="btn btn-primary">Áp dụng</button>
        </div>


        {{-- Phần 1: Thống Kê Tổng Quan --}}
        <h4 class="section-title">Tổng Quan</h4>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-primary">
                    <div class="icon"><i class="fas fa-chalkboard"></i></div>
                    <div class="content">
                        <h4>Tổng số lớp học</h4>
                        <p>{{ $totalClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-success">
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="content">
                        <h4>Tổng số học viên</h4>
                        <p>{{ $totalStudents ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-info">
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="content">
                        <h4>{{ $revenueTitle }}</h4>
                        <p>{{ number_format($currentPeriodRevenue ?? 0, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-danger">
                    <div class="icon"><i class="fas fa-user-times"></i></div>
                    <div class="content">
                        <h4>Học viên chưa đóng học phí</h4>
                        <p>{{ $totalStudentsWithDebtCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-primary">{{-- Có thể đổi màu khác nếu muốn --}}
                    <div class="icon"><i class="fas fa-book-open"></i></div>
                    <div class="content">
                        <h4>Tổng số khóa học</h4>
                        <p>{{ $totalCourses ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-success"> {{-- Có thể đổi màu khác nếu muốn --}}
                    <div class="icon"><i class="fas fa-user-tie"></i></div>
                    <div class="content">
                        <h4>Tổng số nhân viên</h4>
                        <p>{{ $totalStaffs ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-info"> {{-- Có thể đổi màu khác nếu muốn --}}
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <div class="content">
                        <h4>Tổng số phòng học</h4>
                        <p>{{ $totalClassrooms ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-danger"> {{-- Có thể đổi màu khác nếu muốn --}}
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="content">
                        <h4>Tổng số giáo viên</h4>
                        <p>{{ $totalTeachers ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="chart-container-wrapper">
                    <h5>Biểu đồ: Doanh thu theo tháng</h5>
                    <canvas id="monthlyRevenueChart"></canvas> {{-- Giữ lại một canvas này --}}
                </div>
            </div>
        </div>


        {{-- Phần 2: Thống Kê Lớp Học --}}
        <h4 class="section-title">Thống Kê Lớp Học</h4>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-play-circle" style="color: #28a745;"></i></div>
                    <div class="content">
                        <h4>Lớp đang hoạt động</h4>
                        <p>{{ $activeClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-hourglass-start" style="color: #ffc107;"></i></div>
                    <div class="content">
                        <h4>Lớp sắp khai giảng</h4>
                        <p>{{ $upcomingClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-check-circle" style="color: #6c757d;"></i></div>
                    <div class="content">
                        <h4>Lớp đã kết thúc</h4>
                        <p>{{ $endedClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lớp học sắp kết thúc (trong 30 ngày tới)</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($endingClasses) && $endingClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Tên lớp</th>
                                        <th>Mã lớp</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Học viên</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($endingClasses as $class)
                                    <tr>
                                        <td>{{ $class->tenlophoc }}</td>
                                        <td>{{ $class->malophoc }}</td>
                                        <td>{{ \Carbon\Carbon::parse($class->ngayketthuc)->format('d/m/Y') }}</td>
                                        <td>{{ $class->hocviens_count ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center">Không có lớp học nào sắp kết thúc trong 30 ngày tới.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Phần 3: Thống Kê Học Viên --}}
        <h4 class="section-title">Thống Kê Học Viên</h4>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-users" style="color: #6f42c1;"></i></div>
                    <div class="content">
                        <h4>Học viên mới (tháng này)</h4>
                        <p>{{ $newStudentsThisMonth ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-chart-line" style="color: #fd7e14;"></i></div>
                    <div class="content">
                        <h4>Tỷ lệ học viên hoàn thành học phí</h4>
                        <p>{{ number_format($tuitionCompletionRate ?? 0, 2) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Phần 4: Thống Kê Tài Chính / Học Phí --}}
        <h4 class="section-title">Thống Kê Tài Chính / Học Phí</h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Học viên còn nợ học phí (Top 5)</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($studentsWithDebt) && $studentsWithDebt->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Học viên</th>
                                        <th>Lớp học</th>
                                        <th>Còn nợ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsWithDebt as $student)
                                    <tr>
                                        <td>{{ $student['ten'] ?? 'N/A' }}</td>
                                        <td>{{ $student['lophoc'] ?? 'N/A' }}</td>
                                        <td>{{ number_format($student['remaining_amount'] ?? 0, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center">Không có học viên nào còn nợ học phí.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Phần 5: Thống Kê Yêu Cầu Tư Vấn (Ví dụ) --}}
        <h4 class="section-title">Thống Kê Yêu Cầu Tư Vấn</h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Yêu cầu tư vấn đang chờ xử lý</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($pendingConsultations) && $pendingConsultations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Ngày đăng ký</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingConsultations as $consultation)
                                    <tr>
                                        <td>{{ $consultation->hoten }}</td>
                                        <td>{{ $consultation->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($consultation->created_at)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center">Không có yêu cầu tư vấn nào đang chờ xử lý.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelector = document.getElementById('period-selector');
        const monthSelection = document.getElementById('month-selection');
        const quarterSelection = document.getElementById('quarter-selection');
        const yearSelection = document.getElementById('year-selection'); // Thẻ div cho lựa chọn năm
        const applyFilterBtn = document.getElementById('apply-filter-btn');

        function togglePeriodInputs() {
            const selectedPeriod = periodSelector.value;
            // Ẩn tất cả các phần tử lựa chọn thời gian
            monthSelection.classList.add('d-none');
            quarterSelection.classList.add('d-none');
            yearSelection.classList.add('d-none'); // Đảm bảo ẩn cả năm

            // Hiển thị phần tử tương ứng với lựa chọn
            if (selectedPeriod === 'month') {
                monthSelection.classList.remove('d-none');
            } else if (selectedPeriod === 'quarter') {
                quarterSelection.classList.remove('d-none');
            } else if (selectedPeriod === 'year') {
                yearSelection.classList.remove('d-none'); // Hiển thị lựa chọn năm
            }
        }

        // Gọi hàm khi trang tải để thiết lập trạng thái ban đầu dựa trên `period` từ controller
        togglePeriodInputs();

        // Lắng nghe sự kiện thay đổi trên dropdown chọn kỳ hạn
        periodSelector.addEventListener('change', togglePeriodInputs);

        // Lắng nghe sự kiện click trên nút áp dụng bộ lọc
        applyFilterBtn.addEventListener('click', function() {
            const selectedPeriod = periodSelector.value;
            // Thay thế 'dashboard' bằng tên route thực tế của bạn
            let url = '{{ route("dashboard") }}';

            const params = new URLSearchParams();
            params.append('period', selectedPeriod);

            if (selectedPeriod === 'month') {
                const month = document.getElementById('month-selector').value;
                const year = document.getElementById('month-year-input').value;
                params.append('month', month);
                params.append('year', year);
            } else if (selectedPeriod === 'quarter') {
                const quarter = document.getElementById('quarter-selector').value;
                const year = document.getElementById('quarter-year-input').value;
                params.append('quarter', quarter);
                params.append('year', year);
            } else if (selectedPeriod === 'year') {
                // Khi chọn 'Theo năm', chỉ cần gửi giá trị của year-selector
                const year = document.getElementById('year-selector').value;
                params.append('year', year);
            }

            // Chuyển hướng trình duyệt đến URL mới với các tham số đã cập nhật
            window.location.href = url + '?' + params.toString();
        });

        // Khởi tạo biểu đồ doanh thu hàng tháng
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart');
        if (monthlyRevenueCtx) {
            new Chart(monthlyRevenueCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: @json($monthlyRevenueData),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Thời gian'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            console.error("Không tìm thấy phần tử canvas với ID 'monthlyRevenueChart'.");
        }
    });
</script>
@endsection