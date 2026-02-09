<?php
session_start();
require_once '../includes/get_user.php';
// require_once './get_my_grounds.php';
require_once './get_all_sports.php';


$week_offset = isset($_GET['week_offset']) ? (int) $_GET['week_offset'] : 0;
// Lấy sportID từ URL để giữ trạng thái sau khi load lại trang
$active_sport_id = isset($_GET['sportID']) ? (int) $_GET['sportID'] : null;

// Lấy thứ 2 của tuần hiện tại
$monday = new DateTime();
$monday->modify('monday this week');

// Cộng / trừ tuần
if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

$days = [];
$dayLabels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];

for ($i = 0; $i < 7; $i++) {
    $days[] = [$dayLabels[$i], $monday->format('d/m')];
    $monday->modify('+1 day');
}

$week_range = $days[0][1] . ' - ' . $days[6][1];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sân - CTUMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar_member.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        #right-panel {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0;
            opacity: 0;
            pointer-events: none;
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

        .grid-table {
            border-collapse: collapse;
            min-width: 100%;
            table-layout: fixed;
        }

        .grid-table th,
        .grid-table td {
            border: 1px solid #e2e8f0;
        }

        .grid-table th,
        .grid-table td {
            width: calc(100% / 8);
            /* 1 cột giờ + 7 ngày */
        }

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

        /* 
        .cell-booked {
            font-size: 8px;
            line-height: 1.1;
            padding: 2px;
        } */

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


        @media (max-width: 1024px) {
            #right-panel.active {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                z-index: 60;
                width: 100%;
            }

            body:has(#view-timetable:not(.view-hidden)) header,
            body:has(#view-timetable:not(.view-hidden)) button[onclick="toggleSidebar()"] {
                display: none !important;
            }

            body:has(#view-timetable:not(.view-hidden)) #main-sidebar {
                transform: translateX(-100%) !important;
            }
        }

        @media (max-width: 768px) {
            .grid-table {
                font-size: 7px;
            }

            .grid-table th:first-child,
            .time-cell {
                width: 32px !important;
                min-width: 32px !important;
                padding: 0 !important;
            }

            .time-cell {
                height: 28px !important;
                line-height: 28px !important;
                font-size: 6px !important;
            }

            .grid-cell {
                min-width: 34px !important;
                height: 30px !important;
            }

            .grid-table th div:first-child {
                font-size: 6px !important;
            }

            .grid-table th div:last-child {
                font-size: 7px !important;
            }



        }

        #lock-panel {
            width: 300px;
            opacity: 0;
            pointer-events: none;
            transform: translateX(40px);
            transition: all .3s ease;
        }

        #lock-panel.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        /* LOCK SLOT */
        .cell-locked {
            background: #fef2f2 !important;
            /* xám đậm */
            cursor: not-allowed !important;
            position: relative;
            color: white;
            font-weight: bold;
        }

        .cell-locked:hover {
            background: #9ca3af !important;
            outline: none;
        }
    </style>
</head>
<!-- ADD GROUND MODAL -->
<div id="add-ground-modal"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl p-6 space-y-4 shadow-xl">

        <h3 class="font-bold text-indigo-600 text-lg">Thêm sân mới</h3>

        <input id="ground-name" placeholder="Tên sân"
            class="w-full p-3 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500">

        <input id="ground-location" placeholder="Vị trí (tuỳ chọn)"
            class="w-full p-3 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500">

        <div class="flex justify-end gap-2">
            <button onclick="closeAddGroundModal()" class="px-4 py-2 text-slate-500">Huỷ</button>

            <button onclick="submitAddGround()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold">
                Thêm
            </button>
        </div>

    </div>
</div>

<!-- SETTING MODAL -->
<div id="setting-modal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl p-6 space-y-4 shadow-xl">

        <h3 class="font-bold text-slate-700 text-lg">
            <i class="bi bi-gear text-[14px] p-2"></i>Cài đặt hệ thống
        </h3>

        <label class="text-sm font-semibold">Giới hạn đặt sân / tuần / môn</label>

        <input id="weekly-limit-input" type="number" min="1"
            class="w-full p-3 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500">

        <div class="flex justify-end gap-2">
            <button onclick="closeSettingModal()" class="px-4 py-2 text-slate-500">Huỷ</button>

            <button onclick="saveSetting()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold">
                Lưu
            </button>
        </div>

    </div>
</div>

<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4">
    <div id="sidebar-overlay" onclick="toggleSidebar()"
        class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">
        <?php include '../includes/sidebar_manager.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">

            <div class="flex-shrink-0 flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-indigo-600">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>

            <div class="flex-1 relative overflow-hidden">
                <div id="view-clubs"
                    class="absolute inset-0 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[30px] md:rounded-[45px] p-4 md:p-8 border border-white">
                    <div class="mb-5 md:mb-6">
                        <div class="flex items-end justify-between">
                            <div>
                                <h2
                                    class="text-base text-lg md:text-2xl font-black text-slate-800 tracking-tight uppercase">
                                    Quản lý lịch đặt sân</h2>
                                <p class="text-[11px] md:text-[13px] text-slate-400 mt-0.5 font-medium">Vui lòng chọn
                                </p>
                            </div>
                        </div>
                        <div class="h-[2px] w-full bg-slate-200 mt-4 relative">
                            <div class="absolute left-0 top-0 h-full w-20 bg-indigo-500"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <?php foreach ($sports as $sport): ?>
                            <div onclick="openTimetable(<?php echo $sport['sportID']; ?>, '<?php echo $sport['sport_name']; ?>')"
                                data-sport-id="<?php echo $sport['sportID']; ?>"
                                class="club-card p-2 md:p-5 flex items-center justify-between shadow-sm hover:shadow-md cursor-pointer group bg-white rounded-[20px] md:rounded-[25px] transition-all">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl transition-transform group-hover:scale-110">
                                        <i class="bi bi-dribbble text-indigo-500 text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1">Sân
                                            <?php echo htmlspecialchars($sport['sport_name']); ?>
                                        </h3>
                                        <p class="text-[11px] text-indigo-500 font-medium leading-tight">Các sân thuộc
                                            <?php echo htmlspecialchars($sport['sport_name']); ?>
                                        </p>
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
                    class="view-hidden absolute inset-0 flex flex-col overflow-hidden bg-white/70 backdrop-blur-sm rounded-[30px] border border-white">
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-white/50">
                        <div class="flex items-center gap-3">
                            <button onclick="backToClubs()"
                                class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all border border-slate-100">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </button>
                            <div>
                                <h2 id="current-club-title"
                                    class="text-xs font-black text-indigo-700 uppercase leading-none">SÂN</h2>
                                <p id="selected-court-label"
                                    class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">SÂN SỐ 01
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">

                            <!-- Chuyển tuần -->
                            <div
                                class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm">
                                <button onclick="changeWeek(<?php echo $week_offset - 1; ?>)"
                                    class="hover:text-indigo-600 px-1">
                                    <i class="bi bi-caret-left-fill text-[10px]"></i>
                                </button>

                                <span class="text-[9px] font-bold text-slate-500 uppercase">
                                    <?php echo $week_range; ?>
                                </span>

                                <button onclick="changeWeek(<?php echo $week_offset + 1; ?>)"
                                    class="hover:text-indigo-600 px-1">
                                    <i class="bi bi-caret-right-fill text-[10px]"></i>
                                </button>
                            </div>

                            <div class="flex items-center gap-2">

                                <!-- Thêm sân -->
                                <button onclick="openAddGroundModal()"
                                    class="flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase shadow-sm hover:bg-indigo-700 transition">
                                    <i class="bi bi-plus-lg text-[10px]"></i>
                                    Thêm sân
                                </button>


                                <!-- Cài đặt -->
                                <button onclick="openSettingModal()"
                                    class="flex items-center gap-1 bg-slate-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase shadow-sm hover:bg-slate-800 transition">
                                    <i class="bi bi-gear text-[10px]"></i>
                                    Cài đặt
                                </button>
                                <!-- Khoá sân (admin) -->
                                <button onclick="openLockPanelManual()"
                                    class="flex items-center gap-1 bg-red-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase shadow-sm hover:bg-red-700 transition">
                                    <i class="bi bi-lock-fill text-[10px]"></i>
                                    Khoá sân
                                </button>


                            </div>


                        </div>

                    </div>

                    <div class="flex flex-1 overflow-hidden">
                        <div class="w-12 border-r border-slate-100 flex flex-col items-center py-4 gap-2 bg-slate-50/30 overflow-y-auto custom-scroll"
                            id="court-tabs-container">
                        </div>

                        <div class="flex-1 overflow-auto custom-scroll">
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
                                                <div class="text-[9px] text-slate-600 font-bold">
                                                    <?php echo $d[1]; ?>
                                                </div>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $timeRanges = ["06:00-07:00", "07:00-08:00", "08:00-09:00", "09:00-10:00", "13:00-14:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00", "18:00-19:00", "19:00-20:00", "20:00-21:00"];
                                    foreach ($timeRanges as $range):
                                        ?>
                                        <tr>
                                            <td class="time-cell text-center bg-[#FDFDFF] px-1">
                                                <span class="text-[9px] font-bold text-slate-500 tracking-tighter">
                                                    <?php echo $range; ?>
                                                </span>
                                            </td>
                                            <?php for ($i = 0; $i < 7; $i++): ?>
                                                <td class="grid-cell cell-available" data-day="<?php echo $days[$i][1]; ?>"
                                                    data-time="<?php echo $range; ?>">
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
        <aside id="lock-panel"
            class="bg-white flex flex-col shrink-0 shadow-2xl rounded-l-[30px] border-l border-slate-100 hidden">

            <div class="p-6 space-y-4">

                <h3 class="font-bold text-slate-700 uppercase text-xs">
                    Khoá sân nhiều ngày
                </h3>

                <!-- FROM -->
                <div>
                    <label class="text-xs font-semibold text-slate-500">Từ</label>
                    <input type="datetime-local" id="lock-from" class="w-full p-2 bg-slate-50 rounded-xl">
                </div>

                <!-- TO -->
                <div>
                    <label class="text-xs font-semibold text-slate-500">Đến</label>
                    <input type="datetime-local" id="lock-to" class="w-full p-2 bg-slate-50 rounded-xl">
                </div>

                <!-- MULTI GROUND -->
                <div>
                    <label class="text-xs font-semibold text-slate-500">Chọn sân</label>
                    <div id="lock-ground-list"
                        class="max-h-32 overflow-auto bg-slate-50 rounded-xl p-2 space-y-1 text-xs">
                    </div>
                </div>

                <button onclick="submitLock()" class="w-full py-2 bg-red-600 text-white rounded-xl font-bold">
                    Khoá
                </button>

                <button onclick="closeLockPanel()" class="text-xs text-slate-400">
                    Huỷ
                </button>

            </div>
        </aside>

        <!-- <aside id="right-panel"
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
        </aside> -->
    </div>

    <script>
        let currentCourtName = "Sân số 01";
        let selectedBooking = { groundID: null, booking_date: null, start_time: null, end_time: null };
        let currentSportID = <?php echo $active_sport_id ?? 'null'; ?>;

        function openTimetable(sportID, sportName) {
            currentSportID = sportID;
            document.getElementById('current-club-title').innerText = 'Sân ' + sportName;
            document.getElementById('view-clubs').classList.add('view-hidden');
            document.getElementById('view-timetable').classList.remove('view-hidden');

            fetch(`get_all_grounds_by_sport.php?sportID=${sportID}`)
                .then(res => res.json())
                .then(data => renderCourts(data));
        }

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

            renderLockGroundList(grounds);

            const targetBtn = savedGroundID
                ? container.querySelector(`[data-ground-id="${savedGroundID}"]`)
                : container.querySelector('.court-tab');

            if (targetBtn) {
                const idx = targetBtn.innerText.replace('S', '');
                changeCourt(parseInt(idx), targetBtn);

                selectedBooking.groundID = targetBtn.dataset.groundId;
            }
            sessionStorage.removeItem('reopen_ground_id');
        }

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
            });
            // document.querySelectorAll('.cell-available').forEach(cell => {
            //     cell.onclick = () => openLockPanel(cell);
            // });

            fetch(`get_booked_slots.php?groundID=${groundID}&week_offset=<?php echo $week_offset; ?>`)
                .then(res => res.json())
                .then(data => {
                    markBookedSlots(data);
                    disablePastSlots();

                    return fetch(`get_locked_slots.php?groundID=${groundID}&week_offset=<?php echo $week_offset; ?>`);
                })
                .then(r => r.json())
                .then(markLockedSlots);


        }

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

        function openPanel(time, date) {
            const [start, end] = time.split('-');
            selectedBooking.start_time = start;
            selectedBooking.end_time = end;
            selectedBooking.booking_date = date.split(' - ')[1];

            document.getElementById('info-time').innerText = time;
            document.getElementById('info-date').innerText = date;
            document.getElementById('info-court').innerText = currentCourtName;
            document.getElementById('right-panel').classList.add('active');
        }

        function closePanel() { document.getElementById('right-panel').classList.remove('active'); }

        // function backToClubs() {
        //     document.getElementById('view-timetable').classList.add('view-hidden');
        //     document.getElementById('view-clubs').classList.remove('view-hidden');
        //     // Xóa sportID trên URL khi quay lại
        //     const url = new URL(window.location.href);
        //     url.searchParams.delete('sportID');
        //     window.history.replaceState({}, '', url.href);
        //     currentSportID = null;
        //     sessionStorage.clear();
        //     closePanel();
        // }

        const confirmBtn = document.getElementById('btn-confirm');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                if (!selectedBooking.groundID) return alert('Lỗi: Chưa chọn sân');

                const formData = new FormData();
                for (let k in selectedBooking) formData.append(k, selectedBooking[k]);

                fetch('./handle_booking.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Đặt sân thành công');
                            closePanel();
                            const activeBtn = document.querySelector('.court-tab.active');
                            if (activeBtn) changeCourt(parseInt(activeBtn.innerText.replace('S', '')), activeBtn);
                        } else alert(data.message);
                    });
            });
        }


        function markBookedSlots(bookings) {
            bookings.forEach(b => {
                document.querySelectorAll('.grid-cell').forEach(cell => {
                    if (cell.dataset.day === b.booking_date && cell.dataset.time === `${b.start_time}-${b.end_time}`) {
                        cell.classList.remove('cell-available');
                        cell.classList.add('cell-booked');
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

        function disablePastSlots() {
            const now = new Date();

            // Giới hạn đặt trước tối đa 14 ngày
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

        /* ===============================
   ADD GROUND
================================ */

        function openAddGroundModal() {
            if (!currentSportID) return alert("Chọn môn trước");
            document.getElementById('add-ground-modal').classList.remove('hidden');
        }

        function closeAddGroundModal() {
            document.getElementById('add-ground-modal').classList.add('hidden');
        }

        function submitAddGround() {

            const name = document.getElementById('ground-name').value.trim();
            const location = document.getElementById('ground-location').value.trim();

            if (!name) return alert("Nhập tên sân");

            const formData = new FormData();
            formData.append('name', name);
            formData.append('location', location);
            formData.append('sportID', currentSportID);

            fetch('./handle_add_ground.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {

                        closeAddGroundModal();

                        // reload lại danh sách sân
                        fetch(`get_all_grounds_by_sport.php?sportID=${currentSportID}`)

                            .then(res => res.json())
                            .then(data => renderCourts(data));

                    } else {
                        alert(data.message);
                    }
                });
        }
        /* ===============================
           SETTING
        ================================ */

        function openSettingModal() {

            fetch('get_setting.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('weekly-limit-input').value = data.limit;
                    document.getElementById('setting-modal').classList.remove('hidden');
                });
        }

        function closeSettingModal() {
            document.getElementById('setting-modal').classList.add('hidden');
        }

        function saveSetting() {

            const limit = document.getElementById('weekly-limit-input').value;

            const formData = new FormData();
            formData.append('limit', limit);

            fetch('update_setting.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    closeSettingModal();
                });
        }
        function closeLockPanel() {
            const panel = document.getElementById('lock-panel');
            panel.classList.remove('show');
            setTimeout(() => panel.classList.add('hidden'), 300);
        }

        function submitLock() {

            const from = document.getElementById('lock-from').value;
            const to = document.getElementById('lock-to').value;

            if (!from || !to) return alert("Chọn thời gian");

            const checked = document.querySelectorAll('.lock-ground-checkbox:checked');

            if (checked.length === 0) return alert("Chọn ít nhất 1 sân");

            const formData = new FormData();

            formData.append('from', from);
            formData.append('to', to);

            checked.forEach(c => {
                formData.append('grounds[]', c.value);
            });

            fetch('./handle_lock_ground.php', {
                method: 'POST',
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {

                        alert("Khoá sân thành công");
                        closeLockPanel();
                        reloadCurrentGround();
                    } else {
                        alert(data.message);
                    }
                });
        }

        function reloadCurrentGround() {
            const activeBtn = document.querySelector('.court-tab.active');
            if (activeBtn) changeCourt(parseInt(activeBtn.innerText.replace('S', '')), activeBtn);
        }

        function toMin(t) {
            const [h, m] = t.split(':');
            return +h * 60 + +m;
        }


        function markLockedSlots(locks) {

            locks.forEach(l => {
                document.querySelectorAll('.grid-cell').forEach(cell => {

                    if (cell.dataset.day === formatVNDate(l.lock_date)) {

                        const time = cell.dataset.time;
                        const [s, e] = time.split('-');

                        const lockStart = l.start_time.slice(0, 5);
                        const lockEnd = l.end_time.slice(0, 5);

                        if (
                            toMin(s) >= toMin(lockStart) &&
                            toMin(e) <= toMin(lockEnd)
                        ) {

                            cell.classList.remove('cell-available');
                            cell.classList.remove('cell-past');
                            cell.classList.add('cell-locked');

                            cell.innerHTML = `
                    <div class="cell-content text-red-500 text-[11px]">
                    <i class="bi bi-lock-fill text-[12px]"></i>

                    </div>
                `;
                            cell.onclick = null;
                        }
                    }
                });
            });
        }

        function formatVNDate(date) {
            const [y, m, d] = date.split('-');
            return `${d.padStart(2, '0')}/${m.padStart(2, '0')}`;
        }



        function renderLockGroundList(grounds) {
            const list = document.getElementById('lock-ground-list');
            list.innerHTML = '';

            grounds.forEach(g => {
                list.innerHTML += `
            <label class="flex items-center gap-2">
                <input type="checkbox" value="${g.groundID}" class="lock-ground-checkbox">
                <span>${g.name}</span>
            </label>
        `;
            });
        }


        function openLockPanelManual() {

            const now = new Date();

            const from = now.toISOString().slice(0, 16);

            now.setHours(now.getHours() + 1);
            const to = now.toISOString().slice(0, 16);

            document.getElementById('lock-from').value = from;
            document.getElementById('lock-to').value = to;

            const panel = document.getElementById('lock-panel');

            panel.classList.remove('hidden');
            setTimeout(() => panel.classList.add('show'), 10);
        }






    </script>
</body>

</html>