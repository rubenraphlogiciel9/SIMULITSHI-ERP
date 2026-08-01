document.addEventListener('DOMContentLoaded', function () {
    const produitForm = document.getElementById('produitForm');

    if (produitForm) {
        produitForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveProduit');
            btn.disabled = true;

            fetch(BASE_URL + '/produit/store', {
                method: 'POST',
                body: new FormData(produitForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Succès !', text: data.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: data.message });
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
            });
        });
    }
});

function toggleStatutProduit(idProduit, nouveauStatut) {
    const formData = new FormData();
    formData.append('id_produit', idProduit);
    formData.append('statut', nouveauStatut);

    fetch(BASE_URL + '/produit/toggle-statut', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: data.message });
        }
    });
}