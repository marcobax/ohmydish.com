<?php

/**
 * Class Router
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Router
{
    /**
     * @param Request $request
     */
    static public function parse(Request $request)
    {
        // Fetch the URI.
        $URI = $request->getURI();

        $parsed_url = parse_url($URI);

        if (is_array($parsed_url) && count($parsed_url)) {
            if (array_key_exists('path', $parsed_url)) {
                $URI = $parsed_url['path'];
            }
            if (array_key_exists('query', $parsed_url)) {
                $request->setParams(['query' => $parsed_url['query']]);
            }
        }

        if (
            array_key_exists('path', $parsed_url) &&
            strlen($URI) &&
            !in_array($URI, ['/'])
        ) {
            $explode_url = explode('/', $URI);
            $explode_url = array_filter($explode_url); // Remove empty items.
            $explode_url = array_values($explode_url);  // Re-index keys

            if (
                is_array($explode_url) &&
                count($explode_url)
            ) {
                if (1 === count($explode_url)) {
                    $request->setAction($explode_url[0]);
                } else {
                    $request->setController($explode_url[0]);

                    if (array_key_exists(1, $explode_url)) {
                        $request->setAction($explode_url[1]);
                    }

                    if (
                        array_key_exists(2, $explode_url) &&
                        strlen($explode_url[2])
                    ) {
                        $existing_params = $request->getParams();
                        $new_params = array_slice($explode_url, 2);

                        foreach ($new_params as $key => $param) {
                            $existing_params[$key] = $param;
                        }
                        $request->setParams($existing_params);
                    }
                }
            }
        }
    }
}