<?php 
$title = "Gérer les Besoins - BNGRC"; 
$activePage = 'besoins';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

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
