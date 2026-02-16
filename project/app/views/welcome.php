<?php $title = "Bienvenue - BNGRC"; ?>
<?php include __DIR__ . '/includes/header.php'; ?>

    <div class="row g-0">
        <div class="col-12">
            <div class="p-5 text-center">
                <h1>Welcome to the FlightPHP Skeleton Example!</h1>
                <?php if(!empty($message)) { ?>
                <h3><?=$message?></h3>
                <?php } ?>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
