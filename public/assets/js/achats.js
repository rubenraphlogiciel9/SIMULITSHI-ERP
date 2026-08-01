document.addEventListener('DOMContentLoaded', function () {
    const poidsBrutInput = document.getElementById('poids_brut');
    const tareInput = document.getElementById('tare');
    const poidsNetInput = document.getElementById('poids_net');
    const prixUnitaireInput = document.getElementById('prix_unitaire');
    const montantTotalInput = document.getElementById('montant_total');
    const achatForm = document.getElementById('achatForm');

    // Calcul automatique du Poids Net et du Montant Total
    function calculateTotals() {
        const brut = parseFloat(poidsBrutInput.value) || 0;
        const tare = parseFloat(tareInput.value) || 0;
        const pu = parseFloat(prixUnitaireInput.value) || 0;

        const net = Math.max(0, brut - tare);
        const total = net * pu;

        poidsNetInput.value = net.toFixed(2);
        montantTotalInput.value = total.toFixed(2);

        // SÉCURITÉ : Forcer l'assignation de la valeur sur l'attribut name="montant"
        let montantHidden = achatForm.querySelector('[name="montant"]');
        if (montantHidden) {
            montantHidden.value = total.toFixed(2);
        }
    }

    if (poidsBrutInput && tareInput && prixUnitaireInput) {
        poidsBrutInput.addEventListener('input', calculateTotals);
        tareInput.addEventListener('input', calculateTotals);
        prixUnitaireInput.addEventListener('input', calculateTotals);
    }

    // Soumission AJAX du formulaire d'achat
    if (achatForm) {
        achatForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // SÉCURITÉ : Forcer le calcul des totaux juste avant l'envoi du formulaire
            calculateTotals();

            const submitBtn = document.getElementById('btnSubmitAchat');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enregistrement...';

            const formData = new FormData(achatForm);

            fetch(BASE_URL + '/achat/store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Achat Enregistré !',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
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
                    title: 'Erreur Système',
                    text: 'Une erreur inattendue est survenue.',
                    confirmButtonColor: '#2563eb'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});