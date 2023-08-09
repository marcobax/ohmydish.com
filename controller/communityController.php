<?php

/**
 * Class communityController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class communityController extends Controller
{
    var $collection_model,
        $user_model,
        $saved_recipe_model;

    /**
     * communityController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->collection_model   = new CollectionModel();
        $this->user_model         = new UserModel();
        $this->saved_recipe_model = new SavedRecipeModel();
    }

    /**
     * General overview of community actions.
     */
    public function overzicht()
    {
        $this->loginCheck();

        $this->set([
            'page_title'       => 'Community',
            'meta_description' => 'Overview of all community items',
            'page_canonical'   => Core::url('community/overzicht'),
        ]);

        $this->render('index');
    }

    /**
     * Log into the application.
     */
    public function login()
    {
        if ($this->isLoggedIn())
        {
            Core::redirect(Core::url('community/overzicht'));
        }

        if (
            array_key_exists('HTTP_REFERER', $_SERVER) &&
            (
                is_array($_SESSION) &&
                !array_key_exists('http_referer', $_SESSION)
            ) &&
            (
                rtrim($_SERVER['HTTP_REFERER'], '/') !== DOMAIN_NAME
            )
        ) {
            $_SESSION['http_referer'] = $_SERVER['HTTP_REFERER'];
        }

        $default_login_url = Core::url('community/saved-recipes');
        if (
            is_array($_SESSION) &&
            array_key_exists('http_referer', $_SESSION) &&
            strlen($_SESSION['http_referer'])
        ) {
            $default_login_url = trim($_SESSION['http_referer']);
        }

        $query = $this->getRequest()->getQuery();
        if (
            (is_array($query) && count($query)) &&
            array_key_exists('state', $query) &&
            FB_APP_STATE === $query['state']
        ) {
            $facebook_login = FacebookHelper::loginWithFacebook($query);

            if (is_array($facebook_login) && count($facebook_login)) {
                if (array_key_exists('status', $facebook_login)) {
                    if ($facebook_login['status'] === FacebookHelper::STATUS_OK) {
                        if ($this->isLoggedIn()) {
                            unset($_SESSION['http_referer']);

                            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hey ' . $_SESSION['user']['first_name'] . ', you are now logged in on Ohmydish. <a href="' . Core::url('community/saved-recipes') . '">Go to your saved recipes</a>.'];
                            Core::redirect($default_login_url);
                        }
                    }

                    if (array_key_exists('message', $facebook_login)) {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => $facebook_login['message']];
                    }
                    Core::redirect(Core::url('login'));
                }
            }
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            if (
                (array_key_exists('email', $post_values) && strlen($post_values['email'])) &&
                (array_key_exists('password', $post_values) && strlen($post_values['password']))
            ) {
                $user_model = new UserModel();

                $email       = trim($post_values['email']);
                $password    = trim($post_values['password']);
                $remember_me = (array_key_exists('onthoud-mij', $post_values));

                $user = $user_model->get($email, 'email');

                if (is_array($user) && count($user)) {
                    // Check password.
                    $hash = CoreHelper::hash($password, $user['salt']);
                    if ($user['password'] === $hash['value']) {
                        $user_model->update($user['id'], [
                            'last_logged_in'  => date('Y-m-d H:i:s')
                        ]);
                        $_SESSION['logged_in'] = true;
                        $_SESSION['user']      = $user;

                        if ($this->isLoggedIn()) {
                            unset($_SESSION['http_referer']);
                            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hey ' . $_SESSION['user']['first_name'] . ', you are now logged in at Ohmydish. <a href="' . Core::url('community/saved-recipes') . '">Go to your saved recipes</a>.'];
                            Core::redirect($default_login_url);
                        }
                        Core::redirect($default_login_url);
                    }
                }

                $this->set([
                    '_flash' => [
                        'type'    => 'danger',
                        'message' => 'Dit emailadres en/of wachtwoord is niet juist.'
                    ],
                    'post_values' => $post_values
                ]);
            }
        }

        $this->set([
            'page_title'       => 'Login',
            'meta_description' => 'Log in and see your saved recipes or create collections',
            'page_canonical'   => Core::url('login')
        ]);

        $this->render('login');
    }

    /**
     * Register users (who probably do not have social logins).
     */
    public function register()
    {
        $default_login_url = Core::url('community/saved-recipes');

        if (SessionHelper::isLoggedIn()) {
            Core::redirect($default_login_url);
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            if (
                array_key_exists('email', $post_values) && strlen($post_values['email']) &&
                array_key_exists('password', $post_values) && strlen($post_values['password']) &&
                array_key_exists('password_again', $post_values) && strlen($post_values['password_again']) &&
                array_key_exists('first_name', $post_values) && strlen($post_values['first_name'])
            ) {
                $email          = trim($post_values['email']);
                $password       = trim($post_values['password']);
                $password_again = trim($post_values['password_again']);
                $first_name     = trim($post_values['first_name']);
                $last_name      = array_key_exists('last_name', $post_values)?trim($post_values['last_name']):'';
                $newsletter     = (array_key_exists('newsletter', $post_values))?1:0;

                if ($password !== $password_again) {
                    $this->set([
                        '_flash'      => [
                            'type'    => 'danger',
                            'message' => 'Controleer de invoer: de twee wachtwoorden komen niet overeen.'
                        ],
                        'post_values' => $post_values
                    ]);
                } else {
                    $user_model = new UserModel();
                    $user = $user_model->get($email, 'email');

                    if (is_array($user) && count($user)) {
                        $this->set([
                            '_flash' => [
                                'type' => 'danger',
                                'message' => 'This user is already registered. <a href="' . Core::url('login') . '">Do you want to login?</a>'
                            ]
                        ]);
                    } else {
                        $hash = CoreHelper::hash($password);

                        $newUser = [
                            'email'           => $email,
                            'password'        => $hash['value'],
                            'salt'            => $hash['salt'],
                            'created'         => date('Y-m-d H:i:s'),
                            'first_name'      => $first_name,
                            'last_name'       => $last_name,
                            'newsletter'      => $newsletter
                        ];

                        if ($user_id = $user_model->create($newUser)) {
                            $admin_stats_model = new AdminStatsModel();
                            $admin_stats_model->increment('user', 'new');
                            $admin_stats_model->increment('new-user', date('Y-m-d'));

                            $user_model->update($user['id'], [
                                'last_logged_in'  => date('Y-m-d H:i:s')
                            ]);
                            $user = $user_model->get($user_id);

                            $_SESSION['logged_in'] = true;
                            $_SESSION['user']      = $user;

                            if ($this->isLoggedIn()) {
                                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hey ' . $_SESSION['user']['first_name'] . ', you are now logged in on Ohmydish.'];
                                Core::redirect($default_login_url);
                            }
                        }
                    }
                }
            }
        }

        $this->set([
            'meta_description' => 'Create your own profile at Ohmydish and save your favorite recipes in collections.',
            'page_title'       => 'Create profile',
            'page_canonical'   => Core::url('create-profile')
        ]);

        $this->render('register');
    }

    public function reset_password()
    {
        $user_model = new UserModel();

        $query = $this->getRequest()->getQuery();
        if (
            (is_array($query) && count($query)) &&
            array_key_exists('reset_token', $query)
        ) {
            $reset_token = $query['reset_token'];
            $user = $user_model->get($reset_token, 'reset_token', [
                'is_admin' => 0
            ]);

            if (is_array($user) && count($user)) {
                $this->set([
                    'reset_token' => $reset_token
                ]);
            } else {
                $this->set([
                    '_flash' => ['type' => 'danger', 'message' => 'This link is no longer valid.']
                ]);
            }
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            if (array_key_exists('email', $post_values)) {
                $email = trim($post_values['email']);

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user = $user_model->get($email, 'email', [
                        'is_admin' => 0
                    ]);

                    if (is_array($user) && count($user)) {
                        $reset_token = CoreHelper::hash($email, $user['salt'])['value'];
                        $user_model->update($user['id'], [
                            'reset_token' => $reset_token
                        ]);

                        // Send mail.
                        $subject = 'Reset your password - Ohmydish';
                        $message = 'Click this link to reset your password: ' . Core::url('forgot-password?reset_token=' . $reset_token);
                        $headers = 'From: noreply@ohmydish.com';
                        mail($email,$subject,$message,$headers);

                        $this->set([
                            'hide' => true,
                            '_flash' => ['type' => 'success', 'message' => 'Your new password has been requested. You will receive it in your mailbox soon, make sure to check the spam folder.']
                        ]);
                    } else {
                        $this->set([
                            '_flash' => ['type' => 'danger', 'message' => 'No profile has been found using the given e-mail address. Please check the input.']
                        ]);
                    }
                } else {
                    $this->set([
                        '_flash' => ['type' => 'danger', 'message' => 'This is now a valid e-mail address']
                    ]);
                }
            } elseif(
                array_key_exists('reset_token', $post_values)
            ) {
                $reset_token = $post_values['reset_token'];
                $this->set([
                    'reset_token' => $reset_token
                ]);

                if (array_key_exists('password', $post_values) && strlen($post_values['password'])) {
                    $user = $user_model->get($reset_token, 'reset_token', [
                        'is_admin' => 0
                    ]);

                    if (is_array($user) && count($user)) {
                        $password = trim($post_values['password']);
                        $hash = CoreHelper::hash($password);

                        $values = [
                            'password'        => $hash['value'],
                            'salt'            => $hash['salt'],
                            'password_reset'  => date('Y-m-d H:i:s'),
                            'reset_token'     => null
                        ];

                        $user_model->update($user['id'], $values);

                        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Your new password has been saved. You can now log in using your new password.'];
                        Core::redirect('login?password-reset=yes');
                } else {
                    $this->set([
                        '_flash' => ['type' => 'danger', 'message' => 'Enter a new password.']
                    ]);
                }
                } else {
                    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'This link is no longer valid.'];
                    Core::redirect('forgot-password');
                }
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Please enter a e-mailaddress.'];
                Core::redirect('forgot-password');
            }

            $this->set([
                'post_values' => $post_values
            ]);
        }

        $this->set([
            'meta_description' => 'Forgot your password? No worries! Here you can create a new password for your profile',
            'page_title'       => 'Forgotten password',
            'page_canonical'   => Core::url('forgot-password')
        ]);

        $this->render('reset_password');
    }

    /**
     * Allows the user to log out of the application.
     */
    public function logout()
    {
        SessionHelper::logout();

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Je bent uitgelogd.'];
        Core::redirect('/');
    }

    /**
     * Save recipe to own collection.
     *
     * @param string $slug
     * @return bool
     */
    public function xhr_save_recipe(string $slug)
    {
        if ($this->isLoggedIn()) {
            if ($recipe = $this->recipe_model->getBySlug($slug)) {
                // Do we have a previous saved recipe?
                $saved_recipe = $this->saved_recipe_model->getRecords([
                    'user_id' => SessionHelper::getUserId(),
                    'recipe_id' => (int)$recipe['id'],
                    'collection_id' => 0
                ]);

                if (is_array($saved_recipe) && count($saved_recipe)) {
                    $saved_recipe = $saved_recipe[0];

                    // Soft delete this recipe.
                    $this->saved_recipe_model->update($saved_recipe['id'], ['deleted' => 1]);
                    echo "false";
                    return false;
                } else {
                    // Has this recipe been saved before, but has been deleted?
                    $saved_before = $this->saved_recipe_model->getRecords([
                        'collection_id' => 0,
                        'recipe_id'     => $recipe['id'],
                        'user_id'       => SessionHelper::getUserId(),
                        'deleted'       => 1
                    ]);

                    if (is_array($saved_before) && count($saved_before) && array_key_exists(0, $saved_before)) {
                        $saved = $saved_before[0];

                        $this->saved_recipe_model->update($saved['id'], ['created' => date('Y-m-d H:i:s'), 'deleted' => 0]);
                    } else {
                        // Save this recipe.
                        $values = [
                            'collection_id' => 0,
                            'created' => date('Y-m-d H:i:s'),
                            'user_id' => SessionHelper::getUserId(),
                            'recipe_id' => $recipe['id'],
                            'deleted' => 0
                        ];

                        $this->saved_recipe_model->create($values);

                        $admin_stats_model = new AdminStatsModel();
                        $admin_stats_model->increment('saved-recipe', date('Y-m-d'));

                        // Add rating.
                        $ip_address = Core::get_client_ip();
                        $rating_model = new RatingModel();

                        // Can only vote once per IP address, per type and id.
                        $rating_record = $rating_model->getRecords([
                            'ip_address' => $ip_address,
                            'page_type'  => 'recipe',
                            'entity_id'  => $recipe['id']
                        ],[],[0,1]);

                        // Rated before?
                        if (is_array($rating_record) && !count($rating_record)) {
                            $recipe_model = new RecipeModel();
                            $data = [
                                'created'    => date('Y-m-d H:i:s'),
                                'page_type'  => 'recipe',
                                'entity_id'  => $recipe['id'],
                                'rating'     => 5,
                                'ip_address' => $ip_address ? $ip_address : 'unknown'
                            ];
                            $rating_model->create($data);

                            $admin_stats_model = new AdminStatsModel();
                            $admin_stats_model->increment('new-rating', date('Y-m-d'));

                            Core::invalidateCache('recipe/' . $recipe['slug']);

                            $total_rating = $rating_model->getTotalRating($recipe['id'], 'recipe');

                            // Cache counts for quick lookup.
                            $recipe_model->update($recipe['id'], [
                                'updated'        => date('Y-m-d H:i:s'),
                                'total_votes'    => $total_rating['total_votes'],
                                'average_rating' => $total_rating['average_rating'],
                                'rating_label'   => $total_rating['rating_label']
                            ]);

                            $admin_stats_model = new AdminStatsModel();
                            $admin_stats_model->increment('recipe_rating', 'new');
                        }
                    }
                    echo "true";
                    return true;
                }
            }
        }

        echo "not-logged-in";
        return false;
    }

    /**
     * Save recipe to own collection.
     *
     * @param string $slug
     */
    public function save_recipe(string $slug)
    {
        $this->loginCheck();

        if (!$recipe = $this->recipe_model->getBySlug($slug)) {
            $this->show404();
        }

        $default_collection = $this->collection_model->getDefaultCollection(SessionHelper::getUserId());

        $post_values = $_POST;

        if (is_array($post_values) && count($post_values)) {
            $values = [];

            if (
                (
                    array_key_exists('new_collection_name', $post_values) &&
                    strlen($post_values['new_collection_name'])
                ) &&
                array_key_exists('new_collection_description', $post_values)
            ) {
                // Create new collection.
                $collection_id = $this->collection_model->create([
                    'unique_id'     => uniqid(),
                    'user_id'       => SessionHelper::getUserId(),
                    'created'       => date('Y-m-d H:i:s'),
                    'updated'       => date('Y-m-d H:i:s'),
                    'name'          => trim($post_values['new_collection_name']),
                    'description'   => trim($post_values['new_collection_description']),
                    'public'        => 1
                ]);

                $admin_stats_model = new AdminStatsModel();
                $admin_stats_model->increment('collection', 'new');
                $admin_stats_model->increment('new-collection', date('Y-m-d'));

                $values['collection_id'] = $collection_id;
            } else {
                if ('favourites' === $post_values['collection_id']) {
                    $values['collection_id'] = $default_collection['id'];
                } else {
                    // Use existing collection.
                    $collection = $this->collection_model->get($post_values['collection_id'], 'unique_id');
                    $values['collection_id'] = $collection['id'];
                }
            }

            $values['created']       = date('Y-m-d H:i:s');
            $values['user_id']       = SessionHelper::getUserId();
            $values['recipe_id']     = $recipe['id'];

            // Has this recipe been saved before, but has been deleted?
            $saved_before = $this->saved_recipe_model->getRecords([
                'recipe_id'     => $recipe['id'],
                'collection_id' => $values['collection_id'],
                'user_id'       => $values['user_id']
            ]);

            $update_collection = false;
            if (
                is_array($saved_before) &&
                count($saved_before) &&
                array_key_exists(0, $saved_before)
            ) {
                if (1 === (int) $saved_before[0]['deleted']) {
                    // Restore.
                    $this->saved_recipe_model->update($saved_before[0]['id'], ['created' => date('Y-m-d H:i:s'), 'deleted' => 0]);
                    $update_collection = true;
                } else {
                    // Well.. do nothing really. This is to prevent recipes being saved more than once within the same collection.
                }
            } else {
                $this->saved_recipe_model->create($values);

                $admin_stats_model = new AdminStatsModel();
                $admin_stats_model->increment('saved-recipe', date('Y-m-d'));

                $update_collection = true;

                // Add rating.
                $ip_address = Core::get_client_ip();
                $rating_model = new RatingModel();

                // Can only vote once per IP address, per type and id.
                $rating_record = $rating_model->getRecords([
                    'ip_address' => $ip_address,
                    'page_type'  => 'recipe',
                    'entity_id'  => $recipe['id']
                ],[],[0,1]);

                // Rated before?
                if (is_array($rating_record) && !count($rating_record)) {
                    $recipe_model = new RecipeModel();
                    $data = [
                        'created'    => date('Y-m-d H:i:s'),
                        'page_type'  => 'recipe',
                        'entity_id'  => $recipe['id'],
                        'rating'     => 5,
                        'ip_address' => $ip_address ? $ip_address : 'unknown'
                    ];
                    $rating_model->create($data);

                    $admin_stats_model = new AdminStatsModel();
                    $admin_stats_model->increment('new-rating', date('Y-m-d'));

                    Core::invalidateCache('recipe/' . $recipe['slug']);

                    $total_rating = $rating_model->getTotalRating($recipe['id'], 'recipe');

                    // Cache counts for quick lookup.
                    $recipe_model->update($recipe['id'], [
                        'updated'        => date('Y-m-d H:i:s'),
                        'total_votes'    => $total_rating['total_votes'],
                        'average_rating' => $total_rating['average_rating'],
                        'rating_label'   => $total_rating['rating_label']
                    ]);

                    $admin_stats_model = new AdminStatsModel();
                    $admin_stats_model->increment('recipe_rating', 'new');
                }
            }

            if (0 !== $values['collection_id']) {
                $collection = $this->collection_model->get($values['collection_id']);

                if (true === $update_collection) {
                    $this->collection_model->updateCount($collection);
                    $this->collection_model->update($collection['id'],
                        [
                            'updated' => date('Y-m-d H:i:s')
                        ]);
                }
            } else {
                $collection = $default_collection;
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'This recipe has been saved to the collection "' . $collection['name'] . '".'];
            Core::redirect(Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])));
        }

        $collections = [$default_collection];
        foreach($this->collection_model->getRecords(['user_id' => SessionHelper::getUserId()], ['id', 'desc']) as $collection) {
            $collections[] = $collection;
        }

        $this->set([
            'meta_description' => 'Sla het recept ' . $recipe['title'] . ' op in een collectie naar keuze.',
            'page_title'       => $recipe['title'] . ' opslaan in een collectie',
            'page_canonical' => Core::url('community/save-recipe/' . $recipe['slug']),
            'recipe'      => $recipe,
            'collections' => $collections
        ]);

        $this->render('save_recipe');
    }

    /**
     * @param $unique_id
     */
    public function collectie_delete($unique_id)
    {
        $this->loginCheck();

        if ($unique_id === 'favourites') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => '<iframe width="320" height="230" src="https://www.youtube.com/embed/XMHt02mHKS0?autoplay=1" frameborder="0"></iframe>'];
            Core::redirect(Core::url('community/saved-recipes'));
        }

        $collections = $this->collection_model->getRecords([
            'unique_id' => $unique_id,
            'user_id'   => SessionHelper::getUserId()
        ]);

        $collection = false;
        if (is_array($collections) && array_key_exists(0, $collections)) {
            $collection = $collections[0];
        }

        if (!$collection) {
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'This collection cannot be found.'];
            Core::redirect(Core::url('community/saved-recipes'));
        }

        $this->collection_model->update($collection['id'], [
            'deleted' => 1
        ]);

        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'The collection "' . $collection['name'] . '" has been removed.'];
        Core::redirect(Core::url('community/saved-recipes'));
    }

    /**
     * @param $slug
     * @param $unique_id
     */
    public function delete_recipe($slug, $unique_id)
    {
        $this->loginCheck();

        $recipe = $this->recipe_model->getBySlug($slug);
        if ($unique_id === 'favourites') {
            $collection = $this->collection_model->getDefaultCollection(SessionHelper::getUserId());
        } else {
            $collection = $this->collection_model->get($unique_id, 'unique_id');
        }

        if (!$recipe || !$collection) {
            Core::redirect(Core::url('community/saved-recipes'));
        }

        $saved_recipe = $this->saved_recipe_model->getRecords([
            'collection_id' => $collection['id'],
            'user_id'       => SessionHelper::getUserId(),
            'recipe_id'     => $recipe['id']
        ]);

        if (is_array($saved_recipe) && count($saved_recipe) && array_key_exists(0, $saved_recipe)) {
            $this->saved_recipe_model->update($saved_recipe[0]['id'],[
                'deleted' => 1
            ]);
            $this->collection_model->updateCount($collection);
        }

        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'The recipe "' . $recipe['title'] . '" has been removed from the collectio "' . $collection['name'] . '".'];
        Core::redirect(Core::url('community/collection/' . $unique_id . '#' . CoreHelper::slugify($collection['name'])));
    }

    /**
     * Overview of saved recipes.
     */
    public function saved_recipes()
    {
        $this->loginCheck();

        $primary_collection = $this->collection_model->getDefaultCollection(SessionHelper::getUserId());

        $collections = $this->collection_model->getRecords(['user_id' => SessionHelper::getUserId()], ['created', 'desc']);

        array_unshift($collections, $primary_collection);

        $recipes = [];
        foreach ($collections as $collection) {
            $saved_recipes = $this->saved_recipe_model->getRecords([
                'collection_id' => $collection['id'],
                'user_id'       => SessionHelper::getUserId()
            ],
            ['created', 'desc'],
            [0,4]);

            foreach ($saved_recipes as $i => $saved_recipe) {
                $recipes[$collection['id']][$i] = $this->recipe_model->get($saved_recipe['recipe_id']);
            }
        }

        $this->set([
            'page_title'       => 'Saved recipes',
            'meta_description' => 'Look at your favorite recipes and collections',
            'page_canonical'   => Core::url('community/saved-recipes'),
            'recipes'          => $recipes,
            'collections'      => $collections
        ]);

        $this->render('saved_recipes');
    }

    /**
     * Detail of a collection
     *
     * @param string $unique_id
     * @param bool|int $user_id
     *
     */
    public function collection(string $unique_id, bool|int $user_id = false)
    {
        $default_url = Core::url('community/saved-recipes');

        if ('favourites' !== $unique_id) {
            if (!$collection = $this->collection_model->get($unique_id, 'unique_id')) {
                $this->show404();
            }
        } else {
            if (!$user_id) {
                $this->loginCheck();
            }
            $collection = $this->collection_model->getDefaultCollection($user_id?$user_id:SessionHelper::getUserId());
        }

        if (
            (int) $collection['deleted'] ||
            !(int) $collection['public']
        ) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'This collection is no longer available or has been hidden.'];
            Core::redirect($default_url);
        }

        $where = ['collection_id' => $collection['id'], 'user_id' => $collection['user_id']];
        $this->setTotalResults($this->saved_recipe_model->getRecords($where, [], [], true));

        $user = $this->user_model->get($collection['user_id']);

        if (!$user) {
            Core::redirect(Core::url('community/saved-recipes'));
        }

        if (
            !SessionHelper::isLoggedIn() &&
            is_array($user) && count($user)
        ) {
            $this->set([
                '_flash' => [
                    'type' => 'warning',
                    'message' => 'Would you like to save recipes in your own collections, just like ' . $user['first_name'] . '? <a href="' . Core::url('create-profile') . '">Create a profile</a> and start saving your favorite recipes!'
                ]
            ]);
        }

        $pageCanonical = Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name']));
        if ($user_id) {
            $pageCanonical = Core::url('community/collection/favourites/' . $user_id . '#' . CoreHelper::slugify($collection['name']));
        }

        $this->set([
            'page_title'       => 'Collection: ' . ucfirst($collection['name']),
            'meta_description' => 'Saved recipes from the ' . ucfirst($collection['name']) . ' collection',
            'page_canonical'   => $pageCanonical,
            'collection'       => $collection,
            'user'             => $user,
            'saved_recipes'    => $this->saved_recipe_model->getRecords($where, ['id','desc'], $this->getPagination()),
            'pagination'       => $this->getPagination()
        ]);

        $this->render('collection');
    }

    /**
     * User profile
     */
    public function mijn_profiel()
    {
        $this->loginCheck();

        if (!$user = $this->user_model->get(SessionHelper::getUserId())) {
            $this->show404();
        }

        $this->set([
            'page_title'       => 'Mijn profiel',
            'meta_description' => 'Stel je voorkeuren in',
            'user'             => $user,
            'page_canonical'   => Core::url('community/mijn-profiel'),
        ]);

        $this->render('profile');
    }

    /**
     * User weekly menu overview.
     */
    public function week_menu_plan()
    {
        $this->loginCheck();

        $this->set([
            'page_title'       => 'Week menu plan',
            'meta_description' => 'Compose your week menu plan or look at your previous menus',
            'page_canonical'   => Core::url('community/week-menu-plan'),
        ]);

        $this->render('weekly_menu');
    }
}