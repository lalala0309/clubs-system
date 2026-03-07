<?php
session_start();
require_once '../includes/get_user.php';
require_once './get_my_grounds.php';
require_once './get_sports.php';
require_once '../includes/auth_member.php';
// === Lấy tuần ===
$week_offset = isset($_GET['week_offset']) ? (int) $_GET['week_offset'] : 0;

// === Giữ trạng thái sport khi reload
$active_sport_id = isset($_GET['sportID']) ? (int) $_GET['sportID'] : null;

// Lấy thứ 2 của tuần hiện tại
$monday = new DateTime();
$monday->modify('monday this week');

// Cộng trừ tuần
if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

// Tạo mảng 7 ngày
$days = [];
$dayLabels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];

for ($i = 0; $i < 7; $i++) {
    $days[] = [$dayLabels[$i], $monday->format('d/m')];
    $monday->modify('+1 day');
}
$week_range = $days[0][1] . ' - ' . $days[6][1];


// ===== LẤY KHOÁ THEO CẢ TUẦN =====
$monday = new DateTime();
$monday->modify('monday this week');

if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

$start = $monday->format('Y-m-d');
$jsMonday = $start;
$endDate = clone $monday;
$endDate->modify('+6 day');
$end = $endDate->format('Y-m-d');

// Lấy lock tuần 
$sql = "
SELECT lock_date, start_time, end_time
FROM ground_locks
WHERE groundID = ?
AND lock_date BETWEEN ? AND ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $groundID, $start, $end);
$stmt->execute();
$result = $stmt->get_result();
$lockedSlots = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt sân</title>
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <link rel="stylesheet" href="../assets/css/fonts.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/sidebar_member.css">



    <style>
        /* Global & Animation Layer */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Right Panel Animation */
        #right-panel {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0;
            opacity: 0;
            pointer-events: none;
            z-index: 60;
        }

        #right-panel.active {
            width: 300px;
            opacity: 1;
            pointer-events: auto;
        }

        .view-hidden {
            display: none !important;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        /* Table Booking Core Layout */
        .grid-table {
            border-collapse: collapse;
            min-width: 100%;
            table-layout: fixed;
        }

        .grid-table th,
        .grid-table td {
            border: 1px solid #e2e8f0;
        }

        /* ===== TABLE FULL 7 NGÀY ===== */
        .grid-table {
            width: 100%;
            table-layout: fixed;
        }

        .grid-table th:first-child,
        .grid-table td:first-child {
            width: 60px;
            /* cột giờ */
        }

        .grid-table th:not(:first-child),
        .grid-table td:not(:first-child) {
            width: calc((100% - 60px) / 7);
        }

        .custom-scroll {
            -webkit-overflow-scrolling: touch;
        }

        .week-range-text {
            white-space: nowrap !important;
        }

        .grid-table thead th {
            white-space: nowrap !important;
        }

        /* Grid Cell System */
        .grid-cell {

            height: 38px;

            padding: 0;
            overflow: hidden;
            vertical-align: middle;
            position: relative;

            transition: background 0.15s;
        }

        .cell-available {
            background: white;
            cursor: pointer;
        }

        .cell-available:hover {
            background: #f5f8ff;
            outline: 2px solid #6366f1;
            outline-offset: -2px;
            z-index: 10;
        }

        .cell-content {
            width: 100%;
            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            font-size: 8px;
            line-height: 1.1;

            padding: 2px;
            text-align: center;
            overflow: hidden;
        }

        .court-tab.active {
            background: #6366f1 !important;
            color: white !important;
            border-color: #6366f1 !important;
        }

        .time-cell {
            height: 38px;
            white-space: nowrap;
            line-height: 38px;
            padding: 0;
        }

        #main-sidebar.show {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .cell-past {
            background: #f1f5f9 !important;
            cursor: not-allowed !important;
            position: relative;
        }

        .cell-past::after {
            content: "";
            color: #94a3b8;
            font-size: 14px;
            font-weight: 900;
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Mobile Optimization Layer */
        @media (max-width: 1024px) {
            #right-panel.active {
                position: fixed;
                right: 0;
                bottom: 0;
                top: 110px;

                height: auto;
                width: 100%;
                border-radius: 20px 20px 0 0;
            }

            #view-timetable>.px-5 {
                position: relative;
                z-index: 80;
            }
        }

        /* Bị khóa */
        .cell-locked {
            background: #fef2f2 !important;
            /* đỏ pastel */
            cursor: not-allowed !important;
            pointer-events: none;
            position: relative;
        }

        #view-timetable h2 {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cell-locked .lock-content {
            width: 100%;
            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            font-size: 9px;
            font-weight: 700;
            color: #dc2626;
            /* red-600 */

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .cell-locked .lock-icon {
            font-size: 11px;
            margin-bottom: 2px;
        }

        .grid-table thead th {
            position: sticky;
            top: 0;
            z-index: 30;
            background: #f8fafc;
        }



        /* Tối ưu cho màn hình nhỏ */
        @media (max-width: 768px) {

            /* Phóng to ô lịch để dễ chạm (Touch target) */
            .grid-cell {
                height: 50px !important;
                /* Tăng chiều cao ô */
                min-width: 50px !important;
                /* Tăng chiều rộng ô */
            }

            /* Giữ cố định cột thời gian khi cuộn ngang */
            .grid-table th:first-child,
            .time-cell {
                position: sticky;
                left: 0;
                z-index: 10;
                background: #f8fafc;
                width: 50px !important;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            }

            /* Ẩn bớt thông tin text rườm rà trong ô trên mobile */
            .cell-content .truncate {
                max-width: 45px;
                font-size: 7px;
            }

            /* Panel xác nhận tràn toàn màn hình trên mobile */
            #right-panel {
                border-radius: 0 !important;
            }

            #right-panel.active {
                position: fixed !important;
                right: 0;
                top: 67px;
                bottom: 0;
                width: 100%;
                height: auto;
                z-index: 60;
            }

            /* Header luôn nằm trên */
            #view-timetable>.px-5 {
                position: relative;
                z-index: 80;
                background: white;
            }
        }

        /* Hiệu ứng chọn ô */
        .grid-cell.selected {
            outline: 2px solid #4f46e5 !important;
            background: #eef2ff !important;
            z-index: 5;
        }

        .cell-my-booking {
            background: #bbf7d0 !important;
            /* green-200 */
            color: #111827 !important;
        }

        .cell-blue-light {
            background: #dbeafe !important;
            color: #1e3a8a;
            cursor: not-allowed !important;
            /* pointer-events: none !important; */
            position: relative;
        }

        .cell-blue-light::after {
            content: "\F62A";
            /* bi-slash-circle */
            font-family: "Bootstrap Icons";
            font-size: 16px;
            color: #2563eb;
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.85;
            pointer-events: none;
        }

        /* Còn lượt ưu tiên */
        .cell-available-priority {
            background-color: #dcfce7 !important;
            /* green-100 */
            color: #166534 !important;
        }

        /* Hết lượt ưu tiên */
        .cell-no-priority {
            background-color: #fee2e2 !important;
            /* red-100 */
            color: #7f1d1d !important;
        }

        /* Ô còn trống */
        .cell-available {
            background: white;
            cursor: pointer;
        }

        /* Hover cho ô trống */
        .cell-available:hover {
            background: #f5f8ff;
            outline: 2px solid #6366f1;
            outline-offset: -2px;
            z-index: 10;
            cursor: pointer;
        }

        /* Còn lượt ưu tiên của mình */
        .cell-available-priority {
            background-color: #dcfce7 !important;
            color: #166534 !important;
            cursor: pointer;
        }

        /* Hết lượt ưu tiên của mình */
        .cell-no-priority {
            background-color: #fee2e2 !important;
            color: #7f1d1d !important;
            cursor: pointer;
        }

        /* Người khác đã hết lượt ưu tiên (có thể đè) */
        .cell-booked {
            background-color: #f1f5f9;
            cursor: pointer;
        }

        /* Người khác còn lượt ưu tiên (không đè được) */
        .cell-blue-light {
            background: #dbeafe !important;
            color: #1e3a8a;
            cursor: not-allowed !important;
            position: relative;
        }

        /* Ô bị khóa
        .cell-locked {
            background: #fef2f2 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        } */

        /* Ô quá khứ */
        .cell-past {
            background: #f1f5f9 !important;
            cursor: not-allowed !important;
        }

        /* ===== TỐI ƯU HEADER CHO MOBILE ===== */
        @media (max-width: 768px) {

            /* HEADER WRAPPER - Flex hàng ngang, gap nhỏ, tất cả gọn gàng */
            #view-timetable .px-5.py-3.border-b {
                display: flex !important;
                flex-wrap: nowrap !important;
                align-items: center !important;
                gap: 14px !important;
                /* Tăng khoảng cách giữa các khối */
                padding: 14px 16px !important;
                /* Tăng padding trên dưới */
                min-height: 40px;
            }

            /* KHỐI 1: Back button + Tiêu đề (cột dọc) */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(1) {
                display: flex !important;
                flex-direction: row !important;
                gap: 4px !important;
                flex: 0 1 auto;
                min-width: 0;
            }

            /* Nút back nhỏ gọn */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(1) .w-7.h-7 {
                min-width: 24px !important;
                width: 24px !important;
                height: 24px !important;
                flex-shrink: 0;
            }

            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(1) .w-7.h-7 i {
                font-size: 10px !important;
            }

            /* Nhóm tiêu đề nằm vertical */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(1)>div {
                display: flex !important;
                flex-direction: column !important;
                gap: 2px !important;
                min-width: 0;
            }

            #current-club-title {
                font-size: 9px !important;
                font-weight: 900 !important;
                /* line-height: 1.2 !important; */
                /* white-space: nowrap !important; */
                overflow: hidden;
                /* text-overflow: ellipsis; */
                /* line-height: 1.4 !important; */
                overflow: visible !important;

                margin: 0 !important;
                padding: 0 !important;
            }

            #selected-court-label {
                font-size: 7px !important;
                font-weight: 700 !important;
                /* line-height: 1.2 !important; */
                white-space: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* KHỐI 2: Badge (cột dọc, gọn) */
            #weeklyUsageBadge {
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                align-items: flex-start !important;
                gap: 0 !important;
                padding: 0 !important;
                background: transparent !important;
                border: none !important;
                flex: 0 1 auto;
                min-width: 0;
                font-size: 7px !important;
                /* line-height: 1.1 !important; */
                font-weight: 700 !important;
            }

            /* Ẩn icon badge */
            #weeklyUsageBadge i {
                display: none !important;
            }

            /* Mỗi dòng trong badge */
            #weeklyUsageBadge span {
                font-size: 7px !important;
                white-space: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block !important;
                /* line-height: 1 !important; */
                margin: 0 !important;
            }

            #weeklyUsageBadge>div {
                display: contents !important;
            }

            /* KHỐI 3: Control buttons (tuần + dấu hỏi, nằm hàng) */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(2) {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 3px !important;
                flex: 0 1 auto;
                margin-left: 5px;
            }

            /* Thanh chuyển tuần - compact */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(2) .flex.items-center.gap-2 {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 2px !important;
                padding: 2px 4px !important;
                background: white !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 6px !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            }

            /* Nút chuyển tuần */
            #view-timetable .px-5.py-3.border-b>.flex.items-center.gap-3:nth-of-type(2) .flex.items-center.gap-2 button {
                padding: 0 2px !important;
                margin: 0 !important;
                font-size: 8px !important;
                min-width: auto !important;
                background: transparent !important;
                border: none !important;
                cursor: pointer !important;
            }

            /* Text khoảng ngày */
            .week-range-text {
                font-size: 7px !important;
                letter-spacing: -0.5px;
                white-space: nowrap !important;
                margin: 0 2px !important;
            }

            /* Nút dấu hỏi */
            #legendToggle {
                width: 22px !important;
                height: 22px !important;
                min-width: 22px !important;
                flex-shrink: 0;
                padding: 0 !important;
                font-size: 10px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin-left: 4px !important;
            }



            /* Chỉ tác động vào container 2 cột (tabs + bảng) */
            #view-timetable>div.flex.flex-1 {
                flex-direction: column !important;
                min-height: 0 !important;
            }

            /* Tabs nằm ngang */
            #court-tabs-container {
                width: 100% !important;
                height: auto !important;

                /* display: flex !important; */
                flex-direction: row !important;
                /* justify-content: cente !important; */
                gap: 10px !important;

                border-right: none !important;
                border-bottom: 1px solid #e5e7eb !important;
                padding: 6px 0 !important;
                padding-left: 8px;
                background: #f8fafc;
            }

            /* Bảng booking full width */
            #view-timetable>div.flex.flex-1>div.flex-1 {
                width: 100% !important;
            }





            /* Nút S nhỏ gọn */
            #court-tabs-container .court-tab {
                width: 26px !important;
                height: 26px !important;
                font-size: 10px !important;
                border-radius: 4px !important;
            }

            td.time-cell .time-text {
                font-size: 7px !important;

            }

            td.time-cell .time-text {
                display: block;
                white-space: normal !important;
                line-height: 1.1 !important;
            }

            td.time-cell .time-text::after {
                content: attr(data-end);
                display: block;
            }
        }

        /* Legend Mobile Slide Panel */
        @media (max-width: 768px) {
            #colorLegend {
                position: fixed !important;
                top: 0 !important;
                right: -320px !important;
                width: 100% !important;
                /* FULL WIDTH */
                max-width: 100% !important;
                /* BỎ GIỚI HẠN */
                height: 100vh !important;
                background: white !important;
                z-index: 999 !important;
                padding: 24px !important;
                border-radius: 20px 0 0 20px !important;
                box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                overflow-y: auto !important;
                box-sizing: border-box !important;
            }

            #colorLegend.active {
                right: 0 !important;
            }
        }

        @media (max-width: 768px) {
            #colorLegend {
                box-sizing: border-box !important;
                overflow-y: auto !important;
            }

            #colorLegend span {
                white-space: normal !important;
                word-break: break-word !important;
            }
        }
    </style>
</head>

<body class="bg-[#F8FAFF] min-h-screen p-1 md:p-2">
    <div id="sidebar-overlay" onclick="toggleSidebar()"
        class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-2 md:gap-3">
        <?php include '../includes/sidebar_member.php'; ?>

        <main class="flex-1 flex flex-col min-h-0 min-w-0">
            <div class="flex-shrink-0 flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-indigo-600">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>
            <div class="flex-1 relative min-h-0 flex flex-col overflow-hidden">
                <div id="view-clubs"
                    class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[35px] md:rounded-[45px] p-4 md:p-8 border border-white">
                    <div class="mb-2 md:mb-3">
                        <div class="flex items-end justify-between">
                            <div>
                                <h2 class=" text-xs md:text-2xl font-black text-slate-800 tracking-tight uppercase">
                                    Đặt sân</h2>
                                <p class="text-[11px] md:text-[13px] text-slate-400 mt-0.5 font-medium">Vui lòng chọn
                                    loại sân bạn muốn sử dụng</p>
                            </div>
                        </div>
                        <div class="h-[2px] w-full bg-slate-200 mt-4 relative">
                            <div class="absolute left-0 top-0 h-full w-20 bg-indigo-500"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-2 md:gap-3">
                        <?php foreach ($sports as $sport): ?>
                            <div onclick="openTimetable(<?php echo $sport['sportID']; ?>, '<?php echo $sport['sport_name']; ?>')"
                                data-sport-id="<?php echo $sport['sportID']; ?>"
                                class="club-card p-2 md:p-4 flex items-center justify-between shadow-sm hover:shadow-md cursor-pointer group bg-white rounded-[20px] md:rounded-[25px] transition-all">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl transition-transform group-hover:scale-110">
                                        <i class="bi bi-dribbble text-indigo-500 text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1">Sân
                                            <?php echo htmlspecialchars($sport['sport_name']); ?>
                                        </h3>
                                        <p class="text-[11px] text-indigo-500 font-medium leading-tight">Các sân thuộc CLB
                                            bạn tham gia</p>
                                    </div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center">
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="view-timetable"
                    class="view-hidden flex flex-col flex-1 min-h-0 bg-white/70 backdrop-blur-sm rounded-[30px] border border-slate-200">
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-white/50">
                        <div class="flex items-center gap-3">
                            <button onclick="backToClubs()"
                                class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all border border-slate-100">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </button>
                            <div>
                                <h2 id="current-club-title"
                                    class="text-[10px] md:text-sm font-extrabold text-indigo-700 uppercase">
                                    ĐẶT SÂN</h2>
                                <p id="selected-court-label"
                                    class="text-[px] md:text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                                    SÂN SỐ 01
                                </p>
                            </div>
                            <div id="weeklyUsageBadge"
                                class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all duration-300">
                                <i class="bi bi-calendar-check"></i>
                                <span>Đang tải...</span>
                            </div>

                        </div>

                        <div class="flex items-center gap-3">

                            <!-- Khung chuyển tuần -->
                            <div
                                class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm">
                                <button onclick="changeWeek(<?php echo $week_offset - 1; ?>)"
                                    class="hover:text-indigo-600 px-1">
                                    <i class="bi bi-caret-left-fill text-[10px]"></i>
                                </button>

                                <span class="whitespace-nowrap text-[9px] font-bold text-slate-500 uppercase">
                                    <?php echo $week_range; ?>
                                </span>

                                <button onclick="changeWeek(<?php echo $week_offset + 1; ?>)"
                                    class="hover:text-indigo-600 px-1">
                                    <i class="bi bi-caret-right-fill text-[10px]"></i>
                                </button>
                            </div>

                            <!-- Dấu hỏi -->
                            <div class="relative">
                                <button id="legendToggle"
                                    class="w-5 h-5 rounded-full bg-slate-200 hover:bg-indigo-100 text-slate-600 flex items-center justify-center text-[11px] shadow-sm">
                                    <i class="bi bi-question-lg"></i>
                                </button>

                                <div id="colorLegend"
                                    class="hidden absolute top-8 right-0 bg-white shadow-xl border border-slate-200 rounded-xl p-4 w-64 text-[11px] space-y-3 z-50">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="font-bold text-slate-700 uppercase text-[10px]">
                                            Chú thích
                                        </div>

                                        <button onclick="closeLegend()"
                                            class="w-6 h-6 flex items-center justify-center rounded-full bg-slate-100 md:hidden">
                                            <i class="bi bi-x-lg text-[10px]"></i>
                                        </button>
                                    </div>

                                    <div class="flex items-start gap-2 min-w-0">
                                        <div
                                            class="w-4 h-4 min-w-[16px] min-h-[16px] rounded border flex-shrink-0 bg-green-100">
                                        </div>
                                        <span>Lượt ưu tiên của bạn, bạn có thể đè lịch của người khác không ưu
                                            tiên</span>
                                    </div>

                                    <div class="flex items-start gap-2 min-w-0">
                                        <div
                                            class="w-4 h-4 min-w-[16px] min-h-[16px] rounded border flex-shrink-0 bg-blue-100">
                                        </div>
                                        <span>Người khác còn lượt ưu tiên, bạn không thể đè lịch của người này</span>
                                    </div>

                                    <div class="flex items-start gap-2 min-w-0">
                                        <div
                                            class="w-4 h-4 min-w-[16px] min-h-[16px] rounded border flex-shrink-0 bg-red-100">
                                        </div>
                                        <span>Bạn đã hết lượt ưu tiên, có thể bị đè lịch nếu đặt tiếp</span>
                                    </div>

                                    <div class="flex items-start gap-2 min-w-0">

                                        <div
                                            class="w-4 h-4 min-w-[16px] min-h-[16px] rounded border flex-shrink-0 border">
                                        </div>
                                        <span class="break-words leading-tight">Người khác hết lượt ưu tiên, bạn có thể
                                            dùng lượt ưu tiên để đặt</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-1 min-h-0">
                        <div class="w-12 border-r border-slate-100 flex flex-col items-center py-4 gap-2 bg-slate-50/30 overflow-y-auto custom-scroll flex-shrink-0"
                            id="court-tabs-container">
                        </div>

                        <div class="flex-1 overflow-auto custom-scroll min-h-0">
                            <table class="grid-table">
                                <thead class="sticky top-0 z-20 bg-slate-50">
                                    <tr>
                                        <th
                                            class="p-2 text-[8px] font-black text-slate-400 uppercase w-20 bg-slate-100/50">
                                            Giờ</th>
                                        <?php foreach ($days as $d): ?>
                                            <th class="p-1">
                                                <div class="text-[7px] text-indigo-500 font-black uppercase leading-none">
                                                    <?php echo $d[0]; ?>
                                                </div>
                                                <div class="text-[9px] text-slate-600 font-bold"><?php echo $d[1]; ?></div>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $timeRanges = ["06:00-07:00", "07:00-08:00", "08:00-09:00", "09:00-10:00", "13:00-14:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00", "18:00-19:00", "19:00-20:00", "20:00-21:00"];
                                    $timeRanges = ["05:00-06:00", "06:00-07:00", "07:00-08:00", "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00", "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00", "18:00-19:00", "19:00-20:00", "20:00-21:00", "21:00-22:00", "22:00-23:00"];
                                    foreach ($timeRanges as $range):
                                        ?>
                                        <tr>
                                            <td class="time-cell text-center bg-[#FDFDFF] px-1">
                                                <span
                                                    class="time-text text-[9px] font-bold text-slate-500 tracking-tighter">
                                                    <?php echo $range; ?>
                                                </span>
                                            </td>
                                            <?php for ($i = 0; $i < 7; $i++): ?>
                                                <td class="grid-cell cell-available" data-day="<?php echo $days[$i][1]; ?>"
                                                    data-time="<?php echo $range; ?>"
                                                    onclick="openPanel('<?php echo $range; ?>', '<?php echo $days[$i][0] . ' - ' . $days[$i][1]; ?>')">
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <aside id="right-panel"
            class="bg-white flex flex-col shrink-0 shadow-2xl rounded-l-[30px] border-l border-indigo-50">
            <div class="p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xs font-black text-indigo-600 uppercase tracking-widest">Xác nhận đặt sân</h2>
                    <button onclick="closePanel()"
                        class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
                <div class="flex-1 space-y-4">
                    <div class="p-6 bg-indigo-50 rounded-[25px] border border-indigo-100 text-center">
                        <div class="text-indigo-600 font-black text-xl" id="info-time">00:00-00:00</div>
                        <div class="text-slate-500 font-bold text-[10px] uppercase mt-1 tracking-widest" id="info-date">
                            Thứ ...</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-500">
                            <i class="bi bi-geo-alt-fill text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 tracking-tight uppercase italic"
                            id="info-court">Sân số 01</span>
                    </div>
                </div>
                <button id="btn-confirm"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase">
                    Xác nhận gửi yêu cầu
                </button>
            </div>
        </aside>
    </div>

    <script>
        // Xác định user hiện tại để phân biệt lịch của mình / người khác
        const CURRENT_USER_ID = <?php echo $userID; ?>;

        //Tên sân đang chọn (Sân số 01...)
        let currentCourtName = "Sân số 01";

        // Object giữ trạng thái booking đang thao tác
        let selectedBooking = { groundID: null, booking_date: null, start_time: null, end_time: null };

        //Môn đang mở (để giữ trạng thái khi reload)
        let currentSportID = <?php echo $active_sport_id ?? 'null'; ?>;

        // Backup bảng lịch khi bị thay bằng cảnh báo phí
        let originalTableHTML = '';

        // Lưu interval đếm ngược hạn phí
        let countdownInterval = null;

        window.addEventListener('DOMContentLoaded', () => {
            originalTableHTML = document.querySelector('#view-timetable .flex-1').innerHTML;
        });

        // Mở giao diện lịch khi chọn môn 
        function openTimetable(sportID, sportName) {
            currentSportID = sportID;
            document.getElementById('current-club-title').innerText = 'Đặt sân ' + sportName;
            document.getElementById('view-clubs').classList.add('view-hidden');
            document.getElementById('view-timetable').classList.remove('view-hidden');

            fetch(`get_grounds_by_sport.php?sportID=${sportID}&userID=<?php echo $userID; ?>`)
                .then(res => res.json())
                .then(data => {

                    if (!data || data.length === 0) {
                        showFeeWarning();
                        return;
                    }

                    const expireDateStr = data[0].fee_expire_date;

                    if (!expireDateStr) {
                        showFeeWarning();
                        return;
                    }

                    const now = new Date();
                    const expireDate = new Date(expireDateStr);

                    // nếu DB chỉ lưu yyyy-mm-dd thì set hết ngày 23:59:59
                    expireDate.setHours(23, 59, 59, 999);

                    // Tính số ngày còn lại
                    const diffTime = expireDate - now;
                    const remainingDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (remainingDays < 0) {
                        showFeeWarning();
                        return;
                    }

                    // format ngày thành giờ việt nam
                    function formatDateTimeVN(date) {
                        const d = String(date.getDate()).padStart(2, '0');
                        const m = String(date.getMonth() + 1).padStart(2, '0');
                        const y = date.getFullYear();
                        const h = String(date.getHours()).padStart(2, '0');
                        const min = String(date.getMinutes()).padStart(2, '0');
                        return `${d}/${m}/${y} - ${h}:${min}`;
                    }
                    window.expireDate = expireDate;
                    startCountdown();
                    const tableContainer = document.querySelector('#view-timetable .flex-1');

                    if (originalTableHTML && tableContainer.innerHTML !== originalTableHTML) {
                        tableContainer.innerHTML = originalTableHTML;
                    }
                    document.getElementById("weeklyUsageBadge").style.display = "flex";
                    renderCourts(data);
                });
        }

        // render các tab chọn sân
        function renderCourts(grounds) {
            const container = document.getElementById('court-tabs-container');
            container.innerHTML = '';

            const savedGroundID = sessionStorage.getItem('reopen_ground_id');

            grounds.forEach((g, i) => {
                const isActive = (savedGroundID && g.groundID == savedGroundID) || (!savedGroundID && i == 0);
                container.innerHTML += `
                    <button data-ground-id="${g.groundID}" onclick="changeCourt(${i + 1}, this)"
                        class="court-tab w-8 h-8 rounded-md border bg-white shadow-sm text-[9px] font-black transition-all ${isActive ? 'active' : 'text-slate-400'}">
                        S${i + 1}
                    </button>`;
            });

            const targetBtn = savedGroundID
                ? container.querySelector(`[data-ground-id="${savedGroundID}"]`)
                : container.querySelector('.court-tab');

            if (targetBtn) {
                const idx = targetBtn.innerText.replace('S', '');
                changeCourt(parseInt(idx), targetBtn);
            }
            sessionStorage.removeItem('reopen_ground_id');
        }

        // reload toàn bộ bảng sân
        function changeCourt(index, btn) {
            const label = index < 10 ? '0' + index : index;
            currentCourtName = 'Sân số ' + label;
            document.getElementById('selected-court-label').innerText = currentCourtName;
            document.querySelectorAll('.court-tab').forEach(el => {
                el.classList.remove('active');
                el.classList.add('text-slate-400');
            });
            btn.classList.add('active');
            btn.classList.remove('text-slate-400');

            const groundID = btn.dataset.groundId;
            selectedBooking.groundID = groundID;

            document.querySelectorAll('.grid-cell').forEach(cell => {
                cell.className = 'grid-cell cell-available';
                cell.innerHTML = '';
                delete cell.dataset.bookingId;
                delete cell.dataset.bookedBy;
                delete cell.dataset.priority;

            });
            Promise.all([
                fetch(`get_locked_slots.php?groundID=${groundID}&week_offset=<?php echo $week_offset; ?>`)
                    .then(r => r.json()),
                fetch(`get_booked_slots.php?groundID=${groundID}&week_offset=<?php echo $week_offset; ?>`)
                    .then(r => r.json())
            ]).then(([locks, bookings]) => {

                markBookedSlots(bookings);   // tô booking trước
                markLockedSlots(locks);      // khoá đè lên sau
                disablePastSlots();          // xử lý quá khứ cuối cùng

            });

            const mondayDate = "<?= $jsMonday ?>";
            loadWeeklyUsage(groundID, mondayDate);
        }


        // Chuyển tuần
        function changeWeek(offset) {
            const url = new URL(window.location.href);
            url.searchParams.set('week_offset', offset);

            // Đính kèm sportID vào URL để giữ trạng thái
            if (currentSportID) {
                url.searchParams.set('sportID', currentSportID);
                // Lưu lại sân đang chọn vào session
                const activeGroundID = document.querySelector('.court-tab.active')?.getAttribute('data-ground-id');
                if (activeGroundID) {
                    sessionStorage.setItem('reopen_ground_id', activeGroundID);
                }
            }
            window.location.href = url.href;
        }

        window.addEventListener('DOMContentLoaded', () => {
            // Kiểm tra nếu có sportID trên URL thì tự động mở môn đó
            if (currentSportID) {
                const sportCard = document.querySelector(`.club-card[data-sport-id="${currentSportID}"]`);
                if (sportCard) {
                    // Lấy sportName từ text của card để truyền vào hàm
                    const sportName = sportCard.querySelector('h3').innerText.replace('Sân ', '').trim();
                    openTimetable(currentSportID, sportName);
                }
            }
        });

        // Mỏ panel xác nhận
        function openPanel(time, date) {

            // RESET bookingID để đảm bảo đây là đặt mới
            delete selectedBooking.bookingID;

            const [start, end] = time.split('-');
            selectedBooking.start_time = start;
            selectedBooking.end_time = end;
            selectedBooking.booking_date = date.split(' - ')[1];

            const btn = document.getElementById('btn-confirm');
            btn.innerText = "Xác nhận gửi yêu cầu";
            btn.classList.remove('bg-red-600');
            btn.classList.add('bg-indigo-600');

            document.getElementById('info-time').innerText = time;
            document.getElementById('info-date').innerText = date;
            document.getElementById('info-court').innerText = currentCourtName;

            document.getElementById('right-panel').classList.add('active');

            const cell = document.querySelector(
                `.grid-cell[data-day="${selectedBooking.booking_date}"][data-time="${time}"]`
            );

            if (cell) {
                selectedBooking.bookedBy = cell.dataset.bookedBy || null;
                selectedBooking.oldPriority = cell.dataset.priority || 0;
            }
        }

        // Đóng panel xác nhận
        function closePanel() {
            document.getElementById('right-panel').classList.remove('active');

        }

        function backToClubs() {
            document.getElementById('view-timetable').classList.add('view-hidden');
            document.getElementById('view-clubs').classList.remove('view-hidden');
            // Xóa sportID trên URL khi quay lại
            const url = new URL(window.location.href);
            url.searchParams.delete('sportID');
            window.history.replaceState({}, '', url.href);
            currentSportID = null;
            sessionStorage.clear();
            closePanel();
        }


        //Đây là booking decision engine.
        document.getElementById('btn-confirm').addEventListener('click', () => {
            // ====== NẾU LÀ HUỶ ======
            if (selectedBooking.bookingID) {
                if (!confirm("Bạn chắc chắn muốn huỷ lịch này?")) return;

                fetch('./cancel_booking.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ bookingID: selectedBooking.bookingID })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {

                            alert('Huỷ thành công');

                            closePanel();

                            const activeBtn = document.querySelector('.court-tab.active');

                            if (activeBtn) {
                                changeCourt(
                                    parseInt(activeBtn.textContent.replace('S', '')),
                                    activeBtn
                                );
                            }

                            // reset luôn selectedBooking để tránh giữ bookingID cũ
                            selectedBooking = {
                                groundID: activeBtn?.dataset.groundId || null
                            };
                        } else {
                            alert(data.message);
                        }
                    });

                return;
            }

            // ====== NẾU SLOT ĐANG CÓ NGƯỜI KHÁC ======
            if (selectedBooking.bookedBy && selectedBooking.bookedBy != CURRENT_USER_ID) {

                if (!confirm("Khung giờ này đã có người đặt. Bạn có muốn gửi yêu cầu đặt?")) {
                    return;
                }

                const formData = new FormData();
                for (let k in selectedBooking) {
                    formData.append(k, selectedBooking[k]);
                }

                fetch('./override_booking.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Đặt lịch thành công');
                            closePanel();
                            reloadCurrentCourt();
                        } else {
                            alert(data.message);
                        }
                    });

                return;
            }
            if (!selectedBooking.groundID) return alert('Lỗi: Chưa chọn sân');
            const formData = new FormData();
            for (let k in selectedBooking) formData.append(k, selectedBooking[k]);

            fetch('./handle_booking.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Đặt sân thành công');
                        selectedBooking = {
                            groundID: selectedBooking.groundID
                        };
                        closePanel();
                        reloadCurrentCourt();
                        const activeBtn = document.querySelector('.court-tab.active');
                        if (activeBtn) changeCourt(parseInt(activeBtn.innerText.replace('S', '')), activeBtn);
                    } else { alert(data.message); }
                });
        });

        // Tô màu các slot đã đặt
        function markBookedSlots(bookings) {

            document.querySelectorAll('.grid-cell').forEach(cell => {
                cell.classList.remove('cell-my-booking', 'cell-booked');
                const thMap = {
                    0: 'CN',
                    1: 'Thứ 2',
                    2: 'Thứ 3',
                    3: 'Thứ 4',
                    4: 'Thứ 5',
                    5: 'Thứ 6',
                    6: 'Thứ 7'
                };

                cell.onclick = () => {
                    if (cell.classList.contains('cell-blue-light')) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    const [d, m] = cell.dataset.day.split('/');
                    const year = new Date().getFullYear();
                    const dateObj = new Date(year, m - 1, d);
                    const label = thMap[dateObj.getDay()];
                    openPanel(
                        cell.dataset.time,
                        `${label} - ${cell.dataset.day}`
                    );
                };
                cell.innerHTML = '';
            });

            bookings.forEach(b => {

                document.querySelectorAll('.grid-cell').forEach(cell => {

                    if (
                        cell.dataset.day === b.booking_date &&
                        cell.dataset.time === `${b.start_time}-${b.end_time}`
                    ) {
                        cell.classList.remove(
                            'cell-available',
                            'cell-available-priority',
                            'cell-no-priority'
                        );

                        if (b.userID == CURRENT_USER_ID) {
                            cell.dataset.bookingId = b.bookingID;
                            cell.onclick = () => openCancelPanel(b);

                            if (b.priority == 1) {
                                // còn lượt ưu tiên
                                cell.classList.add('cell-available-priority');
                            } else {
                                // hết lượt ưu tiên
                                cell.classList.add('cell-no-priority');
                            }
                        } else {

                            cell.dataset.bookedBy = b.userID;
                            cell.dataset.priority = b.priority;

                            if (b.priority == 1) {
                                // chưa vượt limit → xanh nhạt
                                cell.classList.add('cell-blue-light');
                            } else {
                                // đã vượt limit → màu đậm
                                cell.classList.add('cell-booked');
                            }
                        }

                        cell.innerHTML = `
                            <div class="cell-content">
                                <div class="font-semibold truncate">${b.full_name}</div>
                                <div class="opacity-60 truncate">${b.email}</div>
                            </div>
                        `;
                    }
                });
            });
        }


        // Disable quá khứ và quá 14 ngày kể từ ngày hiện tại 
        function disablePastSlots() {
            const now = new Date();
            const maxBookingDate = new Date();
            maxBookingDate.setDate(now.getDate() + 14);
            document.querySelectorAll('.grid-cell').forEach(cell => {
                const day = cell.dataset.day;
                const time = cell.dataset.time;
                if (!day || !time) return;

                const [d, m] = day.split('/');
                const [start] = time.split('-');
                const [h, min] = start.split(':');

                const year = new Date().getFullYear();
                const cellTime = new Date(year, m - 1, d, h, min);

                //  Quá khứ or quá 2 tuần
                if (cellTime < now || cellTime > maxBookingDate) {
                    cell.classList.remove('cell-available');
                    cell.classList.add('cell-past');
                    cell.onclick = null;
                }
            });
        }

        function toggleSidebar() {
            document.getElementById('main-sidebar').classList.toggle('show');
            document.getElementById('sidebar-overlay').classList.toggle('active');
        }

        function toMin(t) {
            const [h, m] = t.split(':');
            return (+h) * 60 + (+m);
        }


        // Đánh dấu sân bị admin khoá
        function markLockedSlots(locks) {
            locks.forEach(lock => {
                const lockDate = formatVNDate(lock.lock_date); // 2026-02-09 → 09/02
                const lockStart = lock.start_time.slice(0, 5);
                const lockEnd = lock.end_time.slice(0, 5);

                document.querySelectorAll('.grid-cell').forEach(cell => {

                    if (cell.dataset.day !== lockDate) return;
                    const [s, e] = cell.dataset.time.split('-');
                    if (
                        toMin(s) < toMin(lockEnd) &&
                        toMin(e) > toMin(lockStart)
                    ) {
                        cell.classList.remove('cell-available');
                        cell.classList.add('cell-locked');
                        cell.onclick = null;
                        cell.innerHTML = `
                            <div class="lock-content">
                                <i class="bi bi-lock-fill lock-icon"></i>
                            </div>
                        `;
                    }
                });
            });
        }


        function formatVNDate(mysqlDate) {
            const d = new Date(mysqlDate);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            return `${day}/${month}`;
        }


        // Hiển thị số lượt còn lại
        function loadWeeklyUsage(groundID, date) {
            fetch(`get_weekly_usage.php?groundID=${groundID}&date=${date}`)
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById("weeklyUsageBadge");

                    // Cập nhật nội dung (giữ lại icon nếu muốn)
                    badge.innerHTML = `
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wide">
                        <i class="bi bi-calendar-check"></i>
                        <span>Còn ${data.remain}/${data.limit}</span>
                        <span id="countdown-text" class="text-indigo-600"></span>
                    </div>
                    `;

                    if (data.remain <= 0) {
                        badge.className = "badge bg-danger ms-2";
                    } else {
                        badge.className = "badge bg-success ms-2";
                    }
                });
        }

        // Nếu chưa đóng phí sẽ khoá và cảnh báo
        function showFeeWarning() {
            const tableContainer = document.querySelector('#view-timetable .flex-1');
            tableContainer.innerHTML = `
                <div class="w-full h-full flex items-center justify-center">
                    <div class="text-center">
                        <i class="bi bi-exclamation-circle text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500 font-bold text-lg">
                            Vui lòng đóng phí để sử dụng tiện ích câu lạc bộ
                        </p>
                    </div>
                </div>
            `;
            document.getElementById("weeklyUsageBadge").style.display = "none";
        }

        // Mở panel ơ chế độ huỷ
        function openCancelPanel(booking) {
            selectedBooking.bookingID = booking.bookingID;

            document.getElementById('info-time').innerText =
                booking.start_time + "-" + booking.end_time;

            document.getElementById('info-date').innerText =
                booking.booking_date;

            document.getElementById('info-court').innerText =
                currentCourtName;

            const btn = document.getElementById('btn-confirm');

            btn.innerText = "Huỷ đặt sân";
            btn.classList.remove('bg-indigo-600');
            btn.classList.add('bg-red-600');
            document.getElementById('right-panel').classList.add('active');
        }

        // Láy tab active
        function reloadCurrentCourt() {
            const activeBtn = document.querySelector('.court-tab.active');
            if (!activeBtn) return;
            const index = parseInt(activeBtn.innerText.replace('S', ''));
            changeCourt(index, activeBtn);
        }


        // Đếm ngược hạn phí còn lại
        function startCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdownInterval = setInterval(() => {
                const badgeTime = document.getElementById("countdown-text");
                if (!badgeTime) return;
                const now = new Date();
                const diff = window.expireDate - now;

                if (diff <= 0) {
                    clearInterval(countdownInterval);
                    badgeTime.innerText = "Hết hạn";
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);

                badgeTime.innerText =
                    `${days} ngày ${hours} giờ`;

            }, 1000);
        }

        const legendBtn = document.getElementById("legendToggle");
        const legendBox = document.getElementById("colorLegend");

        legendBtn.addEventListener("click", (e) => {
            e.stopPropagation();

            if (window.innerWidth <= 768) {
                legendBox.classList.remove("hidden");
                legendBox.classList.toggle("active");
            } else {
                legendBox.classList.toggle("hidden");
            }
        });

        document.addEventListener("click", (e) => {
            if (!legendBox.contains(e.target) && e.target !== legendBtn) {
                if (window.innerWidth <= 768) {
                    legendBox.classList.remove("active");
                } else {
                    legendBox.classList.add("hidden");
                }
            }
        });


        // Đóng tab chú thích
        function closeLegend() {
            const legend = document.getElementById("colorLegend");

            if (window.innerWidth <= 768) {
                legend.classList.remove("active");
                setTimeout(() => {
                    legend.classList.add("hidden");
                }, 400);
            } else {
                legend.classList.add("hidden");
            }
        }
    </script>

</body>

</html>