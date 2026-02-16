<?php
namespace app\models;

class AchatModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function simulateAchat($donArgentId, $productId, $quantite, $frais = 0, $besoinId = null) {
        try {
            $sqlProduit = "SELECT id, nom, prix_unitaire, categorie_id FROM s3fin_product WHERE id = :id";
            $stmtProduit = $this->db->prepare($sqlProduit);
            $stmtProduit->execute([':id' => $productId]);
            $produit = $stmtProduit->fetch(\PDO::FETCH_ASSOC);

            if (!$produit) {
                return ['success' => false, 'message' => 'Produit introuvable'];
            }

            $sqlDonsNature = "SELECT COALESCE(SUM(d.quantite - COALESCE((SELECT SUM(dist.quantite_distribuee) FROM s3fin_distribution dist WHERE dist.don_id = d.id), 0)), 0) as dons_disponibles
                              FROM s3fin_don d
                              JOIN s3fin_product p ON d.id_product = p.id
                              JOIN s3fin_categorie c ON p.categorie_id = c.id
                              WHERE d.id_product = :product_id AND c.nom != 'Argent'
                              HAVING dons_disponibles > 0";
            $stmtDonsNature = $this->db->prepare($sqlDonsNature);
            $stmtDonsNature->execute([':product_id' => $productId]);
            $donsNature = $stmtDonsNature->fetch(\PDO::FETCH_ASSOC);

            if ($donsNature && $donsNature['dons_disponibles'] > 0) {
                return [
                    'success' => false,
                    'message' => 'Achat refusé : Des dons en nature/matériaux (' . $donsNature['dons_disponibles'] . ' unités) sont encore disponibles pour ce produit. Utilisez d\'abord les dons existants.',
                    'dons_nature_disponibles' => $donsNature['dons_disponibles']
                ];
            }

            $sqlDon = "SELECT d.id, d.quantite, d.descriptions,
                       COALESCE((SELECT SUM(montant_total) FROM s3fin_achat WHERE don_id = d.id), 0) as montant_utilise
                       FROM s3fin_don d
                       JOIN s3fin_product p ON d.id_product = p.id
                       JOIN s3fin_categorie c ON p.categorie_id = c.id
                       WHERE d.id = :don_id AND c.nom = 'Argent'";
            $stmtDon = $this->db->prepare($sqlDon);
            $stmtDon->execute([':don_id' => $donArgentId]);
            $don = $stmtDon->fetch(\PDO::FETCH_ASSOC);

            if (!$don) {
                return ['success' => false, 'message' => 'Don en argent introuvable ou invalide'];
            }

            $montantDisponible = $don['quantite'] - $don['montant_utilise'];
            $prixUnitaire = $produit['prix_unitaire'] ?? 0;
            $coutBase = $quantite * $prixUnitaire;
            $montantFrais = $coutBase * ($frais / 100);
            $coutTotal = $coutBase + $montantFrais;

            if ($coutTotal > $montantDisponible) {
                return [
                    'success' => false,
                    'message' => 'Fonds insuffisants',
                    'produit' => $produit['nom'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'frais_pourcentage' => $frais,
                    'cout_base' => $coutBase,
                    'montant_frais' => $montantFrais,
                    'cout_total' => $coutTotal,
                    'montant_disponible' => $montantDisponible,
                    'deficit' => $coutTotal - $montantDisponible
                ];
            }

            return [
                'success' => true,
                'message' => 'Simulation réussie',
                'produit' => $produit['nom'],
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'frais_pourcentage' => $frais,
                'cout_base' => $coutBase,
                'montant_frais' => $montantFrais,
                'cout_total' => $coutTotal,
                'montant_disponible' => $montantDisponible,
                'reste_apres_achat' => $montantDisponible - $coutTotal
            ];

        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }

    public function createAchat($donArgentId, $productId, $quantite, $frais = 0, $besoinId = null) {
        try {
            $simulation = $this->simulateAchat($donArgentId, $productId, $quantite, $frais);
            
            if (!$simulation['success']) {
                return $simulation;
            }

            $this->db->beginTransaction();

            $villeId = null;
            if ($besoinId) {
                $sqlVille = "SELECT ville_id FROM s3fin_besoin WHERE id = :besoin_id";
                $stmtVille = $this->db->prepare($sqlVille);
                $stmtVille->execute([':besoin_id' => $besoinId]);
                $besoinVille = $stmtVille->fetch(\PDO::FETCH_ASSOC);
                $villeId = $besoinVille ? $besoinVille['ville_id'] : null;
            }

            $sqlAchat = "INSERT INTO s3fin_achat (don_id, product_id, besoin_id, ville_id, quantite, prix_unitaire, frais_pourcentage, montant_ht, montant_frais, montant_total, date_achat)
                         VALUES (:don_id, :product_id, :besoin_id, :ville_id, :quantite, :prix_unitaire, :frais, :montant_ht, :montant_frais, :montant_total, NOW())";
            $stmtAchat = $this->db->prepare($sqlAchat);
            $stmtAchat->execute([
                ':don_id' => $donArgentId,
                ':product_id' => $productId,
                ':besoin_id' => $besoinId,
                ':ville_id' => $villeId,
                ':quantite' => $quantite,
                ':prix_unitaire' => $simulation['prix_unitaire'],
                ':frais' => $frais,
                ':montant_ht' => $simulation['cout_base'],
                ':montant_frais' => $simulation['montant_frais'],
                ':montant_total' => $simulation['cout_total']
            ]);
            
            $achatId = $this->db->lastInsertId();

            if ($besoinId) {
                $sqlBesoin = "SELECT id, quantite, id_product,
                              COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = s3fin_besoin.id), 0) as deja_distribue
                              FROM s3fin_besoin 
                              WHERE id = :besoin_id AND id_product = :product_id";
                $stmtBesoin = $this->db->prepare($sqlBesoin);
                $stmtBesoin->execute([':besoin_id' => $besoinId, ':product_id' => $productId]);
                $besoin = $stmtBesoin->fetch(\PDO::FETCH_ASSOC);

                if (!$besoin) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => 'Besoin introuvable ou ne correspond pas au produit'];
                }

                $quantiteRestante = $besoin['quantite'] - $besoin['deja_distribue'];
                
                if ($quantiteRestante <= 0) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => 'Ce besoin est déjà entièrement couvert'];
                }

                $quantiteADistribuer = min($quantite, $quantiteRestante);

                $sqlDistrib = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution, achat_id)
                               VALUES (:besoin_id, :don_id, :quantite, NOW(), :achat_id)";
                $stmtDistrib = $this->db->prepare($sqlDistrib);
                $stmtDistrib->execute([
                    ':besoin_id' => $besoinId,
                    ':don_id' => $donArgentId,
                    ':quantite' => $quantiteADistribuer,
                    ':achat_id' => $achatId
                ]);
            }

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Achat créé avec succès',
                'achat_id' => $achatId,
                'montant_utilise' => $simulation['cout_total'],
                'reste_disponible' => $simulation['reste_apres_achat']
            ];

        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }

    public function getAllAchats() {
        try {
            $sql = "SELECT a.id, a.quantite, a.prix_unitaire, a.frais_pourcentage, 
                           a.montant_ht, a.montant_frais, a.montant_total, a.date_achat,
                           p.nom AS produit_nom,
                           d.descriptions AS don_description
                    FROM s3fin_achat a
                    JOIN s3fin_product p ON a.product_id = p.id
                    JOIN s3fin_don d ON a.don_id = d.id
                    ORDER BY a.date_achat DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }

    public function getDonsArgentDisponibles() {
        try {
            $sql = "SELECT d.id, d.descriptions, d.quantite as montant_total, d.date_saisie,
                           COALESCE((SELECT SUM(montant_total) FROM s3fin_achat WHERE don_id = d.id), 0) as montant_utilise,
                           (d.quantite - COALESCE((SELECT SUM(montant_total) FROM s3fin_achat WHERE don_id = d.id), 0)) as montant_disponible
                    FROM s3fin_don d
                    JOIN s3fin_product p ON d.id_product = p.id
                    JOIN s3fin_categorie c ON p.categorie_id = c.id
                    WHERE c.nom = 'Argent'
                    HAVING montant_disponible > 0
                    ORDER BY d.date_saisie ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }

    public function getAchatsByVille($villeId) {
        try {
            $sql = "SELECT a.id, a.quantite, a.prix_unitaire, a.frais_pourcentage, 
                           a.montant_ht, a.montant_frais, a.montant_total, a.date_achat,
                           p.nom AS produit_nom,
                           v.nom AS ville_nom,
                           d.descriptions AS don_description,
                           b.descriptions AS besoin_description
                    FROM s3fin_achat a
                    JOIN s3fin_product p ON a.product_id = p.id
                    JOIN s3fin_don d ON a.don_id = d.id
                    LEFT JOIN s3fin_ville v ON a.ville_id = v.id
                    LEFT JOIN s3fin_besoin b ON a.besoin_id = b.id
                    WHERE a.ville_id = :ville_id
                    ORDER BY a.date_achat DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':ville_id' => $villeId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }
}
