<?php

namespace OCooking;

class Plugin
{

    public function __construct()
    {
        // Dans ce constructeur je vais venir fabriquer mes premiers CPT :D !! 
        // au moment de l'initialisation de Wordpress
        add_action(
            'init',
            [$this, 'createRecipePostType']
        );
        add_action(
            'init',
            [$this, 'createIngredientCustomTaxonomy']
        );
        add_action(
            'init',
            [$this, 'createRecipeTypeCustomTaxonomy']
        );
        add_action(
            'init',
            [$this, 'createRecipeDifficultyCustomTaxonomy']
        );
    }

    public function createRecipePostType()
    {
        # code...
        register_post_type(
            // identifiant du post type
            'recipe',
            // les options pour configurer le post type
            [   // intitulé
                'label' => 'recette',
                'public' => true,
                'hierarchical' => false,
                'menu_icon' => 'dashicons-food',
                'supports' => [
                    'title',
                    'thumbnail',
                    'editor',
                    'author',
                    'excerpt',
                    'comments'
                ],
                'capability_type' => 'recipe',
                'map_meta_cap' => true,
                'show_in_rest' => true,
            ]
        );
    }
    public function addCapAdmin($customCapArray)
    {
        // methode qui nous permet d'ajouter les droits sur les CPT  pour le role administrateur
        //! Attention, sans cette opération les CPT recipe vont disparaitre
        //! en effet, nous avons définis un "capability_type" pour ce dernier
        //! et l'adminstrateur ne vas pas avoir automatiquement les droits 
        // il est possible d'intéragir avec les roles et capabilities en dehors de la création des roles a l'aide des deux fonctions suivante :
        // je peux récupérer un role WP sous la forme d'un objet grace fonction WP get_role()
        $role = get_role('administrator');
        foreach ($customCapArray as $customCap) {
            // grace a la methode add_cap de cet objet qui représente le role, je peux ajouter des droits a ce dernier
            $role->add_cap('delete_others_' . $customCap . 's');
            $role->add_cap('delete_private_' . $customCap . 's');
            $role->add_cap('delete_' . $customCap . 's');
            $role->add_cap('delete_published_' . $customCap . 's');
            $role->add_cap('edit_others_' . $customCap . 's');
            $role->add_cap('edit_private_' . $customCap . 's');
            $role->add_cap('edit_' . $customCap . 's');
            $role->add_cap('edit_published_' . $customCap . 's');
            $role->add_cap('publish_' . $customCap . 's');
            $role->add_cap('read_private_' . $customCap . 's');
        }
    }
    public function createIngredientCustomTaxonomy()
    {
        register_taxonomy(
            //identifiant taxonomie 
            'ingredient',
            // cette "étiquette" pourra etre utilisé sur le CPT recipe
            ['recipe'],
            // tableau d'options
            [
                'label' => 'Ingrédient',
                'hierarchical' => true,
                'public' => true,
                'show_in_rest' => true,
            ]
        );
    }
    public function createRecipeTypeCustomTaxonomy()
    {
        register_taxonomy(
            //identifiant taxonomie 
            'type',
            // cette "étiquette" pourra etre utilisé sur le CPT recipe
            ['recipe'],
            // tableau d'options
            [
                'label' => 'Type de recette',
                'hierarchical' => false,
                'public' => true,
                'show_in_rest' => true,
            ]
        );
    }
    public function createRecipeDifficultyCustomTaxonomy()
    {
        register_taxonomy(
            //identifiant taxonomie 
            'Difficulty',
            // cette "étiquette" pourra etre utilisé sur le CPT recipe
            ['recipe'],
            // tableau d'options
            [
                'label' => 'Difficulté',
                'hierarchical' => true,
                'public' => true,
                'show_in_rest' => true,
            ]
        );
    }
    public function activate()
    {
        $this->addCapAdmin(['recipe']);
        $this->registerChefRole();
    }

    public function deactivate()
    {
        remove_role('chef'); //! warning!!! ne pas oublier de remove_role à la désactivation du plugin
    }
    public function registerChefRole() //! methode appelé à l'activation du plugin
    {
        add_role(
            // identifiant du role 
            'chef',
            // libellé
            'Chef Cuisinier',
        );
    }
}
