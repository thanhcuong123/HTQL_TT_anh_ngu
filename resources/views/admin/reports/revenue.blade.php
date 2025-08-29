@extends('index') {{-- Đảm bảo bạn đang extend layout chính của admin --}}

@section('title-content')
<title>Báo Cáo Doanh Thu</title>
@endsection

@section('main-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .report-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .report-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .report-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .section-title {
        margin-top: 30px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        font-size: 1.5em;
    }

    .statistic-box {
        background-color: #e9f7ff;
        border: 1px solid #cce5ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 120px;
    }

    .statistic-box .icon {
        font-size: 2.5em;
        color: #007bff;
        margin-bottom: 10px;
    }

    .statistic-box h5 {
        margin: 0;
        font-size: 1.1em;
        color: #0056b3;
    }

    .statistic-box p {
        font-size: 1.6em;
        font-weight: bold;
        color: #003f80;
        margin: 5px 0 0 0;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .data-table th,
    .data-table td {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
    }

    .data-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .data-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .data-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .no-data-message {
        background-color: #f0f8ff;
        color: #0056b3;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        font-size: 1.1em;
        border: 1px solid #cce5ff;
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

    /* NEW: Style for Export button */
    .btn-export-excel {
        background-color: #28a745;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export-excel:hover {
        background-color: #218838;
    }
</style>

<div class="report-card">
    <h3><i class="fas fa-chart-line"></i> {{ $reportTitle }}</h3>

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
                <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Quý I</option>
                <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Quý II</option>
                <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Quý III</option>
                <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Quý IV</option>
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
        {{-- NEW: Nút Xuất Excel --}}
        <a href="#" id="export-excel-btn" class="btn-export-excel">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
    </div>

    {{-- Phần 1: Tổng quan doanh thu --}}
    <h4 class="section-title">Tổng quan doanh thu</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="statistic-box">
                <i class="fas fa-dollar-sign icon"></i>
                <h5>Tổng doanh thu</h5>
                <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="statistic-box">
                <i class="fas fa-exchange-alt icon"></i>
                <h5>Số lượng giao dịch</h5>
                <p>{{ number_format($totalTransactions, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="statistic-box">
                <i class="fas fa-money-check-alt icon"></i>
                <h5>Giá trị giao dịch trung bình</h5>
                <p>{{ number_format($averageTransactionValue, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>
    </div>

    {{-- Phần 2: Doanh thu theo Lớp học/Khóa học --}}
    <h4 class="section-title">Doanh thu theo Lớp học/Khóa học</h4>
    @if(!empty($revenueByClass))
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Lớp học</th>
                    <th>Doanh thu thực tế</th>
                    <th>Học phí dự kiến (mỗi lớp)</th>
                    <th>Tỷ lệ hoàn thành thanh toán</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByClass as $classData)
                <tr>
                    <td>{{ $classData['name'] }}</td>
                    <td>{{ number_format($classData['actual_revenue'], 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($classData['expected_tuition'], 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($classData['payment_completion_rate'], 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data-message">
        <p>Không có dữ liệu doanh thu theo lớp học trong kỳ này.</p>
    </div>
    @endif

    {{-- Phần 3: Doanh thu theo Phương thức thanh toán --}}
    <h4 class="section-title">Doanh thu theo Phương thức thanh toán</h4>
    @if(!empty($revenueByPaymentMethod))
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Phương thức thanh toán</th>
                    <th>Tổng doanh thu</th>
                    <th>Tỷ lệ (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByPaymentMethod as $method => $data)
                <tr>
                    <td>{{ $method }}</td>
                    <td>{{ number_format($data['total_amount'], 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($data['percentage'], 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data-message">
        <p>Không có dữ liệu doanh thu theo phương thức thanh toán trong kỳ này.</p>
    </div>
    @endif

    {{-- Phần 4: Xu hướng doanh thu theo thời gian --}}
    <h4 class="section-title">Xu hướng doanh thu theo thời gian</h4>
    @if(!empty($monthlyRevenueTrend))
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyRevenueTrend as $data)
                <tr>
                    <td>{{ $data['month_year'] }}</td>
                    <td>{{ number_format($data['revenue'], 0, ',', '.') }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data-message">
        <p>Không có dữ liệu xu hướng doanh thu trong kỳ này.</p>
    </div>
    @endif

    {{-- Phần 5: Top học viên còn nợ học phí --}}
    <h4 class="section-title">Top 5 Học viên còn nợ học phí</h4>
    @if(!empty($top5StudentsWithDebt))
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã học viên</th>
                    <th>Họ tên học viên</th>
                    <th>Lớp học</th>
                    <th>Số tiền còn nợ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top5StudentsWithDebt as $student)
                <tr>
                    <td>{{ $student['mahocvien'] ?? 'N/A' }}</td>
                    <td>{{ $student['ten'] ?? 'N/A' }}</td>
                    <td>{{ $student['lophoc'] ?? 'N/A' }}</td>
                    <td>{{ number_format($student['remaining_amount'], 0, ',', '.') }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data-message">
        <p>Không có học viên nào còn nợ học phí.</p>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelector = document.getElementById('period-selector');
        const monthSelection = document.getElementById('month-selection');
        const quarterSelection = document.getElementById('quarter-selection');
        const yearSelection = document.getElementById('year-selection');
        const applyFilterBtn = document.getElementById('apply-filter-btn');
        const exportExcelBtn = document.getElementById('export-excel-btn'); // NEW: Lấy nút Export Excel

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

        // Hàm để xây dựng URL với các tham số lọc
        function buildFilterUrl(baseUrl) {
            const selectedPeriod = periodSelector.value;
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
            return baseUrl + '?' + params.toString();
        }

        // Lắng nghe sự kiện click trên nút áp dụng bộ lọc
        applyFilterBtn.addEventListener('click', function() {
            window.location.href = buildFilterUrl('{{ route("admin.reports.revenue") }}');
        });

        // NEW: Lắng nghe sự kiện click trên nút Xuất Excel
        exportExcelBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
            window.location.href = buildFilterUrl('{{ route("admin.reports.revenue.export") }}');
        });
    });
</script>

@endsection