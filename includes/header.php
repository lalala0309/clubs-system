<?php
require_once __DIR__ . '/get_user.php';
?>
<header
    class="w-full rounded-[20px] max-md:rounded-[15px] flex items-center justify-between px-8 max-md:px-4 py-2.5 max-md:py-2 bg-[#4F46E5] shadow-lg z-50">

    <div class="flex items-center gap-2 max-md:gap-1">
        <button id="openProfile"
            class="w-11 h-11 max-md:w-8 max-md:h-8 rounded-full bg-white/20 overflow-hidden border border-white/30 flex items-center justify-center">

            <?php if (!empty($avatar)): ?>
                <img src="<?= htmlspecialchars($avatar) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <i class="bi bi-person-circle text-xl text-white"></i>
            <?php endif; ?>

        </button>

        <div class="text-left">
            <p class="text-xs max-md:text-[11px] font-black text-white leading-tight">
                <?= htmlspecialchars($fullName) ?>
            </p>
            <p
                class="text-[9px] max-md:text-[8px] text-indigo-100 font-bold uppercase tracking-wider opacity-80 leading-none">
                <?= htmlspecialchars($userEmail) ?>
            </p>
        </div>
    </div>
</header>


<!-- PROFILE MODAL -->
<div id="profileModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[9999]">

    <div class="bg-white w-[650px] max-md:w-[95%] rounded-2xl shadow-2xl relative">

        <button id="closeProfile" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl">
            &times;
        </button>

        <div id="profileContent"></div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const avatarBtn = document.querySelector("#openProfile");
        const modal = document.getElementById("profileModal");
        const closeBtn = document.getElementById("closeProfile");
        const content = document.getElementById("profileContent");

        if (avatarBtn) {
            avatarBtn.addEventListener("click", function () {

                fetch("/clubs-system/users/profile.php")
                    .then(res => res.text())
                    .then(data => {
                        content.innerHTML = data;
                        modal.classList.remove("hidden");
                        modal.classList.add("flex");
                        initProfileScript();
                    });

            });
        }

        if (closeBtn) {
            closeBtn.addEventListener("click", function () {
                modal.classList.add("hidden");
                content.innerHTML = "";
            });
        }

        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.add("hidden");
                content.innerHTML = "";
            }
        });

    });

    const input = document.getElementById("tagInput");
    const container = document.getElementById("tagContainer");

    function enableEdit() {
        document.getElementById("viewMode").classList.add("hidden");
        document.getElementById("editMode").classList.remove("hidden");
    }

    function cancelEdit() {
        location.reload(); // reset lại về trạng thái ban đầu
    }

    if (input) {
        input.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const value = input.value.trim();
                if (value === "") return;

                const tag = document.createElement("span");
                tag.className = "tag-item bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-2";
                tag.innerHTML = `
                    ${value}
                    <button type="button" onclick="removeTag(this)" class="text-red-500 font-bold">×</button>
                    <input type="hidden" name="hobbies[]" value="${value}">
                `;

                container.appendChild(tag);
                input.value = "";
            }
        });
    }

    function removeTag(btn) {
        btn.parentElement.remove();
    }

    function initProfileScript() {

        const input = document.getElementById("tagInput");
        const container = document.getElementById("tagContainer");

        if (!input) return;

        input.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const value = input.value.trim();
                if (value === "") return;

                const tag = document.createElement("span");
                tag.className = "tag-item bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-2";
                tag.innerHTML = `
            ${value}
            <button type="button" onclick="removeTag(this)" class="text-red-500 font-bold">×</button>
            <input type="hidden" name="hobbies[]" value="${value}">
        `;

                container.appendChild(tag);
                input.value = "";
            }
        });

    }

    function saveHobby() {

        const form = document.getElementById("hobbyForm");
        if (!form) return;

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {

                if (data.trim() === "success") {

                    // Lấy tất cả checkbox đã chọn
                    const checked = form.querySelectorAll("input[name='sports[]']:checked");

                    // Container hiển thị tag ở view mode
                    const tagContainer = document.querySelector("#viewMode .flex");

                    tagContainer.innerHTML = "";

                    if (checked.length === 0) {
                        tagContainer.innerHTML = '<p class="text-gray-400 text-sm">Chưa có sở thích</p>';
                    } else {
                        checked.forEach(cb => {

                            const sportName = cb.nextElementSibling.innerText;

                            const span = document.createElement("span");
                            span.className = "bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold";
                            span.innerText = sportName;

                            tagContainer.appendChild(span);
                        });
                    }

                    // Chuyển về chế độ xem
                    document.getElementById("editMode").classList.add("hidden");
                    document.getElementById("viewMode").classList.remove("hidden");

                    alert("Cập nhật thành công");

                } else {
                    alert("Lỗi: " + data);
                }

            })
            .catch(err => console.error(err));
    }
</script>