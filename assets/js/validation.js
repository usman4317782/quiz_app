document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                // Custom validation checks
                if (form.id === 'registerForm') {
                    if (!validateRegistration(form)) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                }

                form.classList.add('was-validated');
            }, false);
        });
});

function validateRegistration(form) {
    let isValid = true;
    
    // Username Validation
    const username = form.querySelector('#username');
    const usernameRegex = /^[a-zA-Z0-9]{3,}$/;
    if (!usernameRegex.test(username.value)) {
        username.setCustomValidity('Username must be at least 3 characters and alphanumeric.');
        isValid = false;
    } else {
        username.setCustomValidity('');
    }

    // Password Validation
    const password = form.querySelector('#password');
    // Minimum 8 chars, at least 1 uppercase, 1 lowercase, 1 number, 1 special char
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    
    if (!passwordRegex.test(password.value)) {
        password.setCustomValidity('Password must be at least 8 characters long, include uppercase, lowercase, number, and special character.');
        isValid = false;
    } else {
        password.setCustomValidity('');
    }

    // Confirm Password Validation
    const confirmPassword = form.querySelector('#confirm_password');
    if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Passwords do not match.');
        isValid = false;
    } else {
        confirmPassword.setCustomValidity('');
    }

    return isValid;
}
