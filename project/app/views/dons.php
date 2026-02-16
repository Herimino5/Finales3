<?php $title = "Gérer les Dons - BNGRC"; ?>
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

        .stat-box {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border-radius: 15px;
            padding: 25px;
            color: white;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.4);
        }

        .stat-box h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .stat-box p {
            margin: 0;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .stat-box i {
            font-size: 3rem;
            opacity: 0.8;
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
                                <a class="nav-link active" href="dons.html">
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
                            <i class="bi bi-gift-fill"></i> Gestion des Dons
                        </h2>

                        <!-- Statistiques des dons -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="bi bi-gift"></i>
                                    <h3>847</h3>
                                    <p>Dons Reçus</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="bi bi-check-circle"></i>
                                    <h3>623</h3>
                                    <p>Dons Distribués</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="bi bi-hourglass-split"></i>
                                    <h3>224</h3>
                                    <p>Dons Disponibles</p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire d'ajout -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-plus-circle me-2"></i> Enregistrer un Don
                            </div>
                            <div class="card-body">
                                <form id="formDon">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="typeDon" class="form-label">
                                                <i class="bi bi-tag-fill text-success"></i> Type de Don
                                            </label>
                                            <select class="form-select" id="typeDon" required>
                                                <option value="">Sélectionner un type</option>
                                                <option value="nature">En nature</option>
                                                <option value="materiel">Matériaux</option>
                                                <option value="argent">Argent</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="donateur" class="form-label">
                                                <i class="bi bi-person-fill text-info"></i> Donateur
                                            </label>
                                            <input type="text" class="form-control" id="donateur" 
                                                   placeholder="Nom du donateur" required>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="designationDon" class="form-label">
                                                <i class="bi bi-pencil-fill text-primary"></i> Désignation
                                            </label>
                                            <input type="text" class="form-control" id="designationDon" 
                                                   placeholder="Ex: Riz, Tôle, Argent..." required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="quantiteDon" class="form-label">
                                                <i class="bi bi-plus-slash-minus text-warning"></i> Quantité
                                            </label>
                                            <input type="number" class="form-control" id="quantiteDon" 
                                                   placeholder="Ex: 100" min="1" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="uniteDon" class="form-label">
                                                <i class="bi bi-rulers text-secondary"></i> Unité
                                            </label>
                                            <input type="text" class="form-control" id="uniteDon" 
                                                   placeholder="Ex: kg, L, pièces, Ar" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="valeurDon" class="form-label">
                                                <i class="bi bi-cash text-success"></i> Valeur Unitaire (Ar)
                                            </label>
                                            <input type="number" class="form-control" id="valeurDon" 
                                                   placeholder="Ex: 5000" min="0" step="0.01" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="dateReception" class="form-label">
                                                <i class="bi bi-calendar-check text-danger"></i> Date de Réception
                                            </label>
                                            <input type="datetime-local" class="form-control" id="dateReception" required>
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

                        <!-- Liste des dons -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-list-ul me-2"></i> Liste des Dons Reçus
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-select" id="filterType">
                                                <option value="">Tous les types</option>
                                                <option value="nature">En nature</option>
                                                <option value="materiel">Matériaux</option>
                                                <option value="argent">Argent</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" id="filterStatut">
                                                <option value="">Tous les statuts</option>
                                                <option value="disponible">Disponible</option>
                                                <option value="distribue">Distribué</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="searchDon" placeholder="Rechercher...">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Donateur</th>
                                                <th>Désignation</th>
                                                <th>Quantité</th>
                                                <th>Valeur Unit.</th>
                                                <th>Total</th>
                                                <th>Date Réception</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableDons">
                                            <tr>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>ONG Humanité</td>
                                                <td>Riz</td>
                                                <td>200 kg</td>
                                                <td>3,000 Ar</td>
                                                <td class="fw-bold">600,000 Ar</td>
                                                <td>16/02/2026 10:30</td>
                                                <td><span class="badge bg-success">Disponible</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning btn-action" title="Distribuer">
                                                        <i class="bi bi-send-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>Entreprise BTP</td>
                                                <td>Tôle</td>
                                                <td>100 pièces</td>
                                                <td>25,000 Ar</td>
                                                <td class="fw-bold">2,500,000 Ar</td>
                                                <td>16/02/2026 09:15</td>
                                                <td><span class="badge bg-secondary">Distribué</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action" title="Détails">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-warning text-dark badge-custom">Argent</span></td>
                                                <td>Association Solidarité</td>
                                                <td>Don financier</td>
                                                <td>1</td>
                                                <td>500,000 Ar</td>
                                                <td class="fw-bold">500,000 Ar</td>
                                                <td>15/02/2026 16:45</td>
                                                <td><span class="badge bg-success">Disponible</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning btn-action" title="Distribuer">
                                                        <i class="bi bi-send-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>Particulier Anonyme</td>
                                                <td>Huile</td>
                                                <td>50 L</td>
                                                <td>8,000 Ar</td>
                                                <td class="fw-bold">400,000 Ar</td>
                                                <td>15/02/2026 14:20</td>
                                                <td><span class="badge bg-success">Disponible</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning btn-action" title="Distribuer">
                                                        <i class="bi bi-send-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>Quincaillerie Centrale</td>
                                                <td>Clous</td>
                                                <td>30 kg</td>
                                                <td>12,000 Ar</td>
                                                <td class="fw-bold">360,000 Ar</td>
                                                <td>15/02/2026 11:00</td>
                                                <td><span class="badge bg-secondary">Distribué</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-action" title="Détails">
                                                        <i class="bi bi-eye-fill"></i>
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
        // Initialiser la date actuelle
        document.getElementById('dateReception').value = new Date().toISOString().slice(0, 16);

        // Gestion du formulaire
        document.getElementById('formDon').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const type = document.getElementById('typeDon').value;
            const donateur = document.getElementById('donateur').value;
            const designation = document.getElementById('designationDon').value;
            const quantite = document.getElementById('quantiteDon').value;
            const unite = document.getElementById('uniteDon').value;
            const valeur = parseFloat(document.getElementById('valeurDon').value);
            const dateReception = new Date(document.getElementById('dateReception').value);
            
            const total = (quantite * valeur).toLocaleString('fr-FR');
            
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
            
            const dateFormatted = dateReception.toLocaleString('fr-FR');
            
            const newRow = `
                <tr>
                    <td><span class="badge ${badgeClass} badge-custom">${badgeText}</span></td>
                    <td>${donateur}</td>
                    <td>${designation}</td>
                    <td>${quantite} ${unite}</td>
                    <td>${valeur.toLocaleString('fr-FR')} Ar</td>
                    <td class="fw-bold">${total} Ar</td>
                    <td>${dateFormatted}</td>
                    <td><span class="badge bg-success">Disponible</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" title="Distribuer">
                            <i class="bi bi-send-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" title="Supprimer">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            document.getElementById('tableDons').insertAdjacentHTML('afterbegin', newRow);
            this.reset();
            document.getElementById('dateReception').value = new Date().toISOString().slice(0, 16);
            
            alert('Don enregistré avec succès!');
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
