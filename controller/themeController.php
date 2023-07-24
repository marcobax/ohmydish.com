<?php
require_once(ROOT . 'controller/courseController.php');

/**
 * Class themeController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class themeController extends courseController
{
    public function index(): void
    {
        $this->set([
            'meta_description'     => 'Overview of recipes per theme, sorted by latest to oldest.',
            'page_title'           => 'Recipes per theme',
            'page_canonical'       => Core::url('theme'),
            'categories_by_theme'   => $this->category_model->getRecords(['type' => 'theme', 'total_entities' => '> 0'],['title','asc']),
        ]);

        $this->render('index');
    }

    /**
     * Recipe category detail.
     */
    public function detail($type = 'theme'): void
    {
        parent::detail($type);
    }
}