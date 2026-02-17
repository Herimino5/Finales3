<?php 
$title = "Gérer les Besoins - BNGRC"; 
$activePage = 'besoins';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-exclamation-circle-fill"></i> Gestion des Besoins
                        </h2>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter un Besoin
                            </div>
                            <div class="card-body">
                                <form id="formBesoin" method="POST" action="<?= BASE_URL ?>besoinsInsert">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="ville" class="form-label">
                                                <i class="bi bi-geo-alt-fill text-primary"></i> Ville
                                            </label>
                                            <select class="form-select" id="ville" name="ville" required>
                                                <option value="">Sélectionner une ville</option>
                                                <?php if(isset($villes) && !empty($villes)): ?>
                                                    <?php foreach($villes as $ville): ?>
                                                        <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom']) ?> (<?= htmlspecialchars($ville['region_nom']) ?>)</option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="produit" class="form-label">
                                                <i class="bi bi-box-fill text-success"></i> Produit
                                            </label>
                                            <div class="input-group">
                                                <select class="form-select" id="produit" name="produit" required>
                                                    <option value="">Sélectionner un produit</option>
                                                    <?php if(isset($produits) && !empty($produits)): ?>
                                                        <?php foreach($produits as $produit): ?>
                                                            <option value="<?= $produit['id'] ?>" data-prix="<?= $produit['prix_unitaire'] ?? 0 ?>">
                                                                <?= htmlspecialchars($produit['nom']) ?> - <?= number_format($produit['prix_unitaire'] ?? 0, 0, ',', ' ') ?> Ar
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNouveauProduit">
                                                    <i class="bi bi-plus-circle"></i> Nouveau
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">
                                                <i class="bi bi-pencil-fill text-info"></i> Description (Optionnel)
                                            </label>
                                            <textarea class="form-control" id="description" name="description" rows="2"
                                                   placeholder="Ex: Pour reconstruction de maisons..."></textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="quantite" class="form-label">
                                                <i class="bi bi-plus-slash-minus text-warning"></i> Quantité
                                            </label>
                                            <input type="number" class="form-control" id="quantite" name="quantite"
                                                   placeholder="Ex: 100" min="1" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="prixTotal" class="form-label">
                                                <i class="bi bi-cash text-success"></i> Prix Total Estimé (Ar)
                                            </label>
                                            <input type="text" class="form-control" id="prixTotal" readonly>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary-custom btn-custom">
                                            <i class="bi bi-check-circle me-2"></i> Enregistrer le Besoin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Liste des besoins -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-list-ul me-2"></i> Liste des Besoins Enregistrés
                                <?php if(isset($totalBesoins)): ?>
                                    <span class="badge bg-primary float-end"><?= $totalBesoins ?> besoins au total</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if(isset($success)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Catégorie</th>
                                                <th>Quantité</th>
                                                <th>Prix Unit.</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($besoins) && !empty($besoins)): ?>
                                                <?php foreach($besoins as $besoin): ?>
                                                    <tr>
                                                        <td><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($besoin['ville_nom']) ?></td>
                                                        <td><?= htmlspecialchars($besoin['produit_nom']) ?></td>
                                                        <td>
                                                            <?php 
                                                            $badgeClass = 'bg-secondary';
                                                            if($besoin['categorie_nom'] == 'Nature') $badgeClass = 'bg-success';
                                                            elseif($besoin['categorie_nom'] == 'Matériaux') $badgeClass = 'bg-info';
                                                            elseif($besoin['categorie_nom'] == 'Argent') $badgeClass = 'bg-warning text-dark';
                                                            ?>
                                                            <span class="badge <?= $badgeClass ?> badge-custom"><?= htmlspecialchars($besoin['categorie_nom']) ?></span>
                                                        </td>
                                                        <td><?= number_format($besoin['quantite'], 0, ',', ' ') ?></td>
                                                        <td><?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="fw-bold"><?= number_format($besoin['quantite'] * $besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                                        <td><?= date('d/m/Y', strtotime($besoin['Date_saisie'])) ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info btn-action" title="Modifier">
                                                                <i class="bi bi-pencil-fill"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">Aucun besoin enregistré</td>
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
                                                <a class="page-link" href="<?= BASE_URL ?>besoinsform?page=<?= $currentPage - 1 ?>" tabindex="-1">
                                                    <i class="bi bi-chevron-left"></i> Précédent
                                                </a>
                                            </li>
                                            
                                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                                <?php if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)): ?>
                                                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                        <a class="page-link" href="<?= BASE_URL ?>besoinsform?page=<?= $i ?>"><?= $i ?></a>
                                                    </li>
                                                <?php elseif($i == $currentPage - 3 || $i == $currentPage + 3): ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= BASE_URL ?>besoinsform?page=<?= $currentPage + 1 ?>">
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

    <!-- Modal Nouveau Produit -->
    <div class="modal fade" id="modalNouveauProduit" tabindex="-1" aria-labelledby="modalNouveauProduitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNouveauProduitLabel">
                        <i class="bi bi-plus-circle"></i> Ajouter un Nouveau Produit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNouveauProduit">
                        <div class="mb-3">
                            <label for="nomProduit" class="form-label">
                                <i class="bi bi-box"></i> Nom du Produit
                            </label>
                            <input type="text" class="form-control" id="nomProduit" name="nom_produit" 
                                   placeholder="Ex: Riz 25kg, Tôle 3m" required>
                        </div>
                        <div class="mb-3">
                            <label for="categorieProduit" class="form-label">
                                <i class="bi bi-tag"></i> Catégorie
                            </label>
                            <select class="form-select" id="categorieProduit" name="categorie" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php if(isset($categories) && !empty($categories)): ?>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prixUnitaireProduit" class="form-label">
                                <i class="bi bi-cash"></i> Prix Unitaire (Ar)
                            </label>
                            <input type="number" class="form-control" id="prixUnitaireProduit" name="prix_unitaire"
                                   placeholder="Ex: 5000" min="0" step="0.01" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="btnSaveProduit">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/besoins.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
