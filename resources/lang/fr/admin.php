<?php

return [
    'title' => 'Wiki',

    'categories' => [
        'title' => 'Catégories',
        'edit' => 'Éditer la catégorie #:category',
        'create' => 'Créer une catégorie',

        'add' => 'Ajouter une catégorie',
        'enable' => 'Activer cette catégorie',
        'parent' => 'Catégorie parente',
        'private' => 'Limiter l\'accès à cette catégorie à certains rôles.',

        'empty' => 'Aucune catégorie n\'a été créée actuellement.',

        'info' => 'Pour être visible, une catégorie doit contenir au moins une page qui n\'est pas dans une sous-catégorie.',
    ],

    'pages' => [
        'title' => 'Pages',
        'category' => 'catégorie',
        'updated' => 'Ordre des pages mis à jour.',
    ],

    'settings' => [
        'title' => 'Paramètres',
        'layout' => 'Mise en page',
        'layout_default' => 'Par défaut',
        'layout_documentation' => 'Documentation',
        'layout_info' => 'La mise en page documentation affiche toutes les catégories dans une arborescence latérale, avec une table des matières et une recherche rapide.',
    ],

    'permission' => 'Gérer le wiki',
];
