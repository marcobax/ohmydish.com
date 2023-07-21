<?php

/**
 * Class FacebookHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class FacebookHelper
{
    const STATUS_OK     = 'ok';
    const STATUS_FAILED = 'failed';

    /**
     * CURL API call.
     *
     * @param string $endpoint
     * @param array $params
     *
     * @return array
     */
    public static function APICall(string $endpoint, array $params = [])
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

        $fbResponse = curl_exec( $ch );
        $fbResponse = json_decode( $fbResponse, TRUE );
        curl_close( $ch );

        return [
            'endpoint'      => $endpoint,
            'params'        => $params,
            'has_errors'    => isset( $fbResponse['error'] ),
            'error_message' => isset( $fbResponse['error'] ) ? $fbResponse['error']['message'] : '',
            'fb_response'   => $fbResponse
        ];
    }

    /**
     * Returns the Facebook login URL.
     *
     * @return string
     */
    public static function getLoginURL()
    {
        $endpoint = FB_URL . FB_GRAPH_VERSION . '/dialog/oauth';

        $params = [
            'client_id'    => FB_APP_ID,
            'redirect_uri' => FB_REDIRECT_URI,
            'state'        => FB_APP_STATE,
            'scope'        => 'email',
            'auth_type'    => 'rerequest'
        ];

        return $endpoint . '?' . http_build_query($params);
    }

    /**
     * Returns the access token by given code.
     *
     * @param string $code
     * @return false|array
     */
    public static function getAccessToken(string $code)
    {
        if (is_string($code) && strlen($code)) {
            $endpoint = FB_GRAPH_DOMAIN . FB_GRAPH_VERSION . '/oauth/access_token';

            $params = [
                'client_id'     => FB_APP_ID,
                'client_secret' => FB_APP_SECRET,
                'redirect_uri'  => FB_REDIRECT_URI,
                'code'          => $code,
            ];

            return self::APICall($endpoint, $params);
        }

        return false;
    }

    /**
     * Get (basic) user information.
     *
     * @param string $accessToken
     * @return array|false
     */
    public static function getUserInformation(string $accessToken)
    {
        if (is_string($accessToken) && strlen($accessToken)) {
            $endpoint = FB_GRAPH_DOMAIN . 'me';

            $params = [
                'fields'       => 'first_name,last_name,email,picture',
                'access_token' => $accessToken
            ];

            return self::APICall($endpoint, $params);
        }

        return false;
    }

    /**
     * @param array $get_params
     *
     * @return array
     */
    public static function loginWithFacebook(array $get_params = [])
    {
        $status  = self::STATUS_FAILED;
        $message = '';

        $_SESSION['fb_access_token']     = [];
        $_SESSION['fb_user_information'] = [];

        if (is_array($get_params) && count($get_params)) {
            if (array_key_exists('error', $get_params)) {
                $message = $get_params['error_description'];
            } else {
                if (array_key_exists('code', $get_params) && strlen($get_params['code'])) {
                    $code = $get_params['code'];

                    $accessToken = self::getAccessToken($code);

                    if (is_array($accessToken) && count($accessToken)) {
                        if (array_key_exists('has_errors', $accessToken) && true === $accessToken['has_errors']) {
                            $message = $accessToken['error_message'];
                        } else {
                            $accessTokenString = $accessToken['fb_response']['access_token'];

                            $_SESSION['fb_access_token'] = $accessTokenString;
                            $user_information = self::getUserInformation($accessTokenString);

                            if (
                                (array_key_exists('has_errors', $user_information) && (false === $user_information['has_errors'])) &&
                                !empty($user_information['fb_response']['id']) &&
                                !empty($user_information['fb_response']['email'])
                            ) {
                                $status = self::STATUS_OK;

                                $_SESSION['fb_user_information'] = $user_information['fb_response'];

                                // Does the user exist in DB?
                                $user_model    = new UserModel();
                                $user          = $user_model->get($user_information['fb_response']['id'], 'fb_user_id');
                                if (!$user || (is_array($user) && !count($user))) {
                                    // Try by email.
                                    $user = $user_model->get($user_information['fb_response']['email'], 'email');
                                }

                                if (is_array($user) && count($user)) {
                                    $user_model->update($user['id'], [
                                        'last_logged_in'  => date('Y-m-d H:i:s'),
                                        'fb_access_token' => $accessTokenString
                                    ]);

                                    $_SESSION['logged_in'] = true;
                                    $_SESSION['user']      = $user;
                                } else {
                                    // User does not exist yet.
                                    $newUser = [
                                        'email'           => $user_information['fb_response']['email'],
                                        'password'        => '',
                                        'created'         => date('Y-m-d H:i:s'),
                                        'last_logged_in'  => date('Y-m-d H:i:s'),
                                        'first_name'      => $user_information['fb_response']['first_name'],
                                        'last_name'       => $user_information['fb_response']['last_name'],
                                        'fb_user_id'      => $user_information['fb_response']['id'],
                                        'fb_access_token' => $accessTokenString
                                    ];

                                    if ($user_id = $user_model->create($newUser)) {
                                        $admin_stats_model = new AdminStatsModel();
                                        $admin_stats_model->increment('user', 'new');

                                        $user = $user_model->get((int) $user_id);

                                        $_SESSION['logged_in'] = true;
                                        $_SESSION['user']      = $user;
                                    }
                                }
                            }
                        }
                    } else {
                        $message = 'Access token is invalid.';
                    }
                }
            }
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }
}