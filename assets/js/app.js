document.querySelectorAll('.sidebar a').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();

        const page = this.getAttribute('data-page');

        fetch(page)
            .then(res => res.text())
            .then(html => {
                document.getElementById('content').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('content').innerHTML =
                    "<p>Lỗi tải trang</p>";
            });
    });
});
