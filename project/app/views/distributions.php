<?php 
$title = "Distributions - BNGRC"; 
$activePage = 'distributions';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 style="color: #2c3e50; font-weight: 700;">
                                <i class="bi bi-arrow-left-right"></i> Distribution des Dons
                            </h2>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalReinit">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                            </button>
                        </div>

                        <?php if(isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Informations -->
                        <div class="alert-info-custom">
                            <h5><i class="bi bi-info-circle-fill me-2"></i> Algorithme de Distribution</h5>
                            <p class="mb-0">Choisissez le mode de distribution et lancez la simulation. Le système attribuera automatiquement les dons disponibles aux besoins selon l'algorithme sélectionné.</p>
                        </div>

                        <!-- Bouton de simulation -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-play-circle me-2"></i> Lancer la Distribution
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= BASE_URL ?>distribuerAutomatique">
                                    <!-- Sélection du mode de distribution -->
                                    <div class="row mb-4">
                                        <div class="col-md-8 offset-md-2">
                                            <label for="mode" class="form-label fw-bold">
                                                <i class="bi bi-gear-fill me-2"></i>Mode de Distribution
                                            </label>
                                            <select name="mode" id="mode" class="form-select form-select-lg" required>
                                                <option value="fifo" <?= (isset($mode) && $mode == 'fifo') ? 'selected' : '' ?>>
                                                    📅 FIFO (First In, First Out) - Par ordre de date
                                                </option>
                                                <option value="proportionnel" <?= (isset($mode) && $mode == 'proportionnel') ? 'selected' : '' ?>>
                                                    ⚖️ Proportionnel - Distribution équitable par ville
                                                </option>
                                                <option value="quantite" <?= (isset($mode) && $mode == 'quantite') ? 'selected' : '' ?>>
                                                    📊 Par Quantité - Priorité aux petits besoins
                                                </option>
                                            </select>
                                            <div class="form-text mt-2" id="modeDescription">
                                                <?php if(isset($mode) && $mode == 'quantite'): ?>
                                                    <strong>Par Quantité:</strong> Priorité aux besoins les plus petits. Optimise la satisfaction du maximum de besoins.
                                                <?php else: ?>
                                                    <strong>FIFO:</strong> Les besoins les plus anciens sont satisfaits en priorité avec les dons les plus anciens.
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary-custom btn-custom btn-lg">
                                            <i class="bi bi-play-fill me-2"></i> Distribuer Automatiquement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php if(isset($resultats) && !empty($resultats)): ?>
                        <!-- Résultats de distribution -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-check2-circle me-2"></i> Résultats de la Distribution
                            </div>
                            <div class="card-body">
                                <?php 
                                $villes = [];
                                $total_distribue = 0;
                                
                                // Normaliser les résultats selon le mode
                                foreach($resultats as $r) {
                                    // Déterminer le nom de la ville selon la structure
                                    $ville_nom = isset($r['besoin_ville']) ? $r['besoin_ville'] : (isset($r['ville_nom']) ? $r['ville_nom'] : 'Inconnu');
                                    
                                    if(!isset($villes[$ville_nom])) {
                                        $villes[$ville_nom] = [];
                                    }
                                    $villes[$ville_nom][] = $r;
                                    $total_distribue++;
                                }
                                ?>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-success"><?= $total_distribue ?></h3>
                                        <p class="text-muted">Distributions effectuées</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-info"><?= count($villes) ?></h3>
                                        <p class="text-muted">Villes servies</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-warning"><?= count($resultats) ?></h3>
                                        <p class="text-muted">Produits distribués</p>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Détails des distributions :</h5>
                                
                                <?php foreach($villes as $ville => $distributions): ?>
                                <div class="timeline-item">
                                    <h5><i class="bi bi-geo-alt-fill me-2"></i> <?= htmlspecialchars($ville) ?></h5>
                                    <ul>
                                        <?php foreach($distributions as $dist): ?>
                                        <?php 
                                            // Normaliser les champs selon la structure
                                            $produit = isset($dist['produit']) ? $dist['produit'] : (isset($dist['produit_nom']) ? $dist['produit_nom'] : 'N/A');
                                            $quantite = isset($dist['quantite']) ? $dist['quantite'] : (isset($dist['quantite_distribuee']) ? $dist['quantite_distribuee'] : 0);
                                            $date_besoin = isset($dist['date_besoin']) ? $dist['date_besoin'] : null;
                                            $date_don = isset($dist['date_don']) ? $dist['date_don'] : null;
                                        ?>
                                        <li><?= htmlspecialchars($produit) ?> - Quantité: <?= $quantite ?>
                                            <?php if($date_besoin && $date_don): ?>
                                            <small class="text-muted">(Besoin: <?= date('d/m/Y H:i', strtotime($date_besoin)) ?>, Don: <?= date('d/m/Y H:i', strtotime($date_don)) ?>)</small>
                                            <?php endif; ?>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Résumé des distributions par ville -->
                        <?php if(isset($detailsParVille) && !empty($detailsParVille)): ?>
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-bar-chart-fill me-2"></i> Résumé des Distributions par Ville
                                <?php if(isset($totalDistributions)): ?>
                                    <span class="badge bg-primary float-end"><?= $totalDistributions ?> distribution(s)</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php 
                                    $villesGrouped = [];
                                    $totalGeneralBesoins = 0;
                                    $totalGeneralDons = 0;
                                    $totalGeneralDistribue = 0;
                                    
                                    foreach($detailsParVille as $detail) {
                                        if(!isset($villesGrouped[$detail['ville_nom']])) {
                                            $villesGrouped[$detail['ville_nom']] = [
                                                'details' => [],
                                                'total_besoins' => 0,
                                                'total_dons' => 0,
                                                'total_distribue' => 0,
                                                'nb_distributions' => 0
                                            ];
                                        }
                                        $villesGrouped[$detail['ville_nom']]['details'][] = $detail;
                                        $villesGrouped[$detail['ville_nom']]['total_besoins'] += $detail['total_besoins'];
                                        $villesGrouped[$detail['ville_nom']]['total_dons'] += $detail['total_dons'];
                                        $villesGrouped[$detail['ville_nom']]['total_distribue'] += $detail['total_distribue'];
                                        $villesGrouped[$detail['ville_nom']]['nb_distributions'] += $detail['nb_distributions'];
                                        
                                        $totalGeneralBesoins += $detail['total_besoins'];
                                        $totalGeneralDons += $detail['total_dons'];
                                        $totalGeneralDistribue += $detail['total_distribue'];
                                    }
                                    
                                    foreach($villesGrouped as $ville => $villeData): 
                                        $tauxSatisfaction = $villeData['total_besoins'] > 0 ? 
                                            round(($villeData['total_distribue'] / $villeData['total_besoins']) * 100) : 0;
                                        $tauxUtilisation = $villeData['total_dons'] > 0 ? 
                                            round(($villeData['total_distribue'] / $villeData['total_dons']) * 100) : 0;
                                    ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <span><i class="bi bi-geo-alt-fill me-2"></i><?= htmlspecialchars($ville) ?></span>
                                                <span class="badge bg-light text-dark"><?= $villeData['nb_distributions'] ?> dist.</span>
                                            </div>
                                            <div class="card-body">
                                                <!-- Indicateurs clés -->
                                                <div class="row mb-3">
                                                    <div class="col-4 text-center">
                                                        <div class="mb-1">
                                                            <i class="bi bi-exclamation-circle text-warning" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <h5 class="mb-0"><?= number_format($villeData['total_besoins'], 0, ',', ' ') ?></h5>
                                                        <small class="text-muted">Besoins</small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="mb-1">
                                                            <i class="bi bi-gift text-info" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <h5 class="mb-0"><?= number_format($villeData['total_dons'], 0, ',', ' ') ?></h5>
                                                        <small class="text-muted">Dons</small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="mb-1">
                                                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <h5 class="mb-0"><?= number_format($villeData['total_distribue'], 0, ',', ' ') ?></h5>
                                                        <small class="text-muted">Distribué</small>
                                                    </div>
                                                </div>

                                                <!-- Taux de satisfaction -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <small><strong>Taux de satisfaction des besoins</strong></small>
                                                        <small><strong><?= $tauxSatisfaction ?>%</strong></small>
                                                    </div>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $tauxSatisfaction ?>%">
                                                            <?= $tauxSatisfaction ?>%
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Détails par produit -->
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Produit</th>
                                                                <th class="text-center">Besoin</th>
                                                                <th class="text-center">Don</th>
                                                                <th class="text-center">Distribué</th>
                                                                <th class="text-center">%</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($villeData['details'] as $d): 
                                                                $pct = $d['total_besoins'] > 0 ? round(($d['total_distribue'] / $d['total_besoins']) * 100) : 0;
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php 
                                                                    $badgeClass = 'bg-secondary';
                                                                    $cat = $d['categorie_nom'] ?? 'Autre';
                                                                    if($cat == 'Nature') $badgeClass = 'bg-success';
                                                                    elseif($cat == 'Matériaux') $badgeClass = 'bg-info';
                                                                    elseif($cat == 'Argent') $badgeClass = 'bg-warning text-dark';
                                                                    ?>
                                                                    <span class="badge <?= $badgeClass ?> badge-sm"><?= htmlspecialchars($cat) ?></span>
                                                                    <small><?= htmlspecialchars($d['produit_nom'] ?? 'N/A') ?></small>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-warning text-dark"><?= number_format($d['total_besoins'], 0, ',', ' ') ?></span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info"><?= number_format($d['total_dons'], 0, ',', ' ') ?></span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success"><?= number_format($d['total_distribue'], 0, ',', ' ') ?></span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <small class="<?= $pct >= 100 ? 'text-success' : ($pct >= 50 ? 'text-warning' : 'text-danger') ?> fw-bold">
                                                                        <?= $pct ?>%
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Statistiques globales -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h5><i class="bi bi-graph-up me-2"></i>Statistiques Globales</h5>
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <h4><?= count($villesGrouped) ?></h4>
                                                    <p class="mb-0">Villes servies</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h4><?= number_format($totalGeneralBesoins, 0, ',', ' ') ?></h4>
                                                    <p class="mb-0">Total besoins</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h4><?= number_format($totalGeneralDons, 0, ',', ' ') ?></h4>
                                                    <p class="mb-0">Total dons</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h4 class="text-success"><?= number_format($totalGeneralDistribue, 0, ',', ' ') ?></h4>
                                                    <p class="mb-0">Total distribué</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Changer la description selon le mode sélectionné
        const modeSelect = document.getElementById('mode');
        const modeDescription = document.getElementById('modeDescription');
        
        if(modeSelect) {
            const descriptions = {
                'fifo': '<strong>FIFO:</strong> Les besoins les plus anciens sont satisfaits en priorité avec les dons les plus anciens.',
                'proportionnel': '<strong>Proportionnel:</strong> Les dons sont distribués proportionnellement aux besoins de chaque ville.',
                'quantite': '<strong>Par Quantité:</strong> Priorité aux besoins les plus petits. Optimise la satisfaction du maximum de besoins.'
            };
            
            modeSelect.addEventListener('change', function() {
                modeDescription.innerHTML = descriptions[this.value] || '';
            });
        }
    </script>

<?php include __DIR__ . '/includes/modal_reinitialiser.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
