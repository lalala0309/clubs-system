<?php
require_once __DIR__ . '/../includes/get_user.php';
require_once __DIR__ . '/../config/database.php';

$sportResult = $conn->query("SELECT sportID, sport_name FROM sports ORDER BY sport_name ASC");
$allSports = $sportResult->fetch_all(MYSQLI_ASSOC);

$userHobbies = [];
if (!empty($hobbies)) {
    $userHobbies = array_map('trim', explode(',', $hobbies));
}
?>

<div id="profile" class="p-2 md:p-8 w-full max-w-2xl mx-auto">
    <div class="flex flex-col items-center text-center">
        <?php if (!empty($avatar)): ?>
            <img src="<?= htmlspecialchars($avatar) ?>"
                class="w-20 h-20 md:w-36 md:h-36 rounded-full object-cover border-2 md:border-4 border-indigo-500 shadow-lg">
        <?php else: ?>
            <i class="bi bi-person-circle text-[80px] md:text-[120px] text-gray-300"></i>
        <?php endif; ?>

        <h2 class="mt-2 md:mt-4 font-bold text-lg md:text-2xl text-gray-800 leading-tight">
            <?= htmlspecialchars($fullName) ?>
        </h2>

        <p class="text-gray-500 text-[11px] md:text-sm mt-0.5 md:mt-1 break-all">
            <?= htmlspecialchars($userEmail) ?>
        </p>

        <span
            class="mt-2 md:mt-3 px-3 md:px-4 py-0.5 md:py-1 text-[10px] md:text-xs bg-indigo-100 text-indigo-600 rounded-full font-semibold tracking-wide">
            <?= htmlspecialchars($roleName) ?>
        </span>
    </div>

    <hr class="my-4 md:my-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-6 text-[12px] md:text-sm text-gray-700">
        <div class="bg-gray-50 flex justify-between items-center p-2 rounded-lg md:bg-transparent md:block md:p-0">
            <p class="font-semibold text-gray-500 md:mb-1">Ngày tạo</p>
            <p class="font-medium text-gray-800">
                <?= !empty($createdAt) ? date("d/m/Y", strtotime($createdAt)) : 'Chưa cập nhật' ?>
            </p>
        </div>

        <div class="bg-gray-50 flex justify-between items-center p-2 rounded-lg md:bg-transparent md:block md:p-0">
            <p class="font-semibold text-gray-500 md:mb-1">MSSV</p>
            <p class="font-medium text-gray-800"><?= htmlspecialchars($studentCode) ?></p>
        </div>
    </div>

    <div class="mt-6 md:mt-8">
        <p class="text-[11px] md:text-sm font-bold text-gray-400 mb-2 md:mb-3 border-b pb-1 md:border-0">
            Sở thích</p>

        <div id="viewMode">
            <div class="flex flex-wrap gap-1.5 md:gap-2 mb-4 md:mb-6">
                <?php
                if (!empty($hobbies)) {
                    $tags = explode(',', $hobbies);
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        if ($tag !== '') {
                            // Thu nhỏ tag trên mobile
                            echo '<span class="bg-indigo-100 text-indigo-600 px-2 md:px-3 py-1 md:py-1.5 rounded-full text-[10px] md:text-xs font-semibold">'
                                . htmlspecialchars($tag) .
                                '</span>';
                        }
                    }
                } else {
                    echo '<p class="text-gray-400 text-xs italic">Chưa có sở thích</p>';
                }
                ?>
            </div>

            <button onclick="enableEdit()"
                class="w-full bg-indigo-500 hover:bg-indigo-600 text-white py-2.5 md:py-3.5 rounded-lg md:rounded-xl transition text-sm md:text-base font-semibold shadow-md active:scale-[0.98]">
                Chỉnh sửa
            </button>
        </div>

        <div id="editMode" class="hidden">
            <form id="hobbyForm" action="/clubs-system/users/update_hobbies.php">
                <div class="flex flex-wrap gap-1.5 md:gap-3 mb-4 md:mb-6">
                    <?php foreach ($allSports as $sport): ?>
                        <?php $checked = in_array($sport['sport_name'], $userHobbies); ?>

                        <label class="cursor-pointer">
                            <input type="checkbox" name="sports[]" value="<?= $sport['sportID'] ?>" class="hidden peer"
                                <?= $checked ? 'checked' : '' ?>>
                            <span class="
                                inline-block px-3 md:px-4 py-1.5 md:py-2.5 rounded-full text-[10px] md:text-sm font-semibold transition
                                border border-indigo-200 bg-indigo-50 text-indigo-600
                                peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500
                            ">
                                <?= htmlspecialchars($sport['sport_name']) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="flex gap-2 md:gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-500 text-white py-2.5 md:py-3.5 rounded-lg md:rounded-xl transition text-sm md:text-base font-semibold">
                        Lưu
                    </button>

                    <button type="button" onclick="cancelEdit()"
                        class="flex-1 bg-gray-200 text-gray-600 py-2.5 md:py-3.5 rounded-lg md:rounded-xl transition text-sm md:text-base font-semibold">
                        Huỷ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function enableEdit() {
        document.getElementById('viewMode').classList.add('hidden');
        document.getElementById('editMode').classList.remove('hidden');
    }

    function cancelEdit() {
        document.getElementById('editMode').classList.add('hidden');
        document.getElementById('viewMode').classList.remove('hidden');
    }

    document.addEventListener("submit", function (e) {
        if (e.target && e.target.id === "hobbyForm") {
            e.preventDefault();
            const formData = new FormData(e.target);
            const submitBtn = e.target.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.innerText = "..."; // Rút gọn trạng thái chờ trên mobile

            fetch(e.target.action, {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === "success") {
                        window.location.reload();
                    } else {
                        alert("Lỗi!");
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Lưu thay đổi";
                    }
                })
                .catch(err => {
                    console.error(err);
                    submitBtn.disabled = false;
                });
        }
    });
</script>