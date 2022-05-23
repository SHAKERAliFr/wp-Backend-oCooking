<?php

namespace OCooking;

use WP_REST_Request;
use WP_User;

class Api
{

    protected $baseURI;

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'initialize']);
    }

    public function initialize()
    {
        // récupération de la baseURI grace a la surperglobale $SERVER et la fonction php dirname
        // https://www.php.net/manual/fr/function.dirname.php
        $this->baseURI = dirname($_SERVER['SCRIPT_NAME']);

        // creation d'une route API
        // https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/

        register_rest_route(
            'ocooking/v1', // le nom de notre API
            'inscription', // la route qui se mettra apres le nom de notre api
            [
                // attention, methods avec un s !
                'methods' => 'post',
                // si on a un utilisateur qui sollicite notre API sur /ocooking/v1/inscription en utilisant la methode HTTP "post" on va executer la methode inscription
                'callback' => [$this, 'inscription']
            ]
        );
    }

    // avec mon parametre WP_REST_Request $request j'indique qu'on va reçevoir un objet($request) de type WP_REST_Request 
    // attention, contrairement a un docbloc (purement informatif), cette instruction va contraidre le type de donnée du parametre
    public function inscription(WP_REST_Request $request)
    {

        print_r($request);
        die;
        // dans l'objet request je vais récupérer ce qui a été envoyé a l'api sur le endpoint /ocooking/v1/inscription en POST ( et donc je vais récupérer un nom d'utilisateur, un email et un password)

        // l'odre de récupération des données n'est pas important
        $userName = $request->get_param('username');
        // équivalent a un filter_input(INPUT_POST, 'password')
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        // creation d'un nouvel utilisateur
        // https://developer.wordpress.org/reference/functions/wp_create_user/

        $userCreateResult = wp_create_user(
            $userName,
            $password,
            $email
        );
        // si l'utilisateur a bien été créé, nous allons récupérer dans $userCreateResult l'ID de se dernier

        // vérification: est ce que l'utilisateur a bien été créé

        if (is_int($userCreateResult)) {
            // modification du role de l'utilisateur...
            // je vais récupérer un objet user qui représente l'utilisateur fraichement inscrit.
            // pour ce faire, j'instancie la classe WP_User en lui donnant l'ID de l'utilisateur
            $user = new WP_User($userCreateResult);
            $user->remove_role('subscriber');
            $user->add_role('contributor');
            return [
                'success' => true,
                'userId' => $userCreateResult,
                'username' => $userName,
                'email' => $email,
                'role' => 'contributor'
            ];
        } else {
            return [
                'success' => false,
                'error' => $userCreateResult
            ];
        }
    }
}
