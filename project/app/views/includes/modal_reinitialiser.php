<?php
// filepath: /opt/lampp/htdocs/Finales3/project/app/views/includes/modal_reinitialiser.php
?>
<!-- Modal Réinitialisation -->
<div class="modal fade" id="modalReinit" tabindex="-1" aria-labelledby="modalReinitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalReinitLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Réinitialisation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- État actuel chargé en Ajax -->
                <div id="reinitLoading" class="text-center py-3">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2">Chargement de l'état actuel...</p>
                </div>

                <div id="reinitEtat" style="display: none;">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Attention !</strong> Cette action est <strong>irréversible</strong>. Les données suivantes seront supprimées :
                    </div>

                    <table class="table table-sm table-bordered">
                        <thead class="table-danger">
                            <tr>
                                <th>Donnée</th>
                                <th class="text-end">Valeur</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="bi bi-arrow-left-right text-primary me-2"></i> Distributions</td>
                                <td class="text-end fw-bold" id="reinitDistributions">0</td>
                                <td class="text-center"><span class="badge bg-danger">Supprimé</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-box-seam text-info me-2"></i> Quantité distribuée</td>
                                <td class="text-end fw-bold" id="reinitQuantite">0</td>
                                <td class="text-center"><span class="badge bg-danger">Supprimé</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-cash-coin text-warning me-2"></i> Achats</td>
                                <td class="text-end fw-bold" id="reinitAchats">0</td>
                                <td class="text-center"><span class="badge bg-danger">Supprimé</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-wallet2 text-success me-2"></i> Montant achats</td>
                                <td class="text-end fw-bold" id="reinitMontant">0 Ar</td>
                                <td class="text-center"><span class="badge bg-danger">Supprimé</span></td>
                            </tr>
                            <tr class="table-success">
                                <td><i class="bi bi-gift text-success me-2"></i> Dons</td>
                                <td class="text-end fw-bold" id="reinitDons">0</td>
                                <td class="text-center"><span class="badge bg-success">Conservé</span></td>
                            </tr>
                            <tr class="table-success">
                                <td><i class="bi bi-exclamation-circle text-success me-2"></i> Besoins</td>
                                <td class="text-end fw-bold" id="reinitBesoins">0</td>
                                <td class="text-center"><span class="badge bg-success">Conservé</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="reinitVide" style="display: none;">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Rien à réinitialiser. Aucune distribution ni achat enregistré.
                    </div>
                </div>

                <div id="reinitResultat" style="display: none;">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span id="reinitMessage"></span>
                    </div>
                    <div id="reinitDetails"></div>
                </div>
            </div>
            <div class="modal-footer" id="reinitFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Annuler
                </button>
                <button type="button" id="btnConfirmReinit" class="btn btn-danger" disabled>
                    <i class="bi bi-trash3-fill me-1"></i> Confirmer la Réinitialisation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const BASE_URL = document.body.getAttribute('data-base-url') || '/';
    const modal = document.getElementById('modalReinit');
    const btnConfirm = document.getElementById('btnConfirmReinit');
    const reinitLoading = document.getElementById('reinitLoading');
    const reinitEtat = document.getElementById('reinitEtat');
    const reinitVide = document.getElementById('reinitVide');
    const reinitResultat = document.getElementById('reinitResultat');
    const reinitFooter = document.getElementById('reinitFooter');

    // Charger l'état quand le modal s'ouvre
    modal.addEventListener('show.bs.modal', function() {
        reinitLoading.style.display = 'block';
        reinitEtat.style.display = 'none';
        reinitVide.style.display = 'none';
        reinitResultat.style.display = 'none';
        reinitFooter.style.display = 'flex';
        btnConfirm.disabled = true;
        btnConfirm.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Confirmer la Réinitialisation';

        fetch(BASE_URL + 'api/reinitialiser/etat')
            .then(r => r.json())
            .then(data => {
                reinitLoading.style.display = 'none';

                if (data.success) {
                    if (data.total_distributions === 0 && data.total_achats === 0) {
                        reinitVide.style.display = 'block';
                        btnConfirm.disabled = true;
                    } else {
                        reinitEtat.style.display = 'block';
                        document.getElementById('reinitDistributions').textContent = 
                            Number(data.total_distributions).toLocaleString('fr-FR');
                        document.getElementById('reinitQuantite').textContent = 
                            Number(data.total_quantite_distribuee).toLocaleString('fr-FR');
                        document.getElementById('reinitAchats').textContent = 
                            Number(data.total_achats).toLocaleString('fr-FR');
                        document.getElementById('reinitMontant').textContent = 
                            Number(data.montant_achats).toLocaleString('fr-FR') + ' Ar';
                        document.getElementById('reinitDons').textContent = 
                            Number(data.total_dons).toLocaleString('fr-FR');
                        document.getElementById('reinitBesoins').textContent = 
                            Number(data.total_besoins).toLocaleString('fr-FR');
                        btnConfirm.disabled = false;
                    }
                } else {
                    reinitVide.style.display = 'block';
                    reinitVide.querySelector('.alert').className = 'alert alert-danger';
                    reinitVide.querySelector('.alert').innerHTML = 
                        '<i class="bi bi-exclamation-triangle-fill me-2"></i>' + (data.message || 'Erreur');
                }
            })
            .catch(err => {
                reinitLoading.style.display = 'none';
                reinitVide.style.display = 'block';
                reinitVide.querySelector('.alert').className = 'alert alert-danger';
                reinitVide.querySelector('.alert').innerHTML = 
                    '<i class="bi bi-exclamation-triangle-fill me-2"></i> Erreur de connexion au serveur';
            });
    });

    // Confirmer la réinitialisation
    btnConfirm.addEventListener('click', function() {
        if (!confirm('⚠️ DERNIÈRE CONFIRMATION ⚠️\n\nToutes les distributions et achats seront définitivement supprimés.\n\nContinuer ?')) {
            return;
        }

        btnConfirm.disabled = true;
        btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Réinitialisation...';

        fetch(BASE_URL + 'api/reinitialiser', { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                reinitEtat.style.display = 'none';
                reinitVide.style.display = 'none';
                reinitResultat.style.display = 'block';

                if (data.success) {
                    document.getElementById('reinitMessage').textContent = data.message;
                    
                    let detailsHtml = '<table class="table table-sm">';
                    detailsHtml += '<tr><td>Distributions supprimées :</td><td class="text-danger fw-bold">' + 
                        (data.supprime ? data.supprime.distributions : 0) + '</td></tr>';
                    detailsHtml += '<tr><td>Achats supprimés :</td><td class="text-danger fw-bold">' + 
                        (data.supprime ? data.supprime.achats : 0) + '</td></tr>';
                    detailsHtml += '<tr><td>Dons conservés :</td><td class="text-success fw-bold">' + 
                        (data.conserve ? data.conserve.dons : 0) + '</td></tr>';
                    detailsHtml += '<tr><td>Besoins conservés :</td><td class="text-success fw-bold">' + 
                        (data.conserve ? data.conserve.besoins : 0) + '</td></tr>';
                    detailsHtml += '</table>';
                    
                    document.getElementById('reinitDetails').innerHTML = detailsHtml;

                    // Rediriger vers une URL GET propre pour éviter resoumission du formulaire POST
                    reinitFooter.innerHTML = '<button type="button" class="btn btn-primary" onclick="window.location.href = BASE_URL + \'distributions\'">' +
                        '<i class="bi bi-arrow-clockwise me-1"></i> Retour aux distributions</button>';
                    
                    setTimeout(() => { window.location.href = BASE_URL + 'distributions'; }, 2500);
                } else {
                    document.getElementById('reinitResultat').querySelector('.alert').className = 'alert alert-danger';
                    document.getElementById('reinitMessage').textContent = data.message;
                    btnConfirm.disabled = false;
                    btnConfirm.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Réessayer';
                }
            })
            .catch(err => {
                btnConfirm.disabled = false;
                btnConfirm.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Réessayer';
                alert('Erreur de connexion au serveur');
            });
    });
})();
</script>