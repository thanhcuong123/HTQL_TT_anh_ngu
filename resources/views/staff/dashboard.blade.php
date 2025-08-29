@extends('staff.index') {{-- Đảm bảo đây là layout chính của admin/nhân viên của bạn --}}

@section('title-content')
<title>Dashboard Nhân Viên</title>
@endsection

@section('staff-content')

{{-- Import Tailwind CSS (nếu bạn đang dùng Tailwind, nếu không thì dùng Bootstrap) --}}
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="container mx-auto p-6 md:p-10 bg-gray-100 min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Chào mừng, {{ $nhanvien->ten ?? 'Nhân viên' }}! 👋</h1>
        <p class="text-gray-600">Đây là bảng điều khiển của bạn. Dưới đây là danh sách các buổi tư vấn được lên lịch cho hôm nay ({{ \Carbon\Carbon::now()->format('d/m/Y') }}).</p>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Danh sách Tư vấn Hôm nay</h3>

        {{-- Thông báo thành công/lỗi --}}
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Thành công!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l3.029-2.651-3.029-2.651a1.2 1.2 0 1 1 1.697-1.697l2.651 3.029 2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-3.029 2.651 3.029 2.651a1.2 1.2 0 0 1 0 1.697z" />
                </svg>
            </span>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Lỗi!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l3.029-2.651-3.029-2.651a1.2 1.2 0 1 1 1.697-1.697l2.651 3.029 2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-3.029 2.651 3.029 2.651a1.2 1.2 0 0 1 0 1.697z" />
                </svg>
            </span>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nội dung tư vấn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">


                    @if ($consultations->count() > 0)
                    @foreach($consultations as $consultation)
                    <tr class="hover:bg-gray-100 cursor-pointer transition duration-200" onclick="window.location='{{ route('staff.tuvan', $consultation->id) }}'">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $consultation->hoten }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $consultation->created_at->format('d/m/Y H:i')  }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $consultation->loinhan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($consultation->trangthai == 'Đã xác nhận')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $consultation->trangthai }}
                            </span>
                            @elseif($consultation->trangthai == 'Đang chờ')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $consultation->trangthai }}
                            </span>
                            @elseif($consultation->trangthai == 'Đã hoàn thành')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $consultation->trangthai }}
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $consultation->trangthai }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @else
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Không có buổi tư vấn nào được lên lịch cho hôm nay. 🎉
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection