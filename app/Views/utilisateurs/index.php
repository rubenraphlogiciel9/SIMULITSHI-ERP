<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-users-cog text-primary me-2"></i>Gestion des Utilisateurs
        </h4>
        <p class="text-muted small mb-0">Gestion des accès, des comptes et des affectations aux postes d'achat.</p>
    </div>
    <button class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUtilisateur">
        <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3"># ID</th>
                        <th>Nom complet</th>
                        <th>Nom d'utilisateur</th>
                        <th>Rôle</th>
                        <th>Poste d'achat</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $u): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">
                                    #<?= (int)$u['id_utilisateur'] ?>
                                </td>
                                <td class="fw-semibold text-dark">
                                    <?= htmlspecialchars(trim(($u['nom'] ?? '') . ' ' . ($u['postnom'] ?? '') . ' ' . ($u['prenom'] ?? ''))) ?>
                                </td>
                                <td class="text-primary fw-bold">
                                    @<?= htmlspecialchars($u['username']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border border-info">
                                        <?= htmlspecialchars($u['role_nom'] ?? 'Non défini') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-building me-1 text-muted"></i><?= htmlspecialchars($u['nom_poste'] ?? 'Siège / Général') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if (($u['statut'] ?? 'Actif') === 'Actif'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Création Utilisateur -->
<div class="modal fade" id="modalUtilisateur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Créer un utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreateUtilisateur" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Postnom</label>
                            <input type="text" name="postnom" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nom d'utilisateur (Username) <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required placeholder="Ex: ruben.yibunga">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="mot_passe" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
                            <select name="id_role" class="form-select" required>
                                <option value="">-- Sélectionner un rôle --</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= (int)$r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Poste d'achat <span class="text-danger">*</span></label>
                            <select name="id_poste" class="form-select" required>
                                <option value="">-- Affecter à un poste --</option>
                                <?php foreach ($postes as $p): ?>
                                    <option value="<?= (int)$p['id_poste'] ?>"><?= htmlspecialchars($p['nom_poste']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSaveUser" class="btn btn-primary fw-semibold">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = "<?= \App\Config\Config::BASE_URL ?>";
    const form = document.getElementById('formCreateUtilisateur');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveUser');
            btn.disabled = true;

            fetch(baseUrl + '/utilisateur/store', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                alert('Erreur réseau lors de la création de l\'utilisateur.');
            });
        });
    }
});
</script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>