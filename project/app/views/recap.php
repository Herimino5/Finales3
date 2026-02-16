<?php
$title = "Récapitulation des Besoins - BNGRC"; 
$activePage = 'recap';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 style="color: #2c3e50; font-weight: 700;">
                                <i class="bi bi-clipboard-data"></i> Récapitulation des Besoins
                            </h2>
                            <button id="btnActualiser" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-arrow-clockwise me-2"></i> Actualiser
                            </button>
                        </div>

                        <!-- Dernière mise à jour -->
                        <div class="text-end mb-3">
                            <small class="text-muted" id="lastUpdate">
                                <i class="bi bi-clock me-1"></i> Dernière actualisation : <?= date('d/m/Y H:i:s') ?>
                            </small>
                        </div>

                        <!-- Cartes Récapitulatives Globales -->
                        <div class="row mb-4" id="cardsGlobal">
                            <div class="col-md-4">
                                <div class="stat-card nature">
                                    <i class="bi bi-cash-stack text-primary" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-primary" id="montantTotal">
                                        <?= number_format($recapGlobal['montant_total_besoins'] ?? 0, 0, ',', ' ') ?> Ar
                                    </h3>
                                    <p>Montant Total des Besoins</p>
                                    <small class="text-muted" id="qteTotale">
                                        <?= number_format($recapGlobal['quantite_totale_besoins'] ?? 0, 0, ',', ' ') ?> unités 
                                        (<?= $recapGlobal['nombre_besoins'] ?? 0 ?> besoins)
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card materiel">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-success" id="montantSatisfait">
                                        <?= number_format($recapGlobal['montant_satisfait'] ?? 0, 0, ',', ' ') ?> Ar
                                    </h3>
                                    <p>Montant des Besoins Satisfaits</p>
                                    <small class="text-muted" id="qteSatisfaite">
                                        <?= number_format($recapGlobal['quantite_satisfaite'] ?? 0, 0, ',', ' ') ?> unités distribuées
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card argent">
                                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                                    <h3 class="text-danger" id="montantRestant">
                                        <?= number_format($recapGlobal['montant_restant'] ?? 0, 0, ',', ' ') ?> Ar
                                    </h3>
                                    <p>Montant des Besoins Restants</p>
                                    <small class="text-muted" id="qteRestante">
                                        <?= number_format($recapGlobal['quantite_restante'] ?? 0, 0, ',', ' ') ?> unités à couvrir
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression globale -->
                        <div class="card card-custom mb-4">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i> Progression Globale</h5>
                                <?php 
                                    $pourcentageGlobal = ($recapGlobal['montant_total_besoins'] > 0) 
                                        ? round(($recapGlobal['montant_satisfait'] / $recapGlobal['montant_total_besoins']) * 100) 
                                        : 0;
                                ?>
                                <div class="progress" style="height: 30px;" id="progressGlobal">
                                    <div class="progress-bar <?= $pourcentageGlobal >= 75 ? 'bg-success' : ($pourcentageGlobal >= 50 ? 'bg-info' : ($pourcentageGlobal >= 25 ? 'bg-warning' : 'bg-danger')) ?>" 
                                         role="progressbar" 
                                         style="width: <?= $pourcentageGlobal ?>%" 
                                         aria-valuenow="<?= $pourcentageGlobal ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"
                                         id="progressBar">
                                        <?= $pourcentageGlobal ?>% couvert
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-success" id="progressSatisfait">
                                        <i class="bi bi-check-circle me-1"></i> Satisfait : <?= number_format($recapGlobal['montant_satisfait'] ?? 0, 0, ',', ' ') ?> Ar
                                    </small>
                                    <small class="text-danger" id="progressRestant">
                                        <i class="bi bi-exclamation-circle me-1"></i> Restant : <?= number_format($recapGlobal['montant_restant'] ?? 0, 0, ',', ' ') ?> Ar
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Récapitulatif par Ville -->
                        <div class="card card-custom mb-4">
                            <div class="card-header">
                                <i class="bi bi-geo-alt-fill me-2"></i> Récapitulatif par Ville
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ville</th>
                                                <th>Nb Besoins</th>
                                                <th>Montant Total</th>
                                                <th>Montant Satisfait</th>
                                                <th>Montant Restant</th>
                                                <th>Progression</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableVilles">
                                            <?php if(!empty($recapParVille)): ?>
                                                <?php foreach($recapParVille as $ville): ?>
                                                    <?php 
                                                        $pctVille = ($ville['montant_total_besoins'] > 0) 
                                                            ? round(($ville['montant_satisfait'] / $ville['montant_total_besoins']) * 100) 
                                                            : 0;
                                                    ?>
                                                    <tr>
                                                        <td><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($ville['ville_nom']) ?></td>
                                                        <td><?= $ville['nombre_besoins'] ?></td>
                                                        <td class="fw-bold"><?= number_format($ville['montant_total_besoins'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-success"><?= number_format($ville['montant_satisfait'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-danger fw-bold"><?= number_format($ville['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                                        <td>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar <?= $pctVille >= 75 ? 'bg-success' : ($pctVille >= 50 ? 'bg-info' : ($pctVille >= 25 ? 'bg-warning' : 'bg-danger')) ?>" 
                                                                     style="width: <?= $pctVille ?>%">
                                                                    <?= $pctVille ?>%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucune donnée disponible</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Récapitulatif par Produit -->
                        <div class="card card-custom mb-4">
                            <div class="card-header">
                                <i class="bi bi-box-seam me-2"></i> Récapitulatif par Produit
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Catégorie</th>
                                                <th>Prix Unit.</th>
                                                <th>Qté Totale</th>
                                                <th>Qté Satisfaite</th>
                                                <th>Qté Restante</th>
                                                <th>Montant Total</th>
                                                <th>Montant Satisfait</th>
                                                <th>Montant Restant</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableProduits">
                                            <?php if(!empty($recapParProduit)): ?>
                                                <?php foreach($recapParProduit as $produit): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($produit['produit_nom']) ?></td>
                                                        <td>
                                                            <?php 
                                                            $badgeClass = 'bg-secondary';
                                                            if($produit['categorie_nom'] == 'Nature') $badgeClass = 'bg-success';
                                                            elseif($produit['categorie_nom'] == 'Matériaux') $badgeClass = 'bg-info';
                                                            elseif($produit['categorie_nom'] == 'Argent') $badgeClass = 'bg-warning text-dark';
                                                            ?>
                                                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($produit['categorie_nom']) ?></span>
                                                        </td>
                                                        <td><?= number_format($produit['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                                        <td><?= number_format($produit['quantite_totale'], 0, ',', ' ') ?></td>
                                                        <td class="text-success"><?= number_format($produit['quantite_satisfaite'], 0, ',', ' ') ?></td>
                                                        <td class="text-danger"><?= number_format($produit['quantite_restante'], 0, ',', ' ') ?></td>
                                                        <td class="fw-bold"><?= number_format($produit['montant_total_besoins'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-success"><?= number_format($produit['montant_satisfait'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-danger fw-bold"><?= number_format($produit['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">Aucune donnée disponible</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Détail par Besoin -->
                        <div class="card card-custom mb-4">
                            <div class="card-header">
                                <i class="bi bi-list-check me-2"></i> Détail par Besoin
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Catégorie</th>
                                                <th>Qté Demandée</th>
                                                <th>Qté Distribuée</th>
                                                <th>Qté Restante</th>
                                                <th>Montant Besoin</th>
                                                <th>Montant Satisfait</th>
                                                <th>Montant Restant</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableDetaille">
                                            <?php if(!empty($recapDetaille)): ?>
                                                <?php foreach($recapDetaille as $besoin): ?>
                                                    <?php 
                                                        $pctBesoin = ($besoin['montant_besoin'] > 0) 
                                                            ? round(($besoin['montant_satisfait'] / $besoin['montant_besoin']) * 100) 
                                                            : 0;
                                                        $statutClass = '';
                                                        $statutText = '';
                                                        if ($besoin['quantite_restante'] <= 0) {
                                                            $statutClass = 'bg-success';
                                                            $statutText = 'Couvert';
                                                        } elseif ($besoin['quantite_distribuee'] > 0) {
                                                            $statutClass = 'bg-warning text-dark';
                                                            $statutText = 'Partiel (' . $pctBesoin . '%)';
                                                        } else {
                                                            $statutClass = 'bg-danger';
                                                            $statutText = 'Non couvert';
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><strong>#<?= str_pad($besoin['besoin_id'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                                                        <td><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($besoin['ville_nom']) ?></td>
                                                        <td><?= htmlspecialchars($besoin['produit_nom']) ?></td>
                                                        <td>
                                                            <?php 
                                                            $badgeClass = 'bg-secondary';
                                                            if($besoin['categorie_nom'] == 'Nature') $badgeClass = 'bg-success';
                                                            elseif($besoin['categorie_nom'] == 'Matériaux') $badgeClass = 'bg-info';
                                                            elseif($besoin['categorie_nom'] == 'Argent') $badgeClass = 'bg-warning text-dark';
                                                            ?>
                                                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($besoin['categorie_nom']) ?></span>
                                                        </td>
                                                        <td><?= number_format($besoin['quantite_demandee'], 0, ',', ' ') ?></td>
                                                        <td class="text-success"><?= number_format($besoin['quantite_distribuee'], 0, ',', ' ') ?></td>
                                                        <td class="text-danger"><?= number_format($besoin['quantite_restante'], 0, ',', ' ') ?></td>
                                                        <td><?= number_format($besoin['montant_besoin'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-success"><?= number_format($besoin['montant_satisfait'], 0, ',', ' ') ?> Ar</td>
                                                        <td class="text-danger fw-bold"><?= number_format($besoin['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                                        <td><span class="badge <?= $statutClass ?>"><?= $statutText ?></span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="11" class="text-center text-muted">Aucun besoin enregistré</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const BASE_URL = document.body.getAttribute('data-base-url') || '/';
    const btnActualiser = document.getElementById('btnActualiser');

    btnActualiser.addEventListener('click', function() {
        // Afficher le spinner
        btnActualiser.disabled = true;
        btnActualiser.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Actualisation...';

        fetch(BASE_URL + 'api/recap')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le timestamp
                    document.getElementById('lastUpdate').innerHTML = 
                        '<i class="bi bi-clock me-1"></i> Dernière actualisation : ' + data.timestamp;

                    const g = data.global;

                    // Mettre à jour les cartes globales
                    document.getElementById('montantTotal').textContent = 
                        Number(g.montant_total_besoins).toLocaleString('fr-FR') + ' Ar';
                    document.getElementById('qteTotale').textContent = 
                        Number(g.quantite_totale_besoins).toLocaleString('fr-FR') + ' unités (' + g.nombre_besoins + ' besoins)';
                    
                    document.getElementById('montantSatisfait').textContent = 
                        Number(g.montant_satisfait).toLocaleString('fr-FR') + ' Ar';
                    document.getElementById('qteSatisfaite').textContent = 
                        Number(g.quantite_satisfaite).toLocaleString('fr-FR') + ' unités distribuées';
                    
                    document.getElementById('montantRestant').textContent = 
                        Number(g.montant_restant).toLocaleString('fr-FR') + ' Ar';
                    document.getElementById('qteRestante').textContent = 
                        Number(g.quantite_restante).toLocaleString('fr-FR') + ' unités à couvrir';

                    // Mettre à jour la barre de progression
                    const pct = g.montant_total_besoins > 0 
                        ? Math.round((g.montant_satisfait / g.montant_total_besoins) * 100) 
                        : 0;
                    const progressBar = document.getElementById('progressBar');
                    progressBar.style.width = pct + '%';
                    progressBar.textContent = pct + '% couvert';
                    progressBar.className = 'progress-bar ' + 
                        (pct >= 75 ? 'bg-success' : (pct >= 50 ? 'bg-info' : (pct >= 25 ? 'bg-warning' : 'bg-danger')));

                    document.getElementById('progressSatisfait').innerHTML = 
                        '<i class="bi bi-check-circle me-1"></i> Satisfait : ' + Number(g.montant_satisfait).toLocaleString('fr-FR') + ' Ar';
                    document.getElementById('progressRestant').innerHTML = 
                        '<i class="bi bi-exclamation-circle me-1"></i> Restant : ' + Number(g.montant_restant).toLocaleString('fr-FR') + ' Ar';

                    // Mettre à jour le tableau par ville
                    const tbodyVilles = document.getElementById('tableVilles');
                    tbodyVilles.innerHTML = '';
                    if (data.par_ville.length > 0) {
                        data.par_ville.forEach(ville => {
                            const pctV = ville.montant_total_besoins > 0 
                                ? Math.round((ville.montant_satisfait / ville.montant_total_besoins) * 100) 
                                : 0;
                            const barClass = pctV >= 75 ? 'bg-success' : (pctV >= 50 ? 'bg-info' : (pctV >= 25 ? 'bg-warning' : 'bg-danger'));
                            tbodyVilles.innerHTML += `
                                <tr>
                                    <td><i class="bi bi-geo-alt-fill text-primary"></i> ${ville.ville_nom}</td>
                                    <td>${ville.nombre_besoins}</td>
                                    <td class="fw-bold">${Number(ville.montant_total_besoins).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-success">${Number(ville.montant_satisfait).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-danger fw-bold">${Number(ville.montant_restant).toLocaleString('fr-FR')} Ar</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar ${barClass}" style="width: ${pctV}%">${pctV}%</div>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        tbodyVilles.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune donnée</td></tr>';
                    }

                    // Mettre à jour le tableau par produit
                    const tbodyProduits = document.getElementById('tableProduits');
                    tbodyProduits.innerHTML = '';
                    if (data.par_produit.length > 0) {
                        data.par_produit.forEach(p => {
                            let badgeClass = 'bg-secondary';
                            if (p.categorie_nom === 'Nature') badgeClass = 'bg-success';
                            else if (p.categorie_nom === 'Matériaux') badgeClass = 'bg-info';
                            else if (p.categorie_nom === 'Argent') badgeClass = 'bg-warning text-dark';
                            
                            tbodyProduits.innerHTML += `
                                <tr>
                                    <td>${p.produit_nom}</td>
                                    <td><span class="badge ${badgeClass}">${p.categorie_nom}</span></td>
                                    <td>${Number(p.prix_unitaire).toLocaleString('fr-FR')} Ar</td>
                                    <td>${Number(p.quantite_totale).toLocaleString('fr-FR')}</td>
                                    <td class="text-success">${Number(p.quantite_satisfaite).toLocaleString('fr-FR')}</td>
                                    <td class="text-danger">${Number(p.quantite_restante).toLocaleString('fr-FR')}</td>
                                    <td class="fw-bold">${Number(p.montant_total_besoins).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-success">${Number(p.montant_satisfait).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-danger fw-bold">${Number(p.montant_restant).toLocaleString('fr-FR')} Ar</td>
                                </tr>`;
                        });
                    } else {
                        tbodyProduits.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Aucune donnée</td></tr>';
                    }

                    // Mettre à jour le tableau détaillé
                    const tbodyDetail = document.getElementById('tableDetaille');
                    tbodyDetail.innerHTML = '';
                    if (data.detaille.length > 0) {
                        data.detaille.forEach(b => {
                            const pctB = b.montant_besoin > 0 ? Math.round((b.montant_satisfait / b.montant_besoin) * 100) : 0;
                            let statutClass = '', statutText = '';
                            if (b.quantite_restante <= 0) {
                                statutClass = 'bg-success'; statutText = 'Couvert';
                            } else if (b.quantite_distribuee > 0) {
                                statutClass = 'bg-warning text-dark'; statutText = 'Partiel (' + pctB + '%)';
                            } else {
                                statutClass = 'bg-danger'; statutText = 'Non couvert';
                            }
                            let badgeCat = 'bg-secondary';
                            if (b.categorie_nom === 'Nature') badgeCat = 'bg-success';
                            else if (b.categorie_nom === 'Matériaux') badgeCat = 'bg-info';
                            else if (b.categorie_nom === 'Argent') badgeCat = 'bg-warning text-dark';

                            tbodyDetail.innerHTML += `
                                <tr>
                                    <td><strong>#${String(b.besoin_id).padStart(3, '0')}</strong></td>
                                    <td><i class="bi bi-geo-alt-fill text-primary"></i> ${b.ville_nom}</td>
                                    <td>${b.produit_nom}</td>
                                    <td><span class="badge ${badgeCat}">${b.categorie_nom}</span></td>
                                    <td>${Number(b.quantite_demandee).toLocaleString('fr-FR')}</td>
                                    <td class="text-success">${Number(b.quantite_distribuee).toLocaleString('fr-FR')}</td>
                                    <td class="text-danger">${Number(b.quantite_restante).toLocaleString('fr-FR')}</td>
                                    <td>${Number(b.montant_besoin).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-success">${Number(b.montant_satisfait).toLocaleString('fr-FR')} Ar</td>
                                    <td class="text-danger fw-bold">${Number(b.montant_restant).toLocaleString('fr-FR')} Ar</td>
                                    <td><span class="badge ${statutClass}">${statutText}</span></td>
                                </tr>`;
                        });
                    } else {
                        tbodyDetail.innerHTML = '<tr><td colspan="11" class="text-center text-muted">Aucun besoin</td></tr>';
                    }

                    // Animation de succès
                    btnActualiser.classList.remove('btn-primary-custom');
                    btnActualiser.classList.add('btn-success');
                    btnActualiser.innerHTML = '<i class="bi bi-check-circle me-2"></i> Actualisé !';
                    
                    setTimeout(() => {
                        btnActualiser.classList.remove('btn-success');
                        btnActualiser.classList.add('btn-primary-custom');
                        btnActualiser.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i> Actualiser';
                        btnActualiser.disabled = false;
                    }, 1500);
                } else {
                    alert('Erreur lors de l\'actualisation');
                    btnActualiser.disabled = false;
                    btnActualiser.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i> Actualiser';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'actualisation');
                btnActualiser.disabled = false;
                btnActualiser.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i> Actualiser';
            });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>