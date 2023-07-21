<?php

/**
 * Class ContactModel
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class ContactModel extends Model
{
    var $table_name = 'contact';

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