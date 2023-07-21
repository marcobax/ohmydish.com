<?php

/**
 * Class CommentModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class CommentModel extends Model
{
    var $table_name = 'comment';

    /**
     * @return bool
     */
    public function deleteSpam()
    {
        $sql = 'DELETE FROM `' . $this->getTableName() .'` WHERE `spam` = 1';
        $req = Database::getBDD()->prepare($sql);
        return $req->execute();
    }
}