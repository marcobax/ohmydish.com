<?php

/**
 * Class CategoryModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class CategoryModel extends Model
{
    var $table_name = 'category';

    /**
     * Returns the primary category name of an entity.
     *
     * @param array $entity
     * @return array
     */
    public function getMainCategory($entity = [])
    {
        if (is_array($entity) && count($entity)) {
            if (array_key_exists('categories', $entity) && strlen($entity['categories'])) {
                $categories = $this->getRecords([
                    'in' => [
                        'id' => $entity['categories']
                    ]
                ], ['id', 'asc']);

                if (is_array($categories) && count($categories)) {
                    return $categories[0];
                }
            }
        }

        return [];
    }

    public function updateCounts()
    {
        $categories = $this->getRecords([], ['id', 'asc']);

        if (is_array($categories) && count($categories)) {
            foreach ($categories as $category) {
                $this->updateCount($category);
            }
        }
    }

    public function updateCount(array $category = [])
    {
        if (is_array($category) && count($category) && array_key_exists('type', $category)) {
            $recipe_model = new RecipeModel();
            $blog_model   = new BlogModel();
            $total_entities = 0;
            $total_recipes = 0;

            $key = 'categories';

            if ($category['type'] === 'kitchen') {
                $key = 'cuisines';
            }
            if ($category['type'] === 'dishtype') {
                $key = 'dishtypes';
            }

            $total_recipes += $recipe_model->getRecords([
                'status' => 'publish',
                'find_in_set' => [
                    $key => $category['id']
                ]
            ], [], [], true);

            $total_blogs = $blog_model->getRecords([
                'status' => 'publish',
                'in' => [
                    'categories' => $category['id']
                ]
            ], [], [], true);

            if ($total_recipes > 0) {
                $total_entities += $total_recipes;
            }

            if ($total_blogs > 0) {
                $total_entities += $total_blogs;
            }

            if ($total_entities !== (int) $category['total_entities']) {
                $this->update($category['id'], [
                    'total_entities' => $total_entities
                ]);
            }
            return true;
        }

        return false;
    }

    /**
     * Get schema.org data.
     *
     * @param array $args
     * @return false|string
     */
    public function getStructuredData(array $args = [])
    {
        $default_schema = json_decode(TemplateHelper::getDefaultSchema(), true);

        if (array_key_exists('category', $args)) {

            if (is_array($args['category']) && count($args['category'])) {
                $category = $args['category'];

                if (array_key_exists('seo_content', $category) && $category['seo_content'] && strlen($category['seo_content'])) {
                    $sd = [];
                    $sd['@type'] = "FAQPage";

                    $e = explode('</p>', $category['seo_content']);

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
                                'name' => $stripped_part
                            ];
                        } else {
                            if (strlen($stripped_part)) {
                                if (array_key_exists($question_id, $questions)) {
                                    if (!array_key_exists('acceptedAnswer', $questions[$question_id])) {
                                        $questions[$question_id]['acceptedAnswer'] = [
                                            '@type' => 'Answer',
                                            'text' => $stripped_part
                                        ];
                                    } else {
                                        $questions[$question_id]['acceptedAnswer']['text'] .= ' ' . $stripped_part;
                                    }
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
}
