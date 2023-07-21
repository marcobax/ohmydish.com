<?php

/**
 * Class Url
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Url
{
    /**
     * Returns URL mapping.
     *
     * @return array
     */
    public static function get_mapping()
    {
        $url_mapping = [
            'admin' => [
                'controller' => 'admin',
                'action'     => 'index'
            ],
            'blog' => [
                'controller' => 'blog',
                'action'     => 'index'
            ],
            'contact-us' => [
                'controller' => 'main',
                'action'     => 'contact'
            ],
            'community/saved-recipes' => [
                'controller' => 'community',
                'action'     => 'saved_recipes'
            ],
            'community/mijn-profiel' => [
                'controller' => 'community',
                'action'     => 'mijn_profiel'
            ],
            'community/save-recipe/*' => [
                'controller' => 'community',
                'action'     => 'save_recipe'
            ],
            'community/delete-recipe/*' => [
                'controller' => 'community',
                'action'     => 'delete_recipe'
            ],
            'feed' => [
                'controller' => 'main',
                'action'     => 'feed'
            ],
            'forum' => [
                'controller' => 'forum',
                'action'     => 'index'
            ],
            'login' => [
                'controller' => 'community',
                'action'     => 'login'
            ],
            'kitchen' => [
                'controller' => 'kitchen',
                'action'     => 'index'
            ],
            'course' => [
                'controller' => 'course',
                'action'     => 'index'
            ],
            'latest-recipes' => [
                'controller' => 'recipe',
                'action'     => 'latest'
            ],
            'newsletter' => [
                'controller' => 'main',
                'action'     => 'newsletter'
            ],
            'converter' => [
                'controller' => 'converter',
                'action'     => 'index'
            ],
            'about-us' => [
                'controller' => 'main',
                'action'     => 'about'
            ],
            'privacystatement' => [
                'controller' => 'main',
                'action'     => 'privacy'
            ],
            'create-profile' => [
                'controller' => 'community',
                'action'     => 'register'
            ],
            'recipe-index' => [
                'controller' => 'recipe',
                'action'     => 'index'
            ],
            'rss' => [
                'controller' => 'main',
                'action'     => 'feed'
            ],
            'sitemap.xml' => [
                'controller' => 'sitemap',
                'action'     => 'index'
            ],
            'sitemap_index.xml' => [
                'controller' => 'sitemap',
                'action'     => 'index'
            ],
            'sitemap-blog.xml' => [
                'controller' => 'sitemap',
                'action'     => 'blog'
            ],
            'sitemap-blog-comments.xml' => [
                'controller' => 'sitemap',
                'action'     => 'blog_comments'
            ],
            'sitemap-category.xml' => [
                'controller' => 'sitemap',
                'action'     => 'category'
            ],
            'sitemap-collection.xml' => [
                'controller' => 'sitemap',
                'action'     => 'collection'
            ],
            'sitemap-forum.xml' => [
                'controller' => 'sitemap',
                'action'     => 'forum'
            ],
            'sitemap-main.xml' => [
                'controller' => 'sitemap',
                'action'     => 'main'
            ],
            'sitemap-page.xml' => [
                'controller' => 'sitemap',
                'action'     => 'page'
            ],
            'sitemap-question.xml' => [
                'controller' => 'sitemap',
                'action'     => 'question'
            ],
            'sitemap-recipe.xml' => [
                'controller' => 'sitemap',
                'action'     => 'recipe'
            ],
            'sitemap-recipe-comments.xml' => [
                'controller' => 'sitemap',
                'action'     => 'recipe_comments'
            ],
            'sitemap-tag.xml' => [
                'controller' => 'sitemap',
                'action'     => 'tag'
            ],
            'theme' => [
                'controller' => 'theme',
                'action'     => 'index'
            ],
            'logout' => [
                'controller' => 'community',
                'action'     => 'logout'
            ],
            'today' => [
                'controller' => 'today',
                'action'     => 'index'
            ],
            'cooking-questions' => [
                'controller' => 'question',
                'action'     => 'index'
            ],
            'forgot-password' => [
                'controller' => 'community',
                'action'     => 'reset_password'
            ],
            'search' => [
                'controller' => 'search',
                'action'     => 'result'
            ]
        ];

        $quick_lookup = [];
        foreach ($url_mapping as $route => $url_map) {
            $quick_lookup[$url_map['controller'] . '/' . $url_map['action']] = $route;
        }

        $url_mapping['_quick_lookup'] = $quick_lookup;

        return $url_mapping;
    }
}
