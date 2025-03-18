<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="jumbotron text-center">
    <h1 class="display-4">Bem-vindo ao <?php echo APP_NAME; ?></h1>
    <p class="lead">Sistema de Gestão de Hospedagem de Sites</p>
    <hr class="my-4">
    <p>Escolha um de nossos planos de hospedagem e comece a hospedar seu site hoje mesmo!</p>
    <a class="btn btn-primary btn-lg" href="<?php echo BASE_URL; ?>/plans" role="button">Ver Planos</a>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-server fa-4x mb-3"></i>
                <h3 class="card-title">Hospedagem Confiável</h3>
                <p class="card-text">Nossos servidores possuem 99,9% de uptime, garantindo que seu site esteja sempre disponível.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-headset fa-4x mb-3"></i>
                <h3 class="card-title">Suporte 24/7</h3>
                <p class="card-text">Nossa equipe de suporte está disponível 24 horas por dia, 7 dias por semana para ajudá-lo.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-shield-alt fa-4x mb-3"></i>
                <h3 class="card-title">Segurança Garantida</h3>
                <p class="card-text">Seus dados estão seguros conosco. Realizamos backups diários e utilizamos certificados SSL.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>

