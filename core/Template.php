<?php

/**
 * Class Template
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Template
{
    /**
     * Returns a (Dutch) formatted string by given yield.
     *
     * @param int $yield
     *
     * @return string
     */
    public static function displayYield($yield = 1)
    {
        if ($yield <= 1) {
            return '1 person';
        }

        return $yield . ' people';
    }

    /**
     * Returns a (Dutch) formatted string by given display time.
     *
     * @param int $displaytime
     *
     * @return string
     */
    public static function displayTimeToDescription($displaytime = 0)
    {
        if ($displaytime < 1) {
            return '0';
        }

        $format = '%2d hours %2d minutes';

        $hours = floor($displaytime / 60);
        $minutes = ($displaytime % 60);

        if (!$hours) {
            if (1 === $minutes) {
                $format = '%2d minute';
            } else {
                $format = '%2d minutes';
            }

            return sprintf($format, $minutes);
        } else {

            if (!$minutes) {
                $format = '%2d hour';
            } else {
                if (1 === $minutes) {
                    $format = '%2d hour %2d minute';
                }
            }
        }

        return sprintf($format, $hours, $minutes);
    }

    /**
     * Removes emojis from a string.
     *
     * @param string $text
     * @return String
     */
    public static function stripEmoji(string $text = ''):String
    {
        $pattern = "~[^a-zA-Z0-9_ !@#$%^&*();\\\/|<>\"'+.,:?=-]~";
        $text = preg_replace($pattern, "", $text);

        return htmlspecialchars($text);
    }
}