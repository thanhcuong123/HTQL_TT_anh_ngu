@extends('index')

@section('main-content')

<style>
    /* body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;

    } */

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        /* Adjust card width as needed */
        gap: 30px;
    }

    .course-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    /* Hover effect */
    .course-card:hover {
        transform: translateY(-5px);
        /* Lifts the card slightly */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        /* Stronger shadow on hover */
    }

    .class-name {
        background-color: #007bff;
        /* Example blue background */
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.1em;
    }

    .course-details {
        flex-grow: 1;
        /* Allows details section to take up available space */
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        border-bottom: 1px dashed #eee;
        /* Subtle separator */
        padding-bottom: 5px;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .label {
        font-weight: bold;
        color: #555;
        flex-shrink: 0;
        /* Prevent label from shrinking */
        margin-right: 10px;
    }

    .value {
        color: #333;
        text-align: right;
        flex-grow: 1;
    }

    .study-days {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .day-tag {
        background-color: #dc3545;
        /* Red for days, similar to image */
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9em;
        white-space: nowrap;
        /* Prevent day tags from breaking */
    }

    .attendance-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .status {
        color: #6c757d;
        font-style: italic;
    }

    .action-link {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    .action-link:hover {
        text-decoration: underline;
    }
</style>





<body>
    <div class="container">
        <h1>Danh sách khóa học</h1>

        <div class="course-grid">
            @foreach ($dsKhoaHoc as $course)
            <div class="course-card">
                <div class="class-name">{{ $course->ten}}</div>
                <div class="course-details">
                    <div class="detail-row">
                        <span class="label">Chương trình học:</span>
                        <span class="value">{!! $course-> mota !!}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Khai giảng ngày:</span>
                        <span class="value">{{ $course-> thoiluong }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Phòng học:</span>
                        <span class="value">{{ $course-> sobuoi }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Đã học:</span>
                        <span class="value">{{ $course->ma }}</span>
                    </div>

                </div>
                <div class="attendance-section">
                    <span class="status">Chưa điểm danh</span>
                    <a href="#" class="action-link">Điểm danh</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
@endsection