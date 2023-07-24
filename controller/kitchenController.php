<?php
require_once(ROOT . 'controller/courseController.php');

/**
 * Class kitchenController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class kitchenController extends courseController
{
    /**
     * Overview of recipes per type of cuisine.
     */
    public function index(): void
    {
        $this->set([
            'meta_description'     => 'Overview of all recipes per type of kitchen, sorted by latest to oldest.',
            'page_title'           => 'Recipes per kitchen',
            'page_canonical'       => Core::url('kitchen'),
            'categories_by_cuisine' => $this->category_model->getRecords(['type' => 'kitchen', 'parent_id' => '0', 'total_entities' => '> 3'],['title','asc']),
        ]);

        $this->render('index');
    }

    /**
     * Recipe category detail.
     */
    public function detail($type = 'kitchen'): void
    {
        parent::detail($type);
    }
}