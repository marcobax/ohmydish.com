<?php

/**
 * Class QuestionModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class QuestionModel extends Model
{
    var $table_name = 'question';

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
     * Get previous question
     *
     * @param array $question
     * @return array
     */
    public function getPreviousQuestion(array $question = [])
    {
        if (is_array($question) && count($question)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MAX(`id`) FROM `' . $this->getTableName() . '` WHERE `id` < "' . $question['id'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $previous_question = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($previous_question) &&
                count($previous_question)
            ) {
                return $previous_question;
            }
        }

        return [];
    }

    /**
     * Get next question.
     *
     * @param array $question
     * @return array|mixed
     */
    public function getNextQuestion(array $question = [])
    {
        if (is_array($question) && count($question)) {
            $sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = (SELECT MIN(`id`) FROM `' . $this->getTableName() . '` WHERE `id` > "' . $question['id'] . '" AND `status` = "publish") LIMIT 1';

            $req = Database::getBDD()->prepare($sql);
            $req->execute();
            $next_question = $req->fetch(PDO::FETCH_ASSOC);

            if (
                is_array($next_question) &&
                count($next_question)
            ) {
                return $next_question;
            }
        }

        return [];
    }
}