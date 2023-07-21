<?php

/**
 * Class ratingController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class ratingController extends Controller
{
    var $rating_model = false;

    /**
     * ratingController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->rating_model = new RatingModel();
    }

    public function xhr_rating()
    {
        header('Content-Type: application/json');

        $rating_model = new RatingModel();
        $query = $this->request->getQuery();
        $allowed_page_types = RatingModel::getPageTypes();

        $average_rating = 0;
        $total_votes    = 0;
        $label          = '0 votes';

        if (
            (array_key_exists('pt', $query) && strlen($query['pt'])) &&
            (array_key_exists('slug', $query) && strlen($query['slug'])) &&
            (array_key_exists('rating', $query) && strlen($query['rating']))
        ) {
            $page_type = trim($query['pt']);
            $slug = trim($query['slug']);
            $rating = (int) $query['rating'];
            if ($rating <= 1) {
                $rating = 2; // hue hue hue </geeselaugh>
            } elseif ($rating > 5) {
                $rating = 5;
            }

            if (in_array($page_type, $allowed_page_types)) {
                $model_name = $page_type . 'Model';
                $model = new $model_name;
                $entity = $model->getBySlug($slug);

                $ip_address = Core::get_client_ip();

                if (is_array($entity) && count($entity)) {
                    // Blacklisted?
                    $blacklist_model = new BlacklistModel();
                    $blacklist_item = $blacklist_model->getRecords([
                        'ip_address' => $ip_address
                    ],[],[0,1]);

                    if (is_array($blacklist_item) && count($blacklist_item)) {
                        $admin_stats_model = new AdminStatsModel();
                        $admin_stats_model->increment('vote', 'blocked', $ip_address);
                    }

                    // Can only vote once per IP address, per type and id.
                    $rating_record = $rating_model->getRecords([
                        'ip_address' => $ip_address,
                        'page_type' => $page_type,
                        'entity_id' => $entity['id']
                    ],[],[0,1]);

                    // Permission check.
                    if (
                        $this->hasPermission('is_admin') |
                        (
                            (is_array($blacklist_item) && !count($blacklist_item)) &&
                            (is_array($rating_record) && !count($rating_record))
                        )
                    ) {
                        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
                            $referer = $_SERVER['HTTP_REFERER'];
                            $uri = ltrim(str_replace(DOMAIN_NAME, '', $referer), '/');
                            Core::invalidateCache($uri);
                        }

                        $data = [
                            'created'    => date('Y-m-d H:i:s'),
                            'page_type'  => $page_type,
                            'entity_id'  => $entity['id'],
                            'rating'     => $rating,
                            'ip_address' => $ip_address?$ip_address:'unknown',
                            'original_rating' => (int) $query['rating']
                        ];
                        $rating_model->create($data);

                        // Auto blacklist.
                        if (1 === (int) $query['rating']) {
                            $blacklist_model->create([
                                'created'    => date('Y-m-d H:i:s'),
                                'ip_address' => $ip_address?$ip_address:'unknown',
                                'reason'     => '1 voter (automatically blacklisted)'
                            ]);
                        }

                        $admin_stats_model = new AdminStatsModel();
                        $admin_stats_model->increment('new-rating', date('Y-m-d'));

                        // Invalidate cache.
                        if (in_array($page_type, ['recipe', 'blog'])) {
                            $url_part = ($page_type==='recipe')?'recept':$page_type;
                            Core::invalidateCache($page_type . '/' . $entity['slug']);
                        }

                        $admin_stats_model = new AdminStatsModel();
                        $admin_stats_model->increment('recipe_rating', 'new');
                    }

                    $total_rating   = $rating_model->getTotalRating($entity['id'], $page_type);
                    $average_rating = $total_rating['average_rating'];
                    $total_votes    = $total_rating['total_votes'];

                    if (
                        $this->hasPermission('is_admin') |
                        (
                            is_array($rating_record) && !count($rating_record)
                        )
                    ) {
                        $label = $total_votes;
                        if ($total_votes > 1 || 0 === $total_votes) {
                            $label .= ' votes';
                        } else {
                            $label .= ' vote';
                        }

                        // Cache counts for quick lookup.
                        $model->update($entity['id'], [
                            'updated'        => date('Y-m-d H:i:s'),
                            'total_votes'    => $total_rating['total_votes'],
                            'average_rating' => $total_rating['average_rating'],
                            'rating_label'   => $label
                        ]);
                    }
                }
            }
        }

        $label = $total_votes;
        if ($total_votes > 1 || 0 === $total_votes) {
            $label .= ' votes';
        } else {
            $label .= ' vote';
        }

        $response = [
            'average_rating' => $average_rating,
            'total_votes'    => $total_votes,
            'rating_label'   => $label
        ];

        echo json_encode($response);
        return false;
    }
}