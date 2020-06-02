<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Inscription</p>
        <?php  if($message = App\Session::getMessage()): ?>
        <div class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></div>
        <?php App\Session::unsetMessage(); ?>
        <?php endif; ?>
    </div>
    <p class="lead mt-4">Vous avez déjà un compte ? <a href="/security/login">Connectez-vous !</a></p>
    <form id="security-form" action="" method="POST" class="p-4">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control" name="pseudo">
        </div>
        <div class="form-group">
            <label for="mail">E-mail</label>
            <input type="mail" class="form-control" name="mail">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
            <label for="password2">Confirmez le mot de passe</label>
            <input type="password" class="form-control" name="password2">
        </div>
        <input type="hidden" name="token" value="<?= App\Session::getToken() ?>">
        <button type="submit" class="submit" name="submit">Inscription</button>
    </form>
</div>