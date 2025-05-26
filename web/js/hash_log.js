$(document).ready(function () {
    $('#login-form').on('submit', function (e) {
        const passwordField = $('#password-fields');
        const hiddenField = $('#hashed-password');

        const password = passwordField.val();
        if (password) {
            const hashedPassword = CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
            hiddenField.val(hashedPassword);
        }
        return true;
    });
});