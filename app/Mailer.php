<?php

namespace App;

abstract class Mailer {

    /**
     * Fonction d'envoi de mail de validation de compte
     */
    public static function registerMail($mail, $pseudo, $token) {

        $link = "http://blogmvc.test/security/validMail/$token";

        $subject = "BLOGMVC - Validation de l'adresse mail";
        $message = "Bienvenue, $pseudo !<br>
        Veuillez valider votre adresse e-mail en cliquant sur <a href='$link'>ce lien</a>.";

        mail($mail, $subject, $message);
    }

    /**
     * Fonction d'envoi de mail de récupération de mot de passe
     */
    public static function recoverMail($mail, $pseudo, $token) {

        $link = "http://blogmvc.test/security/recoverpass/$token";

        $subject = "Bonjour, $pseudo, vous avez demandé une récupération de mot de passe";
        $message = "Pour récupérer votre mot de passe, veuillez vous rendre sur ce lien : <a href='$link'>Lien</a>";

        mail($mail, $subject, $message);
    }

    public static function accountValidation($mail, $pseudo) {

        $link = "http://blogmvc.test/security/login";

        $subject = "Validation de votre compte";
        $message = "Bonjour, $pseudo ! Vous avez validé votre compte, vous pouvez dès à présent pour connecter en suivant ce <a href='$link'>lien</a>.<br><br>À bientôt !";

        mail($mail, $subject, $message);
    }
}