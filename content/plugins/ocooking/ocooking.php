<?php
//  Déclaration du plugin
/**
 * Plugin Name: oCooking
 * Author: Xandar team
 *  Description: Découverte wp API
 */


use OCooking\Plugin;
use OCooking\Api;

require __DIR__ . '/vendor-ocooking/autoload.php';
$ocooking = new Plugin();
// instanciation du plugin (classe principale)




// activation "hook" https://developer.wordpress.org/reference/functions/register_activation_hook/
register_activation_hook(
    // premier argument, le chemin vers le fichier de déclaration du plugin
    __FILE__,
    // Deuxieme argument, je vais indiquer la methode a executer sur l'objet $oProfile
    [$ocooking, 'activate']

);

register_deactivation_hook(
    __FILE__,
    [$ocooking, 'deactivate']
);
$api = new Api();
