<?php

/**
 * Class Request
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Request
{
    public
            $controller = '',
            $action     = '',
            $params     = [],
            $query      = '',
            $URI        = '';

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->setURI();

        // Defaults.
        $this->setController('main');
        $this->setAction('index');
    }

    /**
     * Sets the current URI.
     */
    public function setURI()
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        if (
            strlen($request_uri) &&
            strlen($request_uri) > 1 &&
            '/' === substr($request_uri,0,1)
        ) {
            $request_uri = substr($request_uri, 1, strlen($request_uri));
        }

        $this->URI = trim($request_uri);
    }

    /**
     * Returns the controller.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the controller.
     *
     * @param string $controller
     */
    public function setController($controller = '')
    {
        $this->controller = trim($controller);
    }

    /**
     * Sets the action.
     *
     * @param string $action
     */
    public function setAction($action = '')
    {
        $this->action = $action;
    }

    /**
     * Returns the action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the params.
     *
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        if (array_key_exists('query', $params)) {
            $this->setQuery($params['query']);
            unset($params['query']);
        }

        $this->params = $params;
    }

    /**
     * Returns the params.
     *
     * @param string $key
     *
     * @return array
     */
    public function getParams(string $key = '')
    {
        $params = $this->params;

        if (is_string($key) && strlen($key)) {
            if (is_array($params) && count($params) && array_key_exists($key, $params)) {
                return $params[$key];
            }

            return false;
        }

        return $params;
    }

    /**
     * @return mixed|string
     */
    public function getURI()
    {
        return trim($this->URI);
    }

    /**
     * Tries to match the current controller name to given controller.
     *
     * @param $controller_name_to_match
     * @return bool
     */
    public function isCurrentController($controller_name_to_match)
    {
        $current_controller = $this->getController();

        if (is_array($controller_name_to_match)) {
            foreach ($controller_name_to_match as $match) {
                if ($match === $current_controller) {
                    return true;
                }
            }
        } else {
            if ($controller_name_to_match === $current_controller) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tries to match the current action name to given action.
     *
     * @param string|array $action_name_to_match
     * @return bool
     */
    public function isCurrentAction($action_name_to_match)
    {
        $current_action = $this->getAction();

        if (is_array($action_name_to_match)) {
            foreach ($action_name_to_match as $to_match) {
                if ($to_match === $current_action) {
                    return true;
                }
            }
        } else {
            if ($action_name_to_match === $current_action) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets the URL query.
     *
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * Returns URI query.
     *
     * @return array
     */
    public function getQuery()
    {
        $query = [];
        parse_str($this->query, $query);
        return $query;
    }

    /**
     * @param $uri_string
     * @param $sort_key
     * @return mixed|string
     */
    public function filterURI($uri_string, $sort_key)
    {
        $query = $this->getQuery();

        $order = 'asc';

        if (is_array($query) && count($query)) {
            if (
                array_key_exists('order', $query) &&
                array_key_exists('sort', $query)
            ) {
                if (
                    $query['sort'] === $sort_key &&
                    in_array(strtolower($query['order']), ['asc', 'desc'])
                ) {
                    $order = ('asc' === $query['order'])?'desc':'asc';
                }
            }
        }

        $query['sort'] = trim($sort_key);
        $query['order'] = $order;

        return Core::url($uri_string . '?' . http_build_query($query));
    }
}