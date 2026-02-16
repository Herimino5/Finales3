<?php 
$title = "Achats avec Dons en Argent - BNGRC"; 
$activePage = 'achats';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <h2 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="bi bi-cash-coin"></i> Achats avec Dons en Argent
                        </h2>

                        <!-- Alertes -->
                        <div id="alertContainer"></div>

                        <?php if(isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Formulaire de simulation/validation -->
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header">
                                        <i class="bi bi-calculator me-2"></i> Simuler / Valider un Achat
                                    </div>
                                    <div class="card-body">
                                        <form id="formAchat">
                                            <div class="mb-3">
                                                <label for="ville" class="form-label">
                                                    <i class="bi bi-geo-alt-fill text-primary"></i> Ville
                                                </label>
                                                <select class="form-select" id="ville" name="ville_id" required>
                                                    <option value="">Sélectionner une ville</option>
                                                    <?php if(isset($villes) && !empty($villes)): ?>
                                                        <?php foreach($villes as $ville): ?>
                                                            <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="besoin" class="form-label">
                                                    <i class="bi bi-exclamation-circle-fill text-warning"></i> Besoin à couvrir
                                                </label>
                                                <select class="form-select" id="besoin" name="besoin_id" required disabled>
                                                    <option value="">Sélectionner d'abord une ville</option>
                                                </select>
                                                <div class="form-text" id="besoinInfo"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="donArgent" class="form-label">
                                                    <i class="bi bi-wallet-fill text-success"></i> Don en Argent à utiliser
                                                </label>
                                                <select class="form-select" id="donArgent" name="don_argent_id" required>
                                                    <option value="">Sélectionner un don en argent</option>
                                                    <?php if(isset($donsArgent) && is_array($donsArgent) && !isset($donsArgent['success'])): ?>
                                                        <?php foreach($donsArgent as $don): ?>
                                                            <?php if(is_array($don) && isset($don['id'])): ?>
                                                            <option value="<?= $don['id'] ?>" data-disponible="<?= $don['montant_disponible'] ?? 0 ?>">
                                                                <?= htmlspecialchars($don['descriptions'] ?? 'Don #'.$don['id']) ?> 
                                                                - Disponible: <?= number_format($don['montant_disponible'] ?? 0, 0, ',', ' ') ?> Ar
                                                            </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="form-text text-success" id="donInfo"></div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="quantite" class="form-label">
                                                        <i class="bi bi-plus-slash-minus text-info"></i> Quantité à acheter
                                                    </label>
                                                    <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Ex: 10" min="1" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="frais" class="form-label">
                                                        <i class="bi bi-percent text-secondary"></i> Frais (%)
                                                    </label>
                                                    <input type="number" class="form-control" id="frais" name="frais" value="0" min="0" max="100" step="0.5">
                                                </div>
                                            </div>

                                            <!-- Résultat de la simulation -->
                                            <div id="simulationResult" class="mb-3" style="display: none;">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title"><i class="bi bi-info-circle"></i> Résultat de la simulation</h6>
                                                        <table class="table table-sm mb-0">
                                                            <tr>
                                                                <td>Produit :</td>
                                                                <td id="simProduit" class="fw-bold"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Prix unitaire :</td>
                                                                <td id="simPrix"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Coût de base :</td>
                                                                <td id="simCoutBase"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Frais :</td>
                                                                <td id="simFrais"></td>
                                                            </tr>
                                                            <tr class="table-primary">
                                                                <td><strong>Coût total :</strong></td>
                                                                <td id="simCoutTotal" class="fw-bold"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Montant disponible :</td>
                                                                <td id="simDisponible"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Reste après achat :</td>
                                                                <td id="simReste"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <div class="btn-group" role="group">
                                                    <button type="button" id="btnSimuler" class="btn btn-outline-primary btn-custom">
                                                        <i class="bi bi-calculator me-2"></i> Simuler
                                                    </button>
                                                    <button type="button" id="btnValider" class="btn btn-primary-custom btn-custom" disabled>
                                                        <i class="bi bi-check-circle me-2"></i> Valider l'Achat
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Liste des besoins non couverts -->
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header">
                                        <i class="bi bi-list-check me-2"></i> Besoins Non Couverts
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-custom table-hover table-sm" id="tableBesoins">
                                                <thead class="sticky-top bg-white">
                                                    <tr>
                                                        <th>Ville</th>
                                                        <th>Produit</th>
                                                        <th>Demandé</th>
                                                        <th>Restant</th>
                                                        <th>Prix unit.</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="besoinsTableBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">
                                                            <i class="bi bi-arrow-left me-2"></i>Sélectionnez une ville pour voir les besoins
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dons en argent disponibles -->
                                <div class="card card-custom mt-3">
                                    <div class="card-header bg-success text-white">
                                        <i class="bi bi-wallet2 me-2"></i> Dons en Argent Disponibles
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Total</th>
                                                        <th>Disponible</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(isset($donsArgent) && is_array($donsArgent) && !isset($donsArgent['success'])): ?>
                                                        <?php foreach($donsArgent as $don): ?>
                                                            <?php if(is_array($don) && isset($don['id'])): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($don['descriptions'] ?? 'Don #'.$don['id']) ?></td>
                                                                <td><?= number_format($don['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                                <td class="text-success fw-bold"><?= number_format($don['montant_disponible'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">Aucun don en argent disponible</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historique des achats -->
                        <div class="card card-custom mt-4">
                            <div class="card-header">
                                <i class="bi bi-clock-history me-2"></i> Historique des Achats
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Produit</th>
                                                <th>Quantité</th>
                                                <th>Frais</th>
                                                <th>Montant</th>
                                                <th>Don utilisé</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($achats) && is_array($achats) && !isset($achats['success'])): ?>
                                                <?php foreach($achats as $achat): ?>
                                                    <?php if(is_array($achat) && isset($achat['id'])): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?></td>
                                                        <td><?= htmlspecialchars($achat['produit_nom'] ?? '-') ?></td>
                                                        <td><?= number_format($achat['quantite'] ?? 0, 0, ',', ' ') ?></td>
                                                        <td><?= $achat['frais_pourcentage'] ?? 0 ?>%</td>
                                                        <td class="fw-bold"><?= number_format($achat['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                        <td><?= htmlspecialchars($achat['don_description'] ?? '-') ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucun achat enregistré</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const BASE_URL = document.body.getAttribute('data-base-url') || '/';
    const villeSelect = document.getElementById('ville');
    const besoinSelect = document.getElementById('besoin');
    const donArgentSelect = document.getElementById('donArgent');
    const quantiteInput = document.getElementById('quantite');
    const fraisInput = document.getElementById('frais');
    const btnSimuler = document.getElementById('btnSimuler');
    const btnValider = document.getElementById('btnValider');
    const simulationResult = document.getElementById('simulationResult');
    const alertContainer = document.getElementById('alertContainer');

    let simulationData = null;

    // Charger les besoins quand une ville est sélectionnée
    villeSelect.addEventListener('change', function() {
        const villeId = this.value;
        besoinSelect.disabled = !villeId;
        
        if (villeId) {
            fetch(BASE_URL + 'api/dispatch/besoins?ville_id=' + villeId)
                .then(response => response.json())
                .then(data => {
                    besoinSelect.innerHTML = '<option value="">Sélectionner un besoin</option>';
                    const tbody = document.getElementById('besoinsTableBody');
                    tbody.innerHTML = '';

                    if (data.success && data.besoins && data.besoins.length > 0) {
                        data.besoins.forEach(besoin => {
                            // Ajouter à la liste déroulante
                            const option = document.createElement('option');
                            option.value = besoin.id;
                            option.dataset.productId = besoin.id_product;
                            option.dataset.prixUnitaire = besoin.prix_unitaire || 0;
                            option.dataset.quantiteRestante = besoin.quantite_restante;
                            option.textContent = `${besoin.produit_nom} - Restant: ${besoin.quantite_restante}`;
                            besoinSelect.appendChild(option);

                            // Ajouter au tableau
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${besoin.ville_nom}</td>
                                <td>${besoin.produit_nom}</td>
                                <td>${besoin.quantite_demandee}</td>
                                <td class="text-danger fw-bold">${besoin.quantite_restante}</td>
                                <td>${Number(besoin.prix_unitaire || 0).toLocaleString('fr-FR')} Ar</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-success">Tous les besoins sont couverts !</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showAlert('danger', 'Erreur lors du chargement des besoins');
                });
        } else {
            besoinSelect.innerHTML = '<option value="">Sélectionner d\'abord une ville</option>';
        }
    });

    // Afficher info du besoin sélectionné
    besoinSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option && option.dataset.quantiteRestante) {
            document.getElementById('besoinInfo').textContent = 
                `Quantité restante: ${option.dataset.quantiteRestante} | Prix unitaire: ${Number(option.dataset.prixUnitaire).toLocaleString('fr-FR')} Ar`;
        } else {
            document.getElementById('besoinInfo').textContent = '';
        }
    });

    // Afficher info du don sélectionné
    donArgentSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option && option.dataset.disponible) {
            document.getElementById('donInfo').textContent = 
                `Montant disponible: ${Number(option.dataset.disponible).toLocaleString('fr-FR')} Ar`;
        } else {
            document.getElementById('donInfo').textContent = '';
        }
    });

    // Simuler l'achat
    btnSimuler.addEventListener('click', function() {
        const villeId = villeSelect.value;
        const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
        const productId = besoinOption ? besoinOption.dataset.productId : null;
        const quantite = quantiteInput.value;
        const frais = fraisInput.value || 0;

        if (!villeId || !productId || !quantite) {
            showAlert('warning', 'Veuillez remplir tous les champs obligatoires');
            return;
        }

        btnSimuler.disabled = true;
        btnSimuler.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Simulation...';

        fetch(BASE_URL + 'api/achat/simulate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `ville_id=${villeId}&product_id=${productId}&quantite=${quantite}&frais=${frais}`
        })
        .then(response => response.json())
        .then(data => {
            btnSimuler.disabled = false;
            btnSimuler.innerHTML = '<i class="bi bi-calculator me-2"></i> Simuler';

            if (data.success) {
                simulationData = data;
                displaySimulation(data);
                btnValider.disabled = false;
                showAlert('success', data.message);
            } else {
                simulationData = null;
                btnValider.disabled = true;
                displaySimulation(data);
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btnSimuler.disabled = false;
            btnSimuler.innerHTML = '<i class="bi bi-calculator me-2"></i> Simuler';
            showAlert('danger', 'Erreur lors de la simulation');
        });
    });

    // Valider l'achat
    btnValider.addEventListener('click', function() {
        const donArgentId = donArgentSelect.value;
        const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
        const productId = besoinOption ? besoinOption.dataset.productId : null;
        const besoinId = besoinSelect.value;
        const quantite = quantiteInput.value;
        const frais = fraisInput.value || 0;

        if (!donArgentId || !productId || !quantite) {
            showAlert('warning', 'Veuillez sélectionner un don en argent et remplir tous les champs');
            return;
        }

        if (!confirm('Confirmer cet achat ? Cette action va débiter le don en argent.')) {
            return;
        }

        btnValider.disabled = true;
        btnValider.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Validation...';

        fetch(BASE_URL + 'api/achat/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `don_argent_id=${donArgentId}&product_id=${productId}&quantite=${quantite}&frais=${frais}&besoin_id=${besoinId}`
        })
        .then(response => response.json())
        .then(data => {
            btnValider.innerHTML = '<i class="bi bi-check-circle me-2"></i> Valider l\'Achat';

            if (data.success) {
                showAlert('success', data.message);
                // Recharger la page après 1.5 secondes
                setTimeout(() => location.reload(), 1500);
            } else {
                btnValider.disabled = false;
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btnValider.disabled = false;
            btnValider.innerHTML = '<i class="bi bi-check-circle me-2"></i> Valider l\'Achat';
            showAlert('danger', 'Erreur lors de la validation');
        });
    });

    function displaySimulation(data) {
        simulationResult.style.display = 'block';
        document.getElementById('simProduit').textContent = data.produit || '-';
        document.getElementById('simPrix').textContent = Number(data.prix_unitaire || 0).toLocaleString('fr-FR') + ' Ar';
        document.getElementById('simCoutBase').textContent = Number(data.cout_base || 0).toLocaleString('fr-FR') + ' Ar';
        document.getElementById('simFrais').textContent = (data.frais_pourcentage || 0) + '%';
        document.getElementById('simCoutTotal').textContent = Number(data.cout_total || 0).toLocaleString('fr-FR') + ' Ar';
        document.getElementById('simDisponible').textContent = Number(data.montant_disponible || 0).toLocaleString('fr-FR') + ' Ar';
        
        const reste = data.reste_apres_achat !== undefined ? data.reste_apres_achat : (data.montant_disponible - data.cout_total);
        document.getElementById('simReste').textContent = Number(reste || 0).toLocaleString('fr-FR') + ' Ar';
        document.getElementById('simReste').className = reste >= 0 ? 'text-success' : 'text-danger';
    }

    function showAlert(type, message) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'}-fill me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }
});
</script>
