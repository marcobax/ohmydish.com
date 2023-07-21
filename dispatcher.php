<?php

/**
 * Class Dispatcher
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Dispatcher
{
    public $request;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->dispatch();
    }

    /**
     * Dispatch request.
     */
    protected function dispatch()
    {
        Router::parse($this->request);

        $disallow_caching_urls = [
            'login',
            'today',
            'forgot-password',
            'search',
            'comments',
            'contact-us',
            'create-profile',
            'about-us',
            'sitemap.xml',
            'sitemap-blog.xml',
            'sitemap-blog-comments.xml',
            'sitemap-category.xml',
            'sitemap-collection.xml',
            'sitemap-main.xml',
            'sitemap-page.xml',
            'sitemap-question.xml',
            'sitemap-recipe.xml',
            'sitemap-recipe-comments.xml',
            'sitemap-tag.xml',
            'rss',
            'feed'
        ];

        if (
            (defined('CACHING_ENABLED') && true === CACHING_ENABLED) &&
            !in_array($this->request->getAction(), $disallow_caching_urls)
        ) {
            $uri = $this->request->getURI();
            $e = explode('/', $uri);

            $directory = '';
            if (is_array($e) && count($e) && array_key_exists(0, $e) && array_key_exists(1, $e)) {
                $directory = $e[0] . '/' . str_replace('?' . $this->request->query, '', $e[1]) . '/';
            }

            // 604800 = 1 week
            $cache_time = 604800;
            $cache_file = CACHE_DIR . $directory . sha1($uri) . '.html';

            define('CURRENT_CACHE_FILENAME', $cache_file);

            if (
                file_exists($cache_file) && ((time() - $cache_time) < filemtime($cache_file))
            ) {
                include($cache_file);
                exit;
            }
        }

        if (
            $this->request->getAction() === 'page'
        ) { // Old WP URL, example: "/taart-en-cake/page/9/"
            if (!$page = (int) $this->request->getParams(0)) {
                $page = 1;
            }

            if ($page) {
                $new_url = '/' . $this->request->getController() . '?p=' . $this->request->getParams(0);
                Core::redirect($new_url);
            }
        }

        // Remember for 404 errors.
        $original_request = json_encode($this->request);

        // Redirect rules check.
        $redirect_model = new RedirectModel();

        $query = $this->request->getQuery();
        $lookup_url = rtrim($this->request->getURI(), '/');
        if (!array_key_exists('s', $query)) {
            $lookup_url = str_replace('?', '', $lookup_url); // This ignores URLs such as ?epik= (Pinterest)
            $lookup_url = str_replace($this->request->query, '', $lookup_url);
        }

        $urls = ['"/' . $lookup_url . '"'];

        $where = [
            'in' => [
                'request_url' => implode(',', $urls)
            ]
        ];

        $rules = $redirect_model->getRecords($where);

        if (is_array($rules) && count($rules)) {
            $rule = $rules[0];

            $url = $rule['destination_url'];
            if ('/' === substr($rule['destination_url'], 0, 1)) {
                $destination_url = substr($rule['destination_url'], 1, strlen($rule['destination_url']));
                $url = Core::url($destination_url);

                $params = $this->request->getParams();
                if (
                    is_array($params) &&
                    count($params) &&
                    array_key_exists('query', $params)
                ) {
                    $url .= '?' . $params['query'];
                }
            }

            Core::redirect($url);
        }

        $url_mapping = Url::get_mapping();

        // Redirect to URL mapping if it has been configured.
        $quick_lookup = $url_mapping['_quick_lookup'];

        if (array_key_exists($this->request->getURI(), $quick_lookup)) {
            Core::redirect(Core::url($quick_lookup[$this->request->getURI()]));
        }

        if (array_key_exists($this->request->getURI(), $url_mapping)) {
            $this->request->setController($url_mapping[$this->request->getURI()]['controller']);
            $this->request->setAction($url_mapping[$this->request->getURI()]['action']);
            $controller = $this->loadController();

            call_user_func_array([$controller, $this->request->getAction()], $this->request->getParams());
            exit;
        }

        // Page check.
        $controller_file = ROOT . 'controller/' . $this->request->getController() . 'Controller.php';

        if (!file_exists($controller_file)) {
            $this->request->setController('page');
        }

        $page_model = new PageModel();
        $page = $page_model->getBySlug($this->request->getAction());

        if (is_array($page) && count($page)) {
            $this->request->setController('page');
            $this->request->setAction('detail');
            $controller = $this->loadController();

            call_user_func_array([$controller, $this->request->getAction()], $this->request->getParams());
            exit;
        }

        if (substr($this->request->getURI(), 0, 3) === '?s=') {
            Core::redirect(Core::url('search?s=' . substr($this->request->getURI(), 3, strlen($this->request->getURI()))));
        }

        $controller = $this->loadController();

        if ($controller instanceof Controller) {
            if (
                array_key_exists($this->request->getAction(), $url_mapping) &&
                $this->request->getAction() !== $this->request->getController()
            ) {
                $url_map = $url_mapping[$this->request->getAction()];
                $this->request->setController($url_map['controller']);
                $this->request->setAction($url_map['action']);
                $controller = $this->loadController();
            } else {
                if (!method_exists($controller, $this->request->getAction())) {
                    // Wildcard URL mapping.
                    $wildcard_uri = $this->request->getController() . '/' . $this->request->getAction() . '/*';
                    if (array_key_exists($wildcard_uri, $url_mapping)) {
                        $this->request->setController($url_mapping[$wildcard_uri]['controller']);
                        $this->request->setAction($url_mapping[$wildcard_uri]['action']);
                    } else {
                        // Perhaps a slug!
                        if (method_exists($controller, 'detail')) {
                            $this->request->setAction('detail');
                        } else {
                            if ('404' !== $this->request->getAction()) {
                                $not_found_model = new NotFoundModel();
                                $not_found_model->create([
                                    'created' => date('Y-m-d H:i:s'),
                                    'request' => $original_request,
                                    'server_vars' => json_encode($_SERVER)
                                ]);

                                Core::redirect(Core::url('404'));
                            }

                            // Defaults to 404.
                            $this->request->setController('main');
                            $this->request->setAction('show404');
                            $controller = $this->loadController();
                        }
                    }
                }
            }

            call_user_func_array([$controller, $this->request->getAction()], $this->request->getParams());
        }

        return false;
    }

    /**
     * Load controller.
     *
     * @return mixed
     */
    protected function loadController()
    {
        $controller_name = $this->request->getController() . 'Controller';
        $controller_filename = ROOT . 'controller/' . $controller_name . '.php';

        if (false === file_exists($controller_filename)) {
            $not_found_model = new NotFoundModel();
            $not_found_model->create([
                'created' => date('Y-m-d H:i:s'),
                'request' => json_encode($this->request),
                'server_vars' => json_encode($_SERVER)
            ]);

            Core::redirect(Core::url('404'));
        }

        require_once($controller_filename);
        /** @var Controller $controller */
        $controller = new $controller_name();

        $controller->setRequest($this->request);

        return $controller;
    }
}