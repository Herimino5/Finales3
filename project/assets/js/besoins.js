// besoins.js - Script pour la gestion des besoins

document.addEventListener('DOMContentLoaded', function() {
    // Calculer le prix total automatiquement
    const produitSelect = document.getElementById('produit');
    const quantiteInput = document.getElementById('quantite');
    const prixTotalInput = document.getElementById('prixTotal');

    function calculerPrixTotal() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        const prixUnitaire = parseFloat(selectedOption.getAttribute('data-prix')) || 0;
        const quantite = parseFloat(quantiteInput.value) || 0;
        const total = prixUnitaire * quantite;
        
        prixTotalInput.value = total > 0 ? total.toLocaleString('fr-FR') + ' Ar' : '';
    }

    produitSelect.addEventListener('change', calculerPrixTotal);
    quantiteInput.addEventListener('input', calculerPrixTotal);

    // Gérer l'enregistrement d'un nouveau produit
    const btnSaveProduit = document.getElementById('btnSaveProduit');
    if (btnSaveProduit) {
        btnSaveProduit.addEventListener('click', function() {
            const form = document.getElementById('formNouveauProduit');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            const baseUrl = document.body.getAttribute('data-base-url') || '/';
            
            fetch(baseUrl + 'produitsInsert', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produit ajouté avec succès!');
                    // Recharger la page pour mettre à jour la liste des produits
                    window.location.reload();
                } else {
                    alert('Erreur lors de l\'ajout du produit');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout du produit');
            });
        });
    }
});
