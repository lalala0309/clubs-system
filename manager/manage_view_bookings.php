<?php
session_start();
require_once '../config/database.php';
require_once '../includes/get_user.php';

$week_offset = isset($_GET['week_offset']) ? (int)$_GET['week_offset'] : 0;

/* ===== TÍNH TUẦN ===== */
$monday = new DateTime();
$monday->modify('monday this week');
$monday->modify(($week_offset >= 0 ? '+' : '') . $week_offset . ' week');

$days = [];
$labels = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'];

for ($i = 0; $i < 7; $i++) {
    $days[] = [
        'label' => $labels[$i],
        'date'  => $monday->format('Y-m-d'),
        'short' => $monday->format('d/m')
    ];
    $monday->modify('+1 day');
}

/* ===== LẤY BOOKING TRONG TUẦN ===== */
$sql = "
    SELECT 
        b.booking_date,
        b.start_time,
        b.end_time,
        u.full_name,
        u.email,
        g.name AS ground_name
    FROM bookings b
    JOIN users u   ON b.userID = u.userID
    JOIN grounds g ON b.groundID = g.groundID
    WHERE b.booking_date BETWEEN ? AND ?
    ORDER BY b.start_time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $days[0]['date'], $days[6]['date']);
$stmt->execute();
$res = $stmt->get_result();

/* ===== MAP BOOKING ===== */
$bookings = [];
while ($row = $res->fetch_assoc()) {
    $key = $row['booking_date'].'_'.$row['start_time'].'_'.$row['end_time'];
    $bookings[$key][] = $row;
}

/* ===== KHUNG GIỜ ===== */
$timeRanges = [
    "06:00-07:00","07:00-08:00","08:00-09:00","09:00-10:00",
    "13:00-14:00","14:00-15:00","15:00-16:00","16:00-17:00",
    "17:00-18:00","18:00-19:00","19:00-20:00","20:00-21:00"
];
?>

<!-- ===== CONTENT LOAD VÀO DASHBOARD ===== -->
<div class="p-4">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-black text-slate-700 uppercase">
            Lịch đặt sân
        </h1>

        <div class="space-x-2">
            <button
                onclick="loadManagerPage('../manager/manage_view_bookings.php?week_offset=<?= $week_offset - 1 ?>')"
                class="px-3 py-1 bg-white border rounded shadow hover:bg-slate-50">
                ◀ Tuần trước
            </button>

            <button
                onclick="loadManagerPage('../manager/manage_view_bookings.php?week_offset=<?= $week_offset + 1 ?>')"
                class="px-3 py-1 bg-white border rounded shadow hover:bg-slate-50">
                Tuần sau ▶
            </button>
        </div>
    </div>

    <table class="w-full border-collapse bg-white text-xs shadow">
        <thead>
            <tr class="bg-slate-200 uppercase text-slate-600">
                <th class="border p-2 w-24">Giờ</th>
                <?php foreach ($days as $d): ?>
                    <th class="border p-2 text-center">
                        <?= $d['label'] ?><br><?= $d['short'] ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($timeRanges as $range): ?>
            <tr>
                <td class="border p-2 font-bold text-center bg-slate-50">
                    <?= $range ?>
                </td>

                <?php foreach ($days as $d):
                    [$start, $end] = explode('-', $range);
                    $key = $d['date'].'_'.$start.':00_'.$end.':00';
                ?>
                <td class="border p-2 align-top min-h-[60px]">
                    <?php if (isset($bookings[$key])): ?>
                        <?php foreach ($bookings[$key] as $b): ?>
                            <div class="mb-2 p-2 rounded bg-red-50 border border-red-200">
                                <div class="font-bold text-red-600">
                                    <?= htmlspecialchars($b['full_name']) ?>
                                </div>
                                <div class="text-[10px] text-slate-600">
                                    <?= htmlspecialchars($b['email']) ?>
                                </div>
                                <div class="text-[10px] italic text-slate-400">
                                    <?= htmlspecialchars($b['ground_name']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-slate-300 text-center">—</div>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
