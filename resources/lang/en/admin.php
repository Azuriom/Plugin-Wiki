<?php

return [
    'title' => 'Wiki',

    'categories' => [
        'title' => 'Categories',
        'edit' => 'Edit category :category',
        'create' => 'Create category',

        'add' => 'Add category',
        'enable' => 'Enable this category',
        'parent' => 'Parent category',
        'private' => 'Limit roles with access to this category',

        'empty' => 'No category has been created currently.',

        'info' => 'To be visible, a category must contain at least one page that is not in a subcategory.',
    ],

    'pages' => [
        'title' => 'Pages',
        'category' => 'category',
        'updated' => 'Pages order updated.',
    ],

    'settings' => [
        'title' => 'Settings',
        'layout' => 'Layout',
        'layout_default' => 'Default',
        'layout_documentation' => 'Documentation',
        'layout_info' => 'The documentation layout displays all the categories in a sidebar tree, with a table of contents and a quick search modal.',
    ],

    'permission' => 'Manage wiki',
];
