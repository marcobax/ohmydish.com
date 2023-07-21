<?php

/**
 * Class AkismetHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 *
 * https://akismet.com/development/api/
 */
class AkismetHelper
{
    var $key = '0b7695f66d4c';
    var $blog = 'https://ohmydish.com';

    /**
     * @param array $data
     * @return bool
     */
    public function comment_check(array $data = [])
    {
        $valid_key = $this->verify_key();

        if (
            true === $valid_key &&
            is_array($data) &&
            count($data)
        ) {
            $request = 'blog=' . urlencode($this->blog) .
                '&user_ip=' . urlencode($data['user_ip']) .
                // '&user_agent=' . urlencode($data['user_agent']) .
                // '&referrer=' . urlencode($data['referrer']) .
                '&permalink=' . urlencode($data['permalink']) .
                '&comment_type=' . urlencode($data['comment_type']) .
                '&comment_author=' . urlencode($data['comment_author']) .
                '&comment_author_email=' . urlencode($data['comment_author_email']) .
                //'&comment_author_url=' . urlencode($data['comment_author_url']) .
                '&comment_content=' . urlencode($data['comment_content']);

            $host = $http_host = $this->key . '.rest.akismet.com';
            $path = '/1.1/comment-check';
            $port = 443;
            $akismet_ua = "WordPress/4.4.1 | Akismet/3.1.7";
            $content_length = strlen($request);
            $http_request = "POST $path HTTP/1.0\r\n";
            $http_request .= "Host: $host\r\n";
            $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $http_request .= "Content-Length: {$content_length}\r\n";
            $http_request .= "User-Agent: {$akismet_ua}\r\n";
            $http_request .= "\r\n";
            $http_request .= $request;
            $response = '';

            if (false != ($fs = @fsockopen('ssl://' . $http_host, $port, $errno, $errstr, 10))) {
                fwrite($fs, $http_request);
                while (!feof($fs)) {
                    $response .= fgets($fs, 1160); // One TCP-IP packet
                }
                fclose($fs);

                $response = explode("\r\n\r\n", $response, 2);
            }

            if ('true' == $response[1]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify key validity.
     *
     * @return bool
     */
    public function verify_key()
    {
        $blog = urlencode($this->blog);
        $request = 'key='. $this->key .'&blog='. $blog;
        $host = $http_host = 'rest.akismet.com';
        $path = '/1.1/verify-key';
        $port = 443;
        $akismet_ua = "WordPress/4.4.1 | Akismet/3.1.7";
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';

        if( false != ( $fs = @fsockopen( 'ssl://' . $http_host, $port, $errno, $errstr, 10 ) ) ) {
            fwrite( $fs, $http_request );
            while ( !feof( $fs ) ) {
                $response .= fgets($fs, 1160); // One TCP-IP packet
            }
            fclose( $fs );
            $response = explode( "\r\n\r\n", $response, 2 );
        }

        if ( 'valid' == $response[1] ) {
            return true;
        }

        return false;
    }
}