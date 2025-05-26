$(document).ready(function() {
    $('#registration-form').on('beforeSubmit', function(e) {
        const passwordField = $('#password-field');
        if (passwordField.length) {
            const password = passwordField.val();
            if (password) {
                // Используем тот же метод, что и при входе
                const hashedPassword = CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
                passwordField.val(hashedPassword);
            }
        }
        return true;
    });
});