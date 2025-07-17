@extends('index')

@section('title-content')
<title>Dashboard Thống Kê</title>
@endsection

@section('main-content')

{{-- Import Font Awesome cho icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
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

            <div id="year-selection" class="time-selection {{ $period == 'year' ? '' : 'd-none' }}">

                <input type="number" id="year-selector" class="form-control" value="{{ $year }}" min="2000" max="{{ \Carbon\Carbon::now()->year + 5 }}">
            </div>

            <button id="apply-filter-btn" class="btn btn-primary">Áp dụng</button>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card bg-info">
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="content">
                        <h4>{{ $revenueTitle }}</h4>
                        <p>{{ number_format($currentPeriodRevenue ?? 0, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
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
                        <h4>Tổng số học viên nợ học phí</h4>
                        <p>{{ $totalStudentsWithDebtCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Phần 2: Thống Kê Lớp Học --}}
        <h4 class="section-title">Thống Kê Lớp Học</h4>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-play-circle" style="color: #28a745;"></i></div>
                    <div class="content">
                        <h4>Lớp đang hoạt động</h4>
                        <p>{{ $activeClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-hourglass-start" style="color: #ffc107;"></i></div>
                    <div class="content">
                        <h4>Lớp sắp khai giảng</h4>
                        <p>{{ $upcomingClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-check-circle" style="color: #6c757d;"></i></div>
                    <div class="content">
                        <h4>Lớp đã kết thúc</h4>
                        <p>{{ $endedClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-times-circle" style="color: #dc3545;"></i></div>
                    <div class="content">
                        <h4>Lớp đã hủy</h4>
                        <p>{{ $canceledClasses ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="chart-placeholder">
                    Biểu đồ: Phân bổ lớp học theo Khóa học/Trình độ (Sử dụng Chart.js/ApexCharts)
                </div>
            </div>
            <div class="col-lg-6">
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
            <div class="col-lg-4 col-md-6">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-users" style="color: #6f42c1;"></i></div>
                    <div class="content">
                        <h4>Học viên mới (tháng này)</h4>
                        <p>{{ $newStudentsThisMonth ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="statistic-card">
                    <div class="icon"><i class="fas fa-chart-line" style="color: #fd7e14;"></i></div>
                    <div class="content">
                        <h4>Tỷ lệ học viên hoàn thành học phí</h4>
                        <p>{{ number_format($tuitionCompletionRate ?? 0, 2) }}%</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="chart-placeholder">
                    Biểu đồ: Phân bổ học viên theo lớp/khóa học (Sử dụng Chart.js/ApexCharts)
                </div>
            </div>
        </div>

        {{-- Phần 4: Thống Kê Tài Chính / Học Phí --}}
        <h4 class="section-title">Thống Kê Tài Chính / Học Phí</h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="chart-placeholder">
                    Biểu đồ: Doanh thu theo tháng (Sử dụng Chart.js/ApexCharts)
                </div>
            </div>
            <div class="col-lg-6">
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
            <div class="col-lg-6">
                <div class="chart-placeholder">
                    Biểu đồ: Số lượng yêu cầu tư vấn theo trạng thái (Sử dụng Chart.js/ApexCharts)
                </div>
            </div>
            <div class="col-lg-6">
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

{{-- Script cho Chart.js hoặc ApexCharts sẽ được đặt ở đây --}}
{{-- Ví dụ:
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Code khởi tạo biểu đồ ở đây, sử dụng dữ liệu từ backend
        // Ví dụ:
        // var ctx = document.getElementById('myChart').getContext('2d');
        // var myChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Tháng 1', 'Tháng 2', 'Tháng 3'],
        //         datasets: [{
        //             label: 'Doanh thu',
        //             data: [12, 19, 3],
        //             backgroundColor: 'rgba(75, 192, 192, 0.2)',
        //             borderColor: 'rgba(75, 192, 192, 1)',
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
    //     }
    // });
</script>
--}}


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelector = document.getElementById('period-selector');
        const monthSelection = document.getElementById('month-selection');
        const quarterSelection = document.getElementById('quarter-selection');
        const yearSelection = document.getElementById('year-selection');
        const applyFilterBtn = document.getElementById('apply-filter-btn');

        function togglePeriodInputs() {
            const selectedPeriod = periodSelector.value;
            monthSelection.style.display = 'none';
            quarterSelection.style.display = 'none';
            yearSelection.style.display = 'none';

            if (selectedPeriod === 'month') {
                monthSelection.style.display = 'flex';
            } else if (selectedPeriod === 'quarter') {
                quarterSelection.style.display = 'flex';
            } else if (selectedPeriod === 'year') {
                yearSelection.style.display = 'flex';
            }
        }

        // Gọi hàm khi trang tải để thiết lập trạng thái ban đầu
        togglePeriodInputs();

        // Lắng nghe sự kiện thay đổi trên dropdown chọn kỳ hạn
        periodSelector.addEventListener('change', togglePeriodInputs);

        // Lắng nghe sự kiện click trên nút áp dụng bộ lọc
        applyFilterBtn.addEventListener('click', function() {
            const selectedPeriod = periodSelector.value;
            let url = '{{ route("dashboard") }}'; // Route đến dashboard của bạn

            // Tạo URLSearchParams để dễ dàng quản lý các tham số
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
                const year = document.getElementById('year-selector').value;
                params.append('year', year);
            }

            // Chuyển hướng trình duyệt đến URL mới
            window.location.href = url + '?' + params.toString();
        });
    });
</script>
@endsection