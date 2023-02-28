const form = document.getElementById('form');
const email= document.getElementById('email');
const password = document.getElementById('password');

form.addEventListener('submit', function (event) {
    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }

}, false);
