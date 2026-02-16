<?php $title = "Gestion des Villes - BNGRC"; ?>
<?php include __DIR__ . '/includes/header.php'; ?>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 30px;
        }

        .header-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: #f0f0f0;
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .btn-custom {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.4);
        }

        .card-custom {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-custom .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .ville-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 5px solid #2c3e50;
            margin-bottom: 20px;
        }

        .ville-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .ville-card h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .stat-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin: 5px;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }
    </style>

            <div class="row g-0">
                <div class="col-md-3 border-end">
                    <div class="p-4">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="index.html">
                                    <i class="bi bi-speedometer2 me-2"></i> Tableau de Bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="besoins.html">
                                    <i class="bi bi-exclamation-circle me-2"></i> Gérer les Besoins
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dons.html">
                                    <i class="bi bi-gift me-2"></i> Gérer les Dons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="distributions.html">
                                    <i class="bi bi-arrow-left-right me-2"></i> Distributions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="villes.html">
                                    <i class="bi bi-geo-alt me-2"></i> Villes
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="p-4">
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
