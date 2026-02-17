<?php 
$title = "Gestion des Régions - BNGRC"; 
$activePage = 'regions';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-map-fill"></i> Gestion des Régions
                        </h2>

                        <!-- Messages de succès/erreur -->
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php 
                                    switch($_GET['success']) {
                                        case '1': echo 'Région ajoutée avec succès!'; break;
                                        case '2': echo 'Région modifiée avec succès!'; break;
                                        case '3': echo 'Région supprimée avec succès!'; break;
                                    }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php 
                                    switch($_GET['error']) {
                                        case '1': echo 'Une erreur s\'est produite.'; break;
                                        case '2': echo 'Impossible de supprimer: cette région contient des villes liées à des données initiales.'; break;
                                    }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter une Région
                            </div>
                            <div class="card-body">
                                <form action="<?= BASE_URL ?>regions/store" method="POST">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="nom" class="form-label">
                                                <i class="bi bi-map text-primary"></i> Nom de la Région
                                            </label>
                                            <input type="text" class="form-control" id="nom" name="nom"
                                                   placeholder="Ex: Analamanga" required>
                                        </div>
                                        <div class="col-md-4 mb-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary-custom btn-custom w-100">
                                                <i class="bi bi-check-circle me-2"></i> Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Liste des régions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-list-ul me-2"></i>Régions Enregistrées (<?= count($regions ?? []) ?>)
                                </h3>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th><i class="bi bi-map me-1"></i> Nom</th>
                                        <th><i class="bi bi-building me-1"></i> Villes</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($regions)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <i class="bi bi-info-circle me-2"></i> Aucune région enregistrée
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($regions as $index => $region): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><strong><?= htmlspecialchars($region['nom']) ?></strong></td>
                                                <td>
                                                    <span class="badge bg-info"><?= $region['nb_villes'] ?? 0 ?> ville(s)</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info" title="Modifier"
                                                            data-bs-toggle="modal" data-bs-target="#editModal<?= $region['id'] ?>">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="Supprimer"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $region['id'] ?>">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Modifier -->
                                            <div class="modal fade" id="editModal<?= $region['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-pencil me-2"></i>Modifier la région
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?= BASE_URL ?>regions/update/<?= $region['id'] ?>" method="POST">
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nom de la région</label>
                                                                    <input type="text" class="form-control" name="nom" 
                                                                           value="<?= htmlspecialchars($region['nom']) ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-info">
                                                                    <i class="bi bi-check me-1"></i>Modifier
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Supprimer -->
                                            <div class="modal fade" id="deleteModal<?= $region['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-trash me-2"></i>Confirmer la suppression
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir supprimer la région <strong><?= htmlspecialchars($region['nom']) ?></strong> ?</p>
                                                            <?php if (($region['nb_villes'] ?? 0) > 0): ?>
                                                                <p class="text-danger">
                                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                                    <strong>Attention:</strong> Cette région contient <?= $region['nb_villes'] ?> ville(s). 
                                                                    Vous devez d'abord supprimer ou déplacer ces villes.
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form action="<?= BASE_URL ?>regions/delete/<?= $region['id'] ?>" method="POST" style="display:inline;">
                                                                <button type="submit" class="btn btn-danger" <?= (($region['nb_villes'] ?? 0) > 0) ? 'disabled' : '' ?>>
                                                                    <i class="bi bi-trash me-1"></i>Supprimer
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
