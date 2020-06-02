<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Connexion</p>
        <?php  if($message = App\Session::getMessage()): ?>
        <div class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></div>
        <?php App\Session::unsetMessage(); ?>
        <?php endif; ?>
    </div>
    <p class="lead mt-4">Vous n'avez pas de compte ? <a href="/security/register">Inscrivez-vous !</a></p>
    <form id="security-form" action="" method="POST" class="p-4">
        <div class="form-group">
            <label for="login">Pseudo ou E-mail</label>
            <input type="text" class="form-control" name="login">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember">Check me out</label>
        </div>
        <input type="hidden" name="token" value="<?= App\Session::getToken() ?>">
        <button type="submit" class="submit" name="submit">Connexion</button>
    </form>
</div>