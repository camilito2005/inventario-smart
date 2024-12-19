function togglePassword(id) {
    var passwordField = document.getElementById("contraseña-" + id);
    var eyeIcon = document.getElementById("eye-icon-" + id);

    // Cambiar el tipo de campo entre 'password' y 'text'
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}