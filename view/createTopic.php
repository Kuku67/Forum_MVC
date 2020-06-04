<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Création de sujet - en tant que <a href="/profile/view/<?= App\Session::getUser()->getId() ?>"><?= App\Session::getUser()->getPseudo() ?></a></p>
        <?php  if($message = App\Session::getMessage()): ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php endif; ?>
    </div>
    <!-- CONTENEUR DU SUJET -->
    <div class="topic-container">
        <form action="" method="post">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" name="titre">
            </div>
            <div class="form-group">
                <label for="contenu">Contenu</label>
                <textarea name="contenu" rows="15"></textarea>
            </div>
            <input type="hidden" name="token" value="<?= $csrf ?>">
            <a href="/home/index" class="forgive">Annuler</a>
            <button type="submit" name="submit" class="submit bg-success text-white">Envoyer</button>
        </form>
    </div>
</div>


