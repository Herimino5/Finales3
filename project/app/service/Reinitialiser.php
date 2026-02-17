<?php
// filepath: /opt/lampp/htdocs/Finales3/project/app/service/Reinitialiser.php
namespace app\service;

class Reinitialiser {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupérer l'état actuel avant réinitialisation (pour confirmation)
     */
    public function getEtatActuel() {
        try {
            // Nombre de distributions
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_distribution");
            $totalDistributions = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Quantité totale distribuée
            $stmt = $this->db->query("SELECT COALESCE(SUM(quantite_distribuee), 0) AS total FROM s3fin_distribution");
            $totalQuantiteDistribuee = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Nombre d'achats
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_achat");
            $totalAchats = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Montant total des achats
            $stmt = $this->db->query("SELECT COALESCE(SUM(montant_total), 0) AS total FROM s3fin_achat");
            $montantAchats = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Nombre de dons
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_don");
            $totalDons = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Nombre de besoins
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_besoin");
            $totalBesoins = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            return [
                'success' => true,
                'total_distributions' => (int)$totalDistributions,
                'total_quantite_distribuee' => (float)$totalQuantiteDistribuee,
                'total_achats' => (int)$totalAchats,
                'montant_achats' => (float)$montantAchats,
                'total_dons' => (int)$totalDons,
                'total_besoins' => (int)$totalBesoins
            ];
        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la lecture de l\'état : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Réinitialiser : supprimer toutes les distributions et achats
     * Les dons et besoins restent intacts (données initiales)
     */
    public function reinitialiser() {
        // Récupérer l'état avant réinitialisation
        $etatAvant = $this->getEtatActuel();

        if ($etatAvant['total_distributions'] === 0 && $etatAvant['total_achats'] === 0) {
            return [
                'success' => false,
                'message' => 'Rien à réinitialiser. Aucune distribution ni achat enregistré.'
            ];
        }

        try {
            // Étape 1 : Supprimer les données dans une transaction
            $this->db->beginTransaction();

            $stmtDist = $this->db->exec("DELETE FROM s3fin_distribution");
            $stmtAchat = $this->db->exec("DELETE FROM s3fin_achat");

            $this->db->commit();

            // Étape 2 : Réinitialiser les auto-increments HORS transaction
            // (ALTER TABLE fait un commit implicite en MySQL, incompatible avec rollBack)
            try {
                $this->db->exec("ALTER TABLE s3fin_distribution AUTO_INCREMENT = 1");
                $this->db->exec("ALTER TABLE s3fin_achat AUTO_INCREMENT = 1");
            } catch (\PDOException $e) {
                // Non bloquant : les auto-increments ne se réinitialisent pas mais les données sont supprimées
            }

            return [
                'success' => true,
                'message' => 'Réinitialisation effectuée avec succès.',
                'supprime' => [
                    'distributions' => (int)$stmtDist,
                    'achats' => (int)$stmtAchat,
                    'quantite_distribuee' => $etatAvant['total_quantite_distribuee'],
                    'montant_achats' => $etatAvant['montant_achats']
                ],
                'conserve' => [
                    'dons' => $etatAvant['total_dons'],
                    'besoins' => $etatAvant['total_besoins']
                ]
            ];

        } catch (\PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return [
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()
            ];
        }
    }
}