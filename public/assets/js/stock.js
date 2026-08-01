document.addEventListener('DOMContentLoaded', function () {
    const stockForm = document.getElementById('stockForm');

    if (stockForm) {
        stockForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmitStock');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';

            const formData = new FormData(stockForm);

            // Utilisation de /stock/ajuster pour correspondre au routeur/message d'erreur
            fetch(BASE_URL + '/stock/ajuster', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalElement = document.getElementById('modalStock');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert('Erreur : ' + (data.message || 'Impossible d\'enregistrer l\'ajustement.'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur réseau est survenue.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});