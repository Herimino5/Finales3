<?php 
$title = "BNGRC - Gestion des Dons pour Sinistrés"; 
$activePage = 'dashboard';
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
                                    <h3 class="text-success">1,250</h3>
                                    <p>Dons en Nature</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card materiel">
                                    <i class="bi bi-tools text-info" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-info">850</h3>
                                    <p>Dons Matériaux</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card argent">
                                    <i class="bi bi-cash-coin text-warning" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-warning">2.5M Ar</h3>
                                    <p>Dons en Argent</p>
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
                            <!-- Ville 1: Antananarivo -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Antananarivo
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>320 / 500</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 64%" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100">
                                                64%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>180 / 350</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 51%" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100">
                                                51%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>800K / 1.2M Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100">
                                                67%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 2: Antsirabe -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Antsirabe
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>150 / 300</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>90 / 200</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                                                45%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>300K / 800K Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 38%" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100">
                                                38%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 3: Fianarantsoa -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Fianarantsoa
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>200 / 400</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>120 / 250</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100">
                                                48%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>400K / 900K Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 44%" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                                                44%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 4: Toamasina -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Toamasina
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>280 / 450</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
                                                62%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>160 / 300</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 53%" aria-valuenow="53" aria-valuemin="0" aria-valuemax="100">
                                                53%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>500K / 1M Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dernières distributions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-clock-history me-2"></i>Dernières Distributions
                                </h3>
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Ville</th>
                                                <th>Type</th>
                                                <th>Quantité</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>16/02/2026 14:30</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>150 kg de riz</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 13:15</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Toamasina</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>50 tôles</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 11:00</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antsirabe</td>
                                                <td><span class="badge bg-warning text-dark badge-custom">Argent</span></td>
                                                <td>200,000 Ar</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 09:45</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Fianarantsoa</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>80 L d'huile</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

    <script>
        // Scripts spécifiques à cette page
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
