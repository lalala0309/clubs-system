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


<!-- <?php ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("profileModal");
        if (modal) {
            modal.classList.remove("hidden");
        }
    });
</script>
<?php ?> -->
<div id="profile" class="p-8 w-full">

    <!-- Avatar + Name -->
    <div class="flex flex-col items-center">

        <?php if (!empty($avatar)): ?>
            <img src="<?= htmlspecialchars($avatar) ?>"
                class="w-36 h-36 rounded-full object-cover border-4 border-indigo-500 shadow-lg">
        <?php else: ?>
            <i class="bi bi-person-circle text-[120px] text-gray-300"></i>
        <?php endif; ?>

        <h2 class="mt-5 font-bold text-2xl text-gray-800">
            <?= htmlspecialchars($fullName) ?>
        </h2>

        <p class="text-gray-500 text-sm mt-1">
            <?= htmlspecialchars($userEmail) ?>
        </p>

        <span class="mt-3 px-4 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-full font-semibold tracking-wide">
            <?= htmlspecialchars($roleName) ?>
        </span>
    </div>

    <hr class="my-6">

    <!-- Thông tin chi tiết -->
    <div class="grid grid-cols-2 gap-6 text-sm text-gray-700">

        <!-- Ngày tạo -->
        <div>
            <p class="font-semibold text-gray-500 mb-1">Ngày tạo</p>
            <p>
                <?= !empty($createdAt) ? date("d/m/Y H:i", strtotime($createdAt)) : '' ?>
            </p>
        </div>

        <!-- Mã số sinh viên -->
        <div>
            <p class="font-semibold text-gray-500 mb-1">Mã số sinh viên</p>
            <p><?= htmlspecialchars($studentCode) ?></p>
        </div>

        <!-- Ô mô tả sở thích (full 2 cột) -->
    </div>

    <!-- Sở thích -->
    <div class="mt-8">

        <p class="font-semibold text-gray-500 mb-3">Sở thích</p>

        <!-- ===== CHẾ ĐỘ XEM ===== -->
        <div id="viewMode">
            <div class="flex flex-wrap gap-2 mb-4">
                <?php
                if (!empty($hobbies)) {
                    $tags = explode(',', $hobbies);
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        if ($tag !== '') {
                            echo '<span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">'
                                . htmlspecialchars($tag) .
                                '</span>';
                        }
                    }
                } else {
                    echo '<p class="text-gray-400 text-sm">Chưa có sở thích</p>';
                }
                ?>
            </div>

            <button onclick="enableEdit()"
                class="w-full bg-indigo-500 hover:bg-indigo-600 text-white py-3 rounded-xl transition font-semibold">
                Chỉnh sửa thông tin
            </button>
        </div>

        <!-- ===== CHẾ ĐỘ CHỈNH SỬA ===== -->
        <div id="editMode" class="hidden">
            <form id="hobbyForm" action="/clubs-system/users/update_hobbies.php">
                <div class="flex flex-wrap gap-3 mb-6">

                    <?php foreach ($allSports as $sport): ?>
                        <?php
                        $checked = in_array($sport['sport_name'], $userHobbies);
                        ?>

                        <label class="cursor-pointer">
                            <input type="checkbox" name="sports[]" value="<?= $sport['sportID'] ?>" class="hidden peer"
                                <?= $checked ? 'checked' : '' ?>>

                            <span class="
                    px-4 py-2 rounded-full text-sm font-semibold transition
                    border
                    peer-checked:bg-indigo-500
                    peer-checked:text-white
                    peer-checked:border-indigo-500
                    bg-indigo-100 text-indigo-600 border-indigo-200
                ">
                                <?= htmlspecialchars($sport['sport_name']) ?>
                            </span>
                        </label>

                    <?php endforeach; ?>

                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="saveHobby()"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-xl transition font-semibold">
                        Lưu
                    </button>

                    <button type="button" onclick="cancelEdit()"
                        class="flex-1 bg-gray-400 hover:bg-gray-500 text-white py-3 rounded-xl transition font-semibold">
                        Huỷ
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
</div>

<!-- Logout
<div class="mt-8">
    <a href="/clubs-system/public/logout.php"
        class="block w-full text-center bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl transition font-semibold">
        Đăng xuất
    </a>
</div> -->

</div>

<script>
    document.addEventListener("submit", function (e) {

        if (e.target && e.target.id === "hobbyForm") {
            e.preventDefault();

            const formData = new FormData(e.target);

            fetch(e.target.action, {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(data => {

                    if (data.trim() === "success") {

                        alert("Cập nhật thành công");

                        window.location.reload();
                    }

                });

        }

    });

</script>