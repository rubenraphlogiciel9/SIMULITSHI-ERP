<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold text-dark mb-0"><i class="fas fa-wallet text-warning me-2"></i>Caisse du Poste</h4>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-warning fw-semibold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCaisse">
            <i class="fas fa-exchange-alt me-2"></i>Nouveau Mouvement Caisse
        </button>
    </div>
</div>

<!-- Carte Solde -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 bg-dark text-white">
            <div class="card-body p-4">
                <div class="text-uppercase small fw-bold text-warning mb-1">Solde Actuel en Caisse</div>
                <div class="display-6 fw-bold">$<?= number_format((float)($solde ?? 0), 2, '.', ' ') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Historique Mouvements -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list text-secondary me-2"></i>Historique des Mouvements de Caisse</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Type</th>
                        <th class="text-end">Montant ($)</th>
                        <th>Motif / Libellé</th>
                        <th>N° Pièce</th>
                        <th class="text-end">Solde Après ($)</th>
                        <th class="pe-3">Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mouvements)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucun mouvement de caisse.</td></tr>
                    <?php else: ?>
                        <?php foreach ($mouvements as $m): ?>
                            <?php 
                                // Déduction sécurisée des clés SQL
                                $rawDate = $m['date_mouvement'] ?? $m['date_operation'] ?? $m['created_at'] ?? null;
                                $dateStr = $rawDate ? date('d/m/Y H:i', strtotime($rawDate)) : '-';
                                
                                $typeRaw = $m['type_mouvement'] ?? $m['type_operation'] ?? 'ENTREE';
                                // Vérification insensible à la casse
                                $isEntree = (strtoupper(trim((string)$typeRaw)) === 'ENTREE');

                                $montant = (float)($m['montant'] ?? 0);
                                $soldeApres = (float)($m['solde_apres'] ?? 0);
                                $libelle = $m['libelle'] ?? $m['motif'] ?? '-';
                                $pieceJustificative = $m['piece_justificative'] ?? $m['num_piece'] ?? '-';
                                $agent = $m['username'] ?? $m['nom_agent'] ?? $m['nom_utilisateur'] ?? 'Système';
                            ?>
                            <tr>
                                <td class="ps-3 small text-muted"><?= $dateStr ?></td>
                                <td>
                                    <span class="badge bg-<?= $isEntree ? 'success' : 'danger' ?>">
                                        <?= htmlspecialchars((string)$typeRaw) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold <?= $isEntree ? 'text-success' : 'text-danger' ?>">
                                    <?= $isEntree ? '+' : '-' ?>$<?= number_format($montant, 2, '.', ' ') ?>
                                </td>
                                <td><?= htmlspecialchars((string)$libelle) ?></td>
                                <td class="small text-muted"><?= htmlspecialchars((string)$pieceJustificative) ?></td>
                                <td class="text-end fw-semibold">$<?= number_format($soldeApres, 2, '.', ' ') ?></td>
                                <td class="pe-3 small text-muted"><?= htmlspecialchars((string)$agent) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Mouvement Caisse -->
<div class="modal fade" id="modalCaisse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-wallet text-warning me-2"></i>Mouvement de Caisse</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="caisseForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type de Mouvement <span class="text-danger">*</span></label>
                        <select name="type_mouvement" class="form-select" required>
                            <option value="Entree">Entrée (Approvisionnement)</option>
                            <option value="Sortie">Sortie (Dépense / Retrait)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="montant" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif / Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="libelle" class="form-control" placeholder="Ex: Approvisionnement depuis Banque, Frais transport..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">N° Pièce Justificative</label>
                        <input type="text" name="piece_justificative" class="form-control" placeholder="Ex: FACT-0012, REC-45, BON-02...">
                        <div class="form-text">Facultatif ou selon les exigences internes.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitCaisse" class="btn btn-warning text-dark fw-semibold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/caisse.js"></script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>