<?php

/**
 * Class Database
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Database
{
    protected static $bdd = null;

    /**
     * Returns BDD
     *
     * @return PDO|null
     */
    public static function getBDD()
    {
        if (is_null(self::$bdd)) {
            $bdd = new PDO("mysql:host=localhost;dbname=ohmydishcom;charset=utf8mb4", 'ohmydishcom', 'QaLqgGJg9oPs');
            self::$bdd = $bdd;
        }

        return self::$bdd;
    }
}
