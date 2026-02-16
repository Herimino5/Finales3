<?php 
$title = "Gérer les Dons - BNGRC"; 
$activePage = 'dons';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-gift-fill"></i> Gestion des Dons
                        </h2>

                        <?php if(isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Enregistrer un Don
                            </div>
                            <div class="card-body">
                                <form id="formDon" method="POST" action="<?= BASE_URL ?>donsInsert">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="produit" class="form-label">
                                                <i class="bi bi-box-fill text-success"></i> Produit
                                            </label>
                                            <div class="input-group">
                                                <select class="form-select" id="produit" name="produit" required>
                                                    <option value="">Sélectionner un produit</option>
                                                    <?php if(isset($products) && !empty($products)): ?>
                                                        <?php foreach($products as $produit): ?>
                                                            <option value="<?= $produit['id'] ?>">
                                                                <?= htmlspecialchars($produit['nom']) ?> (<?= number_format($produit['prix_unitaire'], 0, ',', ' ') ?> Ar)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNouveauProduit">
                                                    <i class="bi bi-plus-circle"></i> Nouveau
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="quantite" class="form-label">
                                                <i class="bi bi-plus-slash-minus text-warning"></i> Quantité
                                            </label>
                                            <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Ex: 100" min="1" required>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">
                                                <i class="bi bi-pencil-fill text-info"></i> Description (Optionnel)
                                            </label>
                                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Ex: Don reçu de..."></textarea>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary-custom btn-custom">
                                            <i class="bi bi-check-circle me-2"></i> Enregistrer le Don
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-list-ul me-2"></i> Liste des Dons Reçus
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Description</th>
                                                <th>Quantité</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($dons) && !empty($dons)): ?>
                                                <?php foreach($dons as $don): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($don['nom_produit']) ?></td>
                                                        <td><?= htmlspecialchars($don['descriptions'] ?? '-') ?></td>
                                                        <td><?= number_format($don['quantite'], 0, ',', ' ') ?></td>
                                                        <td><?= date('d/m/Y H:i', strtotime($don['date_saisie'])) ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted">Aucun don enregistré</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNouveauProduit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Ajouter un Nouveau Produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNouveauProduit">
                        <div class="mb-3">
                            <label for="nomProduit" class="form-label">Nom du Produit</label>
                            <input type="text" class="form-control" id="nomProduit" name="nom_produit" required>
                        </div>
                        <div class="mb-3">
                            <label for="categorieProduit" class="form-label">Catégorie</label>
                            <select class="form-select" id="categorieProduit" name="categorie" required>
                                <option value="">Sélectionner</option>
                                <?php if(isset($categories) && !empty($categories)): ?>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prixUnitaireProduit" class="form-label">Prix Unitaire (Ar)</label>
                            <input type="number" class="form-control" id="prixUnitaireProduit" name="prix_unitaire" min="0" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="btnSaveProduit">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btnSaveProduit').addEventListener('click', function() {
            const nom = document.getElementById('nomProduit').value;
            const categorie = document.getElementById('categorieProduit').value;
            const prix = document.getElementById('prixUnitaireProduit').value;
            
            if (!nom || !categorie || !prix) {
                alert('Veuillez remplir tous les champs');
                return;
            }
            
            fetch('<?= BASE_URL ?>donsProductInsert', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'nom_produit=' + encodeURIComponent(nom) + '&categorie=' + encodeURIComponent(categorie) + '&prix_unitaire=' + encodeURIComponent(prix)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('produit');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = nom + ' (' + parseFloat(prix).toLocaleString('fr-FR') + ' Ar)';
                    select.appendChild(option);
                    select.value = data.id;
                    bootstrap.Modal.getInstance(document.getElementById('modalNouveauProduit')).hide();
                    document.getElementById('formNouveauProduit').reset();
                    alert('Produit ajouté!');
                }
            });
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
