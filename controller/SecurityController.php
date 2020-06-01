<?php
namespace Controller;

use Model\UserManager;
use App\Session;
use App\Router;

class SecurityController {

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

            $model = new UserManager();
            if($login && $password) {
                if($user = $model->findUser($login)){
                    
                    if(password_verify($password, $user->getPassword())){

                        Session::setUser($user);
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

    public function register(){

        if(!empty($_POST)){
            
            $username = trim(filter_input(INPUT_POST, 'pseudo', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9]{5,32}$/')]));
            $mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
            $pass1 = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);
            $pass2 = filter_input(INPUT_POST, 'password2', FILTER_VALIDATE_REGEXP, [
                "options" => array("regexp"=>'/^[a-zA-Z0-9+&*$]{8,24}$/')]);

            if($username && $pass1 && $pass2 && $mail){
                
                if($pass1 == $pass2){

                    $model = new UserManager();

                    if(!$model->findUser($mail) && !$model->findUser($username)){

                        $secret = bin2hex(random_bytes(24));
                        $hash = password_hash($pass1, PASSWORD_ARGON2I);

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

    public function affiche(){
        return false;
    }

    public function logout(){

        Session::removeUser();
        Session::setMessage("Vous êtes maintenant déconnecté(e).", "success");
        Router::redirectTo("home", "index");
    }
}