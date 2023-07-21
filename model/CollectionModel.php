<?php

/**
 * Class Collection
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class CollectionModel extends Model
{
    var $table_name = 'collection';

    /**
     * @param array $collection
     * @return bool
     */
    public function updateCount(array $collection = [])
    {
        if (is_array($collection) && count($collection)) {
            $sql = 'UPDATE `' . $this->getTableName() . '` SET `total_recipes` = (
                SELECT COUNT(*) AS `cnt` FROM `saved_recipe`
                    WHERE `collection_id` = `' . $this->getTableName() . '`.`id`
                    AND `deleted` = 0
            ) WHERE `id` = ?';

            $req = Database::getBDD()->prepare($sql);
            $req->execute([(int) $collection['id']]);
            return true;
        }

        return false;
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function getDefaultCollection(int $user_id)
    {
        $sql = 'SELECT COUNT(*) AS `cnt` FROM `saved_recipe` WHERE `user_id` = ? AND `collection_id` = 0 AND `deleted` = 0';

        $req = Database::getBDD()->prepare($sql);
        $req->execute([(int) $user_id]);
        $results = $req->fetchAll();

        return [
            'id'            => 0,
            'unique_id'     => 'favourites',
            'name'          => 'My favourites',
            'description'   => '',
            'total_recipes' => (int) $results[0]['cnt'],
            'deleted'       => 0,
            'public'        => 1,
            'user_id'       => $user_id
        ];
    }

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