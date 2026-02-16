<?php $title = "BNGRC - Gestion des Dons pour Sinistrés"; ?>
<?php include __DIR__ . '/includes/header.php'; ?>
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-color: #f8f9fa;
        }

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
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .header-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header-section p {
            font-weight: 300;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 5px solid;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-card.nature {
            border-left-color: #198754;
        }

        .stat-card.materiel {
            border-left-color: #0dcaf0;
        }

        .stat-card.argent {
            border-left-color: #ffc107;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card p {
            margin: 5px 0 0 0;
            color: #6c757d;
            font-weight: 500;
        }

        .city-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .city-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }

        .city-card h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .city-card h4 i {
            margin-right: 10px;
            font-size: 1.3rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .progress {
            height: 25px;
            border-radius: 10px;
            margin-bottom: 15px;
            overflow: visible;
        }

        .progress-bar {
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
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

        .table-custom {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
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

        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 1.8rem;
            }
            
            .stat-card h3 {
                font-size: 1.5rem;
            }
    </style>

            <div class="row g-0">
                <!-- Menu latéral -->
                <div class="col-md-3 border-end">
                    <div class="p-4">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.html">
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
                                <a class="nav-link" href="villes.html">
                                    <i class="bi bi-geo-alt me-2"></i> Villes
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="col-md-9">
                    <div class="p-4">
                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-bar-chart-fill"></i> Tableau de Bord
                        </h2>

                        <!-- Statistiques globales -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-card nature">
                                    <i class="bi bi-basket3 text-success" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-success">1,250</h3>
                                    <p>Dons en Nature</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card materiel">
                                    <i class="bi bi-tools text-info" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-info">850</h3>
                                    <p>Dons Matériaux</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card argent">
                                    <i class="bi bi-cash-coin text-warning" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-warning">2.5M Ar</h3>
                                    <p>Dons en Argent</p>
                                </div>
                            </div>
                        </div>

                        <!-- Vue par ville -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-map me-2"></i>Besoins et Dons par Ville
                                </h3>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Ville 1: Antananarivo -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Antananarivo
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>320 / 500</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 64%" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100">
                                                64%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>180 / 350</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 51%" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100">
                                                51%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>800K / 1.2M Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100">
                                                67%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 2: Antsirabe -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Antsirabe
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>150 / 300</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>90 / 200</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                                                45%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>300K / 800K Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 38%" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100">
                                                38%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 3: Fianarantsoa -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Fianarantsoa
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>200 / 400</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>120 / 250</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100">
                                                48%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>400K / 900K Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 44%" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                                                44%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville 4: Toamasina -->
                            <div class="col-md-6">
                                <div class="city-card">
                                    <h4>
                                        <i class="bi bi-geo-alt-fill"></i> Toamasina
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-basket3 text-success"></i> Besoins Nature</span>
                                            <strong>280 / 450</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
                                                62%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-label">
                                            <span><i class="bi bi-tools text-info"></i> Besoins Matériaux</span>
                                            <strong>160 / 300</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 53%" aria-valuenow="53" aria-valuemin="0" aria-valuemax="100">
                                                53%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="progress-label">
                                            <span><i class="bi bi-cash-coin text-warning"></i> Besoins Argent</span>
                                            <strong>500K / 1M Ar</strong>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dernières distributions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3" style="font-weight: 600; color: #495057;">
                                    <i class="bi bi-clock-history me-2"></i>Dernières Distributions
                                </h3>
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Ville</th>
                                                <th>Type</th>
                                                <th>Quantité</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>16/02/2026 14:30</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antananarivo</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>150 kg de riz</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 13:15</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Toamasina</td>
                                                <td><span class="badge bg-info badge-custom">Matériaux</span></td>
                                                <td>50 tôles</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 11:00</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Antsirabe</td>
                                                <td><span class="badge bg-warning text-dark badge-custom">Argent</span></td>
                                                <td>200,000 Ar</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                            <tr>
                                                <td>16/02/2026 09:45</td>
                                                <td><i class="bi bi-geo-alt-fill text-primary"></i> Fianarantsoa</td>
                                                <td><span class="badge bg-success badge-custom">Nature</span></td>
                                                <td>80 L d'huile</td>
                                                <td><span class="badge bg-success">Distribué</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

    <script>
        // Scripts spécifiques à cette page
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
