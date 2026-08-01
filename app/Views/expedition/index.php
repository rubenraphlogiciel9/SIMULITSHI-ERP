<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-truck text-warning me-2"></i>Expéditions / Transferts de Stock</h4>
    <button class="btn btn-warning text-dark fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalExpedition">
        <i class="fas fa-plus-circle me-2"></i>Nouvelle Expédition
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Poste Source</th>
                        <th>Poste Destination</th>
                        <th>Utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transferts)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucune expédition enregistrée pour le moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transferts as $t): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($t['date_transfert'])) ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($t['nom_produit']) ?></td>
                                <td><span class="badge bg-warning-subtle text-dark border fw-bold"><?= number_format((float)$t['quantite'], 2, '.', ' ') ?> <?= htmlspecialchars($t['unite'] ?? 'kg') ?></span></td>
                                <td><?= htmlspecialchars($t['nom_poste_source']) ?></td>
                                <td><span class="fw-semibold text-success"><?= htmlspecialchars($t['nom_poste_destination']) ?></span></td>
                                <td><small class="text-muted"><?= htmlspecialchars($t['nom_utilisateur']) ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Expédition -->
<div class="modal fade" id="modalExpedition" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-truck text-warning me-2"></i>Effectuer une Expédition</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="expeditionForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Produit <span class="text-danger">*</span></label>
                        <select name="id_produit" class="form-select" required>
                            <option value="">-- Sélectionner un produit --</option>
                            <?php foreach ($produits as $p): ?>
                                <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Poste de Destination <span class="text-danger">*</span></label>
                        <select name="poste_destination" class="form-select" required>
                            <option value="">-- Sélectionner le poste destinataire --</option>
                            <?php foreach ($postes as $pos): ?>
                                <option value="<?= $pos['id_poste'] ?>"><?= htmlspecialchars($pos['nom_poste']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité à expédier <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="quantite" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitExpedition" class="btn btn-warning text-dark fw-semibold">Valider l'expédition</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const expeditionForm = document.getElementById('expeditionForm');

    if (expeditionForm) {
        expeditionForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmitExpedition');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';

            const formData = new FormData(expeditionForm);

            fetch(BASE_URL + '/expedition/store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalElement = document.getElementById('modalExpedition');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert('Erreur : ' + (data.message || 'Impossible d\'effectuer l\'expédition.'));
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
</script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>