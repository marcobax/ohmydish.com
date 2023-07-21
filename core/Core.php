<?php

/**
 * Class Core
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Core
{
    /**
     * Dump and Die method.
     *
     * @param $vars
     */
    public static function dd(...$vars)
    {
        echo "<pre>";
        foreach($vars as $var) {
            var_dump($var);
        }
        die;
    }

    /**
     * Returns web URL.
     *
     * @param string $relative_path
     * @param bool $with_domain
     *
     * @return mixed|string
     */
    public static function url($relative_path = '', $with_domain = true)
    {
        if (strlen($relative_path)) {
            if ('/' === $relative_path || !strlen($relative_path)) {
                return ((true===$with_domain)?DOMAIN_NAME:'') . WEBROOT;
            }

            return ((true===$with_domain)?DOMAIN_NAME:'') . WEBROOT . $relative_path;
        }

        return $relative_path;
    }

    /**
     * Returns web URL to assets directory.
     *
     * @param string $relative_path
     * @param bool $with_domain
     *
     * @return mixed|string
     */
    public static function asset($relative_path = '', $with_domain = true)
    {
        return self::url(ASSETS_DIR .'/' . $relative_path, $with_domain);
    }

    /**
     * Returns the file extension of a file.
     *
     * @param $path
     * @return false|string
     */
    public static function get_file_extension($path)
    {
        $n = strrpos($path,".");
        return ($n===false) ? "" : substr($path,$n+1);
    }

    /**
     * Returns filesystem path to directory.
     *
     * @param string $relative_path
     *
     * @return mixed|string|string[]
     */
    public static function path($relative_path = '')
    {
        if (strlen($relative_path)) {
            if ('/' === $relative_path) {
                return ROOT;
            }

            return ROOT . $relative_path;
        }

        return $relative_path;
    }

    /**
     * New lines to paragraph tags. Totally not borrowed from Wordpress!
     *
     * @param $text
     * @return string
     */
    public static function nl2p($text)
    {
        return '<p>' . str_replace( array( "\r\n\r\n", "\n\n" ), '</p><p>', $text) . '</p>';
    }

    /**
     * CKeditor by default never creates <h1> tags, only <h2>, <h3>, <h4>, <h5> and <h6>.
     * We will fix the headings by replacing <h2> with <h1>, <h3> with <h2> and so fort.
     *
     * @param $text
     * @return string
     */
    public static function fixHeadings($text): string
    {
        foreach (range(1, 6) as $range){
            $text = str_replace('<h' . $range . '>', '<h' . ($range-1) . '>', $text);
            $text = str_replace('</h' . $range . '>', '</h' . ($range-1) . '>', $text);
        }

        return $text;
    }

    /**
     * Returns the (parsed) upload path.
     *
     * @param $string
     * @param string $size
     * @param bool $return_local_path
     *
     * @return mixed|string|string[]
     */
    public static function upload_path($string, $size = '', $return_local_path = false)
    {
        $str_replace = [
            Core::url('public/uploads/'),
            'https://ohmydish.com/wp-content/uploads/sites/2/',
            'https://ohmydish.com/wp-content/uploads/sites/1/',
            'https://ohmydish.com/wp-content/uploads/sites/3/',
            'https://ohmydish.com/wp-content/uploads/',
        ];

        $local_file = $string;
        foreach ($str_replace as $replace) {
            $local_file = str_replace($replace, UPLOADS_DIR . '/', $local_file);
        }

        if (file_exists((self::path($local_file)))) {
            if (true === $return_local_path) {
                return $local_file;
            }

            $local_file = self::url($local_file);

            if (strlen($size)) {
                $local_file = str_replace('.' . self::get_file_extension($local_file),'-' . $size . '.' . self::get_file_extension($local_file),$local_file);
            }

            return $local_file;
        }

        return $string;
    }

    public static function get_filesize($filename)
    {
        $path = __DIR__ . '/../' . self::upload_path($filename, '', true);

        if (file_exists($path)) {
            return filesize($path);
        }

        return 0;
    }


    /**
     * Redirects to given URL
     *
     * @param $to_url
     */
    public static function redirect($to_url)
    {
        header("Location: " . $to_url, true, 301);
        exit;
    }

    /**
     * @return string|null
     */
    public static function get_client_ip()
    {
        foreach (array(
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    return trim($ip);
                }
            }
        }

        return null;
    }

    /**
     * @param string $uri
     * @return bool
     */
    public static function invalidateCache(string $uri = ''): bool
    {
        $e = explode('/', $uri);
        $directory = '';
        $sub_files = false;

        if (is_array($e) && count($e) && array_key_exists(0, $e) && array_key_exists(1, $e)) {
            if (strlen($e[0]) && strlen($e[1])) {
                $directory = $e[0] . '/' . $e[1] . '/';
                $sub_files = true;
            }
        }

        $cache_file = CACHE_DIR . $directory . sha1($uri) . '.html';

        if (false === $sub_files) {
            if (file_exists($cache_file)) {
                return unlink($cache_file);
            }
        } else {
            array_map( 'unlink', array_filter((array) glob(CACHE_DIR . $directory . '*') ) );
        }

        return false;
    }
}

function dd(...$vars)
{
    return Core::dd(...$vars);
}
