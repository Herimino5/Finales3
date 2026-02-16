<?php 
$title = "Gestion des Villes - BNGRC"; 
$activePage = 'villes';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-geo-alt-fill"></i> Gestion des Villes
                        </h2>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter une Ville
                            </div>
                            <div class="card-body">
                                <form id="formVille">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nomVille" class="form-label">
                                                <i class="bi bi-building text-primary"></i> Nom de la Ville
                                            </label>
                                            <input type="text" class="form-control" id="nomVille" 
                                                   placeholder="Ex: Antananarivo" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="region" class="form-label">
                                                <i class="bi bi-map text-success"></i> Région
                                            </label>
                                            <select class="form-select" id="region" required>
                                                <option value="">Sélectionner une région</option>
                                                <option value="Analamanga">Analamanga</option>
                                                <option value="Vakinankaratra">Vakinankaratra</option>
                                                <option value="Itasy">Itasy</option>
                                                <option value="Bongolava">Bongolava</option>
                                                <option value="Haute Matsiatra">Haute Matsiatra</option>
                                                <option value="Amoron'i Mania">Amoron'i Mania</option>
                                                <option value="Vatovavy Fitovinany">Vatovavy Fitovinany</option>
                                                <option value="Ihorombe">Ihorombe</option>
                                                <option value="Atsimo Atsinanana">Atsimo Atsinanana</option>
                                                <option value="Atsinanana">Atsinanana</option>
                                                <option value="Analanjirofo">Analanjirofo</option>
                                                <option value="Alaotra Mangoro">Alaotra Mangoro</option>
                                                <option value="Boeny">Boeny</option>
                                                <option value="Sofia">Sofia</option>
                                                <option value="Betsiboka">Betsiboka</option>
                                                <option value="Melaky">Melaky</option>
                                                <option value="Atsimo Andrefana">Atsimo Andrefana</option>
                                                <option value="Androy">Androy</option>
                                                <option value="Anosy">Anosy</option>
                                                <option value="Menabe">Menabe</option>
                                                <option value="Diana">Diana</option>
                                                <option value="Sava">Sava</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">
                                                <i class="bi bi-card-text text-info"></i> Description (optionnelle)
                                            </label>
                                            <textarea class="form-control" id="description" rows="3" 
                                                      placeholder="Informations supplémentaires sur la ville..."></textarea>
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
                                    <i class="bi bi-list-ul me-2"></i>Villes Enregistrées
                                </h3>
                            </div>
                        </div>

                        <div class="row" id="villesContainer">
                            <!-- Antananarivo -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Antananarivo</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Analamanga</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action" title="Modifier">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 8 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 12 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 9 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Antsirabe -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Antsirabe</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Vakinankaratra</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 5 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 7 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 5 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fianarantsoa -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Fianarantsoa</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Haute Matsiatra</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 6 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 8 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 6 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Toamasina -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Toamasina</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Atsinanana</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 7 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 10 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 7 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Mahajanga -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Mahajanga</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Boeny</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 4 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 5 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 4 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Toliara -->
                            <div class="col-md-6">
                                <div class="ville-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4><i class="bi bi-geo-alt-fill me-2"></i>Toliara</h4>
                                            <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>Atsimo Andrefana</strong></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info btn-action">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="stat-badge bg-success text-white">
                                            <i class="bi bi-exclamation-circle me-1"></i> 3 Besoins
                                        </span>
                                        <span class="stat-badge bg-info text-white">
                                            <i class="bi bi-gift me-1"></i> 4 Dons reçus
                                        </span>
                                        <span class="stat-badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> 3 Distributions
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formVille').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nom = document.getElementById('nomVille').value;
            const region = document.getElementById('region').value;
            const regionText = document.getElementById('region').options[document.getElementById('region').selectedIndex].text;
            
            const newVille = `
                <div class="col-md-6">
                    <div class="ville-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4><i class="bi bi-geo-alt-fill me-2"></i>${nom}</h4>
                                <p class="text-muted mb-2"><i class="bi bi-map me-1"></i> Région: <strong>${regionText}</strong></p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-info btn-action" title="Modifier">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <span class="stat-badge bg-success text-white">
                                <i class="bi bi-exclamation-circle me-1"></i> 0 Besoins
                            </span>
                            <span class="stat-badge bg-info text-white">
                                <i class="bi bi-gift me-1"></i> 0 Dons reçus
                            </span>
                            <span class="stat-badge bg-warning text-dark">
                                <i class="bi bi-check-circle me-1"></i> 0 Distributions
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('villesContainer').insertAdjacentHTML('beforeend', newVille);
            this.reset();
            alert('Ville ajoutée avec succès!');
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
