<?php
session_start();
require_once '../includes/get_user.php';
require_once './get_my_grounds.php';
require_once './get_sports.php';


$days = [
    ['Thứ 2', '19/01'], ['Thứ 3', '20/01'], ['Thứ 4', '21/01'], 
    ['Thứ 5', '22/01'], ['Thứ 6', '23/01'], ['Thứ 7', '24/01'], ['CN', '25/01']
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Đặt sân - CTUMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar_member.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        #right-panel {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0; opacity: 0; pointer-events: none;
        }
        #right-panel.active { width: 300px; opacity: 1; pointer-events: auto; }
        .view-hidden { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }

        /* Kẻ bảng rõ hơn và thu hẹp độ rộng */
        .grid-table { border-collapse: collapse; min-width: 100%; table-layout: fixed; }
        .grid-table th, .grid-table td { border: 1px solid #e2e8f0; }

        .grid-cell {
            height: 38px; 
            transition: all 0.15s;
            /* Độ rộng ô cực gọn */
            min-width: 55px; 
        }
        .cell-available { background: white; cursor: pointer; }
        .cell-available:hover { background: #f5f8ff; outline: 2px solid #6366f1; outline-offset: -2px; z-index: 10; }
        .cell-booked {
    background: #fee2e2;
    color: #ef4444;
    cursor: not-allowed;
    text-align: center;
    line-height: 38px;   /* căn giữa dọc */
}

        .court-tab.active {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .time-cell {
    height: 38px;
    white-space: nowrap;   
    line-height: 38px;     /* căn giữa dọc */
    padding: 0;
}

.grid-table tr {
    height: 38px;
}

.grid-table td {
    vertical-align: middle;
}


    </style>
</head>
<body class="bg-[#F8FAFF] min-h-screen p-4 overflow-x-hidden">

    <div class="flex h-[calc(100vh-2rem)] gap-4">
        
        <?php if(file_exists('../includes/sidebar_member.php')) include '../includes/sidebar_member.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden">
            
            <?php if(file_exists('../includes/header.php')) include '../includes/header.php'; ?>

            <div id="view-clubs" class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[45px] p-8 border border-white mt-3 custom-scroll">
                <div class="mb-6"> 
                    <div class="flex items-end justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase leading-none">Đặt sân</h2>
                            <p class="text-[13px] text-slate-400 mt-2 font-medium">Vui lòng chọn loại sân bạn muốn sử dụng</p>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-slate-200 mt-4 relative">
                        <div class="absolute left-0 top-0 h-full w-20 bg-indigo-500"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

<?php foreach ($sports as $sport): ?>
    <div onclick="openTimetable(<?php echo $sport['sportID']; ?>, '<?php echo $sport['sport_name']; ?>')"
         class="group relative bg-white p-5 flex items-center justify-between rounded-[25px]
                border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-300 cursor-pointer">

        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                <i class="bi bi-dribbble text-indigo-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Sân <?php echo htmlspecialchars($sport['sport_name']); ?>
                </h3>
                <p class="text-sm text-indigo-500 font-semibold">
                    Các sân thuộc CLB bạn tham gia
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

            <div id="view-timetable" class="view-hidden flex flex-col h-full overflow-hidden bg-white/70 backdrop-blur-sm rounded-[30px] border border-white mt-3">
                <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-white/50">
                    <div class="flex items-center gap-3">
                        <button onclick="backToClubs()" class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all border border-slate-100">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </button>
                        <div>
                            <h2 id="current-club-title" class="text-xs font-black text-indigo-700 uppercase leading-none">ĐẶT SÂN</h2>
                            <p id="selected-court-label" class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">SÂN SỐ 01</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm">
                        <button class="hover:text-indigo-600"><i class="bi bi-caret-left-fill text-[10px]"></i></button>
                        <span class="text-[9px] font-bold text-slate-500 uppercase">19/01 - 25/01</span>
                        <button class="hover:text-indigo-600"><i class="bi bi-caret-right-fill text-[10px]"></i></button>
                    </div>
                </div>

                <div class="flex flex-1 overflow-hidden">
                    <div class="w-12 border-r border-slate-100 flex flex-col items-center py-4 gap-2 bg-slate-50/30 overflow-y-auto custom-scroll">
                        <?php for($s=1; $s<=5; $s++): ?>
                        <button onclick="changeCourt(<?php echo $s; ?>)" 
                                class="court-tab w-8 h-8 rounded-md border border-white bg-white shadow-sm text-[9px] font-black text-slate-400 hover:border-indigo-200 transition-all <?php echo $s==1 ? 'active' : ''; ?>">
                            S<?php echo $s; ?>
                        </button>
                        <?php endfor; ?>
                    </div>

                    <div class="flex-1 overflow-auto custom-scroll">
                        <table class="grid-table">
                            <thead class="sticky top-0 z-20 bg-slate-50">
                                <tr>
                                    <th class="p-2 text-[8px] font-black text-slate-400 uppercase w-20 bg-slate-100/50">Giờ</th>
                                    <?php foreach($days as $d): ?>
                                    <th class="p-1">
                                        <div class="text-[7px] text-indigo-500 font-black uppercase leading-none"><?php echo $d[0]; ?></div>
                                        <div class="text-[9px] text-slate-600 font-bold"><?php echo $d[1]; ?></div>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                               $timeRanges = [
                                "06:00-07:00",
                                "07:00-08:00",
                                "08:00-09:00",
                                "09:00-10:00",
                                "13:00-14:00",
                                "14:00-15:00",
                                "15:00-16:00",
                                "16:00-17:00",
                                "17:00-18:00",
                                "18:00-19:00",
                                "19:00-20:00",
                                "20:00-21:00"
                            ];
                            
                            foreach ($timeRanges as $range):
                            
                                ?>
                                <tr>
                                    <td class="time-cell text-center bg-[#FDFDFF] px-1">
                                        <span class="text-[9px] font-bold text-slate-500 tracking-tighter"><?php echo $range; ?></span>
                                    </td>
                                    <?php for($i=0; $i<7; $i++): ?>
                                        <td class="grid-cell cell-available"
    data-day="<?php echo $days[$i][1]; ?>"
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
        </main>

        <aside id="right-panel" class="bg-white flex flex-col shrink-0 shadow-2xl rounded-l-[30px] border-l border-indigo-50">
            <div class="p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xs font-black text-indigo-600 uppercase tracking-widest">Xác nhận đặt sân</h2>
                    <button onclick="closePanel()" class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400"><i class="bi bi-x-lg text-xs"></i></button>
                </div>
                
                <div class="flex-1 space-y-4">
                    <div class="p-6 bg-indigo-50 rounded-[25px] border border-indigo-100 text-center">
                        <div class="text-indigo-600 font-black text-xl" id="info-time">00:00-00:00</div>
                        <div class="text-slate-500 font-bold text-[10px] uppercase mt-1 tracking-widest" id="info-date">Thứ ...</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-500">
                             <i class="bi bi-geo-alt-fill text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 tracking-tight uppercase italic" id="info-court">Sân số 01</span>
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
        let currentCourtName = "Sân số 01";
        let selectedBooking = {
            groundID: null,
            booking_date: null,
            start_time: null,
            end_time: null
        };
        function openTimetable(sportID, sportName) {
                    document.getElementById('current-club-title').innerText = 'Đặt sân ' + sportName;
                    document.getElementById('view-clubs').classList.add('view-hidden');
                    document.getElementById('view-timetable').classList.remove('view-hidden');

                    fetch(`get_grounds_by_sport.php?sportID=${sportID}&userID=<?php echo $userID; ?>`)
                        .then(res => res.json())
                        .then(data => renderCourts(data));
        }
        function renderCourts(grounds) {
    const container = document.querySelector('.court-tab').parentElement;
    container.innerHTML = '';

    grounds.forEach((g, i) => {
        container.innerHTML += `
            <button 
                data-ground-id="${g.groundID}"
                onclick="changeCourt(${i+1}, this)"
                class="court-tab w-8 h-8 rounded-md border bg-white text-[9px] font-black ${i==0?'active':''}">
                S${i+1}
            </button>
        `;
    });

    //  TỰ ĐỘNG LOAD BOOKING CHO SÂN ĐẦU TIÊN
    const firstBtn = container.querySelector('.court-tab');
    if (firstBtn) {
        changeCourt(1, firstBtn);
    }
}




        function backToClubs() {
            document.getElementById('view-timetable').classList.add('view-hidden');
            document.getElementById('view-clubs').classList.remove('view-hidden');
            closePanel();
        }
        function changeCourt(index, btn) {
    const label = index < 10 ? '0' + index : index;
    currentCourtName = 'Sân số ' + label;

    document.getElementById('selected-court-label').innerText = currentCourtName;

    document.querySelectorAll('.court-tab').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');

    const groundID = btn.dataset.groundId;

    // reset bảng
    document.querySelectorAll('.grid-cell').forEach(cell => {
        cell.classList.remove('cell-booked');
        cell.classList.add('cell-available');
        cell.innerHTML = '';
    });

    fetch(`get_booked_slots.php?groundID=${groundID}`)
        .then(res => res.json())
        .then(data => markBookedSlots(data));
}



function openPanel(time, date) {
    const [start, end] = time.split('-');

    selectedBooking.start_time = start;
    selectedBooking.end_time = end;
    selectedBooking.booking_date = date.split(' - ')[1]; // 19/01
    selectedBooking.groundID = document.querySelector('.court-tab.active')
        ?.getAttribute('data-ground-id');

    document.getElementById('info-time').innerText = time;
    document.getElementById('info-date').innerText = date;
    document.getElementById('info-court').innerText = currentCourtName;
    document.getElementById('right-panel').classList.add('active');
}


        function closePanel() { 
            document.getElementById('right-panel').classList.remove('active'); 
        }

        document.getElementById('btn-confirm').addEventListener('click', () => {
    if (!selectedBooking.groundID) {
        alert('Vui lòng chọn sân');
        return;
    }

    const formData = new FormData();
    for (let k in selectedBooking) {
        formData.append(k, selectedBooking[k]);
    }

    fetch('./handle_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
    if (data.status === 'success') {
        alert('Đặt sân thành công');
        closePanel();

        // reload lại lịch của sân đang chọn
        const activeBtn = document.querySelector('.court-tab.active');
        const index = activeBtn.innerText.replace('S', '');
        changeCourt(index, activeBtn);
    } else {
        alert('' + data.message);
    }
});

});

function markBookedSlots(bookings) {
    bookings.forEach(b => {
        document.querySelectorAll('.grid-cell').forEach(cell => {
            if (
                cell.dataset.day === b.booking_date &&
                cell.dataset.time === `${b.start_time}-${b.end_time}`
            ) {
                cell.classList.remove('cell-available');
                cell.classList.add('cell-booked');
                cell.innerHTML = '<i class="bi bi-lock-fill text-[9px] opacity-40"></i>';
                // cell.onclick = null;
            }
        });
    });
}

    </script>
</body>
</html>