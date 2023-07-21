<?php

/**
 * Class RatingModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class RatingModel extends Model
{
    var $table_name = 'rating';

    /**
     * @return string[]
     */
    public static function getPageTypes()
    {
        return [
            'recipe',
            'blog',
            'page'
        ];
    }

    public function getTotalRating(int $entity_id, string $page_type = 'recipe')
    {
        $rating      = 0;
        $total_votes = 0;

        if ((int) $entity_id) {
            $input_parameters = [
                'table_name' => $this->table_name,
                'entity_id'  => (int) $entity_id,
                'page_type'  => $page_type
            ];
            $sql = "SELECT COUNT(*) AS `total_votes`, AVG(`rating`) AS `rating` FROM `:table_name` WHERE `entity_id` = :entity_id AND `page_type` = :page_type";

            $input_parameters = [
                'entity_id' => $entity_id,
                'page_type' => $page_type
            ];
            $sql = 'SELECT COUNT(*) AS `total_votes`, AVG(`rating`) AS `rating` FROM `' . $this->getTableName() . '` WHERE `entity_id` = :entity_id AND `page_type` = :page_type';

            $req = Database::getBDD()->prepare($sql);
            $req->execute($input_parameters);
            $results = $req->fetchAll(PDO::FETCH_ASSOC);

            if (is_array($results) && count($results) && array_key_exists(0, $results)) {
                $result = $results[0];

                $rating      = (int) $result['rating'];
                $total_votes = (int) $result['total_votes'];
            }
        }

        $label = $total_votes;
        if ($total_votes > 1 || 0 === $total_votes) {
            $label .= ' votes';
        } else {
            $label .= ' vote';
        }

        return [
            'average_rating' => $rating,
            'total_votes'    => $total_votes,
            'rating_label'   => $label
        ];
    }
}