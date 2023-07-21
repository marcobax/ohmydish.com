<?php

/**
 * Class RecipeTagModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class RecipeTagModel extends Model
{
    var $table_name = 'recipe_tag';

    /**
     * @param int $recipe_id
     * @return bool
     */
    public function purge(int $recipe_id)
    {
        $sql = 'DELETE FROM `' . $this->getTableName() .'` WHERE `recipe_id` = ?';
        $req = Database::getBDD()->prepare($sql);
        return $req->execute([(int) $recipe_id]);
    }
}