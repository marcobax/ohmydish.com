<?php

/**
 * Class Controller
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Controller
{
    var $request;
    var $vars = [];
    var $layout = 'default';

    var $is_amp = false;

    /* Pagination */
    var $page = 0;
    var $results_per_page = 15;
    var $total_results = 0;
    /* End of pagination */

    var $category_model,
        $recipe_model;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->vars['hide_top_search'] = false;

        $this->category_model = new CategoryModel();
        $this->recipe_model = new RecipeModel();

        $this->set([
            '_newsletter_subscriptions' => 892,
            '_monthly_users' => 20217
        ]);

        if ($this->hasPermission('is_admin')) {
            $this->admin_stats_model = new AdminStatsModel();
            $this->admin_stats_model->is_admin = true;
            $this->recipe_model = new RecipeModel();
            $this->recipe_model->is_admin = true;
            $this->redirect_model = new RedirectModel();
            $this->redirect_model->is_admin = true;
            $this->blog_model = new BlogModel();
            $this->blog_model->is_admin = true;
            $this->comment_model = new CommentModel();
            $this->comment_model->is_admin = true;
            $this->page_model = new PageModel();
            $this->page_model->is_admin = true;
            $this->not_found_model = new NotFoundModel();
            $this->not_found_model->is_admin = true;
            $this->user_model = new UserModel();
            $this->user_model->is_admin = true;
            $this->question_model = new QuestionModel();
            $this->question_model->is_admin = true;
            $this->category_model = new CategoryModel();
            $this->category_model->is_admin = true;
            $this->tag_model = new TagModel();
            $this->tag_model->is_admin = true;
            $this->recipe_tag_model = new RecipeTagModel();
            $this->recipe_tag_model->is_admin = true;
            $this->collection_model = new CollectionModel();
            $this->collection_model->is_admin = true;
            $this->contact_model = new ContactModel();
            $this->contact_model->is_admin = true;
            $this->linkify_model = new LinkifyModel();
            $this->linkify_model->is_admin = true;
            $this->saved_recipe_model = new SavedRecipeModel();
            $this->saved_recipe_model->is_admin = true;
            $this->rating_model = new RatingModel();
            $this->rating_model->is_admin = true;
            $this->blacklist_model = new BlacklistModel();
            $this->blacklist_model->is_admin = true;
            $this->search_model = new SearchModel();
            $this->search_model->is_admin = true;

            $new_user_entities = 0;
            $new_users = $this->admin_stats_model->getRecords([
                'identifier' => 'user',
                'section' => 'new'
            ]);
            if (is_array($new_users) && count($new_users) && array_key_exists(0, $new_users)) {
                $this->set(['new_users' => $new_users[0]['value']]);
                $new_user_entities += $new_users[0]['value'];
            }

            $new_collections = $this->admin_stats_model->getRecords([
                'identifier' => 'collection',
                'section' => 'new'
            ]);
            if (is_array($new_collections) && count($new_collections) && array_key_exists(0, $new_collections)) {
                $this->set(['new_collections' => $new_collections[0]['value']]);
                $new_user_entities += $new_collections[0]['value'];
            }
            $this->set(['new_user_entities' => $new_user_entities]);

            $new_contact_entities = 0;
            $new_feedback = $this->admin_stats_model->getRecords([
                'identifier' => 'feedback',
                'section' => 'new'
            ]);
            if (is_array($new_feedback) && count($new_feedback) && array_key_exists(0, $new_feedback)) {
                $this->set(['new_feedback' => $new_feedback[0]['value']]);
                $new_contact_entities += $new_feedback[0]['value'];
            }
            $new_comment = $this->admin_stats_model->getRecords([
                'identifier' => 'comment',
                'section' => 'new'
            ]);
            if (is_array($new_comment) && count($new_comment) && array_key_exists(0, $new_comment)) {
                $this->set(['new_comment' => $new_comment[0]['value']]);
                $new_contact_entities += $new_comment[0]['value'];
            }
            $this->set(['new_contact_entities' => $new_contact_entities]);
            $new_recipe_rating = $this->admin_stats_model->getRecords([
                'identifier' => 'recipe_rating',
                'section' => 'new'
            ]);
            if (is_array($new_recipe_rating) && count($new_recipe_rating) && array_key_exists(0, $new_recipe_rating)) {
                $this->set(['new_recipe_rating' => $new_recipe_rating[0]['value']]);
            }

            $this->set([
                'admin_recipe_count'           => number_format($this->recipe_model->getCount(), 0, ',', '.'),
                'admin_recipe_publish_count'   => number_format($this->recipe_model->getCount('publish'), 0, ',', '.'),
                'admin_recipe_draft_count'     => number_format($this->recipe_model->getCount('draft'), 0, ',', '.'),
                'admin_rating_count'           => number_format($this->rating_model->getCount(), 0, ',', '.'),
                'admin_saved_recipe_count'     => number_format($this->saved_recipe_model->getCount(), 0, ',', '.'),
                'admin_blog_count'             => number_format($this->blog_model->getCount(), 0, ',', '.'),
                'admin_blog_publish_count'     => number_format($this->blog_model->getCount('publish'), 0, ',', '.'),
                'admin_blog_draft_count'       => number_format($this->blog_model->getCount('draft'), 0, ',', '.'),
                'admin_page_count'             => number_format($this->page_model->getCount(), 0, ',', '.'),
                'admin_page_publish_count'     => number_format($this->page_model->getCount('publish'), 0, ',', '.'),
                'admin_page_draft_count'       => number_format($this->page_model->getCount('draft'), 0, ',', '.'),
                'admin_question_count'         => number_format($this->question_model->getCount(), 0, ',', '.'),
                'admin_question_publish_count' => number_format($this->question_model->getCount('publish'), 0, ',', '.'),
                'admin_question_draft_count'   => number_format($this->question_model->getCount('draft'), 0, ',', '.'),
                'admin_category_count'         => number_format($this->category_model->getCount(), 0, ',', '.'),
                'admin_user_count'             => number_format($this->user_model->getRecords(['is_admin' => 0], [], [], true), 0, ',', '.'),
                'admin_tag_count'              => number_format($this->tag_model->getCount(), 0, ',', '.'),
                'admin_collection_count'       => number_format($this->collection_model->getCount(), 0, ',', '.'),
                'admin_contact_count'          => number_format($this->contact_model->getRecords(['spam' => 0], [], [], true), 0, ',', '.'),
                'admin_contact_spam_count'     => number_format($this->contact_model->getRecords(['spam' => 1], [], [], true), 0, ',', '.'),
                'admin_comment_count'          => number_format($this->comment_model->getRecords(['spam' => 0], [], [], true), 0, ',', '.'),
                'admin_comment_spam_count'     => number_format($this->comment_model->getRecords(['spam' => 1], [], [], true), 0, ',', '.'),
            ]);
        }
    }

    /**
     * AMP
     *
     * @return bool
     */
    public function isAMP()
    {
        return false; // @todo;
        if (in_array('amp', $this->request->getParams())) {
            $this->is_amp = true;
        }

        return $this->is_amp;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        if ($this->hasPermission('is_admin')) {
            $query = $request->getQuery();
            if (is_array($query) && count($query)) {
                if (
                    array_key_exists('sort', $query) &&
                    !array_key_exists('order', $query)
                ) {
                    $query['order'] = 'asc';

                    $request->setQuery(http_build_query($query));
                }
            }
        }

        $this->request = $request;
        $this->vars['_request'] = $this->request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Shows the 404 page.
     */
    public function show404()
    {
        if ("404" !== $this->request->getAction()) {
            $not_found_model = new NotFoundModel();
            $not_found_model->create([
                'created' => date('Y-m-d H:i:s'),
                'request' => json_encode($this->request),
                'server_vars' => json_encode($_SERVER)
            ]);
            Core::redirect(Core::url('404'));
        }

        return false;
    }

    /**
     * Fetches the slug from the URI.
     *
     * @return string
     */
    public function getSlug()
    {
        $URI = $_SERVER['REQUEST_URI'];

        $parsed_url = parse_url($URI);
        if (is_array($parsed_url) && count($parsed_url)) {
            if (array_key_exists('path', $parsed_url)) {
                $URI = $parsed_url['path'];
            }
        }

        $explode_url = explode('/', $URI);
        $explode_url = array_filter($explode_url); // Remove empty items.
        $explode_url = array_values($explode_url);  // Re-index keys

        $current_controller = str_replace('Controller', '', get_class($this));

        if (is_array($explode_url) && count($explode_url)) {
            if (
                $current_controller === $explode_url[0] ||
                $current_controller === 'page'
            ) {
                return trim($explode_url[(1 === count($explode_url))?0:1]);
            }
        }

        return '';
    }

    /**
     * Sets variables.
     *
     * @param $d
     */
    public function set($d)
    {
        $this->vars = array_merge($this->vars, $d);
    }

    /**
     * Render and output buffer the layout files.
     *
     * @param string $filename
     * @param string $layout
     */
    public function render($filename = '', $layout = 'default')
    {
        if ('default' !== $layout) {
            $this->layout = $layout;
        } else {
            if ($layout !== 'amp' && true === $this->isAMP()) {
                $this->layout = 'amp';
            }
        }

        //ob_start('ob_gzhandler');

        if (strlen($filename)) {
            ob_start();

            extract($this->vars);
            $controller_name = get_class($this);

            if (in_array($filename, ['404'])) {
                $controller_name = 'main';
            }

            $view_filename = strtolower(str_replace('Controller', '', $controller_name)) . '/' . $filename . '.php';

            require_once(ROOT . '/view/' . $view_filename);
            $the_content = ob_get_clean();
            // @see: adminController:124 for the 'other part'.
            $the_content = str_replace('class="img-fluid mx-auto d-block"', 'class="img-fluid mx-auto d-block" loading="lazy" width="800" height="1200"', $the_content);

            require_once(ROOT . 'view/layout/' . $this->layout . '.php');

            // Caching
            if (
                (defined('CACHING_ENABLED') && true === CACHING_ENABLED) &&
                defined('CURRENT_CACHE_FILENAME')
            ) {
                $directory_name = dirname(CURRENT_CACHE_FILENAME);
                if (!is_dir($directory_name)) {
                    mkdir($directory_name, 0755, true);
                }
                $fp = fopen(CURRENT_CACHE_FILENAME, 'w');
                fwrite($fp, ob_get_contents());
                fclose($fp);
            }

            ob_flush();
        }


        if (ob_get_contents()) {
            ob_end_clean();
        }
    }

    /**
     * Returns the results per page. (pagination)
     *
     * @return int
     */
    public function getResultsPerPage()
    {
        return $this->results_per_page;
    }

    /**
     * Defines the results per page.
     *
     * @param int $results_per_page
     */
    public function setResultsPerPage($results_per_page = 15)
    {
        $this->results_per_page = (int) $results_per_page;
    }

    /**
     * Returns the total results (pagination)
     *
     * @return int
     */
    public function getTotalResults()
    {
        return $this->total_results;
    }

    /**
     * Sets the total results (pagination).
     *
     * @param $total_results
     */
    public function setTotalResults($total_results = 0)
    {
        $this->total_results = $total_results;
    }

    /**
     * Returns the current page (pagination).
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Defines the current page (pagination).
     *
     * @param int $page
     */
    public function setPage($page = 1)
    {
        $this->page = $page;
    }

    /**
     * Returns the current pagination in array result.
     *
     * @param int|bool $results_per_page
     * @return int[]
     */
    public function getPagination($results_per_page = false)
    {
        if (false !== $results_per_page) {
            $this->setResultsPerPage((int) $results_per_page);
        }

        $query = $this->request->getQuery();

        if (is_array($query) && array_key_exists('p', $query)) {
            if (!(int) $query['p'] || ((int) $query['p'] <= 0)) {
                $new_link = Core::url(str_replace('p=' . $query['p'], 'p=1', $this->request->URI));
                Core::redirect($new_link);
            } else {
                $this->setPage((int) $query['p']);
            }
        }

        $page             = $this->getPage();
        $total_results    = $this->getTotalResults();
        $results_per_page = $this->getResultsPerPage();
        $total_pages      = 1;

        if (
            $total_results &&
            $total_results > $results_per_page
        ) {
            $total_pages = (int) ceil(($total_results / $results_per_page));
        }

        return [
            'results_per_page'     => $results_per_page,
            'page'                 => $page,
            'total_results'        => $total_results,
            'total_pages'          => $total_pages,
            'max_steps_per_screen' => 3
        ];
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return SessionHelper::isLoggedIn();
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission = '')
    {
        return SessionHelper::hasPermission($permission);
    }

    /**
     * @param bool $with_redirect
     */
    public function loginCheck($with_redirect = true)
    {
        SessionHelper::loginCheck($with_redirect);
    }
}