const form = document.getElementById('form');
const email= document.getElementById('email');
const name = document.getElementById('name');
const password = document.getElementById('password');
const repeatPassword = document.getElementById('repeat-password');

password.addEventListener('input', () => {
    const passwordIsValid = password.value && password.value.length >= 8;
    
    if (!passwordIsValid) {
        repeatPassword.disabled = true;
        return;
    }

    repeatPassword.disabled = false;

})

form.addEventListener('submit', function (event) {
    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }

}, false);
