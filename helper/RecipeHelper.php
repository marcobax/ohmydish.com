<?php

/**
 * Class RecipeHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class RecipeHelper
{
    var $recipe_model;

    /**
     * RecipeHelper constructor.
     */
    public function __construct()
    {
        $this->recipe_model = new RecipeModel();
    }

    /**
     * @param array $recipe
     *
     * @return bool
     */
    public function updateSEOLists(array $recipe = []): bool
    {
        if (is_array($recipe) && count($recipe)) {
            $seo_ingredients = [];
            $ingredients_list = str_replace('&', '&amp;', $recipe['ingredients']);
            $seo_kitchen_equipments = [];
            $kitchen_equipments_list = str_replace('&', '&amp;', $recipe['kitchen_equipment']);

            if (is_string($ingredients_list) && strlen($ingredients_list)) {
                try {
                    // Parse ingredients.
                    $dom = new DOMDocument();
                    $dom->loadHTML(mb_convert_encoding($ingredients_list, 'HTML-ENTITIES', 'UTF-8'));

                    $ingredients = $dom->getElementsByTagName('li');
                    foreach ($ingredients as $ingredient) {
                        $seo_ingredients[] = $ingredient->nodeValue;
                    }
                } catch (\EngineException $e) {
//                    Core::dd($e->getMessage());
                }
            }

            if (is_string($kitchen_equipments_list) && strlen($kitchen_equipments_list)) {
                try {
                    // Parse ingredients.
                    $dom = new DOMDocument();
                    $dom->loadHTML(mb_convert_encoding($kitchen_equipments_list, 'HTML-ENTITIES', 'UTF-8'));

                    $kitchen_equipments = $dom->getElementsByTagName('li');
                    foreach ($kitchen_equipments as $kitchen_equipment) {
                        $seo_kitchen_equipments[] = $kitchen_equipment->nodeValue;
                    }
                } catch (\EngineException $e) {
//                    Core::dd($e->getMessage());
                }
            }

            $seo_ingredients        = json_encode($seo_ingredients, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $seo_kitchen_equipments = json_encode($seo_kitchen_equipments, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

            $this->recipe_model->update($recipe['id'],
            [
                'seo_ingredients'        => $seo_ingredients,
                'seo_kitchen_equipments' => $seo_kitchen_equipments
            ]);

            return true;
        }

        return false;
    }
}