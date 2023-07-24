<?php

/**
 * Class RecipeModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class RecipeModel extends Model
{
    var $table_name = 'recipe';

    public static function getSavedRecipesQuery()
    {
        if (SessionHelper::isLoggedIn()) {
            return [', (SELECT COUNT(*) FROM `saved_recipe` AS `sr` WHERE `sr`.`recipe_id` = `recipe`.`id` AND `sr`.`deleted` = 0 AND `sr`.`collection_id` = 0 AND `sr`.`user_id` = ' . SessionHelper::getUserId() . ') AS `recipe_saved`'];
        }

        return [', 0 AS `recipe_saved`'];
    }

    /**
     * @param array $where
     * @param array $order_by
     * @param array $limit
     * @param false $return_count
     * @return array|int
     */
    public function getRecords(array $where = [], array $order_by = [], array $limit = [], $return_count = false, $just_these_attributes = [])
    {
        // Fetch "recipe saved".
        $where['_select'] = self::getSavedRecipesQuery();

        if (false === $this->is_admin) {
            if (!array_key_exists('status', $where)) {
                $where['status'] = "publish";
            }
        }

        return parent::getRecords($where, $order_by, $limit, $return_count, $just_these_attributes);
    }

    /**
     * @return int|mixed
     * @param string $status
     *
     * @todo fix and move to core model.
     */
    public function getCount($status = "")
    {
        $input_parameters['status'] = trim($status);

        $sql = 'SELECT COUNT(*) AS `count` FROM `' . $this->getTableName() . '`';

        if (strlen($status)) {
            $sql .= ' WHERE `status` = :status;';
        }

        $req = Database::getBDD()->prepare($sql);

        if (strlen($status)) {
            $req->execute($input_parameters);
        } else {
            $req->execute();
        }

        $result = $req->fetch();

        return (int) $result['count'];
    }

    public function get($id, $field = 'id', array $where = [])
    {
        // Fetch "recipe saved".
        $where['_select'] = self::getSavedRecipesQuery();

        return parent::get($id, $field, $where);
    }

    public function getBySlug($slug = '', array $where = [])
    {
        // Fetch "recipe saved".
        $where['_select'] = self::getSavedRecipesQuery();

        return parent::getBySlug($slug, $where);
    }

    /**
     * Returns tructured data for 'recipe' type.
     * see: https://developers.google.com/search/docs/data-types/recipe
     *
     * @param array $args
     *
     * @return string
     */
    public function getStructuredData(array $args = [])
    {
        $default_schema = json_decode(TemplateHelper::getDefaultSchema(), true);

        if (array_key_exists('recipe', $args)) {

            if (is_array($args['recipe']) && count($args['recipe'])) {
                $recipe = $args['recipe'];

                $category_model = new CategoryModel();
                $main_category = $category_model->getMainCategory($recipe);
                $dish_types    = $category_model->getRecords(['in' => ['id' => $recipe['dishtypes']]], ['title' => 'asc']);

                // The property primaryImageOfPage is not recognized by Google for an object of type Recipe.
//                $default_schema['@graph'][] = [
//                    '@type' => 'ImageObject',
//                    '@id' => Core::url('recept/' . $recipe['slug']) . '#primaryimage',
//                    'url' => TemplateHelper::getFeaturedImage($recipe),
//                    'width' => 1200,
//                    'height' => 800,
//                    'caption' => $recipe['excerpt'],
//                    'inLanguage' => 'nl'
//                ];

                $sd = [];
                $sd['@type'] = "Recipe";
                $sd['isPartOf'] = [
                    '@id' => Core::url('/') . '#website'
                ];
                $sd['mainEntityOfPage'] = [
                    '@type' => 'WebPage',
                    '@id' => Core::url('recipe/' . $recipe['slug'])
                ];
//                $sd['primaryImageOfPage'] = [
//                    '@id' => Core::url('recept/' . $recipe['slug']) . '#primaryimage'
//                ];
                // (The property @url is not recognized by Google for an object of type Recipe.)
//                $sd['@url'] = Core::url('recept/' . $recipe['slug']);
                $sd['name'] = $recipe['title'];
                $sd['image'] = [
                    Core::upload_path($recipe['featured_image']),
                    Core::upload_path($recipe['thumbnail_image'])
                ];
                $sd['author'] = [
                    '@type' => "Person",
                    'name' => 'Ohmydish'
                ];
                $sd['datePublished'] = substr($recipe['created'],0,10);
                $sd['dateModified'] = substr($recipe['updated'],0,10);
                $sd['discussionUrl'] = Core::url('recept/comments/' . $recipe['slug']);

                $sd['inLanguage'] = 'en';
                $sd['commentCount'] = $recipe['total_comments'];

                $sd['description'] = $recipe['excerpt'];
                if (array_key_exists('recipe', $args) && is_array($args['category_cuisines']) && count($args['category_cuisines'])) {
                    $category_cuisines = $args['category_cuisines'];

                    $sdrc = [];
                    foreach ($category_cuisines as $category_cuisine) {
                        $sdrc[] = $category_cuisine['title'];
                    }
                    $sd['recipeCuisine'] = implode(',',$sdrc);
                }
                $sd['prepTime'] = 'PT' . $recipe['preptime'];
                $sd['cookTime'] = 'PT' . $recipe['cooktime'];
                $sd['totalTime'] = 'PT' . $recipe['totaltime'];

                if (array_key_exists('calories', $recipe) && strlen($recipe['calories'])) {
                    $sd['nutrition'] = [
                        '@type' => 'NutritionInformation',
                        'calories' => $recipe['calories'] . ' calories'
                    ];
                }

                $sd['video'] = [
                    '@type'        => 'VideoObject',
                    'name'         => 'The Youtube channel of Ohmydish',
                    'description'  => 'Every now and then we publish food related content on this chanel.',
                    'uploadDate'   => $recipe['published'],
                    'duration'     => 'PT' . $recipe['totaltime'],
                    'contentUrl'   => 'https://www.youtube.com/channel/UChHOlP4pi-5b-5cwKqY74bA',
                    'embedUrl'     => 'https://www.youtube.com/channel/UChHOlP4pi-5b-5cwKqY74bA',
                    'thumbnailUrl' => 'https://i.ytimg.com/vi/1/hqdefault.jpg'
                ];

                if (is_array($dish_types) && count($dish_types)) {
                    $keywords = [];
                    foreach ($dish_types as $dish_type) {
                        $keywords[] = $dish_type['title'];
                    }
                    $sd['keywords'] = implode(',', $keywords);
                }
                // https://developers.google.com/search/docs/data-types/recipe#recipeyield
                $sd['recipeYield'] = (int) $recipe['yield'];
                if (is_array($main_category) && count($main_category)) {
                    $sd['recipeCategory'] = $main_category['title'];
                }

                $sd['recipeIngredient'] = json_decode($recipe['seo_ingredients']);

                $recipeInstructions = [];

                if (strlen($recipe['seo_kitchen_equipments'])) {
                    $recipeInstructions[] = [
                        '@type' => 'HowToStep',
                        'name' => 'Step 1',
                        'text' => 'You will need the following cooking equipment:: ' . implode(', ', json_decode($recipe['seo_kitchen_equipments'])),
                        'url' => Core::url('recipe/' . $recipe['slug']) . '#step-1',
                        'image' => ''
                    ];
                }
                $recipeInstructionsCount = count($recipeInstructions);

                // https://developers.google.com/search/docs/data-types/recipe#recipeinstructions
                $recipe_content = $recipe['content'];
                $recipe_content = str_replace("<p>", ' <p>', $recipe_content);
                $recipe_content = str_replace("\r", '', $recipe_content);
                $recipe_content = str_replace("\n", ' ', $recipe_content);
                $recipe_content = preg_replace('#<h3>(.*?)</h3>#', '', $recipe_content);
                $recipe_content = preg_replace('/\s+/', ' ', $recipe_content); // Remove double whitespaces
                $recipe_content = html_entity_decode($recipe_content);

                $exploded_content = explode('<p>', $recipe_content);

                foreach ($exploded_content as $exploded_content_part) {
                    $exploded_content_part = trim(strip_tags($exploded_content_part));

                    if (strlen($exploded_content_part)) {
                        ++$recipeInstructionsCount;

                        $recipeInstructions[] = [
                            '@type' => 'HowToStep',
                            'name'  => 'Step ' . $recipeInstructionsCount,
                            'text'  => $exploded_content_part,
                            'url'   => Core::url('recipe/' . $recipe['slug']) . '#step-' . $recipeInstructionsCount,
                            'image' => ''
                        ];
                    }
                }

                if (strlen($recipe['seo_ingredients']) && strlen($recipe['seo_kitchen_equipments'])) {
                    $sd['recipeInstructions'] = $recipeInstructions;
                }

                $aggregate_rating = [
                    '@type' => 'AggregateRating',
                    'bestRating'   => 5,
                    'worstRating'  => 1,
                    'ratingValue'  => 5,
                    'ratingCount'  => 1,
                    'reviewAspect' => 'Average recipe quality'
                ];

                if ($recipe['total_votes']) {
                    $aggregate_rating['ratingValue'] = (int) $recipe['average_rating'];
                    $aggregate_rating['ratingCount'] = (int) $recipe['total_votes'];
                }

                $sd['aggregateRating'] = $aggregate_rating;

                $default_schema['@graph'][] = $sd;

                if ($recipe['faq'] && strlen($recipe['faq'])) {
                    $sd = [];
                    $sd['@type'] = "FAQPage";

                    $faq = $recipe['faq'];
                    $e = explode('</p>', $faq);

                    $questions = [];

                    $question_id = 0;
                    foreach ($e as $part) {
                        $part = preg_replace('/<p([^>]*)>/i', '<p>', $part);
                        $part = preg_replace('/<span([^>]*)>/i', '', $part);
                        $part = str_replace('<p>', '', $part);
                        $part = str_replace('</p>', '', $part);

                        $stripped_part = strip_tags($part);

                        if (substr($part, 0, 8) === "<strong>") {
                            if (array_key_exists($question_id, $questions)) {
                                ++$question_id;
                            }

                            $questions[$question_id] = [
                                '@type' => 'Question',
                                'name'  => $stripped_part
                            ];
                        } else {
                            if (
                                array_key_exists($question_id, $questions) &&
                                strlen($stripped_part)
                            ) {
                                if (!array_key_exists('acceptedAnswer', $questions[$question_id])) {
                                    $questions[$question_id]['acceptedAnswer'] = [
                                        '@type' => 'Answer',
                                        'text'  => $stripped_part
                                    ];
                                } else {
                                    $questions[$question_id]['acceptedAnswer']['text'] .= ' ' . $stripped_part;
                                }
                            }
                        }
                    }

                    $sd['mainEntity'] = $questions;

                    if (is_array($questions) && count($questions)) {
                        $default_schema['@graph'][] = $sd;
                    }
                }
            }
        }

        return json_encode($default_schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get previous recipe
     *
     * @param array $recipe
     * @return array
     */
    public function getPreviousRecipe(array $recipe = [])
    {
        if (is_array($recipe) && count($recipe)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MAX(`id`) FROM `' . $this->getTableName() . '` WHERE `published` < "' . $recipe['published'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $previous_recipe = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($previous_recipe) &&
                count($previous_recipe)
            ) {
                return $previous_recipe;
            }
        }

        return [];
    }

    /**
     * Get next recipe.
     *
     * @param array $recipe
     * @return array|mixed
     */
    public function getNextRecipe(array $recipe = [])
    {
        if (is_array($recipe) && count($recipe)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MIN(`id`) FROM `' . $this->getTableName() . '` WHERE `published` > "' . $recipe['published'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $next_recipe = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($next_recipe) &&
                count($next_recipe)
            ) {
                return $next_recipe;
            }
        }

        return [];
    }

    /**
     * Update counts, such as comments.
     */
    public function updateCounts()
    {
        $recipes = $this->getRecords([], ['id', 'asc']);

        if (is_array($recipes) && count($recipes)) {
            foreach ($recipes as $recipe) {
                $this->updateCommentCount($recipe);
            }
        }
    }
}