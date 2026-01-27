<?php
session_start();
require_once '../manager/get_all_clubs.php';
require_once '../manager_sport/get_pending_request.php';
$clubs = require '../manager_sport/get_all_clubs_manager.php';
$sports = require '../manager_sport/get_all_sports.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTUMP Clubs - Admin Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/sidebar_member.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-item-active {
            background-color: #3f51b5;
            color: white;
            border-radius: 12px;
        }

        .club-card.hidden-club {
            display: none;
        }

        .court-input:checked+.court-card {
            border-color: #4F46E5;
            background-color: #f5f3ff;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.15);
        }

        .court-input:checked+.court-card .status-icon {
            background-color: #4F46E5 !important;
            color: white !important;
        }

        .active-menu {
            background: linear-gradient(135deg, #4F46E5 0%, #2A53A2 100%);
            color: white !important;
            border-radius: 24px;
        }

        #right-panel {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0;
            opacity: 0;
            overflow: hidden;
        }

        #right-panel.active {
            width: 400px;
            opacity: 1;
        }

        /* Hiệu ứng trượt cho Form bên phải */
        #add-club-panel {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(100%);
        }

        #add-club-panel.open {
            transform: translateX(0);
        }

        #overlay {
            display: none;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }

        #overlay.active {
            display: block;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4">

    <div id="overlay" class="fixed inset-0 z-40" onclick="toggleAddClubForm()"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">

        <?php include '../includes/sidebar_manager.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-indigo-600">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>

            <div
                class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[30px] md:rounded-[45px] p-4 md:p-8 border border-white">
                <div class="mb-5 md:mb-6">
                    <div class="flex items-end justify-between">
                        <h2 id="page-title" class="text-2xl font-bold text-slate-800">Danh sách câu lạc bộ</h2>

                        <div class="flex items-center gap-3">
                            <button id="back-btn" onclick="showAllClubs()"
                                class="hidden flex items-center gap-2 text-indigo-600 font-bold hover:bg-indigo-50 px-4 py-2 rounded-xl transition">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button id="add-club-btn" onclick="toggleAddClubForm()"
                                class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition flex items-center gap-2 shadow-lg shadow-indigo-100">
                                <i class="fas fa-plus"></i> Thêm câu lạc bộ
                            </button>
                            <button id="add-sport-btn" onclick="toggleSportPanel()"
                                class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 flex items-center gap-2">
                                <i class="fas fa-plus"></i> Môn thể thao
                            </button>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-slate-200 mt-3 md:mt-4 relative">
                        <div class="absolute left-0 top-0 h-full w-12 md:w-20 bg-indigo-500"></div>
                    </div>
                </div>
                <div id="club-container" class="space-y-4">
                    <?php
                    $clubs = getClubs($pdo);

                    foreach ($clubs as $club):
                        $clubID = $club['clubID'];
                        $members = getClubMembers($pdo, $clubID);
                        $memberCount = count($members);
                        $pendingRequests = getPendingRequests($pdo, $clubID);
                        $pendingCount = count($pendingRequests);

                        ?>
                        <div class="club-card bg-white border rounded-2xl overflow-hidden shadow-sm"
                            id="club-<?= $clubID ?>">

                            <!-- HEADER -->
                            <div class="club-header p-5 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition"
                                onclick="openClub('club-<?= $clubID ?>')">

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center">
                                        <i class="bi bi-people-fill text-2xl text-slate-700"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm md:text-lg font-bold text-slate-800">
                                            <?= htmlspecialchars($club['club_name']) ?>
                                        </h3>
                                        <p class="text-xs text-slate-500">
                                            <span class="member-count"><?= $memberCount ?></span> thành viên
                                            • <span class="header-pending-count"><?= $pendingCount ?></span> yêu cầu mới
                                        </p>

                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button onclick="event.stopPropagation(); toggleApprovals('approvals-<?= $clubID ?>');"
                                        class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-full border border-orange-100 hover:bg-orange-600 hover:text-white transition group">

                                        <i class="fas fa-user-clock"></i>
                                        <span class="text-xs font-bold uppercase">Phê duyệt</span>
                                        <span
                                            class="pending-badge bg-orange-600 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white group-hover:bg-white group-hover:text-orange-600">
                                            <?= $pendingCount ?>
                                        </span>


                                    </button>
                                    <button onclick="event.stopPropagation(); deleteClub(<?= $clubID ?>)"
                                        class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <i class="fas fa-chevron-right text-slate-400 chevron-icon"></i>
                                </div>

                                <!-- <i class="fas fa-chevron-right text-slate-400 chevron-icon"></i> -->
                            </div>

                            <!-- DETAILS -->
                            <div class="club-details hidden p-6 bg-slate-50/50 border-t">
                                <div id="approvals-<?= $clubID ?>" class="hidden mb-6 space-y-3">

                                    <h4 class="text-orange-700 font-bold text-xs uppercase tracking-widest">
                                        Yêu cầu chờ (<span class="pending-count"><?= $pendingCount ?></span>)
                                    </h4>


                                    <?php if (empty($pendingRequests)): ?>
                                        <div class="text-slate-400 text-sm italic">
                                            Không có yêu cầu nào
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($pendingRequests as $req): ?>
                                            <div
                                                class="pending-item bg-white p-4 rounded-xl border flex justify-between items-center hover:bg-slate-50 transition">

                                                <div>
                                                    <p class="font-bold text-slate-800">
                                                        <?= htmlspecialchars($req['full_name']) ?>
                                                    </p>
                                                    <p class="text-sm text-slate-500">
                                                        <?= htmlspecialchars($req['email']) ?>
                                                        • Gửi ngày:
                                                        <?= $req['request_date']
                                                            ? date('d/m/Y', strtotime($req['request_date']))
                                                            : '—' ?>

                                                    </p>
                                                </div>

                                                <button onclick="approveMember(<?= $clubID ?>, <?= $req['userID'] ?>, this)"
                                                    class="px-3 py-1 bg-green-600 text-white rounded">
                                                    Duyệt
                                                </button>



                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </div>

                                <h4 class="text-slate-700 font-bold text-xs uppercase mb-3">
                                    Danh sách thành viên
                                </h4>

                                <div class="bg-white rounded-xl border overflow-hidden">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-slate-50 border-b text-slate-600">
                                                <th class="p-3 text-left">Họ tên</th>
                                                <th class="p-3 text-left">Email</th>
                                                <th class="p-3 text-center">Ngày tham gia</th>
                                                <th class="p-3 text-center">Ngày đóng phí</th>
                                                <th class="p-3 text-center">Ngày hết hạn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($members)): ?>
                                                <tr>
                                                    <td colspan="5" class="p-4 text-center text-slate-400">
                                                        Chưa có thành viên
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($members as $m): ?>
                                                    <tr class="border-b hover:bg-slate-50 transition">
                                                        <td class="p-3 font-medium">
                                                            <?= htmlspecialchars($m['full_name']) ?>
                                                        </td>
                                                        <td class="p-3 text-slate-600">
                                                            <?= htmlspecialchars($m['email']) ?>
                                                        </td>
                                                        <td class="p-3 text-center">
                                                            <?= date('d/m/Y', strtotime($m['join_date'])) ?>
                                                        </td>
                                                        <td class="p-3 text-center">
                                                            <?= $m['fee_paid_date']
                                                                ? date('d/m/Y', strtotime($m['fee_paid_date']))
                                                                : '<span class="text-slate-400">Chưa đóng</span>' ?>
                                                        </td>
                                                        <td class="p-3 text-center font-semibold
                                            <?= $m['fee_expire_date'] && strtotime($m['fee_expire_date']) >= time()
                                                ? 'text-green-600'
                                                : 'text-red-500' ?>">
                                                            <?= $m['fee_expire_date']
                                                                ? date('d/m/Y', strtotime($m['fee_expire_date']))
                                                                : '—' ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <aside id="add-club-panel" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl z-50 flex flex-col">

            <div class="p-6 border-b flex justify-between items-center bg-indigo-50">
                <h3 class="font-bold text-indigo-900 uppercase tracking-wider">Thêm câu lạc bộ mới</h3>
                <button onclick="toggleAddClubForm()" class="text-slate-400 hover:text-red-500 transition"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form id="add-club-form" class="p-6 space-y-5">

                <!-- Tên CLB -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">
                        Tên câu lạc bộ
                    </label>
                    <input type="text" name="club_name" required
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Môn thể thao -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">
                        Môn thể thao
                    </label>
                    <select name="sport_id" required
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">

                        <option value="">-- Chọn môn thể thao --</option>
                        <?php foreach ($sports as $sport): ?>
                            <option value="<?= $sport['sportID'] ?>">
                                <?= htmlspecialchars($sport['sport_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl">
                    Lưu câu lạc bộ
                </button>

            </form>
        </aside>

        <aside id="sport-panel" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl z-50
                transform translate-x-full transition-all duration-300">

            <div class="p-6 border-b flex justify-between items-center bg-indigo-50">
                <h3 class="font-bold text-indigo-900 uppercase">Quản lý môn thể thao</h3>
                <button onclick="toggleSportPanel()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Thêm môn -->
            <div class="p-6 flex gap-2">
                <input id="sport-name" class="flex-1 border rounded-xl px-4 py-2" placeholder="Tên môn thể thao">
                <button onclick="addSport()" class="bg-indigo-600 text-white px-4 rounded-xl">
                    Thêm
                </button>
            </div>

            <!-- Danh sách -->
            <div id="sport-list" class="px-6 space-y-2 overflow-y-auto max-h-[calc(100vh-250px)] scrollbar-thin">
                <?php foreach ($sports as $sport): ?>
                    <div id="sport-item-<?= $sport['sportID'] ?>"
                        class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border">

                        <span id="sport-name-<?= $sport['sportID'] ?>">
                            <?= htmlspecialchars($sport['sport_name']) ?>
                        </span>

                        <div class="flex gap-3">
                            <button onclick="editSport(
                            <?= $sport['sportID'] ?>,
                            '<?= htmlspecialchars(addslashes($sport['sport_name'])) ?>'
                        )" class="text-indigo-600">
                                <i class="fas fa-pen"></i>
                            </button>

                            <button onclick="deleteSport(<?= $sport['sportID'] ?>)" class="text-red-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>
    <script>
        function toggleAddClubForm() {
            document.getElementById('add-club-panel').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('active');
        }

        function deleteClub(clubID) {
            if (!confirm('Bạn chắc chắn muốn xóa câu lạc bộ này?')) return;

            fetch('../manager_sport/delete_club.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'clubID=' + clubID
            })
                .then(r => r.json())
                .then(d => {
                    alert(d.message);
                    if (d.success) {
                        document.getElementById('club-' + clubID)?.remove();
                    }
                })
                .catch(() => {
                    alert('Lỗi kết nối server');
                });
        }
        function openClub(clubId) {
            const allCards = document.querySelectorAll('.club-card');
            allCards.forEach(card => {
                if (card.id === clubId) {
                    card.querySelector('.club-details').classList.remove('hidden');
                    card.querySelector('.chevron-icon')?.classList.add('hidden');
                    document.getElementById('page-title').innerText = "Quản lý thành viên";
                    card.querySelector('.club-header').onclick = null;
                    card.querySelector('.club-header').style.cursor = "default";
                } else {
                    card.classList.add('hidden-club');
                }
            });
            document.getElementById('back-btn').classList.remove('hidden');
            document.getElementById('add-club-btn').classList.add('hidden');
        }

        document.getElementById('add-club-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../manager_sport/add_club.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload(); // load lại để thấy CLB mới
                    }
                })
                .catch(() => {
                    alert('Có lỗi xảy ra');
                });
        });

        function showAllClubs() {
            const allCards = document.querySelectorAll('.club-card');
            allCards.forEach(card => {
                card.classList.remove('hidden-club');
                card.querySelector('.club-details').classList.add('hidden');
                card.querySelector('.chevron-icon')?.classList.remove('hidden');
                const id = card.id;
                const header = card.querySelector('.club-header');
                header.onclick = function () { openClub(id); };
                header.style.cursor = "pointer";
            });
            document.getElementById('back-btn').classList.add('hidden');
            document.getElementById('add-club-btn').classList.remove('hidden');
            document.getElementById('page-title').innerText = "Danh sách câu lạc bộ";
        }

        function toggleApprovals(id) {
            const section = document.getElementById(id);
            if (section) section.classList.toggle('hidden');
        }

        function toggleSportPanel() {
            document.getElementById('sport-panel')
                .classList.toggle('translate-x-full');
        }

        function addSport() {
            const input = document.getElementById('sport-name');
            const name = input.value.trim();

            if (!name) {
                alert('Nhập tên môn');
                input.focus();
                return;
            }

            fetch('../manager_sport/add_sport.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'sport_name=' + encodeURIComponent(name)
            })
                .then(r => r.json())
                .then(d => {

                    /* TRÙNG TÊN → XÓA INPUT */
                    if (!d.success && d.message.includes('tồn tại')) {
                        alert(d.message);
                        input.value = '';   // ← XÓA TÊN TRÙNG
                        input.focus();      // ← TRỎ CHUỘT QUAY LẠI
                        return;
                    }

                    /* THÀNH CÔNG */
                    if (d.success) {
                        alert(d.message);
                        const list = document.getElementById('sport-list');
                        const div = document.createElement('div');
                        div.id = 'sport-item-' + d.sportID;
                        div.className = 'flex justify-between items-center bg-slate-50 p-3 rounded-xl border';


                        div.innerHTML = `
                            <span id="sport-name-${d.sportID}">
                                ${name}
                            </span>
                            <div class="flex gap-3">
                                <button onclick="editSport(${d.sportID}, '${name.replace(/'/g, "\\'")}')" class="text-indigo-600">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button onclick="deleteSport(${d.sportID})" class="text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;

                        list.prepend(div); // thêm lên đầu danh sách

                        input.value = '';
                        input.focus();
                        return;
                    }



                    /* LỖI KHÁC */
                    alert(d.message);
                })
                .catch(() => {
                    alert('Lỗi kết nối server');
                });
        }


        function deleteSport(id) {
            if (!confirm('Xóa môn thể thao này?')) return;

            fetch('../manager_sport/delete_sport.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'sportID=' + id
            })
                .then(r => r.json())
                .then(d => {
                    alert(d.message);

                    if (d.success) {
                        const item = document.getElementById('sport-item-' + id);
                        if (item) item.remove();
                    }
                });

        }

        function editSport(id, oldName) {
            const newName = prompt('Nhập tên môn thể thao mới:', oldName);

            if (newName === null) return;
            if (!newName.trim()) {
                alert('Tên môn không được rỗng');
                return;
            }

            fetch('../manager_sport/update_sport.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'sportID=' + id + '&sport_name=' + encodeURIComponent(newName)
            })
                .then(r => r.json())
                .then(d => {
                    alert(d.message);
                    if (d.success) {
                        document.getElementById('sport-name-' + id).innerText = newName;
                    }
                })
                .catch(() => {
                    alert('Lỗi kết nối server');
                });
        }
        function approveMember(clubID, userID, btn) {
            if (!confirm('Duyệt yêu cầu này?')) return;

            fetch('../manager_sport/approve_member.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userID, clubID })
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.error || 'Lỗi duyệt');
                        return;
                    }

                    /* 1️⃣ XÓA REQUEST CHỜ */
                    const pendingItem = btn.closest('.pending-item');
                    if (pendingItem) pendingItem.remove();

                    /* 2️⃣ THÊM THÀNH VIÊN VÀO BẢNG */
                    const member = data.member;

                    const tableBody = document.querySelector(
                        `#club-${clubID} table tbody`
                    );

                    const row = document.createElement('tr');
                    row.className = 'border-b hover:bg-slate-50 transition';

                    row.innerHTML = `
            <td class="p-3 font-medium">${member.full_name}</td>
            <td class="p-3 text-slate-600">${member.email}</td>
            <td class="p-3 text-center">
                ${formatDate(member.join_date)}
            </td>
            <td class="p-3 text-center text-slate-400">
                Chưa đóng
            </td>
            <td class="p-3 text-center text-red-500 font-semibold">
                —
            </td>
        `;

                    /* Nếu bảng đang là "Chưa có thành viên" → xóa dòng đó */
                    const emptyRow = tableBody.querySelector('td[colspan]');
                    if (emptyRow) emptyRow.closest('tr').remove();

                    tableBody.appendChild(row);

                    /* (BONUS) CẬP NHẬT SỐ LƯỢNG YÊU CẦU */
                    updatePendingUI(clubID);

                })
                .catch(err => {
                    console.error(err);
                    alert('Có lỗi xảy ra');
                });
        }

        function formatDate(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            return d.toLocaleDateString('vi-VN');
        }

        function updatePendingUI(clubID) {
            const club = document.getElementById('club-' + clubID);
            if (!club) return;

            const pendingItems = club.querySelectorAll('.pending-item');
            const count = pendingItems.length;

            /* cập nhật tiêu đề trong panel */
            const countText = club.querySelector('.pending-count');
            if (countText) countText.innerText = count;

            /* cập nhật badge */
            const badge = club.querySelector('.pending-badge');
            if (badge) badge.innerText = count;

            /* CẬP NHẬT DÒNG HEADER CLB (CHỖ BẠN BỊ) */
            const headerCount = club.querySelector('.header-pending-count');
            if (headerCount) headerCount.innerText = count;

            /* nếu không còn yêu cầu → ẩn khối */
            const approvals = club.querySelector('#approvals-' + clubID);
            if (approvals && count === 0) {
                approvals.classList.add('hidden');
            }
        }




    </script>
</body>

</html>