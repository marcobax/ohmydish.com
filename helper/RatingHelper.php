<?php

/**
 * Class RatingHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class RatingHelper
{
    var $rating_model = false;

    /**
     * RatingHelper constructor.
     */
    public function __construct()
    {
        $this->rating_model = new RatingModel();
    }

    public function getRating($pageType = 'recipe', $entity_id = 0)
    {
        $rating = 0;

        if ((int) $entity_id > 0 ) {
            // @todo
        }

        return $rating;
    }
}