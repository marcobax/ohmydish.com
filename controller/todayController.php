<?php

/**
 * Class todayController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class todayController extends Controller
{
    var $category_model,
        $recipe_model;

    /**
     * todayController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->category_model = new CategoryModel();
        $this->recipe_model   = new RecipeModel();
    }

    /**
     * Intro
     */
    public function index()
    {
        $query = $this->request->getQuery();

        if (is_array($query) && count($query)) {
            if (array_key_exists('ingredient1', $query)) {
                $this->getPagination();
                $ingredient1 = '';
                $ingredient2 = '';
                $ingredient3 = '';

                $where = [];

                $ingredient1 = trim($query['ingredient1']);

                if (array_key_exists('ingredient2', $query)) {
                    $ingredient2 = trim($query['ingredient2']);
                }

                if (array_key_exists('ingredient3', $query)) {
                    $ingredient3 = trim($query['ingredient3']);
                }

                $input_parameters = [
                    'ingredient1' => '+"'.$ingredient1.'"',
                    'ingredient2' => '+"'.$ingredient2.'"',
                    'ingredient3' => '+"'.$ingredient3.'"'
                ];
                $count_sql = 'SELECT * FROM `recipe` ';
                $count_sql .= ' WHERE `recipe`.`status` = "publish"';
                $count_sql .= ' AND MATCH (`recipe`.`ingredients`) AGAINST (:ingredient1 :ingredient2 :ingredient3 IN BOOLEAN MODE)';
                $sql = $count_sql . ' ORDER BY `recipe`.`published` DESC LIMIT ' . $this->getPage() . ',' . $this->getResultsPerPage();

                $req = Database::getBDD()->prepare($sql);
                $req->execute($input_parameters);
                $recipes = $req->fetchAll(PDO::FETCH_ASSOC);
                $req = Database::getBDD()->prepare($count_sql);
                $req->execute($input_parameters);
                $total_results = $req->rowCount();

                if (!array_key_exists('ingredient1-suggestie', $query)) {
                    $tag_model = new TagModel();
                    $ingredient1_suggestion = '';
                    $tags = $tag_model->getRecords([
                        'or_where' => [
                            'name' => '%' . $ingredient1 . '%',
                            'description' => '%' . $ingredient1 . '%'
                        ]
                    ], ['id','asc'], [0,4]);
                    if (is_array($tags) && count($tags)) {
                        $ingredient1_arr = [];
                        foreach ($tags as $tag) {
                            if ($tag['name'] !== $ingredient1) {
                                $ingredient1_arr[] = $tag['name'];
                            }
                        }
                        if (is_array($ingredient1_arr) && count($ingredient1_arr)) {
                            $ingredient1_suggestion = implode(',', $ingredient1_arr);
                        }
                    }
                    $ingredient2_suggestion = '';
                    if (strlen($ingredient2)) {
                        $tags = $tag_model->getRecords([
                            'or_where' => [
                                'name' => '%' . $ingredient2 . '%',
                                'description' => '%' . $ingredient2 . '%'
                            ]
                        ], ['id','asc'], [0,4]);
                        $ingredient2_arr = [];
                        if (is_array($tags) && count($tags)) {
                            foreach ($tags as $tag) {
                                if ($tag['name'] !== $ingredient2) {
                                    $ingredient2_arr[] = $tag['name'];
                                }
                            }
                            if (is_array($ingredient2_arr) && count($ingredient2_arr)) {
                                $ingredient2_suggestion = implode(',', $ingredient2_arr);
                            }
                        }
                    }
                    $ingredient3_suggestion = '';
                    if (strlen($ingredient3)) {
                        $tags = $tag_model->getRecords([
                            'or_where' => [
                                'name' => '%' . $ingredient3 . '%',
                                'description' => '%' . $ingredient3 . '%'
                            ]
                        ], ['id','asc'], [0,4]);
                        $ingredient3_arr = [];
                        if (is_array($tags) && count($tags)) {
                            foreach ($tags as $tag) {
                                if ($tag['name'] !== $ingredient3) {
                                    $ingredient3_arr[] = $tag['name'];
                                }
                            }
                            if (is_array($ingredient3_arr) && count($ingredient3_arr)) {
                                $ingredient3_suggestion = implode(',', $ingredient3_arr);
                            }
                        }
                    }

                    $options = [
                        'ingredient1'           => $ingredient1,
                        'ingredient2'           => $ingredient2,
                        'ingredient3'           => $ingredient3,
                        'ingredient1-suggestie' => $ingredient1_suggestion,
                        'ingredient2-suggestie' => $ingredient2_suggestion,
                        'ingredient3-suggestie' => $ingredient3_suggestion
                    ];

                    $url = Core::url('today?') . http_build_query($options);

                    Core::redirect($url);
                }

                $this->setTotalResults($total_results);

                $this->set([
                    'ingredient1' => $ingredient1,
                    'ingredient2' => $ingredient2,
                    'ingredient3' => $ingredient3,
                    'recipes'     => $recipes,
                    'pagination'  => $this->getPagination(),
                    'query'       => $query
                ]);
            } else {
                $this->set([
                    '_flash' => ['type' => 'danger', 'message' => 'Voeg tenminste 1 ingrediënt toe.']
                ]);
            }
        }

        $this->set([
            'page_title'          => 'What do you want to cook today?',
            'meta_description'    => 'Discover lots of new, fun and delicious recipes. Easy to follow instructions and great for meal prepping.',
            'page_canonical'      => Core::url('today'),
            'popular_categories'  => $this->category_model->getRecords(['type' => 'theme', 'total_entities' => '> 100'],['RAND()'], [0, 4]),
            'categories_by_theme' => $this->category_model->getRecords(['type' => 'theme', 'total_entities' => '> 0'],['title','asc'])
        ]);

        $this->render('index');
    }
}