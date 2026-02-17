<?php 
$title = "Gestion des Villes - BNGRC"; 
$activePage = 'villes';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-geo-alt-fill"></i> Gestion des Villes
                        </h2>

                        <!-- Messages de succès/erreur -->
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php 
                                    switch($_GET['success']) {
                                        case '1': echo 'Ville ajoutée avec succès!'; break;
                                        case '2': echo 'Ville modifiée avec succès!'; break;
                                        case '3': echo 'Ville supprimée avec succès!'; break;
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
                                        case '2': echo 'Impossible de supprimer: cette ville contient des besoins.'; break;
                                        default: echo 'Une erreur s\'est produite.';
                                    }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter une Ville
                            </div>
                            <div class="card-body">
                                <form action="<?= BASE_URL ?>villes/store" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nom" class="form-label">
                                                <i class="bi bi-building text-primary"></i> Nom de la Ville
                                            </label>
                                            <input type="text" class="form-control" id="nom" name="nom"
                                                   placeholder="Ex: Antananarivo" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="region_id" class="form-label">
                                                <i class="bi bi-map text-success"></i> Région
                                            </label>
                                            <div class="input-group">
                                                <select class="form-select" id="region_id" name="region_id" required>
                                                    <option value="">Sélectionner une région</option>
                                                    <?php if (!empty($regions)): ?>
                                                        <?php foreach ($regions as $region): ?>
                                                            <option value="<?= $region['id'] ?>"><?= htmlspecialchars($region['nom']) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRegionModal" title="Ajouter une région">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary-custom btn-custom">
                                            <i class="bi bi-check-circle me-2"></i> Ajouter la Ville
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Liste des villes -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-list-ul me-2"></i>Villes Enregistrées (<?= count($villes ?? []) ?>)
                                </h3>
                            </div>
                        </div>

                        <div class="row" id="villesContainer">
                            <?php if (empty($villes)): ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> Aucune ville enregistrée pour le moment.
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($villes as $ville): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="ville-card">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h4><i class="bi bi-geo-alt-fill me-2"></i><?= htmlspecialchars($ville['nom']) ?></h4>
                                                    <p class="text-muted mb-2">
                                                        <i class="bi bi-map me-1"></i> Région: 
                                                        <strong><?= htmlspecialchars($ville['region_nom'] ?? 'Non définie') ?></strong>
                                                    </p>
                                                </div>
                                                <div>
                                                    <button class="btn btn-sm btn-info btn-action" title="Modifier"
                                                            data-bs-toggle="modal" data-bs-target="#editModal<?= $ville['id'] ?>">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action" title="Supprimer"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $ville['id'] ?>">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <span class="stat-badge bg-success text-white">
                                                    <i class="bi bi-exclamation-circle me-1"></i> <?= $ville['stats']['besoins'] ?? 0 ?> Besoins
                                                </span>
                                                <span class="stat-badge bg-info text-white">
                                                    <i class="bi bi-gift me-1"></i> <?= $ville['stats']['dons'] ?? 0 ?> Dons
                                                </span>
                                                <span class="stat-badge bg-warning text-dark">
                                                    <i class="bi bi-check-circle me-1"></i> <?= $ville['stats']['distributions'] ?? 0 ?> Distributions
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Modifier -->
                                    <div class="modal fade" id="editModal<?= $ville['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil me-2"></i>Modifier la ville
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="<?= BASE_URL ?>villes/update/<?= $ville['id'] ?>" method="POST">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nom de la ville</label>
                                                            <input type="text" class="form-control" name="nom" 
                                                                   value="<?= htmlspecialchars($ville['nom']) ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Région</label>
                                                            <select class="form-select" name="region_id" required>
                                                                <option value="">Sélectionner une région</option>
                                                                <?php if (!empty($regions)): ?>
                                                                    <?php foreach ($regions as $region): ?>
                                                                        <option value="<?= $region['id'] ?>" 
                                                                            <?= ($ville['region_id'] == $region['id']) ? 'selected' : '' ?>>
                                                                            <?= htmlspecialchars($region['nom']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
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
                                    <div class="modal fade" id="deleteModal<?= $ville['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-trash me-2"></i>Confirmer la suppression
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer la ville <strong><?= htmlspecialchars($ville['nom']) ?></strong> ?</p>
                                                    <?php if (($ville['stats']['besoins'] ?? 0) > 0): ?>
                                                        <p class="text-danger">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            <strong>Attention:</strong> Cette ville contient <?= $ville['stats']['besoins'] ?> besoin(s). 
                                                            Vous devez d'abord supprimer ces besoins.
                                                        </p>
                                                    <?php else: ?>
                                                        <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="<?= BASE_URL ?>villes/delete/<?= $ville['id'] ?>" method="POST" style="display:inline;">
                                                        <button type="submit" class="btn btn-danger" <?= (($ville['stats']['besoins'] ?? 0) > 0) ? 'disabled' : '' ?>>
                                                            <i class="bi bi-trash me-1"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Modal Ajouter Région -->
                        <div class="modal fade" id="addRegionModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-plus-circle me-2"></i>Ajouter une nouvelle région
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom de la région</label>
                                            <input type="text" class="form-control" id="newRegionName" placeholder="Ex: Analamanga" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="button" class="btn btn-success" id="btnAddRegion">
                                            <i class="bi bi-check me-1"></i>Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// Ajouter une région via AJAX
document.getElementById('btnAddRegion').addEventListener('click', function() {
    const nomRegion = document.getElementById('newRegionName').value.trim();
    
    if (!nomRegion) {
        alert('Veuillez entrer un nom de région');
        return;
    }
    
    fetch('<?= BASE_URL ?>api/regions/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom: nomRegion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour le select
            const select = document.getElementById('region_id');
            select.innerHTML = '<option value="">Sélectionner une région</option>';
            data.regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region.id;
                option.textContent = region.nom;
                select.appendChild(option);
            });
            
            // Sélectionner la nouvelle région
            select.value = data.regions[data.regions.length - 1].id;
            
            // Fermer le modal
            bootstrap.Modal.getInstance(document.getElementById('addRegionModal')).hide();
            document.getElementById('newRegionName').value = '';
            
            alert('Région ajoutée avec succès!');
        } else {
            alert('Erreur: ' + (data.message || 'Impossible d\'ajouter la région'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur de connexion');
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
