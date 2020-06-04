<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Récupération de mot de passe</p>
        <?php  if($message = App\Session::getMessage()): ?>
        <div class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></div>
        <?php endif; ?>
    </div>
    <form id="security-form" action="" method="POST" class="p-4">
        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
            <label for="password2">Confirmez le mot de passe</label>
            <input type="password" class="form-control" name="password2">
        </div>
        <input type="hidden" name="token" value="<?= $csrf ?>">
        <button type="submit" class="submit" name="submit">Envoyer</button>
    </form>
</div>