<?php

/**
 * Class TemplateHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class TemplateHelper
{
    /**
     * @param array $recipe
     */
    public static function includeHeart(array $recipe = [], $_heart_size = 'normal')
    {
        include(ROOT . 'view/recipe/_heart.php');
    }

    /**
     * @param array $blog
     */
    public static function includeBlogHeart(array $blog = [], $_heart_size = 'normal')
    {
        include(ROOT . 'view/blog/_heart.php');
    }

    /**
     *
     * @param string $email
     *
     * @return string
     */
    public static function gravatarURL(string $email): string
    {
        return 'https://www.gravatar.com/avatar/'. md5(strtolower(trim($email))) . '?s=60&d=mp';
    }

    /**
     * Returns the featured image URL, or blank image URL if no image is configured.
     *
     * @param array $entity
     * @param int $default_size
     *
     * @return mixed|string|string[]
     */
    public static function getFeaturedImage(array $entity = [], $default_size = 1200)
    {
        if (is_array($entity) && count($entity) && array_key_exists('featured_image', $entity) && strlen($entity['featured_image']))
        {
            $path = Core::upload_path($entity['featured_image']);

            if (strlen($path)) {
                return $path;
            }
        }

        return Core::asset('img/FFFFFF-' . (int) $default_size . '.png');
    }

    /**
     * Returns CloudImage link.
     *
     * @param string $image_link
     * @param bool|int $width
     * @param bool|int $height
     * @param bool $debug
     *
     * @return string
     */
    public static function getCDNImage($image_link = '', $width = false, $height = false, $debug = false)
    {
        if (ENV === 'dev') {
            $image_link = str_replace(DOMAIN_NAME, 'https://ohmydish.com', $image_link);
            $debug = true;
        }

        $CDN_url = 'https://aklmuvxhap.cloudimg.io/v7/';

        $query = [];

        if (true === $debug) {
            $query['ci_info'] = 1;
        }

        $parsed_domain = parse_url($image_link);
        $image_link = $parsed_domain['host'] . '' . $parsed_domain['path'];
        $image_link = $CDN_url . $image_link;

        if (is_int($width)) {
            $query['width'] = (int) $width;
        }

        if (is_int($height)) {
            $query['height'] = (int) $height;
        }

        $http_query = http_build_query($query);

        if (strlen($http_query)) {
            $image_link .= '?' . http_build_query($query);
        }

        return $image_link;
    }

    /**
     * Returns the thumbnail image URL, or blank image URL if no image is configured.
     *
     * @param array $entity
     * @param int $default_size
     *
     * @return mixed|string|string[]
     */
    public static function getThumbnailImage(array $entity = [], $default_size = 550)
    {
        if (is_array($entity) && count($entity) && array_key_exists('thumbnail_image', $entity) && strlen($entity['thumbnail_image']))
        {
            $path = Core::upload_path($entity['thumbnail_image']);

            if (strlen($path)) {
                return $path;
            }
        }

        return Core::asset('img/FFFFFF-' . (int) $default_size . '.png');
    }

    /**
     * @param bool $localised
     *
     * @return string
     */
    public static function getSeason($localised = false): string
    {
        $season = 'winter';
        $day  = date('z');

        // Spring
        $spring_starts = date('z', strtotime('March 21'));
        $spring_ends   = date('z', strtotime('June 20'));

        // Summer
        $summer_starts = date('z', strtotime('June 21'));
        $summer_ends   = date('z', strtotime('September 22'));

        // Autumn
        $autumn_starts = date('z', strtotime('September 23'));
        $autumn_ends   = date('z', strtotime('December 20'));


        if ($day >= $spring_starts && $day <= $spring_ends) {
            $season = 'spring';
        } elseif($day >= $summer_starts && $day <= $summer_ends) {
            $season = 'summer';
        } elseif($day >= $autumn_starts && $day <= $autumn_ends) {
            $season = 'autumn';
        }

        if (true === $localised) {
            $translated = [
                'winter' => 'winter',
                'spring' => 'spring',
                'summer' => 'summer',
                'autumn' => 'autumn'
            ];

            return $translated[$season];
        }

        return $season;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function linkifyText(string $text): string
    {
        if (is_string($text) && strlen($text)) {
            $linkify_model = new LinkifyModel();

            $linkifies = $linkify_model->getRecords([
                'type' => 'recipe'
            ]);

            if (is_array($linkifies) && count($linkifies)) {
                $text_to_link = [];
                foreach ($linkifies as $linkify) {
                    $text_to_link[$linkify['text']] = $linkify['url'];
                }

                $can_do_mb = function_exists('mb_regex_encoding') && function_exists('mb_ereg_replace') && function_exists('mb_strlen');

                $keys = array_map($can_do_mb ? 'mb_strlen' : 'strlen', array_keys($text_to_link));
                array_multisort($keys, SORT_DESC, $text_to_link);

                $limit = 1;
                $open_new_window = true;
                $case_sensitive = false;
                $preg_flags = $case_sensitive ? 'ms' : 'msi';

                foreach ($text_to_link as $old_text => $link) {
                    // Escape user-provided string from having regex characters.
                    $old_text = preg_quote($old_text, '~');

                    // If the string to be linked includes '&', consider '&amp;' and '&#038;' equivalents.
                    // Visual editor will convert the former, but users aren't aware of the conversion.
                    if (false !== strpos($old_text, '&')) {
                        $old_text = str_replace('&', '&(amp;|#038;)?', $old_text);
                    }

                    // Allow spaces in linkable text to represent any number of whitespace chars.
                    $old_text = preg_replace('/\s+/', '\s+', $old_text);

                    // Regex to find text to replace, but not when in HTML tags or shortcodes.
                    $regex = '(?![<\[].*)'  // Not followed by an an opening angle or square bracket
                        . '\b'              // Word boundary
                        . "({$old_text})"   // 1: The text to be linkified
                        . '\b'              // Word boundary
                        . '(?!'             // Non-capturing group
                        . '[^<>\[\]]*?' // 0 or more characters that aren't angle or square brackets
                        . '[\]>]'       // Character that isn't a closing angle or square bracket
                        . ')';              // End of non-capturing group

                    // Check if the text contains the phrase to link.
                    if ($can_do_mb && (strlen($old_text) != mb_strlen($old_text))) {
                        $has_text = mb_ereg_match('.*' . $regex, $text, $preg_flags);
                    } else {
                        $has_text = preg_match("~{$regex}~{$preg_flags}", $text);
                    }

                    // Don't linkify if the text doesn't include this word/phrase to link.
                    if (!$has_text) {
                        continue;
                    }

                    // If the link starts with a colon, treat it as a special shortcut to the
                    // link for the referenced term. Nested referencing is not supported.
                    if ($link && ':' === $link[0]) {
                        $shortcut_to = substr($link, 1);
                        if (isset($text_to_link[$shortcut_to])) {
                            $link = $text_to_link[$shortcut_to];
                        }
                    }

                    // If link is empty, or is another term reference, don't linkify.
                    if (!$link || ':' === $link[0]) {
                        continue;
                    }

                    // If the link does not contain a protocol and isn't absolute, prepend 'http://'
                    // Sorry, not supporting non-root relative paths.
                    if (false === strpos($link, '://') && !path_is_absolute($link)) {
                        // Quick and rough check that the link looks like a link to prevent user
                        // making invalid link. A period is sufficient to denote a file or domain.
                        if (false === strpos($link, '.')) {
                            continue;
                        }
                        $link = 'http://' . $link;
                    }

                    $link_attrs = array('href' => $link);
                    if ($open_new_window) {
                        $link_attrs['target'] = '_blank';
                    }

                    // An href must be provided.
                    if (empty($link_attrs['href'])) {
                        continue;
                    }
                    $attrs = '';
                    foreach ($link_attrs as $attr => $val) {
                        $attrs .= $attr;
                        $attrs .= '="';
                        $attrs .= ('href' === $attr ? $val : $val);
                        $attrs .= '" ';
                    }
                    $new_text = '<a ' . trim($attrs) . '>\\1</a>';

                    // Bail if text is unchanged.
                    if ($new_text === $old_text) {
                        continue;
                    }

                    // If the text to be replaced has multibyte character(s), use
                    // mb_ereg_replace() if possible.
                    if ($can_do_mb && (strlen($old_text) != mb_strlen($old_text))) {
                        // NOTE: mb_ereg_replace() does not support limiting the number of
                        // replacements, hence the different handling if replacing once.
                        if (1 === $limit) {
                            // Find first occurrence of the search string.
                            mb_ereg_search_init($text, $old_text, $preg_flags);
                            $pos = mb_ereg_search_pos();

                            // Only do the replacement if the search string was found.
                            if (false !== $pos) {
                                $match = mb_ereg_search_getregs();
                                $text = mb_substr($text, 0, $pos[0])
                                    . sprintf(str_replace("\\1", '%s', $new_text), $match[0])
                                    . mb_substr($text, $pos[0] + $pos[1] - 1);
                            }
                        } else {
                            $text = mb_ereg_replace($regex, $new_text, $text, $preg_flags);
                        }
                    } else {
                        $text = preg_replace("~{$regex}~{$preg_flags}", $new_text, $text, $limit);
                    }
                }
            }
        }

        return $text;
    }

    /**
     * Truncate text
     *
     * @param string $text
     * @param int $words
     *
     * @return string
     */
    public static function truncate(string $text = '', int $words = 10): string
    {
        preg_match("/(\S+\s*){0,$words}/", $text, $regs);

        return trim($regs[0]);
    }

    /**
     * Returns the default schema.org
     *
     * @return false|string
     */
    public static function getDefaultSchema()
    {
        $sd = [];
        $sd['@context'] = "https://schema.org/";
        $sd['@graph'] = [
            [
                '@type' => 'Organization',
                '@id' => Core::url('/') . '#organization',
                'name' => 'Ohmydish',
                'url' => Core::url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    '@id' => Core::url('/') . '#logo',
                    'inLanguage' => 'nl',
                    'url' => Core::asset('img/Ohmydish_logo.png'),
                    'width' => 263,
                    'height' => 70,
                    'caption' => 'Ohmydish'
                ],
                'sameAs' => [
                    'https://www.instagram.com/ohmydish/',
                    'https://pinterest.com/ohmydishnl/',
                    'https://www.facebook.com/ohmydishNL/',
                    'https://twitter.com/ohmydish_nl',
                ]
            ],
            [
                '@type' => 'Website',
                '@id' => Core::url('/') . '#website',
                'url' => Core::url('/'),
                'name' => 'Ohmydish',
                'inLanguage' => 'nl',
                'publisher' => [
                    '@id' => Core::url('/') . '#organization'
                ],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => Core::url('search') . '?s={query}',
                    'query' => 'required',
                    'query-input' => 'name=query'
                ]
            ]
        ];

        return json_encode($sd, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public static function formatDate($date)
    {
        $format = 'Y-m-d H:i:s';
        return date($format, strtotime($date));
    }

    /**
     * @param $number
     * @return string
     */
    public static function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Returns formatted users name.
     *
     * @param array $user
     * @param bool $full full name
     * @return string
     */
    public static function formatUserName(array $user = [], bool $full = false): string
    {
        $user_name = '';

        if (is_array($user) && count($user)) {
            if (
                array_key_exists('first_name', $user) &&
                strlen($user['first_name'])
            ) {
                $user_name .= ucfirst(strtolower($user['first_name']));
            }
            if (
                array_key_exists('last_name', $user) &&
                strlen($user['last_name'])
            ) {
                $user_name .= ' ' . ucfirst(strtolower($user['last_name']));
            }
        }

        return $user_name;
    }
}