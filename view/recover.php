<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Récupération de mot de passe</p>
        <?php  if($message = App\Session::getMessage()): ?>
        <div class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></div>
        <?php endif; ?>
    </div>
    <p class="lead mt-4">Entrez l'adresse e-mail</p>
    <form id="security-form" action="" method="POST" class="p-4">
        <div class="form-group">
            <label for="mail">E-mail</label>
            <input type="mail" class="form-control" name="mail">
        </div>
        <input type="hidden" name="token" value="<?= $csrf ?>">
        <button type="submit" class="submit" name="submit">Envoyer</button>
    </form>
</div>