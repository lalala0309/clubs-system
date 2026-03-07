<?php
require_once '../config/pdo.php';
$stmt = $pdo->query("SELECT sportID, sport_name FROM sports ORDER BY sport_name");
$sports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Lịch Sân Cao Cấp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 10px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-[#0f172a] dark:text-gray-200 min-h-screen">

    <div class="fixed top-4 right-4 z-[100] flex items-center gap-3">
        <button id="themeToggle"
            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 dark:bg-gray-800 shadow-lg border border-blue-500 dark:border-gray-700 hover:scale-110 transition-all">
            <i id="themeIcon" class="bi bi-moon-stars-fill text-lg text-white"></i>
        </button>
    </div>

    <div id="scheduleWrapper" class="flex flex-col min-h-screen bg-white dark:bg-[#0b0f1a]">
        <div
            class="bg-blue-600 dark:bg-[#1e293b]/80 backdrop-blur-md border-b border-blue-700 dark:border-white/10 p-3 px-6 grid grid-cols-3 items-center sticky top-0 z-50 transition-colors duration-300">

            <div class="flex items-center gap-3">
                <select id="sportSelect"
                    class="bg-white/10 dark:bg-white/5 border border-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-2 rounded-lg focus:outline-none cursor-pointer hover:bg-white/20 transition-all">
                    <?php foreach ($sports as $sport): ?>
                        <option class="text-gray-900" value="<?= $sport['sportID'] ?>">
                            <?= htmlspecialchars($sport['sport_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="login.php"
                    class="flex items-center gap-2 bg-white text-blue-600 text-xs font-bold uppercase px-4 py-2 rounded-lg hover:bg-blue-50 transition-all shadow">
                    Đăng nhập
                </a>
            </div>

            <div class="flex justify-center">
                <div id="realTimeClock"
                    class="text-xl md:text-2xl font-black font-mono tracking-widest text-white dark:text-blue-400 bg-blue-700/50 dark:bg-blue-900/30 px-4 py-1 rounded-xl border border-blue-400/30 dark:border-blue-500/20 shadow-sm">
                    00:00:00
                </div>
            </div>

            <div class="flex justify-end"></div>
        </div>

        <div class="flex-grow overflow-auto custom-scrollbar">
            <table id="scheduleTable" class="w-full border-collapse text-sm"></table>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;
        const sportSelect = document.getElementById("sportSelect");

        // --- THEME LOGIC ---
        function updateThemeIcon() {
            if (html.classList.contains('dark')) {
                themeIcon.className = 'bi bi-sun-fill text-lg text-white';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill text-lg text-white';
            }
        }

        if (localStorage.getItem('theme') === 'light') {
            html.classList.remove('dark');
        }
        updateThemeIcon();

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            updateThemeIcon();
        });

        // --- FETCH & RENDER LOGIC ---
        function fetchSchedule(sportID) {
            if (!sportID) return;
            fetch("get_public_schedule.php?sportID=" + sportID)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                });
        }

        // Tự động tải khi đổi môn
        sportSelect.addEventListener("change", function () {
            fetchSchedule(this.value);
        });

        // Tự động tải lần đầu tiên khi trang web load
        window.addEventListener('DOMContentLoaded', () => {
            fetchSchedule(sportSelect.value);
        });

        function renderTable(data) {
            const table = document.getElementById("scheduleTable");
            table.innerHTML = "";

            if (!data.grounds || !data.grounds.length) {
                table.innerHTML = "<tr><td class='p-20 text-center text-gray-500'>Không tìm thấy dữ liệu sân.</td></tr>";
                return;
            }

            let header = `<thead class="bg-gray-100 dark:bg-[#1e293b] text-blue-600 dark:text-blue-400 uppercase text-[11px] tracking-widest sticky top-0 z-20"><tr>
        <th class="border-b border-r border-gray-200 dark:border-white/5 p-5 sticky left-0 bg-gray-100 dark:bg-[#1e293b] z-30 w-32 shadow-sm">Thời gian</th>`;

            data.grounds.forEach(g => {
                header += `<th class="border-b border-r border-gray-200 dark:border-white/5 p-5 min-w-[200px] font-extrabold text-gray-800 dark:text-white text-center">${g.name}</th>`;
            });
            header += "</tr></thead><tbody>";

            data.slots.forEach((slot, index) => {
                let rowBg = index % 2 === 0 ? 'bg-white dark:bg-[#0f172a]' : 'bg-gray-50/50 dark:bg-[#131c2f]';
                let timeCellBg = index % 2 === 0 ? 'bg-white dark:bg-[#0f172a]' : 'bg-[#fcfcfd] dark:bg-[#0f172a]';

                if (index === 0) {
                    rowBg = 'bg-blue-50/60 dark:bg-blue-600/10 shadow-[inset_0_0_20px_rgba(59,130,246,0.1)]';
                    timeCellBg = 'bg-blue-100/50 dark:bg-blue-900/40';
                }

                let row = `<tr class="${rowBg} hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200">
            <td class="whitespace-nowrap border-r border-b border-gray-200 dark:border-white/5 p-5 font-bold text-center text-blue-600 dark:text-blue-400 sticky left-0 ${timeCellBg} z-10 shadow-sm">
                ${slot.label}
                ${index === 0 ? '<span class="block text-[9px] uppercase mt-1 opacity-70">Hiện tại</span>' : ''}
            </td>`;

                data.grounds.forEach(g => {
                    const booking = data.bookings.find(b => b.groundID == g.groundID && b.start_time === slot.start);
                    if (booking) {
                        row += `
                    <td class="border-r border-b border-gray-200 dark:border-white/5 p-2">
                        <div class="bg-blue-600 text-white border border-blue-400/20 py-3 px-3 rounded-xl flex flex-col items-center shadow-md transform scale-[1.02]">
                            <span class="font-bold text-center leading-tight mb-1">${booking.full_name}</span>
                            <span class="text-[9px] opacity-90 font-mono truncate w-full text-center">${booking.email}</span>
                        </div>
                    </td>`;
                    } else {
                        row += `
                    <td class="border-r border-b border-gray-200 dark:border-white/5 p-2">
                        <div class="py-4 flex justify-center items-center">
                            <span class="text-[12px] font-black uppercase tracking-tighter ${index === 0 ? 'text-blue-400 dark:text-blue-500' : 'text-gray-300 dark:text-gray-700'}">Trống</span>
                        </div>
                    </td>`;
                    }
                });
                row += "</tr>";
                header += row;
            });
            table.innerHTML = header + "</tbody>";
        }

        // --- LOGIC ĐỒNG HỒ ĐIỆN TỬ ---
        function updateClock() {
            const clockElement = document.getElementById('realTimeClock');
            if (!clockElement) return;

            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const isVisible = now.getMilliseconds() < 500;
            const opacity = isVisible ? '1' : '0.2';
            const sep = `<span style="opacity: ${opacity}; transition: opacity 0.1s;">:</span>`;

            clockElement.innerHTML = `${hours}${sep}${minutes}${sep}${seconds}`;
        }

        setInterval(updateClock, 100);
        updateClock();
    </script>
</body>

</html>