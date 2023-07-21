<?php

/**
 * Class CoreHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class CoreHelper
{
    /**
     * @param array $elements
     * @param int $parent_id
     * @return array
     */
    public static function buildTree(array &$elements, $parent_id = 0): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parent_id) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }

    /**
     * @param string $subject
     * @return string
     */
    public static function slugify(string $subject): string
    {
        setlocale(LC_ALL, "en_US.utf8");
        $subject = iconv(mb_detect_encoding($subject), "ASCII//TRANSLIT//IGNORE", $subject);
        $subject = str_replace(' -', '', $subject);
        $subject = preg_replace('/[^A-Za-z0-9-\w ]+/', '', $subject);

        if (strlen($subject)) {
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
        }

        return $subject;
    }

    /**
     * @param string $string
     * @param bool $salt
     *
     * @return array
     */
    public static function hash(string $string, $salt = false): array {
        if (false === $salt) {
            $salt = sprintf('$2y$%02d$%s', 12, substr(strtr(base64_encode(openssl_random_pseudo_bytes(18)), '+', '.'), 0, 22));
        }

        return [
            'value' => crypt($string, $salt),
            'salt'  => $salt
        ];
    }
}