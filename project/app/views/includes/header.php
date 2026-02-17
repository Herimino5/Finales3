<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'BNGRC - Gestion des Dons pour Sinistrés' ?></title>
    
    <!-- Bootstrap CSS (Offline) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    
    <!-- Bootstrap Icons (Offline) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins (Offline) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/poppins.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body data-base-url="<?= BASE_URL ?>">
    <div class="container-fluid">
        <div class="main-container">
            <!-- Header Section -->
            <div class="header-section text-center">
                <h1><i class="bi bi-heart-pulse-fill"></i> BNGRC</h1>
                <p class="lead mb-0">Bureau National de Gestion des Risques et Catastrophes</p>
                <p class="mb-0">Système de Gestion des Dons pour Sinistrés</p>
            </div>

            <div class="row g-0">
                <!-- Menu latéral -->
                <div class="col-md-3 border-end">
                    <div class="p-4">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'dashboard') ? 'active' : '' ?>" href="<?= BASE_URL ?>">
                                    <i class="bi bi-speedometer2 me-2"></i> Tableau de Bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'besoins') ? 'active' : '' ?>" href="<?= BASE_URL ?>besoinsform">
                                    <i class="bi bi-exclamation-circle me-2"></i> Gérer les Besoins
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'dons') ? 'active' : '' ?>" href="<?= BASE_URL ?>donsform">
                                    <i class="bi bi-gift me-2"></i> Gérer les Dons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'distributions') ? 'active' : '' ?>" href="<?= BASE_URL ?>distributions">
                                    <i class="bi bi-arrow-left-right me-2"></i> Distributions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'recap') ? 'active' : '' ?>" href="<?= BASE_URL ?>recap">
                                    <i class="bi bi-clipboard-data me-2"></i> Récapitulation
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'achats') ? 'active' : '' ?>" href="<?= BASE_URL ?>achats">
                                    <i class="bi bi-cash-coin me-2"></i> Achats
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'villes') ? 'active' : '' ?>" href="<?= BASE_URL ?>villes">
                                    <i class="bi bi-geo-alt me-2"></i> Villes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (isset($activePage) && $activePage === 'regions') ? 'active' : '' ?>" href="<?= BASE_URL ?>regions">
                                    <i class="bi bi-map me-2"></i> Régions
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="col-md-9">
                    <div class="p-4">
