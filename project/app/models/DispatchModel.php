<?php
namespace app\models;

class DispatchModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère un besoin avec ses informations de distribution
     * 
     * @param int $besoinId ID du besoin
     * @return array|null Données du besoin ou null
     */
    public function getBesoinById($besoinId) {
        $sql = "SELECT b.id, b.quantite as quantite_demandee, b.id_product, b.ville_id,
                       b.descriptions, b.Date_saisie,
                       p.nom as produit_nom, p.prix_unitaire,
                       c.nom as categorie_nom,
                       v.nom as ville_nom,
                       COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0) as quantite_distribuee
                FROM s3fin_besoin b
                JOIN s3fin_product p ON b.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                JOIN s3fin_ville v ON b.ville_id = v.id
                WHERE b.id = :besoin_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':besoin_id' => $besoinId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons disponibles (nature/matériaux) pour un produit
     * 
     * @param int $productId ID du produit
     * @return array Liste des dons disponibles
     */
    public function getDonsDisponiblesNatureMateriaux($productId) {
        $sql = "SELECT d.id, d.quantite, d.date_saisie,
                       COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0) as distribue,
                       (d.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0)) as disponible
                FROM s3fin_don d
                JOIN s3fin_product p ON d.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                WHERE d.id_product = :product_id 
                AND c.nom != 'Argent'
                HAVING disponible > 0
                ORDER BY d.date_saisie ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le total des dons disponibles pour un produit
     * 
     * @param int $productId ID du produit
     * @return float Total disponible
     */
    public function getTotalDonsDisponibles($productId) {
        $dons = $this->getDonsDisponiblesNatureMateriaux($productId);
        $total = 0;
        
        foreach ($dons as $don) {
            $total += $don['disponible'];
        }
        
        return $total;
    }

    /**
     * Récupère un don par son ID
     * 
     * @param int $donId ID du don
     * @return array|null Données du don ou null
     */
    public function getDonById($donId) {
        $sql = "SELECT d.id, d.quantite, d.id_product, d.descriptions, d.date_saisie,
                       COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0) as distribue,
                       p.nom as produit_nom,
                       c.nom as categorie_nom
                FROM s3fin_don d
                JOIN s3fin_product p ON d.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                WHERE d.id = :don_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':don_id' => $donId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un achat par son ID
     * 
     * @param int $achatId ID de l'achat
     * @return array|null Données de l'achat ou null
     */
    public function getAchatById($achatId) {
        $sql = "SELECT a.*, p.nom as produit_nom, c.nom as categorie_nom
                FROM s3fin_achat a
                JOIN s3fin_product p ON a.product_id = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                WHERE a.id = :achat_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':achat_id' => $achatId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Crée une distribution (don → besoin)
     * 
     * @param int $besoinId ID du besoin
     * @param int $donId ID du don
     * @param float $quantite Quantité à distribuer
     * @return int ID de la distribution créée
     */
    public function creerDistribution($besoinId, $donId, $quantite) {
        $sql = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution)
                VALUES (:besoin_id, :don_id, :quantite, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':besoin_id' => $besoinId,
            ':don_id' => $donId,
            ':quantite' => $quantite
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Crée une distribution avec achat
     * 
     * @param int $besoinId ID du besoin
     * @param int $donId ID du don (argent)
     * @param int $achatId ID de l'achat
     * @param float $quantite Quantité à distribuer
     * @return int ID de la distribution créée
     */
    public function creerDistributionAvecAchat($besoinId, $donId, $achatId, $quantite) {
        $sql = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution, achat_id)
                VALUES (:besoin_id, :don_id, :quantite, NOW(), :achat_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':besoin_id' => $besoinId,
            ':don_id' => $donId,
            ':quantite' => $quantite,
            ':achat_id' => $achatId
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Récupère les besoins non couverts
     * 
     * @param int|null $villeId ID de la ville (null pour toutes)
     * @return array Liste des besoins non couverts
     */
    public function getBesoinsNonCouverts($villeId = null) {
        $sql = "SELECT b.id, b.quantite as quantite_demandee, b.descriptions,
                       b.Date_saisie, b.id_product, b.ville_id,
                       p.nom as produit_nom, p.prix_unitaire,
                       c.nom as categorie_nom,
                       v.nom as ville_nom,
                       COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0) as quantite_distribuee,
                       (b.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0)) as quantite_restante
                FROM s3fin_besoin b
                JOIN s3fin_product p ON b.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                JOIN s3fin_ville v ON b.ville_id = v.id
                WHERE (b.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0)) > 0";
        
        $params = [];
        if ($villeId) {
            $sql .= " AND b.ville_id = :ville_id";
            $params[':ville_id'] = $villeId;
        }
        
        $sql .= " ORDER BY b.Date_saisie ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons argent disponibles
     * 
     * @return array Liste des dons argent disponibles
     */
    public function getDonsArgentDisponibles() {
        $sql = "SELECT d.id AS don_id,
                       d.descriptions,
                       d.quantite AS montant_total,
                       d.date_saisie,
                       COALESCE(SUM(a.montant_total), 0) AS montant_utilise,
                       (d.quantite - COALESCE(SUM(a.montant_total), 0)) AS montant_disponible
                FROM s3fin_don d
                JOIN s3fin_product p ON d.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                LEFT JOIN s3fin_achat a ON d.id = a.don_id
                WHERE c.nom = 'Argent'
                GROUP BY d.id, d.descriptions, d.quantite, d.date_saisie
                HAVING montant_disponible > 0
                ORDER BY d.date_saisie ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les achats effectués
     * 
     * @param int|null $villeId ID de la ville (null pour tous)
     * @return array Liste des achats
     */
    public function getAchats($villeId = null) {
        $sql = "SELECT a.id AS achat_id,
                       a.date_achat,
                       v.id AS ville_id,
                       v.nom AS ville_nom,
                       p.nom AS produit_nom,
                       a.quantite,
                       a.prix_unitaire,
                       a.frais_pourcentage,
                       a.montant_ht,
                       a.montant_frais,
                       a.montant_total,
                       d.descriptions AS don_description,
                       b.descriptions AS besoin_description
                FROM s3fin_achat a
                JOIN s3fin_product p ON a.product_id = p.id
                JOIN s3fin_don d ON a.don_id = d.id
                LEFT JOIN s3fin_ville v ON a.ville_id = v.id
                LEFT JOIN s3fin_besoin b ON a.besoin_id = b.id";
        
        $params = [];
        if ($villeId) {
            $sql .= " WHERE a.ville_id = :ville_id";
            $params[':ville_id'] = $villeId;
        }
        
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si des dons nature/matériaux sont suffisants pour couvrir un besoin
     * 
     * @param int $besoinId ID du besoin
     * @return bool True si suffisant, false sinon
     */
    public function hasDonsSuffisants($besoinId) {
        $besoin = $this->getBesoinById($besoinId);
        if (!$besoin) {
            return false;
        }
        
        $quantiteRestante = $besoin['quantite_demandee'] - $besoin['quantite_distribuee'];
        $donsDisponibles = $this->getTotalDonsDisponibles($besoin['id_product']);
        
        return $donsDisponibles >= $quantiteRestante;
    }

    /**
     * Démarre une transaction
     */
    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    /**
     * Valide une transaction
     */
    public function commit() {
        $this->db->commit();
    }

    /**
     * Annule une transaction
     */
    public function rollBack() {
        $this->db->rollBack();
    }
}
