<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 16/10/2018
 * Time: 08:09
 */

// headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/Connexion.php';
require_once '../objets/Aime.php';

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

use ProjetMobileAPI\Connexion;
use ProjetMobileAPI\Aime;

// récupération de la connexion à la base de données
$bdd = Connexion::get()->connect();

// création des objet requis
$aime = new Aime($bdd);

// récupération des données transmises en POST
$donnees = json_decode(file_get_contents("php://input"));

// définition des valeurs des propriétés de la mention j'aime
$aime->id_utilisateur = $donnees->id_utilisateur;
$aime->id_publication = $donnees->id_publication;

$item_message = array();

// création de la mention j'aime
if ($aime->creer()) {

    $item_message['code'] = 0;
    $item_message['type'] = "info";
    $item_message['message'] = "La mention j'aime a été ajoutée avec succès";
} else {

    // si la mention j'aime n'est pas créée, informe l'utilisateur
    $item_message['code'] = 0;
    $item_message['type'] = "erreur";
    $item_message['message'] = "Impossible d'ajouter la mention j'aime";
}

array_push($aime->reponseAPI->tab_message, $item_message);

$aime->reponseAPI->statut = true;

echo $aime->reponseAPI->construire_reponse();