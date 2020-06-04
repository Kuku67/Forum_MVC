<?php
    $topic = $result['data']['topic'];
    $messages = $result['data']['messages'];
?>
<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Sujet - crée par <a href="/profile/view/<?= $topic->getUser()->getId() ?>"><?= $topic->getUser()->getPseudo() ?></a></p>
        <?php  if($message = App\Session::getMessage()) { ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php } else { 
            if($topic->getVerrouillage() == true): ?>
        <p class="alert alert-danger">Le sujet est verrouillé.</p>
        <?php endif; } ?>
        <!-- BUNDLE D'ADMIN -->
        <?php if($topic->getUser()->getId() == App\Session::getUser()->getId() || App\Session::getUser()->getRole() === 'admin'): ?>
        <div class="administation-bundle d-flex flex-wrap justify-content-center align-items-center p-2">
            <p class="d-flex flex-wrap">
                <a href="/topic/lock/<?= $topic->getId() ?>"><i class="fas fa-key"></i><?= $topic->getVerrouillage() == false ? 'Verrouiller le sujet' : 'Déverrouiller le sujet' ?></a>
                <a href="/topic/resolve/<?= $topic->getId() ?>"><i class="fas fa-check"></i><?= $topic->getResolu() == false ? 'Marquer comme résolu' : 'Retirer le statut « résolu »' ?></a>
                <a href="/topic/edit/<?= $topic->getId() ?>"><i class="fas fa-pencil-ruler"></i>Éditer</a>
                <a href="/topic/delete/<?= $topic->getId() ?>"><i class="fas fa-ban"></i>Supprimer</a>
            </p>
        </div>
        <?php endif; ?>
    </div>
    <div class="topic-container">
    <!-- CONTENEUR DU SUJET -->
        <h3 class="text-center display-6"><?= $topic->getTitre() ?></h3>
        <div class="title-separator"></div>
        <p class="lead"><?= $topic->getContenu() ?></p>
        <hr class="my-4">
        <!-- BLOC COMMENTAIRES -->
        <?php foreach($messages as $message): ?>
        <div class="comment">
            <div class="comment-heading d-flex align-items-center justify-content-between">
                <p>de <a href="/profile/view/<?= $message->getUser()->getId() ?>"><?= $message->getUser()->getPseudo() ?></a>, le <?= $message->getCreation("d/m/Y") ?> à <?= $message->getCreation('H:i') ?></p>
                <?php if($message->getUser()->getId() == App\Session::getUser()->getId() || App\Session::getUser()->getRole() === 'admin'): ?>
                <div class="comment-bundle">
                    <a href="/message/edit/<?= $message->getId() ?>">Éditer<i class="fas fa-pencil-ruler"></i></a>
                    <a href="/message/delete/<?= $message->getId() ?>">Supprimer<i class="fas fa-ban"></i></a>
                </div>
                <?php endif; ?>
            </div>
            <hr class="my-2">
            <p class="lead p-2"><?= $message->getContenu() ?></p>
        </div>
        <?php endforeach; ?>
            <!-- LE FORMULAIRE D'ENVOI DE MESSAGE -->
        <?php if($topic->getVerrouillage() == false): ?>
        <div class="container">
            <h3 class="text-dark display-5">Laisser un message</h3>
            <hr class="my-4">
            <form action="/message/send/<?= $topic->getId() ?>" method="post">
                <div class="form-group">
                    <label for="contenu" class="text-white lead">Message</label>
                    <textarea name="contenu" class="form-control" rows="15"></textarea>
                </div>
                <input type="hidden" name="token" value="<?= $csrf ?>">
                <button type="submit" name="submit" class="submit">Déposer le message</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>