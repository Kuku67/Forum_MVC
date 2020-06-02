<?php
    $topic = $result['data'];
?>
<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Édition de sujet - crée par <a href="/profile/view/<?= $topic->getUser()->getId() ?>"><?= $topic->getUser()->getPseudo() ?></a></p>
        <?php  if($message = App\Session::getMessage()) { ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php App\Session::unsetMessage(); ?>
        <?php } else { 
            if($topic->getVerrouillage() == 1): ?>
        <p class="alert alert-danger">Le sujet est verrouillé.</p>
        <?php endif; } ?>
    </div>
    <!-- CONTENEUR DU SUJET -->
    <div class="topic-container">
        <h3 class="text-center display-6"><?= $topic->getTitre() ?></h3>
        <div class="title-separator"></div>
        <p class="lead"><?= $topic->getContenu() ?></p>
        <hr class="my-4">
        <form action="" method="post">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" name="titre" value="<?= $topic->getTitre() ?>">
            </div>
            <div class="form-group">
                <label for="contenu">Contenu</label>
                <textarea name="contenu" rows="15"><?= $topic->getContenu() ?></textarea>
            </div>
            <input type="hidden" name="token" value="<?= App\Session::getToken() ?>">
            <a href="/topic/view/<?= $topic->getId() ?>" class="forgive">Annuler</a>
            <button type="submit" name="submit" class="submit bg-success text-white">Enregistrer</button>
        </form>
    </div>
</div>