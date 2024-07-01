
document.getElementById('togglePassword').addEventListener('click', function() {
    var passwordField = document.getElementById('password');
    var passwordToggle = document.getElementById('togglePassword').querySelector('img');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.src = 'images/eye-closed.png'; // Update to your closed eye icon
    } else {
        passwordField.type = 'password';
        passwordToggle.src = 'images/eye-open.svg'; // Update to your open eye icon
    }
});