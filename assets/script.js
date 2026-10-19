// Contoh: tampilkan konfirmasi sebelum logout
document.addEventListener('DOMContentLoaded', function () {
    const logoutLink = document.querySelector('a.keluar');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            if (!confirm('Yakin ingin logout?')) {
                e.preventDefault();
            }
        });
    }
});