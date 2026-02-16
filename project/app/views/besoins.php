<?php $title = "Gérer les Besoins - BNGRC"; ?>
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

        .table-custom {
            border-radius: 15px;
            overflow: hidden;
        }

        .table-custom thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .table-custom thead th {
            border: none;
            font-weight: 600;
            padding: 15px;
        }

        .table-custom tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
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
                                <a class="nav-link active" href="besoins.html">
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
                                <a class="nav-link" href="villes.html">
                                    <i class="bi bi-geo-alt me-2"></i> Villes
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="p-4">
                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-exclamation-circle-fill"></i> Gestion des Besoins
                        </h2>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter un Besoin
                            </div>
                            <div class="card-body">
                                <form id="formBesoin">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="ville" class="form-label">
                                                <i class="bi bi-geo-alt-fill text-primary"></i> Ville
                                            </label>
                                            <select class="form-select" id="ville" required>
                                                <option value="">Sélectionner une ville</option>
                                                <option value="Antananarivo">Antananarivo</option>
                                                <option value="Antsirabe">Antsirabe</option>
                                                <option value="Fianarantsoa">Fianarantsoa</option>
                                                <option value="Toamasina">Toamasina</option>
                                                <option value="Mahajanga">Mahajanga</option>
                                                <option value="Toliara">Toliara</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="typeBesoin" class="form-label">
                                                <i class="bi bi-tag-fill text-success"></i> Type de Besoin
                                            </label>
                                            <select class="form-select" id="typeBesoin" required>
                                                <option value="">Sélectionner un type</option>
                                                <option value="nature">En nature</option>
                                                <option value="materiel">Matériaux</option>
                                                <option value="argent">Argent</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="designation" class="form-label">
                                                <i class="bi bi-pencil-fill text-info"></i> Désignation
                                            </label>
                                            <input type="text" class="form-control" id="designation" 
                                                   placeholder="Ex: Riz, Tôle, Aide financière..." required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="quantite" class="form-label">
                                                <i class="bi bi-plus-slash-minus text-warning"></i> Quantité
                                            </label>
                                            <input type="number" class="form-control" id="quantite" 
                                                   placeholder="Ex: 100" min="1" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="unite" class="form-label">
                                                <i class="bi bi-rulers text-secondary"></i> Unité
                                            </label>
                                            <input type="text" class="form-control" id="unite" 
                                                   placeholder="Ex: kg, L, pièces, Ar" required>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="prixUnitaire" class="form-label">
                                                <i class="bi bi-cash text-success"></i> Prix Unitaire (Ar)
                                            </label>
                                            <input type="number" class="form-control" id="prixUnitaire" 
                                                   placeholder="Ex: 5000" min="0" step="0.01" required>
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
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ville</th>
                                                <th>Type</th>
                                                <th>Désignation</th>
                                                <th>Quantité</th>
                                                <th>Prix Unit.</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBesoins">
                                            <tr>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>Riz</td>
                                                <td>500 kg</td>
                                                <td>3,000 Ar</td>
                                                <td class="fw-bold">1,500,000 Ar</td>
                                                <td>15/02/2026</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action" title="Modifier">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antsirabe</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>Tôle</td>
                                                <td>200 pièces</td>
                                                <td>25,000 Ar</td>
                                                <td class="fw-bold">5,000,000 Ar</td>
                                                <td>15/02/2026</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Fianarantsoa</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>Huile</td>
                                                <td>300 L</td>
                                                <td>8,000 Ar</td>
                                                <td class="fw-bold">2,400,000 Ar</td>
                                                <td>15/02/2026</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Toamasina</td>
                                                <td><span class="badge bg-warning text-dark badge-custom">Argent</span></td>
                                                <td>Aide financière</td>
                                                <td>1</td>
                                                <td>1,000,000 Ar</td>
                                                <td class="fw-bold">1,000,000 Ar</td>
                                                <td>15/02/2026</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>Clous</td>
                                                <td>50 kg</td>
                                                <td>12,000 Ar</td>
                                                <td class="fw-bold">600,000 Ar</td>
                                                <td>14/02/2026</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion du formulaire
        document.getElementById('formBesoin').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer les valeurs
            const ville = document.getElementById('ville').value;
            const type = document.getElementById('typeBesoin').value;
            const designation = document.getElementById('designation').value;
            const quantite = document.getElementById('quantite').value;
            const unite = document.getElementById('unite').value;
            const prix = parseFloat(document.getElementById('prixUnitaire').value);
            
            // Calculer le total
            const total = (quantite * prix).toLocaleString('fr-FR');
            
            // Déterminer le badge de type
            let badgeClass = '';
            let badgeText = '';
            switch(type) {
                case 'nature':
                    badgeClass = 'bg-success';
                    badgeText = 'Nature';
                    break;
                case 'materiel':
                    badgeClass = 'bg-info';
                    badgeText = 'Matériaux';
                    break;
                case 'argent':
                    badgeClass = 'bg-warning text-dark';
                    badgeText = 'Argent';
                    break;
            }
            
            // Créer la ligne
            const date = new Date().toLocaleDateString('fr-FR');
            const newRow = `
                <tr>
                    <td><i class="bi bi-geo-alt-fill text-primary"></i> ${ville}</td>
                    <td><span class="badge ${badgeClass} badge-custom">${badgeText}</span></td>
                    <td>${designation}</td>
                    <td>${quantite} ${unite}</td>
                    <td>${prix.toLocaleString('fr-FR')} Ar</td>
                    <td class="fw-bold">${total} Ar</td>
                    <td>${date}</td>
                    <td>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            // Ajouter la ligne au tableau
            document.getElementById('tableBesoins').insertAdjacentHTML('afterbegin', newRow);
            
            // Réinitialiser le formulaire
            this.reset();
            
            // Message de succès
            alert('Besoin enregistré avec succès!');
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
