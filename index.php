<?php
// Définition du namespace.
namespace App;
// Définition des constantes (chemins).
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__).DS);
define('PUBLIC_PATH', 'public'.DS);
define('CSS_PATH', PUBLIC_PATH.'css'.DS);
define('IMG_PATH', PUBLIC_PATH.'img'.DS);

define('SERVICE_PATH', ROOT_DIR.'app'.DS);
define('CTRL_PATH', ROOT_DIR.'controller'.DS);
define('VIEW_PATH', ROOT_DIR.'view'.DS);
// Appel de l'autoloader.
require_once 'app/Autoloader.php';
Autoloader::register();
// Appel du Router
use App\Router;
// Appel de la Session
use App\Session;
// Traitement de la requête HTTP.
use Controller\SecurityController;
SecurityController::autoConnect();
// On génère une clé CSRF
Session::generateKey();

$csrf = hash_hmac('sha256', 'jlgthqvetrkh qkrthkerh', Session::getKey());

if(Router::csrfProtection($csrf)) {
    $result = Router::handleRequest($_GET, $_POST);
} else {
    Router::redirectTo("security", "logout");
}
// Tampon de sortie.
ob_start();
if(is_array($result) && array_key_exists('view', $result)){
    $data = isset($result['data']) ? $result['data'] : null;
    include VIEW_PATH.$result['view'];
}
else include VIEW_PATH."404.html";
$page = ob_get_contents();
ob_end_clean();
// Chargement de la vue.
include VIEW_PATH."layout.php";