<?php

$required_files = [
    'router',
    'request',
    'dispatcher',
    'config/Database',
    'core/Core',
    'core/Model',
    'core/Controller',
    'core/Url',
    'core/Template'
];

$autoload_models = [
    'AdminStats',
    'Blacklist',
    'Blog',
    'Category',
    'Collection',
    'Comment',
    'Contact',
    'ForumBoard',
    'ForumCategory',
    'ForumPost',
    'ForumTopic',
    'Linkify',
    'NotFound',
    'Page',
    'Rating',
    'Recipe',
    'RecipeTag',
    'Redirect',
    'Question',
    'SavedRecipe',
    'Search',
    'Tag',
    'User'
];

$autoload_helpers = [
    'Akismet',
    'Core',
    'Facebook',
    'Recipe',
    'Session',
    'Template'
];

if (
    is_array($autoload_models) &&
    count($autoload_models)
) {
    foreach ($autoload_models as $model) {
        $required_files[] = 'model/' . $model . 'Model';
    }
}

if (
    is_array($autoload_helpers) &&
    count($autoload_helpers)
) {
    foreach ($autoload_helpers as $helper) {
        $required_files[] = 'helper/' . $helper . 'Helper';
    }
}

foreach ($required_files as $required_file) {
    require_once(ROOT . $required_file . '.php');
}
