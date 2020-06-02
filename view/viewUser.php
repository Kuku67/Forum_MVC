<?php
$user = $result['data']['user'];
$topics = $result['data']['topics'];
$messages = $result['data']['messages'];
?>
<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Profil de <a href="/profile/view/<?= $user->getId() ?>"><?= $user->getPseudo() ?></a></p>
        <?php  if($message = App\Session::getMessage()): ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php App\Session::unsetMessage(); ?>
        <?php endif; ?>
        <!-- BUNDLE D'ADMIN -->
        <div class="administation-bundle d-flex justify-content-between align-items-center">
            <i class="fas fa-cogs text-dark"></i>
            <p>
                <?php if(App\Session::getUser()->getId() == $user->getId()): ?>
                <a href="/profile/delete/<?= App\Session::getUser()->getId() ?>" class="bg-danger">Supprimer le compte</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div id="profile-container" class="row">
        <section class="col-lg-4">
            <h4 class="text-center">Informations</h4>
            <hr class="my-4">
            <div class="sub-section">
                <p class="lead">Pseudo : <?= $user->getPseudo() ?></p>
                <p class="lead">Role : <?= ucfirst($user->getRole()) ?></p>
                <p class="lead">Date d'inscription : <?= $user->getInscription('d-m-Y') ?></p>
            </div>
        </section>
        <section class="col-lg-4">
            <h4 class="text-center">Sujets récents</h4>
            <hr class="my-4">
            <div class="sub-section">
                <?php foreach($topics as $topic): ?> 
                <p class="lead"><?= $topic->getCreation('d-m-Y') ?> : <a href="/topic/view/<?= $topic->getId() ?>"><?= $topic->getTitre() ?></a></p>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="col-lg-4">
            <h4 class="text-center">Messages récents</h4>
            <hr class="my-4">
            <div class="sub-section">
                <?php foreach($messages as $message): ?>
                <p class="lead"><?= $message->getCreation('d-m-Y') ?> : <?= strip_tags($message->getContenu()) ?></p>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>