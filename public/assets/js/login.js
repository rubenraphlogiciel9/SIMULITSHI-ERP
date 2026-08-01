document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    // Basculer l'affichage du mot de passe
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    }

    // Traitement AJAX de la connexion
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmit');
            const originalBtnText = submitBtn.innerHTML;

            // Désactivation du bouton avec spinner
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Connexion en cours...';

            const formData = new FormData(loginForm);

            fetch(BASE_URL + '/authenticate', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Connexion réussie',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = BASE_URL + '/dashboard';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur d\'authentification',
                        text: data.message,
                        confirmButtonColor: '#2563eb'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur système',
                    text: 'Une erreur inattendue est survenue. Veuillez réessayer.',
                    confirmButtonColor: '#2563eb'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});