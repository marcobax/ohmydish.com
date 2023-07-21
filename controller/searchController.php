<?php

/**
 * Class searchController
 *
 * @author Marco Bax <marco@ohmydish.nl
 */
class searchController extends Controller
{
    var $search_model;

    /**
     * searchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->search_model = new SearchModel();
    }

    public function result($xhr = false)
    {
        if (true === $xhr) {
            header('Content-Type: application/json');
        }

        $ip_address = Core::get_client_ip();

        // Blacklisted?
        $blacklist_model = new BlacklistModel();
        $blacklist_item = $blacklist_model->getRecords([
            'ip_address' => $ip_address
        ],[],[0,1]);

        if (is_array($blacklist_item) && count($blacklist_item)) {
            $admin_stats_model = new AdminStatsModel();
            $admin_stats_model->increment('search-blocked', date('Y-m-d'));

            if (true == $xhr) {
                return '';
            } else {
                $this->set(['ip_address' => $ip_address]);
                $this->render('blocked');
		die;
            }
        }

        $query          = $this->request->getQuery();
        $searchterm     = '';
        $results        = [];
        $total_results  = 0;

        $post_values = [];
        if (true === $xhr) {
            $post_values = $_POST;
            $searchterm = trim($post_values['s']);
        } else {
            if (array_key_exists('s', $query)) {
                $searchterm = trim($query['s']);
            }
        }

        $searchterm = str_replace('/','', $searchterm);
	$searchterm = str_replace('--','', $searchterm);
        $searchterm = strip_tags($searchterm);
        $searchterm = htmlspecialchars($searchterm);

        if (strlen($searchterm)) {
            if (in_array($searchterm, SearchModel::$forbidden)) {
                $searchterm = '';
            }

            if (!strlen($searchterm)) {
                Core::redirect(Core::url('/'));
            }

            $query = $this->request->getQuery();
            $page = 1;
            if (false === $xhr) {
                if (array_key_exists('p', $query)) {
                    $page = (int)$query['p'];
                }
            }

            if (array_key_exists($searchterm, SearchModel::$mapping)) {
            $storeSearchTerm = true;
            if (
                str_contains($searchterm, '--') ||
                preg_match('/\p{Han}+/u', $searchterm)
            ) {
                $storeSearchTerm = false;
            }

            if (
                true === $storeSearchTerm &&
                (!SessionHelper::hasPermission('is_admin') && strlen($searchterm) > 2)
            ) {
                    // Store search term.
                    $this->search_model->create([
                        'created' => date('Y-m-d H:i:s'),
                        'term' => $searchterm,
                        'total_results' => 1,
                        'page' => 1,
                        'autosuggest' => 0,
                        'ip_address' => Core::get_client_ip()
                    ]);

                    if (false === $xhr) {
                        $admin_stats_model = new AdminStatsModel();
                        $admin_stats_model->increment('searches', date('Y-m-d'));
                    }
                }

                Core::redirect(SearchModel::$mapping[$searchterm]);
            }

            $pagination = $this->getPagination();
            if ($pagination['page'] <= 1) {
                $record_limit = 0;
            } else {
                $record_limit = (($pagination['page']-1)*$pagination['results_per_page']);
            }

            $input_parameters = [
                'status' => 'publish',
                'searchterm' => '%' . $searchterm . '%'
            ];

            $sql = "(SELECT `average_rating`, 'recipe' AS `url_prefix` " . RecipeModel::getSavedRecipesQuery()[0] . ", 'recipe' AS `model`, `recipe`.`featured_image`, `recipe`.`slug`, `recipe`.`title`, `recipe`.`published`, `recipe`.`displaytime`, `recipe`.`yield`, `recipe`.`excerpt` FROM `recipe` AS `recipe` JOIN `recipe_tag` AS `rt` ON `recipe`.`id` = `rt`.`recipe_id` JOIN `tag` AS `t` ON `rt`.`tag_id` = `t`.`id` WHERE `recipe`.`status` = :status AND (`recipe`.`title` LIKE :searchterm OR `recipe`.`content` LIKE :searchterm OR `recipe`.`ingredients` LIKE :searchterm OR `recipe`.`kitchen_equipment` LIKE :searchterm OR (`t`.`name` LIKE :searchterm OR `t`.`description` LIKE :searchterm)) GROUP BY `recipe`.`id`)
                    UNION
                    (SELECT 0 AS `average_rating`, 'blog' AS `url_prefix`, 0 AS `recipe_saved`, 'blog' AS `model`, `b`.`featured_image`, `b`.`slug`, `b`.`title`, `b`.`published`, 0 AS `displaytime`, 0 AS `yield`, `b`.`excerpt` FROM `blog` AS `b` WHERE `b`.`status` = :status AND (`b`.`title` LIKE :searchterm OR `b`.`content` LIKE :searchterm))
                    UNION
                    (SELECT 0 AS `average_rating`, '' AS `url_prefix`, 0 AS `recipe_saved`, 'page' AS `model`, `p`.`featured_image`, `p`.`slug`, `p`.`title`, `p`.`published`, 0 AS `displaytime`, 0 AS `yield`, `p`.`excerpt` FROM `page` AS `p` WHERE `p`.`status` = :status AND (`p`.`title` LIKE :searchterm OR `p`.`content` LIKE :searchterm))
                    ORDER BY `published` DESC
                    ";

            $count_sql = 'SELECT COUNT(*) AS `count` FROM (' . $sql . ') AS `count`';

            $req = Database::getBDD()->prepare($count_sql);
            $req->execute($input_parameters);
            $count_results = $req->fetchAll(PDO::FETCH_ASSOC);
            if (array_key_exists(0, $count_results)) {
                $total_results = (int) $count_results[0]['count'];
            }

            if (true === $xhr) {
                $sql .= ' LIMIT 0,5';
            } else {
                $sql .= ' LIMIT ' . $record_limit . ',' . $pagination['results_per_page'];
            }

            $req = Database::getBDD()->prepare($sql);
            $req->execute($input_parameters);
            $results = $req->fetchAll(PDO::FETCH_ASSOC);

            $suggestion = 0;
            if (!$total_results) {
                $input_parameters = ['searchterm' => $searchterm];
                $count_sql = "SELECT `average_rating`, 'recipe' AS `url_prefix` " . RecipeModel::getSavedRecipesQuery()[0] . ", 'recipe' AS `model`, `recipe`.`featured_image`, `recipe`.`slug`, `recipe`.`title`, `recipe`.`published`, `recipe`.`displaytime`, `recipe`.`yield`, `recipe`.`excerpt` FROM `recipe` AS `recipe`";
                $count_sql .= ' WHERE `recipe`.`status` = "publish"';
                $count_sql .= ' AND MATCH(`recipe`.`title`) AGAINST(+:searchterm IN BOOLEAN MODE)';
                $sql = $count_sql . ' ORDER BY `recipe`.`published` DESC LIMIT 0,' . $this->getResultsPerPage();
                $req = Database::getBDD()->prepare($sql);
                $req->execute($input_parameters);
                $alternative_results = $req->fetchAll(PDO::FETCH_ASSOC);

                if (count($alternative_results)) {
                    $suggestion = 1;
                    $total_results = count($alternative_results);

                    $this->set([
                        'alternative_results' => $alternative_results
                    ]);
                }
            }

            $storeSearchTerm = true;
            if (
                str_contains($searchterm, '--') ||
                preg_match('/\p{Han}+/u', $searchterm)
            ) {
                $storeSearchTerm = false;
            }

            if (
                true === $storeSearchTerm &&
                (!SessionHelper::hasPermission('is_admin') && strlen($searchterm) > 2)
            ) {
                // Store search term.
                $this->search_model->create([
                    'created' => date('Y-m-d H:i:s'),
                    'term' => $searchterm,
                    'total_results' => $total_results,
                    'page' => $page,
                    'autosuggest' => (true === $xhr) ? 1 : 0,
                    'ip_address' => Core::get_client_ip(),
                    'suggestion' => $suggestion
                ]);

                if (false === $xhr) {
                    $admin_stats_model = new AdminStatsModel();
                    $admin_stats_model->increment('searches', date('Y-m-d'));
                }
            }
        }

        $this->setTotalResults($total_results);

        if (true === $xhr) {
            $output = [];
            $output['results'] = '<div class="p-2"><span class="text-danger">No results found for "' . $searchterm . '"</span></div>';
            if (is_array($results) && $total_results > 0) {
                $output['results'] = '';
                $output['results'] .= '<table class="table table-hover table-sm m-0">';
                foreach ($results as $result) {
                    $output['results']  .= '<tr class="d-flex" style="cursor:pointer;" onclick="window.location.href=\'' . Core::url(($result['url_prefix']?$result['url_prefix']. '/':'') . $result['slug']) . '\';">';
                    if ('recipe' === $result['model']) {
                        $output['results'] .= '<td class="col-12 col-sm-8 pl-4">';
                    } else {
                        $output['results'] .= '<td class="col-12 pl-4">';
                    }
                    $output['results']         .= '<a href="' . Core::url(($result['url_prefix']?$result['url_prefix']. '/':'') . $result['slug']) . '" class="h5 text-primary m-0 mr-2">' . $result['title'] . '</a> ';
                    $output['results']      .= '</td>';
                    if ('recipe' === $result['model']) {
                        $output['results'] .= '<td class="d-none d-sm-inline-block col-2 overflow-hidden">';
                        $output['results'] .= '<strong class="h6"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> ' . Template::displayTimeToDescription($result['displaytime']) . '</span></strong>';
                        $output['results'] .= '</td>';
                        $output['results'] .= '<td class="d-none d-sm-inline-block col-2 overflow-hidden">';
                        $output['results'] .= '<strong class="h6"><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> ' . Template::displayYield($result['yield']) . '</span></strong>';
                        $output['results'] .= '</td>';
                    }
                    $output['results'] .= '</tr>';
                }
                if ($total_results>1) {
                    $output['results'] .= '<tr>';
                    $output['results'] .= '<td colspan="100" class="text-center p-2">';
                    $output['results'] .= '<a href="#" onclick="document.getElementById(\'zoekformulier\').submit(); return false;" class="btn btn-warning">View all ' . $total_results . ' results</a>';
                    $output['results'] .= '</td>';
                    $output['results'] .= '</tr>';
                }
                $output['results'] .= '</table>';
            }

            echo json_encode($output);
            exit;
        } else {
            $this->set([
                'page_title' => 'You have searched for "' . $searchterm . '"',
                'meta_description' => 'Result page for the search term : ' . $searchterm,
                'page_canonical' => Core::url('search'),
                'searchterm' => $searchterm,
                'results' => $results,
                'total_results' => $total_results,
                'pagination' => $this->getPagination()
            ]);

            $this->render('result');
        }
    }

    /**
     * XHR
     */
    public function xhr_suggest()
    {
        return $this->result($xhr = true);
    }
}
