<?php
namespace Controller;

use Model\UserManager;
use App\Session;
use App\Router;

class SecurityController {

    // Fonction de contrôle de la connexion
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
                    // Si les password correspondent
                    if(password_verify($password, $user->getPassword())){
                        // S'il a coché « se souvenir de moi »
                        if($_POST['remember'] == true) {
                            // J'attribut un cookie
                            setcookie('auth', $user->getSecret(), time() + 3600*24*7, '/');
                        }
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

    // Fonction de contrôle de l'inscription
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
                        // Je hash le mot de passe
                        $hash = password_hash($pass1, PASSWORD_ARGON2I);
                        // J'ajoute l'utilisateur dans la base de données
                        if($model->addUser($username, $mail, $hash, $secret)){
                            Session::setMessage('Inscription réussie. Vous pouvez maintenant vous connecter.', 'success');
                            Router::redirectTo("security", "login");
                        }
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

    // Fonction de déconnexion, ne renvoit pas de vue
    public function logout(){
        // J'annule les informations d'utilisateur de la session
        Session::removeUser();
        // Je fais expirer le cookie
        setcookie('auth', '', time() -1, '/');
        // Je confirme le tout en le redirigeant
        Session::setMessage("Vous êtes maintenant déconnecté(e).", "success");
        Router::redirectTo("home", "index");
    }

    // Fonction de pré-authentification
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
}