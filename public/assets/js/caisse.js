document.addEventListener('DOMContentLoaded', function () {
    const caisseForm = document.getElementById('caisseForm');

    if (caisseForm) {
        caisseForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmitCaisse');
            if (submitBtn) submitBtn.disabled = true;

            // Déduction propre de la route cible
            // Si window.BASE_URL est défini globalement dans ton layout header, on l'utilise.
            // Sinon, on remplace la fin du path pour toujours cibler /caisse/store
            let actionUrl = (typeof BASE_URL !== 'undefined') 
                ? `${BASE_URL}/caisse/store` 
                : window.location.origin + window.location.pathname.replace(/\/caisse(\/.*)?$/, '/caisse/store');

            fetch(actionUrl, {
                method: 'POST',
                body: new FormData(caisseForm),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async res => {
                const contentType = res.headers.get('content-type');
                if (!res.ok) {
                    throw new Error(`Erreur HTTP ${res.status}`);
                }
                // Vérification si le serveur renvoie bien du JSON
                if (contentType && contentType.includes('application/json')) {
                    return res.json();
                } else {
                    const text = await res.text();
                    console.error('Réponse non JSON reçue :', text);
                    throw new Error('Le serveur a renvoyé du HTML au lieu de JSON (vérifier la route PHP).');
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Succès', 
                        text: data.message || 'Opération enregistrée !', 
                        timer: 1500, 
                        showConfirmButton: false 
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Erreur', 
                        text: data.message || 'Impossible d\'enregistrer.' 
                    });
                    if (submitBtn) submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error('Erreur AJAX:', err);
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Erreur Réseau / Serveur', 
                    text: err.message 
                });
                if (submitBtn) submitBtn.disabled = false;
            });
        });
    }
});