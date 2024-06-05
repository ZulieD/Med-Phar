document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('show-login-form');
    const signupButton = document.getElementById('show-signup-form');
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');

    loginButton.addEventListener('click', function() {
        loginForm.classList.toggle('show');
        signupForm.classList.remove('show'); // Hide signup form if visible
    });

    signupButton.addEventListener('click', function() {
        signupForm.classList.toggle('show');
        loginForm.classList.remove('show'); // Hide login form if visible
    });
});
