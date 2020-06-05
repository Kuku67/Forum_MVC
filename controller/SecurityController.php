<?php
namespace Controller;

use App\Router;
use App\Session;
use App\Mailer;
use Model\UserManager;

class SecurityController {

    /**
     * Fonction de connexion
     */
    public function login(){

        if(!empty($_POST)){

            $login = $_POST['login'];
            /*
            Ai-je un arobase dans la chaine ?
            Si oui, je dois valider un format Email.
            Sinon, je dois valider une expression régulière.
            */
            strpos($login, '@') ?
            $login = filter_input(INPUT_POST, 'login', FILTER_VALIDATE_EMAIL) :
            $login = trim(filter_input(INPUT_POST, 'login', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9]{5,32}$/')]));
            $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
            // Si les champs sont valides
            if($login && $password) {
                // J'instancie le Manager dont j'ai besoin
                $model = new UserManager();
                // Si l'utilisateur existe
                if($user = $model->findUser($login)){
                    // Vérif de la validité du mail
                    if($user->getMailToken() == NULL) {
                        // Si les password correspondent
                        if(password_verify($password, $user->getPassword())){
                            // S'il a coché « se souvenir de moi »
                            if($_POST['remember'] == true) {
                                // J'attribut un cookie
                                setcookie('auth', $user->getSecret(), time() + 3600*24*7, '/');
                            }
                            $model->updateToken($login, '');
                            // J'initialise ses informations dans la session
                            Session::setUser($user);
                            // Message de confirmation et redirection
                            Session::setMessage('Vous êtes maintenant connecté(e).','success');
                            Router::redirectTo("home", "index");
                        }
                        else {
                            Session::setMessage('Mot de passe incorrect.','danger');
                            Router::redirectTo("security", "login");
                        }
                    }
                    else {
                        Session::setMessage('Le compte n\'est pas activé. Veuillez l\'activer en vous servant du lien reçu par mail','danger');
                        Router::redirectTo("security", "login");
                    }
                }   
                else {
                    Session::setMessage('Utilisateur inexistant.','danger');
                    Router::redirectTo("security", "login");
                }
            } else {
                Session::setMessage('<strong>Champs non conformes.</strong><br><br><ul><li>Le pseudo doit contenir entre 5 et 32 caractères.</li><li>Le pseudo ne doit pas contenir de caractères spéciaux.</li><li>L\'email doit posséder le bon format, et ne pas dépasser 70 caractères.</li><li>Le mot de passe doit contenir entre 8 et 24 caractères.</li><li>Le mot de passe ne doit pas contenir de caractères spéciaux à l\'exception de « + », « & », « * » et « $ ».</li></ul>','danger');
                Router::redirectTo("security", "login");
            }
        }
            
        return [
            "view" => "login.php",
            "title" => "Forum - Connexion",
            "data" => null
        ];
    }

    /**
     * Fonction d'inscription
     */
    public function register(){
        // Si j'ai un POST non vide
        if(!empty($_POST)){
            // Vérification des champs
            $username = trim(filter_input(INPUT_POST, 'pseudo', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9]{5,32}$/')]));
            $mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
            $pass1 = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
            $pass2 = filter_input(INPUT_POST, 'password2', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
            // Si les champs sont bons
            if($username && $pass1 && $pass2 && $mail){
                // Et si les mots de passe correspondent
                if($pass1 == $pass2){
                    // Là seulement, j'instancie mon Manager requis
                    $model = new UserManager();
                    // Si l'email ET le pseudo n'existe pas, je continu
                    if(!$model->findUser($mail) && !$model->findUser($username)){
                        // Je génère un code qui servira pour les cookies
                        $secret = bin2hex(random_bytes(24));
                        // Je génère un token pour le mail
                        $mailToken = bin2hex((random_bytes(24)));
                        // Je hash le mot de passe
                        $hash = password_hash($pass1, PASSWORD_ARGON2I);
                        // J'ajoute l'utilisateur dans la base de données
                        $model->addUser($username, $mail, $hash, $secret, $mailToken);
                        // J'envoie le mail
                        Mailer::registerMail($mail, $username, $mailToken);
                        // Message de confirmation, redirection
                        Session::setMessage('Inscription réussie. Validez votre adresse e-mail grâce au mail que vous allez recevoir', 'success');
                        Router::redirectTo("home", "index");
                    }
                    else {
                        Session::setMessage('Pseudo ou E-mail déjà utilisé.','danger');
                        Router::redirectTo("security", "register");
                    } 
                }
                else {
                    Session::setMessage('Les mots de passe ne correspondent pas.','danger');
                    Router::redirectTo("security", "register");
                } 
            }
            else {
                Session::setMessage('<strong>Champs non conformes.</strong><br><br><ul><li>Le pseudo doit contenir entre 5 et 32 caractères.</li><li>Le pseudo ne doit pas contenir de caractères spéciaux.</li><li>L\'email doit posséder le bon format, et ne pas dépasser 70 caractères.</li><li>Le mot de passe doit contenir entre 8 et 24 caractères.</li><li>Le mot de passe ne doit pas contenir de caractères spéciaux à l\'exception de « + », « & », « * » et « $ ».</li></ul>','danger');
                Router::redirectTo("security", "register");
            }   
        }
            
        return [
            "view" => "register.php",
            "title" => "Forum - Inscription",
            "data" => null
        ];
    }

    /**
     * Fonction de déconnexion
     */
    public function logout(){
        Session::eraseKey();
        // J'annule les informations d'utilisateur de la session
        Session::removeUser();
        // Je fais expirer le cookie
        setcookie('auth', '', time() -1, '/');
        // Je confirme le tout en le redirigeant
        Session::setMessage("Vous êtes maintenant déconnecté(e).", "success");
        Router::redirectTo("home", "index");
    }

    /**
     * Fonction d'auto-connection (cookie remember me)
     */
    public static function autoConnect() {
        // Si un cookie est présent sur la machine de l'utilisateur
        if(isset($_COOKIE['auth']) && !empty($_COOKIE['auth'])) {
            // J'instancie mon Manager
            $model = new UserManager();
            // Si je trouve un utilisateur dans la BDD, je le connecte
            if($user = $model->findUserByCookie($_COOKIE['auth'])){
                Session::setUser($user);
            }
        }
    }

    /**
     * Fonction d'envoi d'un mail de récupération
     */
    public function recover() {

        if(Session::getUser()) {
            Router::redirectTo("home", "index");
        }

        if(!empty($_POST)) {

            $mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);

            $model = new UserManager();
            if($mail) {
                if($user = $model->findUser($mail)) {
                    // Je génère un token
                    $recoverToken = bin2hex(random_bytes(28));
                    // Je modifie le token en BDD
                    $model->updateToken($mail, $recoverToken);
                    // J'envoie le mail
                    Mailer::recoverMail($mail, $user->getPseudo(), $recoverToken);
                    // Confirmation et redirection
                    Session::setMessage("Le mail de récupération a bien été envoyé", "success");
                    Router::redirectTo("home", "index");
                } else {
                    Session::setMessage("Le mail n'existe pas.", "danger");
                    Router::redirectTo("security", "recover");
                }
            }
            else {
                Session::setMessage("Le mail n'est pas au bon format.", "danger");
                Router::redirectTo("security", "recover");
            }
        }
        else {

            return [
                "view" => "recover.php",
                "title" => "Récupération",
                "data" => null
            ];
            
        }
    }

    /**
     * Fonction de récupération de mot de passe (formulaire+vue)
     */
    public function recoverpass() {

        if(isset($_GET['id'])) {

            $token = $_GET['id'];
            // J'instancie le manager
            $model = new UserManager();
            // Si un token existe dans la BDD
            if($user = $model->findUserByToken($token)) {
                // Si j'ai un formulaire
                if(!empty($_POST)) {
                    // Je valide les champs
                    $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
                        "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
                    $password2 = filter_input(INPUT_POST, 'password2', FILTER_VALIDATE_REGEXP, [
                        "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
                    // Si les champs sont valides
                    if($password && $password2 && $password === $password2) {
                        // Hash du mot de passe
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        // Update du mot de passe de l'utilisateur
                        $model->updatePassByToken($token, $hash);
                        $model->updateToken($user->getMail(), '');
                        Session::setMessage("Le mot de passe a bien été changé.", "success");
                        Router::redirectTo("security", "login");
                    }
                }
                else {
                    // Si j'ai pas de formulaire, je lui donne la vue pour le faire
                    return [
                        "view" => "recoverLayout.php",
                        "title" => "Récupération",
                        "data" => null
                    ];
                }
            }
            // Si le Token n'existe pas, je redirige
            else {
                Session::setMessage("Token de validation invalide ou expiré.", "danger");
                Router::redirectTo("home", "index");
            }
        }
        // Si pas d'ID dans l'url, je redirige
        else {
            Router::redirectTo("home", "index");
        }
    }

    /**
     * Fonction de validation de l'adresse email
     */
    public function validMail() {

        if(isset($_GET['id'])) {

            $mailToken = $_GET['id'];

            $model = new UserManager();

            if($user = $model->findUserByMailToken($mailToken)) {

                $model->validateAccount($user->getId());
                Mailer::accountValidation($user->getMail(), $user->getPseudo());
                Session::setMessage("Votre compte a été activé, pour pouvez vous connecter.", "success");
                Router::redirectTo("security", "login");
            }
            else {
                Session::setMessage("Aucun identifiant trouvé pour ce token de validation.", "danger");
                Router::redirectTo("security", "login");
            }
        }
        else {
            Router::redirectTo("home", "index");
        }
    }
}