        </div><!-- .main-container -->
    </div><!-- .container-fluid -->

    <!-- Footer Section -->
    <footer class="mt-5">
        <div class="container">
            <div class="row text-white">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">
                        <i class="bi bi-c-circle"></i> <?= date('Y') ?> BNGRC - Bureau National de Gestion des Risques et Catastrophes
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <i class="bi bi-shield-check"></i> Système de Gestion des Dons pour Sinistrés
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle (Offline) -->
    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to main container
            const mainContainer = document.querySelector('.main-container');
            if (mainContainer) {
                mainContainer.style.opacity = '0';
                setTimeout(() => {
                    mainContainer.style.transition = 'opacity 0.5s ease-in';
                    mainContainer.style.opacity = '1';
                }, 100);
            }
        });
    </script>
</body>
</html>
