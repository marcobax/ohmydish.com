<?php

/**
 * Class SessionHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class SessionHelper
{
    /**
     * @return false
     */
    public static function isLoggedIn()
    {
        if (is_array($_SESSION) && count($_SESSION))
        {
            if (array_key_exists('logged_in', $_SESSION)) {
                return $_SESSION['logged_in'];
            }
        }

        return false;
    }

    /**
     * @return false|int
     */
    public static function getUserId()
    {
        if (self::isLoggedIn()) {
            if (array_key_exists('user', $_SESSION) && array_key_exists('id', $_SESSION['user'])) {
                return (int) $_SESSION['user']['id'];
            }
        }

        return false;
    }

    /**
     * See if the user has a certain permission.
     *
     * @param string $permission
     * @return bool
     */
    public static function hasPermission($permission = '')
    {
        if (
            self::isLoggedIn() &&
            strlen($permission) &&
            array_key_exists('user', $_SESSION)
        ) {
            if (
                array_key_exists($permission, $_SESSION['user']) &&
                (int) $_SESSION['user'][$permission] === 1
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param bool $with_redirect
     */
    public static function loginCheck($with_redirect = true)
    {
        if (false === SessionHelper::isLoggedIn()) {
            if (true === $with_redirect) {
                Core::redirect(Core::url('login'));
            }
        }
    }

    public static function logout()
    {
        if (self::isLoggedIn()) {
            session_destroy();
        }
    }
}