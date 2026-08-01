<?php

namespace App\Services;

use App\Repositories\RapportRepository;
use App\Repositories\CaisseRepository;
use App\Repositories\StockRepository;

class RapportService
{
    private RapportRepository $rapportRepo;
    private CaisseRepository $caisseRepo;
    private StockRepository $stockRepo;

    public function __construct()
    {
        $this->rapportRepo = new RapportRepository();
        $this->caisseRepo = new CaisseRepository();
        $this->stockRepo = new StockRepository();
    }

    /**
     * Méthode appelée par le RapportController pour récupérer l'ensemble des données
     */
    public function getRapportComplet(string $debut, string $fin, int $posteId = 1): array
    {
        return $this->getTousLesRapports($posteId, $debut, $fin);
    }

    public function getTousLesRapports(int $posteId, string $debut, string $fin): array
    {
        $dateLimitActif = date('Y-m-d', strtotime('-3 months'));

        return [
            'periode' => ['debut' => $debut, 'fin' => $fin],
            'synthese' => $this->rapportRepo->getSynthesePoste($posteId, $debut, $fin),
            'synthese_poste' => $this->rapportRepo->getSynthesePoste($posteId, $debut, $fin),
            'producteurs_tous' => $this->rapportRepo->getProducteursActivite($dateLimitActif),
            'situation_dettes' => $this->rapportRepo->getSituationDettesAvances(),
            'journal_achats' => $this->rapportRepo->getJournalAchats($debut, $fin),
            'journal_avances' => $this->rapportRepo->getJournalAvances($debut, $fin),
            'journal_depenses' => $this->rapportRepo->getJournalDepenses($debut, $fin),
            'livre_caisse' => $this->caisseRepo->getMouvementsByPoste($posteId),
            'solde_caisse' => $this->caisseRepo->getSoldeActuel($posteId),
            'stocks_actuels' => $this->stockRepo->getStockActuelMagasin($posteId)
        ];
    }
}