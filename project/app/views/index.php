<?php 
$title = "BNGRC - Gestion des Dons pour Sinistrés"; 
$activePage = 'dashboard';

// Organiser les données par ville
$villesData = [];
$totalDons = 0;
$totalBesoins = 0;

if (!empty($data)) {
    foreach ($data as $row) {
        $villeId = $row['id_ville'];
        $villeName = $row['nom_ville'];
        
        if (!isset($villesData[$villeId])) {
            $villesData[$villeId] = [
                'nom' => $villeName,
                'produits' => []
            ];
        }
        
        $villesData[$villeId]['produits'][] = [
            'nom' => $row['nom_produit'],
            'besoin' => $row['quantite_besoin'],
            'don' => $row['quantite_don'],
            'reste' => $row['reste_a_trouver']
        ];
        
        $totalDons += $row['quantite_don'];
        $totalBesoins += $row['quantite_besoin'];
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-bar-chart-fill"></i> Tableau de Bord
                        </h2>

                        <!-- Statistiques globales -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-card nature">
                                    <i class="bi bi-basket3 text-success" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-success"><?= number_format($totalDons) ?></h3>
                                    <p>Total Dons Reçus</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card materiel">
                                    <i class="bi bi-exclamation-circle text-info" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-info"><?= number_format($totalBesoins) ?></h3>
                                    <p>Total Besoins</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card argent">
                                    <i class="bi bi-building text-warning" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-warning"><?= count($villesData) ?></h3>
                                    <p>Villes Concernées</p>
                                </div>
                            </div>
                        </div>

                        <!-- Vue par ville -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-map me-2"></i>Besoins et Dons par Ville
                                </h3>
                            </div>
                        </div>

                        <div class="row">
                            <?php if (!empty($villesData)): ?>
                                <?php foreach ($villesData as $villeId => $ville): ?>
                                <div class="col-md-6">
                                    <div class="city-card">
                                        <h4>
                                            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($ville['nom']) ?>
                                        </h4>
                                        
                                        <?php foreach ($ville['produits'] as $produit): 
                                            $besoin = $produit['besoin'];
                                            $don = $produit['don'];
                                            $pourcentage = $besoin > 0 ? round(($don / $besoin) * 100) : 0;
                                            $pourcentage = min($pourcentage, 100); // Cap à 100%
                                        ?>
                                        <div class="mb-3">
                                            <div class="progress-label">
                                                <span><i class="bi bi-box-seam text-primary"></i> <?= htmlspecialchars($produit['nom']) ?></span>
                                                <strong><?= number_format($don) ?> / <?= number_format($besoin) ?></strong>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar <?= $pourcentage >= 75 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-info' : ($pourcentage >= 25 ? 'bg-warning' : 'bg-danger')) ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $pourcentage ?>%" 
                                                     aria-valuenow="<?= $pourcentage ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= $pourcentage ?>%
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Aucune donnée disponible. Veuillez ajouter des besoins et des dons.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tableau récapitulatif -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-table me-2"></i>Détails par Produit
                                </h3>
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Besoin</th>
                                                <th>Don Reçu</th>
                                                <th>Reste à trouver</th>
                                                <th>Progression</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($data)): ?>
                                                <?php foreach ($data as $row): 
                                                    $pourcentage = $row['quantite_besoin'] > 0 ? round(($row['quantite_don'] / $row['quantite_besoin']) * 100) : 0;
                                                    $pourcentage = min($pourcentage, 100);
                                                ?>
                                                <tr>
                                                    <td><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($row['nom_ville']) ?></td>
                                                    <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                                                    <td><?= number_format($row['quantite_besoin']) ?></td>
                                                    <td class="text-success"><?= number_format($row['quantite_don']) ?></td>
                                                    <td class="<?= $row['reste_a_trouver'] > 0 ? 'text-danger' : 'text-success' ?>">
                                                        <?= number_format($row['reste_a_trouver']) ?>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar <?= $pourcentage >= 75 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-info' : ($pourcentage >= 25 ? 'bg-warning' : 'bg-danger')) ?>" 
                                                                 style="width: <?= $pourcentage ?>%">
                                                                <?= $pourcentage ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucune donnée disponible</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

    <script>
        // Scripts spécifiques à cette page
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
