<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';

$s = $rapport['synthese'] ?? [];
$p = $rapport['periode'] ?? ['debut' => date('Y-m-01'), 'fin' => date('Y-m-d')];
?>

<style>
    /* Style d'affichage écran des blocs rapports */
    .sheet-rapport {
        background: white;
        margin-bottom: 30px;
        padding: 30px 40px 30px 40px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        position: relative;
    }

    /* En-tête normalisé des rapports */
    .rapport-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #2c3e50;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .rapport-header-left h3 {
        margin: 0;
        font-weight: bold;
        font-size: 20px;
        color: #2c3e50;
        text-transform: uppercase;
    }
    .rapport-header-left .slogan {
        margin: 2px 0 0;
        font-style: italic;
        font-size: 9px;
        color: #6c757d;
    }
    .rapport-header-right {
        font-size: 32px;
        color: #0d6efd;
    }

    .rapport-titre {
        text-align: center;
        margin-bottom: 20px;
    }
    .rapport-titre h5 {
        text-decoration: underline;
        font-weight: bold;
        text-transform: uppercase;
        color: #333;
        font-size: 15px;
        margin-bottom: 5px;
    }

    /* Impression ciblée par rapport */
    @media print {
        body * {
            visibility: hidden;
        }
        .sheet-rapport.is-printing, .sheet-rapport.is-printing * {
            visibility: visible;
        }
        .sheet-rapport.is-printing {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
            padding: 20px;
            margin: 0;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>


<!-- Filtre de Date (Masqué à l'impression) -->
<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" action="<?= \App\Config\Config::BASE_URL ?>/rapport" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Date Début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($p['debut']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Date Fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($p['fin']) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="fas fa-filter me-2"></i>Filtrer les Rapports
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4 Cartes d'indicateurs clés globaux -->
<div class="row g-3 mb-4 d-print-none">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4 rounded-3 h-100">
            <div class="card-body">
                <span class="text-muted small fw-bold text-uppercase">Achats Totaux</span>
                <h3 class="fw-bold text-primary mt-2 mb-0"><?= number_format((float)($s['achats']['total_poids_kg'] ?? 0), 2, '.', ' ') ?> <small class="fs-6">kg</small></h3>
                <span class="small text-muted"><?= number_format((float)($s['achats']['total_montant_achat'] ?? 0), 2, '.', ' ') ?> $ (<?= $s['achats']['nb_achats'] ?? 0 ?> lots)</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4 rounded-3 h-100">
            <div class="card-body">
                <span class="text-muted small fw-bold text-uppercase">Avances Accordées</span>
                <h3 class="fw-bold text-warning mt-2 mb-0"><?= number_format((float)($s['avances']['total_avances_donnees'] ?? 0), 2, '.', ' ') ?> <small class="fs-6">$</small></h3>
                <span class="small text-success">Récupérées : <?= number_format((float)($s['achats']['total_avances_recup'] ?? 0), 2, '.', ' ') ?> $</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-4 rounded-3 h-100">
            <div class="card-body">
                <span class="text-muted small fw-bold text-uppercase">Solde Caisse Physique</span>
                <h3 class="fw-bold text-success mt-2 mb-0"><?= number_format((float)($rapport['solde_caisse'] ?? 0), 2, '.', ' ') ?> <small class="fs-6">$</small></h3>
                <span class="small text-muted">Flux : +<?= number_format((float)($s['caisse']['total_entrees'] ?? 0), 2, '.', ' ') ?> $ / -<?= number_format((float)($s['caisse']['total_sorties'] ?? 0), 2, '.', ' ') ?> $</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-info border-4 rounded-3 h-100">
            <div class="card-body">
                <span class="text-muted small fw-bold text-uppercase">Expédiés vers Siège</span>
                <h3 class="fw-bold text-info mt-2 mb-0"><?= number_format((float)($s['expeditions']['total_expedie_kg'] ?? 0), 2, '.', ' ') ?> <small class="fs-6">kg</small></h3>
                <span class="small text-muted"><?= $s['expeditions']['nb_expeditions'] ?? 0 ?> convoi(s)</span>
            </div>
        </div>
    </div>
</div>

<!-- ================= 1. ÉTAT DES STOCKS EN MAGASIN ================= -->
<div class="sheet-rapport" id="bloc-stocks">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-boxes"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>État des Stocks en Magasin (En Temps Réel)</h5>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-stocks')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Code Produit</th>
                    <th>Nom Produit</th>
                    <th class="text-end pe-3">Stock Disponible (kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalStockKg = 0;
                if (empty($rapport['stocks_actuels'])): 
                ?>
                    <tr><td colspan="3" class="text-center py-3 text-muted">Aucun stock disponible.</td></tr>
                <?php else: ?>
                    <?php foreach ($rapport['stocks_actuels'] as $st): 
                        $qteStock = (float)($st['quantite_disponible_kg'] ?? 0);
                        $totalStockKg += $qteStock;
                    ?>
                        <tr>
                            <td class="ps-3 fw-bold"><?= htmlspecialchars($st['code_produit'] ?? $st['id_produit'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($st['nom_produit'] ?? '') ?></td>
                            <td class="text-end pe-3 fw-bold text-primary"><?= number_format($qteStock, 2, '.', ' ') ?> kg</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($rapport['stocks_actuels'])): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end ps-3">TOTAL GENERAL :</th>
                    <th class="text-end pe-3 text-primary fw-bold"><?= number_format($totalStockKg, 2, '.', ' ') ?> kg</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- ================= 2. RÉPERTOIRE & STATUT DES PRODUCTEURS ================= -->
<div class="sheet-rapport" id="bloc-producteurs">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-users"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>Répertoire et Activité des Producteurs</h5>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-producteurs')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nom & Prénom</th>
                    <th>Téléphone</th>
                    <th>Dernier Achat</th>
                    <th class="text-center pe-3">Statut Activité</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalProducteurs = 0;
                $totalActifs = 0;
                if (empty($rapport['producteurs_tous'])): 
                ?>
                    <tr><td colspan="4" class="text-center py-3 text-muted">Aucun producteur enregistré.</td></tr>
                <?php else: ?>
                    <?php foreach ($rapport['producteurs_tous'] as $prod): 
                        $totalProducteurs++;
                        $statut = strtoupper($prod['statut_activite'] ?? 'INACTIF');
                        if ($statut === 'ACTIF') $totalActifs++;
                    ?>
                        <tr>
                            <td class="ps-3 fw-bold"><?= htmlspecialchars(($prod['nom'] ?? '') . ' ' . ($prod['prenom'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($prod['telephone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($prod['dernier_achat'] ?? 'Jamais') ?></td>
                            <td class="text-center pe-3">
                                <span class="badge <?= ($statut === 'ACTIF') ? 'bg-success' : 'bg-danger'; ?>">
                                    <?= htmlspecialchars($statut) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($rapport['producteurs_tous'])): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="ps-3 text-muted">
                        Total Producteurs : <strong><?= $totalProducteurs ?></strong> | Actifs : <strong class="text-success"><?= $totalActifs ?></strong> | Inactifs : <strong class="text-danger"><?= ($totalProducteurs - $totalActifs) ?></strong>
                    </th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- ================= 3. SITUATION INDIVIDUELLE DES DETTES ET AVANCES ================= -->
<div class="sheet-rapport" id="bloc-dettes">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-file-invoice-dollar"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>Situation Individuelle des Dettes et Avances</h5>
        <p class="small text-muted mb-0">Période du : <strong><?= date('d/m/Y', strtotime($p['debut'])) ?></strong> au <strong><?= date('d/m/Y', strtotime($p['fin'])) ?></strong></p>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-dettes')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Producteur</th>
                    <th>Téléphone</th>
                    <th class="text-end">Total Avances Perçues</th>
                    <th class="text-end pe-3">Solde Restant Dû</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $listeDettes = $rapport['situation_dettes'] ?? $rapport['dettes'] ?? [];
                $sommeAvancesPerques = 0;
                $sommeSoldeRestant = 0;
                if (empty($listeDettes)): 
                ?>
                    <tr><td colspan="4" class="text-center py-3 text-muted">Aucune dette ou avance enregistrée pour cette période.</td></tr>
                <?php else: ?>
                    <?php foreach ($listeDettes as $det): 
                        $avPerque = (float)($det['total_avances'] ?? $det['montant_avance'] ?? 0);
                        $soldeRest = (float)($det['solde_dette_restant'] ?? $det['solde_restant'] ?? 0);
                        $sommeAvancesPerques += $avPerque;
                        $sommeSoldeRestant += $soldeRest;
                    ?>
                        <tr>
                            <td class="ps-3 fw-bold"><?= htmlspecialchars(($det['nom'] ?? $det['fournisseur_nom'] ?? '') . ' ' . ($det['prenom'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($det['telephone'] ?? 'N/A') ?></td>
                            <td class="text-end"><?= number_format($avPerque, 2, '.', ' ') ?> $</td>
                            <td class="text-end pe-3 fw-bold text-danger"><?= number_format($soldeRest, 2, '.', ' ') ?> $</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($listeDettes)): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end ps-3">TOTAUX :</th>
                    <th class="text-end"><?= number_format($sommeAvancesPerques, 2, '.', ' ') ?> $</th>
                    <th class="text-end pe-3 text-danger fw-bold"><?= number_format($sommeSoldeRestant, 2, '.', ' ') ?> $</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- ================= JOURNAL DES ACHATS ================= -->
<div class="sheet-rapport" id="bloc-journal-achats">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-shopping-cart"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>Journal des Achats</h5>
        <p class="small text-muted mb-0">Période du : <strong><?= date('d/m/Y', strtotime($p['debut'])) ?></strong> au <strong><?= date('d/m/Y', strtotime($p['fin'])) ?></strong></p>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-journal-achats')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Date</th>
                    <th>Fournisseur / Article</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-end">Prix Unitaire ($)</th>
                    <th class="text-end pe-3">Montant Total ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $journalAchats = $rapport['journal_achats'] ?? $rapport['achats'] ?? $rapport['liste_achats'] ?? [];
                
                $totalMontantAchats = 0;
                if (empty($journalAchats)): 
                ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">Aucun achat enregistré sur cette période.</td></tr>
                <?php else: ?>
                    <?php foreach ($journalAchats as $ja): 
                        $qte = (float)($ja['poids_net'] ?? $ja['qte'] ?? 1);
                        $pu = (float)($ja['prix_unitaire'] ?? $ja['montant'] ?? 0);
                        $montantAch = (float)($ja['montant_total'] ?? $ja['montant'] ?? ($qte * $pu));
                        $totalMontantAchats += $montantAch;
                    ?>
                        <tr>
                            <td class="ps-3"><?= htmlspecialchars($ja['date_achat'] ?? $ja['date'] ?? $ja['created_at'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($ja['fournisseur_nom'] ?? $ja['libelle'] ?? $ja['designation'] ?? 'N/A') ?></td>
                            <td class="text-center"><?= number_format($qte, 2, '.', ' ') ?></td>
                            <td class="text-end"><?= number_format($pu, 2, '.', ' ') ?></td>
                            <td class="text-end pe-3 fw-bold"><?= number_format($montantAch, 2, '.', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($journalAchats)): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end ps-3">TOTAL GENERAL :</th>
                    <th class="text-end pe-3 fw-bold"><?= number_format($totalMontantAchats, 2, '.', ' ') ?> $</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- ================= 5. JOURNAL DES AVANCES ================= -->
<div class="sheet-rapport" id="bloc-journal-avances">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-hand-holding-usd"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>Journal des Avances</h5>
        <p class="small text-muted mb-0">Période du : <strong><?= date('d/m/Y', strtotime($p['debut'])) ?></strong> au <strong><?= date('d/m/Y', strtotime($p['fin'])) ?></strong></p>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-journal-avances')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Date</th>
                    <th>Fournisseur</th>
                    <th class="text-end pe-3">Montant Avancé ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $journalAvances = $rapport['journal_avances'] ?? $rapport['avances'] ?? $rapport['liste_avances'] ?? [];
                
                $totalMontantAvances = 0;
                if (empty($journalAvances)): 
                ?>
                    <tr><td colspan="3" class="text-center py-3 text-muted">Aucune avance enregistrée sur cette période.</td></tr>
                <?php else: ?>
                    <?php foreach ($journalAvances as $jv): 
                        $montantAv = (float)($jv['montant_avance'] ?? $jv['montant'] ?? $jv['valeur'] ?? 0);
                        $totalMontantAvances += $montantAv;
                    ?>
                        <tr>
                            <td class="ps-3"><?= htmlspecialchars($jv['date_avance'] ?? $jv['date'] ?? $jv['created_at'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($jv['fournisseur_nom'] ?? $jv['nom_fournisseur'] ?? $jv['nom'] ?? 'N/A') ?></td>
                            <td class="text-end pe-3 fw-bold text-warning"><?= number_format($montantAv, 2, '.', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($journalAvances)): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end ps-3">TOTAL GENERAL :</th>
                    <th class="text-end pe-3 text-warning fw-bold"><?= number_format($totalMontantAvances, 2, '.', ' ') ?> $</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- ================= 6. JOURNAL DES DÉPENSES & SORTIES DE CAISSE ================= -->
<div class="sheet-rapport" id="bloc-journal-depenses">
    <div class="rapport-header">
        <div class="rapport-header-left">
            <h3>Établissement Simulitshi</h3>
            <p class="slogan">"L'excellence et la rigueur dans la gestion des produits agricoles et de la traçabilité"</p>
        </div>
        <div class="rapport-header-right"><i class="fas fa-receipt"></i></div>
    </div>
    <div class="rapport-titre">
        <h5>Journal des Dépenses & Sorties de Caisse</h5>
        <p class="small text-muted mb-0">Période du : <strong><?= date('d/m/Y', strtotime($p['debut'])) ?></strong> au <strong><?= date('d/m/Y', strtotime($p['fin'])) ?></strong></p>
    </div>
    <div class="text-end mb-3 d-print-none">
        <button onclick="imprimerRapport('bloc-journal-depenses')" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Imprimer ce rapport</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Date</th>
                    <th>Motif / Libellé</th>
                    <th class="text-end pe-3">Montant Sortie ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $journalDepenses = $rapport['journal_depenses'] ?? $rapport['depenses'] ?? [];
                $totalMontantDepenses = 0;
                if (empty($journalDepenses)): 
                ?>
                    <tr><td colspan="3" class="text-center py-3 text-muted">Aucune dépense enregistrée sur cette période.</td></tr>
                <?php else: ?>
                    <?php foreach ($journalDepenses as $dep): 
                        $montantDep = (float)($dep['montant'] ?? 0);
                        $totalMontantDepenses += $montantDep;
                    ?>
                        <tr>
                            <td class="ps-3"><?= htmlspecialchars($dep['date_operation'] ?? $dep['date'] ?? '') ?></td>
                            <td><?= htmlspecialchars($dep['libelle'] ?? $dep['motif'] ?? 'Sortie de caisse') ?></td>
                            <td class="text-end pe-3 fw-bold text-danger"><?= number_format($montantDep, 2, '.', ' ') ?> $</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($journalDepenses)): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end ps-3">TOTAL GENERAL :</th>
                    <th class="text-end pe-3 text-danger fw-bold"><?= number_format($totalMontantDepenses, 2, '.', ' ') ?> $</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<script>
function imprimerRapport(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add('is-printing');
        window.print();
        element.classList.remove('is-printing');
    }
}
</script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>