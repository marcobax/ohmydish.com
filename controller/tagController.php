<?php

/**
 * Class tagController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class tagController extends Controller
{
    var $tag_model = false,
        $recipe_model = false,
        $recipe_tag_model = false;

    /**
     * tagController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tag_model = new TagModel();
        $this->recipe_model = new RecipeModel();
        $this->recipe_tag_model = new RecipeTagModel();
    }

    /**
     * Tag detail.
     */
    public function detail()
    {
        if (!$tag = $this->tag_model->getBySlug($this->getSlug())) {
            $this->show404();
        }

        $where = ['tag_id' => $tag['id']];
        $this->setTotalResults($this->recipe_tag_model->getRecords($where, [], [], true));

        $recipe_tags = $this->recipe_tag_model->getRecords($where, ['recipe_id', 'desc'], $this->getPagination());

        $recipe_ids = [];
        foreach ($recipe_tags as $recipe_tag) {
            $recipe_ids[] = (int) $recipe_tag['recipe_id'];
        }

        $recipes = [];
        if (is_array($recipe_ids) && count($recipe_ids)) {
            $where = [
                'in' => [
                    'id' => implode(',', $recipe_ids)
                ]
            ];

            $recipes = $this->recipe_model->getRecords($where, ['published', 'desc']);
        }

        $this->set([
            'meta_description' => $tag['name'] . ' tag',
            'tag'              => $tag,
            'page_title'       => 'View tag: ' . $tag['name'],
            'recipe_tags'      => $recipe_tags,
            'recipes'          => $recipes,
            'page_canonical'   => Core::url('tag/' . $tag['slug']),
            'pagination'       => $this->getPagination()
        ]);

        $this->render('detail');
    }
}