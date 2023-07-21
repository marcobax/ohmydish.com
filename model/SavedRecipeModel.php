<?php

/**
 * Class SavedRecipeModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class SavedRecipeModel extends Model
{
    var $table_name = 'saved_recipe';

    /**
     * Adds support for soft delete.
     *
     * @param array $where
     * @param array $order_by
     * @param array $limit
     * @param false $return_count
     * @return array|int
     */
    public function getRecords(array $where = [], array $order_by = [], array $limit = [], $return_count = false, $just_these_attributes = [])
    {
        if (!array_key_exists('deleted', $where)) {
            $where['deleted'] = 0;
        }

        return parent::getRecords($where, $order_by, $limit, $return_count, $just_these_attributes);
    }
}