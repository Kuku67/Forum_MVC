<?php
    $comment = $result['data'];
?>
<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Édition de message - crée par <a href="/profile/view/<?= $comment->getUser()->getId() ?>"><?= $comment->getUser()->getPseudo() ?></a></p>
        <?php  if($message = App\Session::getMessage()): ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php endif; ?>
    </div>
    <!-- CONTENEUR DU SUJET -->
    <div class="topic-container">
        <div class="comment">
            <p class="lead p-2"><?= $comment->getContenu() ?></p>
        </div>
        <hr class="my-4">
        <form action="" method="post">
            <div class="form-group">
                <label for="contenu">Contenu</label>
                <textarea name="contenu" rows="15"><?= $comment->getContenu() ?></textarea>
            </div>
            <input type="hidden" name="token" value="<?= $csrf ?>">
            <a href="/topic/view/<?= $comment->getTopic()->getId() ?>" class="forgive">Annuler</a>
            <button type="submit" name="submit" class="submit bg-success text-white">Enregistrer</button>
        </form>
    </div>
</div>