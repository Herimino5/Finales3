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

            // Nombre de dons (total et non-initiaux)
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_don");
            $totalDons = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
            
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_don WHERE initial IS NULL");
            $donsUtilisateurs = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Nombre de besoins (total et non-initiaux)
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_besoin");
            $totalBesoins = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
            
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM s3fin_besoin WHERE initial IS NULL");
            $besoinsUtilisateurs = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            return [
                'success' => true,
                'total_distributions' => (int)$totalDistributions,
                'total_quantite_distribuee' => (float)$totalQuantiteDistribuee,
                'total_achats' => (int)$totalAchats,
                'montant_achats' => (float)$montantAchats,
                'total_dons' => (int)$totalDons,
                'dons_utilisateurs' => (int)$donsUtilisateurs,
                'total_besoins' => (int)$totalBesoins,
                'besoins_utilisateurs' => (int)$besoinsUtilisateurs
            ];
        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la lecture de l\'état : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Réinitialiser : supprimer distributions, achats et données utilisateur,
     * puis restaurer les copies des données initiales
     */
    public function reinitialiser() {
        // Récupérer l'état avant réinitialisation
        $etatAvant = $this->getEtatActuel();
        
        if ($etatAvant['total_distributions'] === 0 && 
            $etatAvant['total_achats'] === 0 && 
            $etatAvant['dons_utilisateurs'] === 0 && 
            $etatAvant['besoins_utilisateurs'] === 0) {
            return [
                'success' => false,
                'message' => 'Rien à réinitialiser. Aucune donnée utilisateur ou distribution enregistrée.'
            ];
        }

        try {
            $this->db->beginTransaction();

            // Étape 1 : Sauvegarder les données initiales (si pas déjà fait)
            $this->sauvegarderDonneesInitiales();

            // Étape 2 : Supprimer toutes les distributions
            $stmtDist = $this->db->exec("DELETE FROM s3fin_distribution");

            // Étape 3 : Supprimer tous les achats
            $stmtAchat = $this->db->exec("DELETE FROM s3fin_achat");

            // Étape 4 : Supprimer les dons créés par l'utilisateur (initial IS NULL)
            $stmtDonsUser = $this->db->exec("DELETE FROM s3fin_don WHERE initial IS NULL");

            // Étape 5 : Supprimer les besoins créés par l'utilisateur (initial IS NULL)
            $stmtBesoinsUser = $this->db->exec("DELETE FROM s3fin_besoin WHERE initial IS NULL");

            // Étape 6 : Restaurer les copies des données initiales
            $this->restaurerDonneesInitiales();

            $this->db->commit();

            // Réinitialiser les auto-increments (hors transaction)
            try {
                $this->db->exec("ALTER TABLE s3fin_distribution AUTO_INCREMENT = 1");
                $this->db->exec("ALTER TABLE s3fin_achat AUTO_INCREMENT = 1");
            } catch (\PDOException $e) {
                // Non bloquant
            }

            return [
                'success' => true,
                'message' => 'Réinitialisation effectuée avec succès.',
                'supprime' => [
                    'distributions' => (int)$stmtDist,
                    'achats' => (int)$stmtAchat,
                    'dons_utilisateurs' => (int)$stmtDonsUser,
                    'besoins_utilisateurs' => (int)$stmtBesoinsUser,
                    'quantite_distribuee' => $etatAvant['total_quantite_distribuee'],
                    'montant_achats' => $etatAvant['montant_achats']
                ],
                'conserve' => [
                    'dons_initiaux' => $etatAvant['total_dons'] - $etatAvant['dons_utilisateurs'],
                    'besoins_initiaux' => $etatAvant['total_besoins'] - $etatAvant['besoins_utilisateurs']
                ],
                'restaure' => [
                    'message' => 'Données initiales restaurées à leur état d\'origine'
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

    /**
     * Sauvegarder les données initiales dans une table temporaire
     */
    private function sauvegarderDonneesInitiales() {
        // Créer tables de sauvegarde si elles n'existent pas
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS s3fin_besoin_backup (
                id INT PRIMARY KEY,
                ville_id INT NOT NULL,
                Date_saisie DATETIME,
                id_product INT,
                descriptions VARCHAR(255),
                quantite INT NOT NULL
            )
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS s3fin_don_backup (
                id INT PRIMARY KEY,
                id_product INT,
                descriptions VARCHAR(255),
                quantite INT NOT NULL,
                date_saisie TIMESTAMP
            )
        ");

        // Sauvegarder uniquement les données initiales si pas déjà sauvegardées
        $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM s3fin_besoin_backup");
        if ($stmt->fetch(\PDO::FETCH_ASSOC)['cnt'] == 0) {
            $this->db->exec("
                INSERT INTO s3fin_besoin_backup 
                SELECT id, ville_id, Date_saisie, id_product, descriptions, quantite 
                FROM s3fin_besoin 
                WHERE initial = 'initial'
            ");
        }

        $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM s3fin_don_backup");
        if ($stmt->fetch(\PDO::FETCH_ASSOC)['cnt'] == 0) {
            $this->db->exec("
                INSERT INTO s3fin_don_backup 
                SELECT id, id_product, descriptions, quantite, date_saisie 
                FROM s3fin_don 
                WHERE initial = 'initial'
            ");
        }
    }

    /**
     * Restaurer les données initiales depuis la sauvegarde
     */
    private function restaurerDonneesInitiales() {
        // Supprimer les données initiales actuelles (qui peuvent avoir été modifiées)
        $this->db->exec("DELETE FROM s3fin_besoin WHERE initial = 'initial'");
        $this->db->exec("DELETE FROM s3fin_don WHERE initial = 'initial'");

        // Restaurer depuis la sauvegarde
        $this->db->exec("
            INSERT INTO s3fin_besoin (id, ville_id, Date_saisie, id_product, descriptions, quantite, initial)
            SELECT id, ville_id, Date_saisie, id_product, descriptions, quantite, 'initial'
            FROM s3fin_besoin_backup
        ");

        $this->db->exec("
            INSERT INTO s3fin_don (id, id_product, descriptions, quantite, date_saisie, initial)
            SELECT id, id_product, descriptions, quantite, date_saisie, 'initial'
            FROM s3fin_don_backup
        ");
    }
}