<?php $title = "Distributions - BNGRC"; ?>
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

        .alert-info-custom {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .timeline-item {
            border-left: 3px solid #2c3e50;
            padding-left: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 13px;
            height: 13px;
            background: #2c3e50;
            border-radius: 50%;
        }

        .timeline-item h5 {
            color: #2c3e50;
            font-weight: 600;
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
                                <a class="nav-link active" href="distributions.html">
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
                            <i class="bi bi-arrow-left-right"></i> Simulation de Distribution
                        </h2>

                        <!-- Informations -->
                        <div class="alert-info-custom">
                            <h5><i class="bi bi-info-circle-fill me-2"></i> Algorithme de Distribution</h5>
                            <p class="mb-0">Les dons sont distribués automatiquement selon l'ordre de date et de saisie. Le système attribue les dons disponibles aux villes ayant des besoins correspondants en priorité.</p>
                        </div>

                        <!-- Bouton de simulation -->
                        <div class="card card-custom">
                            <div class="card-header">
                                <i class="bi bi-play-circle me-2"></i> Lancer la Simulation
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Cliquez sur le bouton ci-dessous pour simuler la distribution automatique des dons disponibles.</p>
                                <button class="btn btn-primary-custom btn-custom btn-lg" id="btnSimuler">
                                    <i class="bi bi-play-fill me-2"></i> Simuler la Distribution
                                </button>
                            </div>
                        </div>

                        <!-- Résultats de simulation -->
                        <div class="card card-custom" id="resultatsSimulation" style="display: none;">
                            <div class="card-header">
                                <i class="bi bi-check2-circle me-2"></i> Résultats de la Simulation
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-success">15</h3>
                                        <p class="text-muted">Distributions effectuées</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-info">4</h3>
                                        <p class="text-muted">Villes servies</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3 class="text-warning">3.2M Ar</h3>
                                        <p class="text-muted">Valeur totale</p>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Détails des distributions :</h5>
                                
                                <div class="timeline-item">
                                    <h5><i class="bi bi-geo-alt-fill me-2"></i> Antananarivo</h5>
                                    <ul>
                                        <li>200 kg de riz distribués (600,000 Ar)</li>
                                        <li>50 tôles distribuées (1,250,000 Ar)</li>
                                        <li>300,000 Ar d'aide financière</li>
                                    </ul>
                                </div>

                                <div class="timeline-item">
                                    <h5><i class="bi bi-geo-alt-fill me-2"></i> Antsirabe</h5>
                                    <ul>
                                        <li>100 kg de riz distribués (300,000 Ar)</li>
                                        <li>30 tôles distribuées (750,000 Ar)</li>
                                    </ul>
                                </div>

                                <div class="timeline-item">
                                    <h5><i class="bi bi-geo-alt-fill me-2"></i> Fianarantsoa</h5>
                                    <ul>
                                        <li>150 kg de riz distribués (450,000 Ar)</li>
                                        <li>40 L d'huile distribués (320,000 Ar)</li>
                                    </ul>
                                </div>

                                <div class="timeline-item">
                                    <h5><i class="bi bi-geo-alt-fill me-2"></i> Toamasina</h5>
                                    <ul>
                                        <li>180 kg de riz distribués (540,000 Ar)</li>
                                        <li>25 tôles distribuées (625,000 Ar)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Historique des distributions -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-clock-history me-2"></i> Historique des Distributions
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date Distribution</th>
                                                <th>Ville</th>
                                                <th>Don</th>
                                                <th>Quantité</th>
                                                <th>Type Besoin</th>
                                                <th>Valeur</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>#001</strong></td>
                                                <td>16/02/2026 14:30</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td>Riz</td>
                                                <td>150 kg</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>450,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#002</strong></td>
                                                <td>16/02/2026 13:15</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Toamasina</td>
                                                <td>Tôle</td>
                                                <td>50 pièces</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>1,250,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#003</strong></td>
                                                <td>16/02/2026 11:00</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antsirabe</td>
                                                <td>Aide financière</td>
                                                <td>200,000 Ar</td>
                                                <td><span class="badge bg-warning text-dark badge-custom">Argent</span></td>
                                                <td>200,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#004</strong></td>
                                                <td>16/02/2026 09:45</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Fianarantsoa</td>
                                                <td>Huile</td>
                                                <td>80 L</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>640,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#005</strong></td>
                                                <td>15/02/2026 16:30</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td>Clous</td>
                                                <td>30 kg</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>360,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#006</strong></td>
                                                <td>15/02/2026 14:15</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Toamasina</td>
                                                <td>Riz</td>
                                                <td>100 kg</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>300,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>#007</strong></td>
                                                <td>15/02/2026 11:20</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antsirabe</td>
                                                <td>Tôle</td>
                                                <td>40 pièces</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>1,000,000 Ar</td>
                                                <td><span class="badge bg-success">Complété</span></td>
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
