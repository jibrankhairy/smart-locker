document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('register-form');

    form.addEventListener('submit', function(event) {
        var name = document.getElementById('name').value;
        var nim = document.getElementById('nim').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var rePassword = document.getElementById('re-pass').value;

        // Cek apakah ada input yang kosong
        if (name === '' || nim === '' || email === '' || password === '' || rePassword === '') {
            alert('Semua kolom harus diisi!');
            event.preventDefault(); // Menghentikan pengiriman formulir
        }

        // Cek apakah kata sandi dan konfirmasi kata sandi cocok
        if (password !== rePassword) {
            alert('Password dan konfirmasi password harus sama!');
            event.preventDefault();
        }
    });
});