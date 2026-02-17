<?php
namespace app\service;

class DistributionProportionnel {
    
    // Distribue un don proportionnellement aux besoins
    // Arrondi vers le bas pour éviter de dépasser le don disponible
    public function distribuer(array $besoins, float $don): array {
        $distribution = [];
        
        // Calcul du total des besoins
        $totalBesoins = array_sum($besoins);
        
        // Si aucun besoin, rien à distribuer
        if ($totalBesoins <= 0) {
            return array_fill_keys(array_keys($besoins), 0);
        }
        
        // Distribution proportionnelle avec arrondi inférieur
        foreach ($besoins as $key => $besoin) {
            $proportion = $besoin / $totalBesoins;
            $distribution[$key] = floor($don * $proportion);
        }
        
        return $distribution;
    }
    
    // Retourne le reste non distribué après la distribution
    public function getReste(array $besoins, float $don): float {
        $distribution = $this->distribuer($besoins, $don);
        return $don - array_sum($distribution);
    }
}
?>