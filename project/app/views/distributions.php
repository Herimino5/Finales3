<?php 
$title = "Distributions - BNGRC"; 
$activePage = 'distributions';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-arrow-left-right"></i> Distribution des Dons
                        </h2>

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
                            <p class="mb-0">Les dons sont distribués automatiquement selon l'ordre de date de saisie (FIFO). Le système attribue les dons disponibles aux besoins les plus anciens en priorité.</p>
                        </div>

                        <!-- Bouton de simulation -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-play-circle me-2"></i> Lancer la Distribution
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Cliquez sur le bouton ci-dessous pour lancer la distribution automatique des dons disponibles.</p>
                                <form method="POST" action="<?= BASE_URL ?>distribuerAutomatique">
                                    <button type="submit" class="btn btn-primary-custom btn-custom btn-lg">
                                        <i class="bi bi-play-fill me-2"></i> Distribuer Automatiquement
                                    </button>
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
                                foreach($resultats as $r) {
                                    if(!isset($villes[$r['besoin_ville']])) {
                                        $villes[$r['besoin_ville']] = [];
                                    }
                                    $villes[$r['besoin_ville']][] = $r;
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
                                        <li><?= htmlspecialchars($dist['produit']) ?> - Quantité: <?= $dist['quantite'] ?>
                                            <small class="text-muted">(Besoin: <?= date('d/m/Y H:i', strtotime($dist['date_besoin'])) ?>, Don: <?= date('d/m/Y H:i', strtotime($dist['date_don'])) ?>)</small>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Historique des distributions -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-clock-history me-2"></i> Historique des Distributions
                                <?php if(isset($totalDistributions)): ?>
                                    <span class="badge bg-primary float-end"><?= $totalDistributions ?> distribution(s)</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date Distribution</th>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Quantité</th>
                                                <th>Catégorie</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($distributions) && !empty($distributions)): ?>
                                                <?php foreach($distributions as $dist): ?>
                                                    <tr>
                                                        <td><strong>#<?= str_pad($dist['id'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                                                        <td><?= date('d/m/Y H:i', strtotime($dist['date_distribution'])) ?></td>
                                                        <td><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($dist['ville_nom']) ?></td>
                                                        <td><?= htmlspecialchars($dist['produit_nom']) ?></td>
                                                        <td><?= number_format($dist['quantite_distribuee'], 0, ',', ' ') ?></td>
                                                        <td>
                                                            <?php 
                                                            $badgeClass = 'bg-secondary';
                                                            if($dist['categorie_nom'] == 'Nature') $badgeClass = 'bg-success';
                                                            elseif($dist['categorie_nom'] == 'Matériaux') $badgeClass = 'bg-info';
                                                            elseif($dist['categorie_nom'] == 'Argent') $badgeClass = 'bg-warning text-dark';
                                                            ?>
                                                            <span class="badge <?= $badgeClass ?> badge-custom"><?= htmlspecialchars($dist['categorie_nom']) ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucune distribution effectuée</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <?php if(isset($totalPages) && $totalPages > 1): ?>
                                    <nav aria-label="Navigation de pagination" class="mt-4">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= BASE_URL ?>distributions?page=<?= $currentPage - 1 ?>" tabindex="-1">
                                                    <i class="bi bi-chevron-left"></i> Précédent
                                                </a>
                                            </li>
                                            
                                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                                <?php if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)): ?>
                                                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                        <a class="page-link" href="<?= BASE_URL ?>distributions?page=<?= $i ?>"><?= $i ?></a>
                                                    </li>
                                                <?php elseif($i == $currentPage - 3 || $i == $currentPage + 3): ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= BASE_URL ?>distributions?page=<?= $currentPage + 1 ?>">
                                                    Suivant <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btnSimuler').addEventListener('click', function() {
            // Afficher un loader
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Simulation en cours...';
            this.disabled = true;

            // Simuler un délai de traitement
            setTimeout(() => {
                // Afficher les résultats
                document.getElementById('resultatsSimulation').style.display = 'block';
                
                // Scroll vers les résultats
                document.getElementById('resultatsSimulation').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });

                // Réinitialiser le bouton
                this.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Simulation terminée';
                this.classList.remove('btn-primary-custom');
                this.classList.add('btn-success');
                
                // Afficher une notification
                alert('Simulation terminée avec succès!\n\n✓ 15 distributions effectuées\n✓ 4 villes servies\n✓ 3.2M Ar distribués');
            }, 2000);
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
