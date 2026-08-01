document.addEventListener('DOMContentLoaded', function () {
    const fournisseurForm = document.getElementById('fournisseurForm');
    const avanceForm = document.getElementById('avanceForm');

    // Soumission Nouveau Fournisseur
    if (fournisseurForm) {
        fournisseurForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('btnSubmitFournisseur');
            submitBtn.disabled = true;

            fetch(BASE_URL + '/fournisseur/store', {
                method: 'POST',
                body: new FormData(fournisseurForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Succès', text: data.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: data.message });
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
            });
        });
    }

    // Soumission Nouvelle Avance
    if (avanceForm) {
        avanceForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('btnSubmitAvance');
            submitBtn.disabled = true;

            fetch(BASE_URL + '/fournisseur/avance/store', {
                method: 'POST',
                body: new FormData(avanceForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Avance Octroyée !', text: data.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: data.message });
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
            });
        });
    }
});