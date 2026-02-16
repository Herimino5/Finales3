<?php
namespace app\models;

class BesoinModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère la liste des besoins et des dons pour chaque ville à partir de la vue.
     */
    public function getBesoinDonsParVille() {
        $sql = "SELECT * FROM v_ville_besoins_dons";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les besoins avec pagination
     */
    public function getBesoinsPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT b.*, v.nom as ville_nom, p.nom as produit_nom, 
                       p.prix_unitaire, c.nom as categorie_nom
                FROM s3fin_besoin b
                LEFT JOIN s3fin_ville v ON b.ville_id = v.id
                LEFT JOIN s3fin_product p ON b.id_product = p.id
                LEFT JOIN s3fin_categorie c ON p.categorie_id = c.id
                ORDER BY b.Date_saisie DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de besoins
     */
    public function countBesoins() {
        $sql = "SELECT COUNT(*) as total FROM s3fin_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    public function insertBesoin($data) {
        $sql = "INSERT INTO s3fin_besoin 
                (ville_id, Date_saisie, id_product, descriptions, quantite) 
                VALUES 
                (:ville_id, :Date_saisie, :id_product, :descriptions, :quantite)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            ':ville_id'    => $data['ville_id'],
            ':Date_saisie' => $data['Date_saisie'],
            ':id_product'  => $data['id_product'],
            ':descriptions'=> $data['descriptions'],
            ':quantite'    => $data['quantite']
        ]);
        
        return $this->db->lastInsertId();
    }

}
