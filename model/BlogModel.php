<?php

/**
 * Class BlogModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class BlogModel extends Model
{
    var $table_name = 'blog';

    /**
     * @param array $where
     * @param array $order_by
     * @param array $limit
     * @param false $return_count
     *
     * @return array|int
     */
    public function getRecords(array $where = [], array $order_by = [], array $limit = [], $return_count = false, $just_these_attributes = [])
    {
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

    /**
     * Get previous blog
     *
     * @param array $blog
     * @return array
     */
    public function getPreviousBlog(array $blog = [])
    {
        if (is_array($blog) && count($blog)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MAX(`id`) FROM `' . $this->getTableName() . '` WHERE `published` < "' . $blog['published'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $previous_blog = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($previous_blog) &&
                count($previous_blog)
            ) {
                return $previous_blog;
            }
        }

        return [];
    }

    /**
     * Get next blog.
     *
     * @param array $blog
     * @return array|mixed
     */
    public function getNextBlog(array $blog = [])
    {
        if (is_array($blog) && count($blog)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MIN(`id`) FROM `' . $this->getTableName() . '` WHERE `published` > "' . $blog['published'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $next_blog = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($next_blog) &&
                count($next_blog)
            ) {
                return $next_blog;
            }
        }

        return [];
    }

    /**
     * Update counts, such as comments.
     */
    public function updateCounts()
    {
        $blogs = $this->getRecords([], ['id', 'asc']);

        if (is_array($blogs) && count($blogs)) {
            foreach ($blogs as $blog) {
                $this->updateCommentCount($blog);
            }
        }
    }
}