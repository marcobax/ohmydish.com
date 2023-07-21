<?php

/**
 * Class courseController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class courseController extends Controller
{
    /**
     * Overview of courses.
     */
    public function index(): void
    {
        $this->set([
            'meta_description'     => 'Total overview of recipes per course, sorted by newest to oldest',
            'page_title'           => 'Recipes per course',
            'page_canonical'       => Core::url('course'),
            'categories_by_course' => $this->category_model->getRecords(['type' => 'course', 'total_entities' => '> 0'],['title','asc']),
        ]);

        $this->render('index');
    }

    /**
     * Recipe category detail.
     */
    public function detail($type = 'course'): void
    {
        $where = [];

        $max_displaytime = 60;
        $min_displaytime = $max_displaytime;
        $min_yield = 1;
        $max_yield = $min_yield;
        $min_rating = 1;
        $max_rating = 5;

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            foreach (['cooktime', 'total-yield', 'total-votes'] as $key) {
                if (array_key_exists($key, $query) && strlen($query[$key])) {
                    switch ($key) {
                        case 'cooktime':
                            if ("hour" === $query[$key]) {
                                $where['displaytime'] = '> 60';
                                $max_displaytime = 60;
                            } else {
                                $displaytime = (int) $query[$key];

                                if ($displaytime > 60) {
                                    $where['displaytime'] = 60;
                                    $displaytime = 60;
                                } else {
                                    $where['displaytime'] = '< ' . ($displaytime + 1);
                                }
                                $max_displaytime = $displaytime;
                            }

                            $min_displaytime = $max_displaytime;
                            break;
                        case 'total-yield':
                            $yield = (int) $query[$key];

                            if ($yield <= 0) {
                                $yield = 1;
                            } elseif($yield > 30) {
                                $yield = 30;
                            }
                            $where['yield'] = '> ' . ($yield-1);

                            //$max_yield = $yield;
                            break;
                        case 'total-votes':
                            $average_rating = (int) $query[$key];
                            if ($average_rating <= 0) {
                                $average_rating = 1;
                                $min_rating = $average_rating;
                            } elseif($average_rating > 5) {
                                $average_rating = 5;
                            }
                            $where['average_rating'] = $average_rating;
                            $max_rating = $average_rating;
                            break;
                    }
                }
            }
        }

        $this->category_model = new CategoryModel();

        switch($type) {
            case 'kitchen':
                if (!$category = $this->category_model->getBySlug($this->getSlug(), ['type' => $type])) {
                    $this->show404();
                }
                $where['find_in_set'] = ['cuisines' => $category['id']];
                break;
            case 'dishtype':
                if (!$category = $this->category_model->getBySlug($this->getSlug(), ['type' => 'dishtype'])) {
                    // gerechtsoort/thais -> keuken/thais
                    if ($category_by_cuisine = $this->category_model->getBySlug($this->getSlug(), ['type' => 'kitchen'])) {
                        Core::redirect(Core::url('kitchen/' . $category_by_cuisine['slug']));
                    }

                    $this->show404();
                }
                $where['find_in_set'] = ['dishtypes' => $category['id']];
                break;
            default:
                if (!$category = $this->category_model->getBySlug($this->getSlug(), ['type' => $type])) {
                    $this->show404();
                }
                $where['find_in_set'] = ['categories' => $category['id']];
                break;
        }

        $this->setTotalResults($this->recipe_model->getRecords($where,[],[],true));
        $all_recipes = $this->recipe_model->getRecords($where, ['published', 'desc'], [], false, ['displaytime', 'yield', 'average_rating']);
        $recipes = $this->recipe_model->getRecords($where,['published','desc'],$this->getPagination());

        $lowest_recipe_yield = false;
        $lowest_recipe_rating = 5;
        foreach ($all_recipes as $recipe) {
            if (
                (int)$recipe['displaytime'] < 60 &&
                ((int)$recipe['displaytime'] > $max_displaytime)
            ) {
                $max_displaytime = (int)$recipe['displaytime'];
            }
            if (
                (int) $recipe['displaytime'] > 0 &&
                ((int) $recipe['displaytime'] < $min_displaytime)
            ) {
                $min_displaytime = (int)$recipe['displaytime'];
            }

            if (
                (int) $recipe['yield'] >= $max_yield
            ) {
                $max_yield = (int) $recipe['yield'];
            }

            if (!$lowest_recipe_yield) {
                $lowest_recipe_yield = (int) $recipe['yield'];
            }
            if (((int) $recipe['yield'] < $lowest_recipe_yield)) {
                $lowest_recipe_yield = (int) $recipe['yield'];
            }

            if (
                ((int) $recipe['average_rating'] > 0) &&
                (int) $recipe['average_rating'] < $lowest_recipe_rating
            ) {
                $lowest_recipe_rating = (int) $recipe['average_rating'];
            }
        }

        if ($lowest_recipe_rating > $min_rating) {
            $min_rating = $lowest_recipe_rating;
        }

        $this->set([
            'query'                 => $query,
            'max_displaytime'       => $max_displaytime,
            'min_displaytime'       => $min_displaytime,
            'max_yield'             => $max_yield,
            'min_yield'             => $min_yield,
            'min_rating'            => $min_rating,
            'max_rating'            => $max_rating,
            'page_title'            => (isset($category['seo_title'])&&strlen($category['seo_title']))?$category['seo_title']:ucfirst($category['type']) . ' ' . strtolower($category['title']),
            'meta_description'      => (!$category['content'])?'Categorie: ' . $category['type'] . ', onderwerp: ' . $category['title']:strip_tags($category['content']),
            'page_canonical'        => Core::url($category['type'] . '/' . $category['slug']),
            'og_image'              => TemplateHelper::getFeaturedImage($category),
            'category'              => $category,
            'recipes'               => $recipes,
            'pagination'            => $this->getPagination(),
            'preload_image'         => TemplateHelper::getFeaturedImage($category),
            'structured_data'       => $this->category_model->getStructuredData(['category' => $category]),
        ]);

        $this->render('detail');
    }
}