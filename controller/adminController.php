<?php

/**
 * Class adminController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class adminController extends Controller
{
    var $redirect_model;
    var $blog_model;
    var $comment_model;
    var $page_model;
    var $not_found_model;
    var $user_model;
    var $question_model;
    var $category_model;
    var $tag_model;
    var $recipe_tag_model;
    var $collection_model;
    var $contact_model;
    var $saved_recipe_model;
    var $rating_model;
    var $blacklist_model;
    var $search_model;

    /**
     * adminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loginCheck();
        if (!$this->hasPermission('is_admin')) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => '<iframe width="320" height="230" src="https://www.youtube.com/embed/XMHt02mHKS0?autoplay=1" frameborder="0"></iframe>'];
            Core::redirect(Core::url('/'));
        }
    }

    /**
     * Default admin dashboard.
     */
    public function index()
    {
        $search_model = new SearchModel();
        $saved_recipe_model = new SavedRecipeModel();

        $this->setTotalResults($search_model->getRecords([], [], [], true));
        $latest_search_terms = $search_model->getRecords([],$this->getSortOrder('id', 'desc'),[0, $this->getResultsPerPage()]);

        $latest_saved_recipes = $saved_recipe_model->getRecords(['deleted' => 0],['id','desc'], [0,$this->getResultsPerPage()]);

        $this->set([
            'page_title'           => 'Admin | dashboard',
            'latest_search_terms'  => $latest_search_terms,
            'latest_saved_recipes' => $latest_saved_recipes
        ]);

        $this->render('index');
    }

    /**
     * Overview of recently saved recipes.
     */
    public function saved_recipe_index()
    {
        $where = [];

        $saved_recipe_model = new SavedRecipeModel();

        $this->setTotalResults($saved_recipe_model->getRecords($where, [], [], true));
        $latest_saved_recipes = $saved_recipe_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination());

        $this->set([
            'page_title'           => 'Admin | Recently saved recipes',
            'pagination'           => $this->getPagination(),
            'latest_saved_recipes' => $latest_saved_recipes
        ]);

        $this->render('saved_recipe_index');
    }

    /**
     * Overview of redirects.
     */
    public function redirect_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (array_key_exists('request_url', $query) && strlen($query['request_url'])) {
            $where['request_url'] = '%' . trim($query['request_url']) . '%';
        }
        if (array_key_exists('destination_url', $query) && strlen($query['destination_url'])) {
            $where['destination_url'] = '%' . trim($query['destination_url']) . '%';
        }

        $redirect_model = new RedirectModel();

        $this->setTotalResults($redirect_model->getRecords($where, [], [], true));
        $redirects = $redirect_model->getRecords($where, $this->getSortOrder('id','asc'), $this->getPagination());

        $this->set([
            'page_title' => 'Admin | Redirects',
            'pagination' => $this->getPagination(),
            'redirects'  => $redirects,
            'query'      => $query
        ]);

        $this->render('redirect_index');
    }

    /**
     * Edit redirect.
     *
     * @param $id
     */
    public function redirect_edit($id)
    {
        $post_values = $_POST;

        $redirect_model = new RedirectModel();

        if ('new' === $id) {
            $redirect = $redirect_model->get(1);

            foreach ($redirect as $field => $value) {
                $redirect[$field] = '';
            }
            $redirect['id'] = $id;
        } else {
            $redirect = $redirect_model->get($id);
        }

        if (is_array($post_values) && count($post_values)) {
            $values = [
                'request_url' => trim($post_values['request_url']),
                'destination_url' => trim($post_values['destination_url'])
            ];

            if ('new' === $id) {
                // Check if redirect exists.
                $redirect_check = $redirect_model->getRecords(['request_url' => $values['request_url']]);
                if (is_array($redirect_check) && count($redirect_check)) {
                    // Redirect already exists.
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Redirect regel bestaat al (' . $values['request_url'] . ')'];
                    Core::redirect(Core::url('admin/redirect_edit/' . $id));
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Redirect regel is aangemaakt'];
                $id = $redirect_model->create($values);
            } else {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Redirect regel is bijgewerkt'];
                $redirect_model->update($redirect['id'], $values);
            }

            Core::redirect(Core::url('admin/redirect_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit redirect',
            'redirect'   => $redirect
        ]);

        $this->render('redirect_edit');
    }

    /**
     * Overview of recent searches
     */
    public function search_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('term', $query) && strlen($query['term'])) {
                $where['term'] = '%' . trim($query['term']) . '%';
            }
            if (array_key_exists('total_results', $query) && strlen($query['total_results'])) {
                $where['total_results'] = (int) $query['total_results'];
            }
            if (array_key_exists('autosuggest', $query) && strlen($query['autosuggest'])) {
                $where['autosuggest'] = (int) $query['autosuggest'];
            }
            if (array_key_exists('suggestion', $query) && strlen($query['suggestion'])) {
                $where['suggestion'] = (int) $query['suggestion'];
            }
        }

        $search_model = new SearchModel();

        $this->setTotalResults($search_model->getRecords($where, [], [], true));
        $latest_search_terms = $search_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination());

        $this->set([
            'page_title'           => 'Admin | Meest recente zoekopdrachten',
            'pagination'           => $this->getPagination(),
            'latest_search_terms'  => $latest_search_terms,
            'query'                => $query
        ]);

        $this->render('search_index');
    }

    public function recipe_rating_index()
    {
        $where = [
            'page_type' => 'recipe'
        ];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('ip_address', $query)) {
                $where['ip_address'] = '%' . trim($query['ip_address']) . '%';
            }
            if (array_key_exists('rating', $query) && strlen($query['rating'])) {
                $where['rating'] = $query['rating'];
            }
            if (array_key_exists('original_rating', $query) && strlen($query['original_rating'])) {
                $where['original_rating'] = $query['original_rating'];
            }
        }

        $this->admin_stats_model->clear('recipe_rating', 'new');

        $this->setTotalResults($this->rating_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Recipe votes',
            'ratings'    => $this->rating_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('recipe_rating_index');
    }

    /**
     * Overview of recipes.
     */
    public function recipe_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('update-counts', $query)) {
                $this->recipe_model->updateCounts();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Counts have been updated'];
                Core::redirect(Core::url('admin/recipe_index'));
            }
        }

        if (is_array($query) && count($query)) {
            if (array_key_exists('title', $query) && strlen($query['title'])) {
                $where['title'] = '%' . trim($query['title']) . '%';
            }
            if (array_key_exists('id', $query) && strlen($query['id'])) {
                $where['id'] = (int) trim($query['id']);
            }
            if (array_key_exists('status', $query)) {
                $where['status'] = $query['status'];
            }
            if (array_key_exists('rating', $query) && strlen($query['rating'])) {
                $where['average_rating'] = $query['rating'];
            }
        }

        $this->setTotalResults($this->recipe_model->getRecords($where,[],[],true));

        $sort_by = $this->getSortOrder('published', 'desc');
        if (array_key_exists('status', $where) && $where['status'] === 'draft') {
            $sort_by = $this->getSortOrder('updated', 'desc');
        }	

        $this->set([
            'page_title' => 'Admin | Recipe overview',
            'recipes'    => $this->recipe_model->getRecords($where, $sort_by, $this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('recipe_index');
    }

    /**
     * Edit a recipe.
     *
     * @param $id
     */
    public function recipe_edit($id)
    {
        if ('new' === $id) {
            $recipe = $this->recipe_model->get(1);
            foreach ($recipe as $field => $value) {
                $recipe[$field] = '';
            }
            $recipe['id'] = $id;
        } else {
            $recipe = $this->recipe_model->get($id);
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            $recipe_tags = [];
            if (array_key_exists('recipe_tags', $post_values)) {
                $recipe_tags = $post_values['recipe_tags'];
                unset($post_values['recipe_tags']);
            }

            $content = $post_values['content'];
            $content = str_replace('<img src="', '<img class="img-fluid mx-auto d-block" src="', $content);

            $values = [
                'updated' => date('Y-m-d H:i:s'),
                'title' => $post_values['title'],
                'ingredients' => $post_values['ingredients'],
                'kitchen_equipment' => $post_values['kitchen_equipment'],
                'content' => $content,
                'excerpt' => $post_values['excerpt'],
                'featured_image' => $post_values['featured_image'],
                'thumbnail_image' => $post_values['thumbnail_image'],
                'ingredients_image' => $post_values['ingredients_image'],
                'preptime' => $post_values['preptime'],
                'cooktime' => $post_values['cooktime'],
                'totaltime' => $post_values['totaltime'],
                'status' => $post_values['status'],
                'displaytime' => strlen($post_values['displaytime'])?$post_values['displaytime']:0,
                'yield' => strlen($post_values['yield'])?$post_values['yield']:0,
                'calories' => strlen($post_values['calories'])?$post_values['calories']:0,
                'faq' => $post_values['faq'],
                'dutch_url' => $post_values['dutch_url']
            ];

            if ($values['status'] === 'publish') {
                $values['published'] = $post_values['published'];
            }

            $multiple_selects = ['categories', 'cuisines', 'dishtypes'];
            foreach ($multiple_selects as $multiple_select) {
                if (
                    array_key_exists($multiple_select, $post_values) &&
                    is_array($post_values[$multiple_select]) &&
                    count($post_values[$multiple_select])
                ) {
                    $values[$multiple_select] = implode(',', $post_values[$multiple_select]);
                }
            }

            $values['slug'] = CoreHelper::slugify($post_values['title']);
            if (
                strlen($recipe['slug']) &&
                $recipe['slug'] !== $values['slug']
            ) {
                $values['old_slug'] = $recipe['slug'];
            }

            if ('new' === $id) {
                $values['created'] = date('Y-m-d H:i:s');
                $values['status'] = 'draft';

                if (!$recipe['slug']) {
                    $values['slug'] = CoreHelper::slugify($post_values['title']);
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Recipe draft has been made'];
                $id = $this->recipe_model->create($values);
            } else {
                if (
                    $post_values['status'] === 'publish' &&
                    (
                        is_null($recipe['published']) ||
                        !strlen($recipe['published'])
                    )
                ) {
                    $values['published'] = date('Y-m-d H:i:s');
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Recipe has been updated'];

                $this->recipe_model->update($id, $values);

                $multiple_selects = ['categories', 'cuisines', 'dishtypes'];
                foreach ($multiple_selects as $multiple_select) {
                    if (
                        array_key_exists($multiple_select, $post_values) &&
                        is_array($post_values[$multiple_select]) &&
                        count($post_values[$multiple_select])
                    ) {
                        foreach ($post_values[$multiple_select] as $ms) {
                            $this->category_model->updateCount([
                                'id' => (int) $ms // @todo: extend with other attributes. @see: CategoryModel:updateCount()
                            ]);
                        }
                    }
                }

                if ($post_values['status'] === 'publish') {
                    $urls = [
                        'recipe/' . $recipe['slug'],
                        '/',
                        'recipe-index',
                        'latest-recipes'
                    ];

                    foreach ($multiple_selects as $multiple_select) {
                        $categories = $this->category_model->getRecords(['in' => ['id' => $recipe[$multiple_select]]]);
                        if (is_array($categories) && count($categories)) {
                            foreach ($categories as $category) {
                                $urls[] = $category['type'] . '/' . $category['slug'];
                            }
                        }
                    }

                    foreach ($urls as $uri) {
                        Core::invalidateCache($uri);
                    }
                }

                $recipe = $this->recipe_model->get($id); // Refetch
                $recipe_helper = new RecipeHelper();
                $recipe_helper->updateSEOLists($recipe);
            }

            // Update tags.
            $tag_model = new TagModel();
            $exploded_tags = explode(',', $recipe_tags);
            array_walk($exploded_tags, function (&$x) {
                return $x = CoreHelper::slugify(str_replace("'", "", "$x"));
            });
            $rt = "'" . implode("','",$exploded_tags) . "'";
            $exploded_tags = array_flip($exploded_tags);
            $tags = $tag_model->getRecords([
                'in' => [
                    'slug' => $rt
                ]
            ]);

            $save_these_tags = [];
            if (is_array($tags) && count($tags)) {
                foreach ($tags as $tag) {
                    $save_these_tags[] = (int) $tag['id'];

                    if (array_key_exists($tag['slug'], $exploded_tags)) {
                        unset($exploded_tags[$tag['slug']]);
                    }

                    Core::invalidateCache('tag/' . $tag['slug']);
                }
            }

            // Try again, but this time with slugified values.
            if (is_array($exploded_tags) && count($exploded_tags)) {
                foreach ($exploded_tags as $index => $exploded_tag) {
                    $exploded_tags[key($exploded_tags)] = CoreHelper::slugify($index);
//                    unset($exploded_tags[key($exploded_tags)]);
//                    $exploded_tags[CoreHelper::slugify($index)] = $exploded_tag;
                }
                $exploded_tags = array_flip($exploded_tags);
                $rt = "'" . implode("','", $exploded_tags) . "'";
                $tags = $tag_model->getRecords([
                    'in' => [
                        'slug' => $rt
                    ]
                ]);

                if (is_array($tags) && count($tags)) {
                    foreach ($tags as $tag) {
                        $save_these_tags[] = (int) $tag['id'];

                        if (array_key_exists($tag['slug'], $exploded_tags)) {
                            unset($exploded_tags[$tag['slug']]);
                        }

                        Core::invalidateCache('tag/' . $tag['slug']);
                    }
                }
            }

            if (is_array($exploded_tags) && count($exploded_tags)) {
                // Some keys are remaining. These will be new tags.
                foreach ($exploded_tags as $key => $exploded_tag) {
                    if ($tag_id = $tag_model->create([
                        'name' => $exploded_tag,
                        'slug' => CoreHelper::slugify($exploded_tag)
                    ])) {
                        $save_these_tags[] = $tag_id;
                    }
                }
            }

            if (is_array($save_these_tags) && count($save_these_tags)) {
                $recipe_tag_model = new RecipeTagModel();
                $recipe_tag_model->purge($id);

                foreach ($save_these_tags as $save_this_tag) {
                    $recipe_tag_model->create([
                        'recipe_id' => $id,
                        'tag_id'    => $save_this_tag
                    ]);
                }
            }

            Core::redirect(Core::url('admin/recipe_edit/' . $id));
        }

        $recipe_tag_model = new RecipeTagModel();
        $recipe_tags = $recipe_tag_model->getRecords([
            'recipe_id' => (int) $recipe['id']
        ],[
            'recipe_id', 'asc'
        ]);

        $tag_ids = [];
        if (is_array($recipe_tags) && count($recipe_tags)) {
            foreach ($recipe_tags as $recipe_tag) {
                $tag_ids[] = $recipe_tag['tag_id'];
            }
        }

        $recipe_tags = [];
        $recipe_tags_slugs = [];
        if (is_array($tag_ids) && count($tag_ids)) {
            $tag_model = new TagModel();
            $tags = $tag_model->getRecords([
                'in' => [
                    'id' => implode(',', $tag_ids)
                ]
            ]);

            if (is_array($tags) && count($tags)) {
                foreach ($tags as $tag) {
                    $recipe_tags_slugs[] = $tag['slug'];
                    $recipe_tags[] = [
                        'tag'   => str_replace('\'', '', $tag['name']),
                        'value' => $tag['slug']
                    ];
                }
            }
        }

        $recipe_tags = json_encode($recipe_tags);
        $recipe_tags_slugs = implode(',', $recipe_tags_slugs);

        $page_title = 'Admin | ';

        if ('new' === $id) {
            $page_title .= 'New recipe';
        } else {
            $page_title .= $recipe['title'];
        }

        $this->set([
            'page_title'  => $page_title,
            'recipe'      => $recipe,
            'categories'  => $this->category_model->getRecords(['in' => ['type' => '"course","theme"']],['title','asc']),
            'cuisines'    => $this->category_model->getRecords(['type' => 'kitchen'],['title','asc']),
            'dishtypes'   => $this->category_model->getRecords(['type' => 'dishtype'],['title','asc']),
            'recipe_tags' => $recipe_tags,
            'recipe_tags_slugs' => $recipe_tags_slugs
        ]);

        $this->render('recipe_edit');
    }

    /**
     * Overview of blogs.
     */
    public function blog_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('update-counts', $query)) {
                $this->blog_model->updateCounts();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Counts have been updated.'];
                Core::redirect(Core::url('admin/blog_index'));
            }
        }

        if (is_array($query) && count($query)) {
            if (array_key_exists('title', $query)) {
                $where['title'] = '%' . trim($query['title']) . '%';
            }
            if (array_key_exists('id', $query)) {
                $where['id'] = (int) trim($query['id']);
            }
            if (array_key_exists('status', $query)) {
                $where['status'] = $query['status'];
            }
        }

        $this->setTotalResults($this->blog_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Blogs',
            'blogs'      => $this->blog_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);
        $this->render('blog_index');
    }

    /**
     * Edit a blog.
     *
     * @param $id
     */
    public function blog_edit($id)
    {
        if ('new' === $id) {
            $blog = $this->blog_model->get(1);
            foreach ($blog as $field => $value) {
                $blog[$field] = '';
            }
            $blog['id'] = $id;
        } else {
            $blog = $this->blog_model->get($id);
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {

            $content = $post_values['content'];
            $content = str_replace('<img src="', '<img class="img-fluid mx-auto d-block" src="', $content);

            $values = [
                'updated' => date('Y-m-d H:i:s'),
                'title' => $post_values['title'],
                'content' => $content,
                'excerpt' => $post_values['excerpt'],
                'featured_image' => $post_values['featured_image'],
                'thumbnail_image' => $post_values['thumbnail_image'],
                'status' => $post_values['status'],
                'faq' => $post_values['faq']
            ];

            if ($values['status'] === 'publish') {
                $values['published'] = $post_values['published'];
            }

            $multiple_selects = ['categories'];
            foreach ($multiple_selects as $multiple_select) {
                if (
                    array_key_exists($multiple_select, $post_values) &&
                    is_array($post_values[$multiple_select]) &&
                    count($post_values[$multiple_select])
                ) {
                    $values[$multiple_select] = implode(',', $post_values[$multiple_select]);
                }
            }

            $values['slug'] = CoreHelper::slugify($post_values['title']);
            if (
                strlen($blog['slug']) &&
                $blog['slug'] !== $values['slug']
            ) {
                $values['old_slug'] = $blog['slug'];
            }

            if ('new' === $id) {
                $values['created'] = date('Y-m-d H:i:s');
                $values['status'] = 'draft';

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Blog concept is aangemaakt'];
                $id = $this->blog_model->create($values);
            } else {
                if (
                    $post_values['status'] === 'publish' &&
                    (
                        is_null($blog['published']) ||
                        !strlen($blog['published'])
                    )
                ) {
                    $values['published'] = date('Y-m-d H:i:s');
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Blog is bijgewerkt'];
                $this->blog_model->update($id, $values);

                $urls = [
                    'blog/' . $blog['slug'],
                    'blog'
                ];

                foreach ($urls as $url) {
                    Core::invalidateCache($url);
                }
            }
            Core::redirect(Core::url('admin/blog_edit/' . $id));
        }

        $this->set([
            'page_title'  => 'Admin | Edit blog',
            'blog'        => $blog,
            'categories'  => $this->category_model->getRecords(['in' => ['type' => '"blog"']],['title','asc']),
        ]);
        $this->render('blog_edit');
    }

    /**
     * Overview of comments.
     */
    public function comment_index()
    {
        $where = [
            'spam' => 0
        ];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('respond', $query)) {
                Core::redirect(Core::url('admin/comment_edit/' . $query['respond']));
            }

            if (array_key_exists('approve', $query)) {
                $approve_id = (int) $query['approve'];
                $this->comment_model->update($approve_id, [
                    'status' => 'approved'
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bericht is goedgekeurd'];
                Core::redirect(Core::url('admin/comment_index'));
            }

            if (array_key_exists('tag-spam', $query)) {
                $spam_id = (int) $query['tag-spam'];
                $this->comment_model->update($spam_id, [
                    'spam' => 1
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bericht is gemarkeerd als spam'];
                Core::redirect(Core::url('admin/comment_index'));
            }

            if (array_key_exists('not-spam', $query)) {
                $not_spam_id = (int) $query['not-spam'];
                $this->comment_model->update($not_spam_id, [
                    'spam' => 0
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bericht is gemarkeerd als normaal bericht'];
                Core::redirect(Core::url('admin/comment_index?spam=1'));
            }

            if (array_key_exists('delete-spam', $query)) {
                $this->comment_model->deleteSpam();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Spam is no more.'];
                Core::redirect(Core::url('admin/comment_index?spam=1'));
            }

            if (array_key_exists('id', $query) && strlen($query['id'])) {
                $where['id'] = (int) $query['id'];
            }
            if (array_key_exists('spam', $query) && strlen($query['spam'])) {
                $where['spam'] = (int) $query['spam'];
            }
            if (array_key_exists('status', $query) && strlen($query['status'])) {
                $where['status'] = trim($query['status']);
            }
        }

        $this->setTotalResults($this->comment_model->getRecords($where,[],[],true));

        $this->admin_stats_model->clear('comment', 'new');

        $this->set([
            'page_title' => 'Admin | Comments',
            'comments'   => $this->comment_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);
        $this->render('comment_index');
    }

    /**
     * Edit comment.
     *
     * @param $id
     */
    public function comment_edit($id)
    {
        $comment = $this->comment_model->get($id);

        $recipe = false;
        $blog   = false;
        $url    = '';

        switch ($comment['page_type']) {
            case 'recipe':
                $recipe = $this->recipe_model->get($comment['entity_id']);
                $url = Core::url('recept/' . $recipe['slug']);
                break;
            case 'blog':
                $blog = $this->blog_model->get($comment['entity_id']);
                $url = Core::url('blog/' . $blog['slug']);
                break;
            default:
                die($comment['page_type'] . ' is unknown');
                break;
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            if (array_key_exists('content', $post_values)) {
                $id = $this->comment_model->create([
                    'created'   => date('Y-m-d H:i:s'),
                    'entity_id' => $comment['entity_id'],
                    'page_type' => $comment['page_type'],
                    'content'   => trim($post_values['content']),
                    'author'    => 'Ohmydish',
                    'email'     => 'info@ohmydish.com',
                    'status'    => 'approved',
                    'spam'      => 0,
                    'parent_id' => $id,
                    'wordpress_post_id' => 0
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Comment saved'];
                Core::redirect(Core::url('admin/comment_index'));
            }
        }

        $this->set([
            'page_title' => 'Admin | Comment',
            'comment'    => $comment,
            'url'        => $url,
            'recipe'     => $recipe,
            'blog'       => $blog
        ]);
        $this->render('comment_edit');
    }

    /**
     * Overview of pages.
     */
    public function page_index()
    {
        $where = [];

        $query = $this->request->getQuery();

        if (is_array($query) && count($query)) {
            if (array_key_exists('title', $query)) {
                $where['title'] = '%' . trim($query['title']) . '%';
            }
            if (array_key_exists('id', $query)) {
                $where['id'] = (int) trim($query['id']);
            }
            if (array_key_exists('status', $query)) {
                $where['status'] = $query['status'];
            }
        }

        $this->setTotalResults($this->page_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Pagina\'s',
            'pages'      => $this->page_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);
        $this->render('page_index');
    }

    /**
     * Edit page.
     *
     * @param $id
     */
    public function page_edit($id)
    {
        $page = $this->page_model->get($id);

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            $content = $post_values['content'];
            $content = str_replace('<img src="', '<img class="img-fluid mx-auto d-block" src="', $content);

            $values = [
                'updated' => date('Y-m-d H:i:s'),
                'title' => $post_values['title'],
                'content' => $content,
                'excerpt' => $post_values['excerpt'],
                'featured_image' => $post_values['featured_image'],
                'thumbnail_image' => $post_values['thumbnail_image'],
                'status' => $post_values['status'],
            ];

            if ($values['status'] === 'publish') {
                $values['published'] = $post_values['published'];
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Page has been updated'];

            $this->page_model->update($id, $values);

            Core::invalidateCache($page['slug']);

            Core::redirect(Core::url('admin/page_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit page',
            'page'       => $page
        ]);
        $this->render('page_edit');
    }

    /**
     * 404 not found pages.
     */
    public function not_found_index()
    {
        $this->setTotalResults($this->not_found_model->getRecords([],[],[],true));

        $this->set([
            'page_title' => 'Admin | Niet gevonden URLs',
            'not_founds' => $this->not_found_model->getRecords([],$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination()
        ]);
        $this->render('not_found_index');
    }

    /**
     * 404 not found pages.
     *
     * @param int $int
     */
    public function not_found_detail($id)
    {
        $this->set([
            'page_title' => 'Admin | Niet gevonden URL detail',
            'not_found'  => $this->not_found_model->get($id)
        ]);

        $this->render('not_found_detail');
    }

    /**
     * @param string $key
     * @param string $order
     * @return string[]
     */
    public function getSortOrder(string $key, string $order): array
    {
        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('sort', $query)) {
                $key = trim($query['sort']);
            }

            if (array_key_exists('order', $query)) {
                $order = trim($query['order']);
            }
        }

        return [$key, $order];
    }

    /**
     * Overview of users.
     */
    public function user_index()
    {
        $where = [
            'is_admin' => 0
        ];

        $query = $this->request->getQuery();

        if (is_array($query) && count($query)) {
            if (array_key_exists('email', $query)) {
                $where['email'] = '%' . trim($query['email']) . '%';
            }
        }

        $this->setTotalResults($this->user_model->getRecords($where,[],[],true));

        $this->admin_stats_model->clear('user', 'new');

        $this->set([
            'page_title' => 'Admin | Gebruikers',
            'users'      => $this->user_model->getRecords($where, $this->getSortOrder('id', 'desc'), $this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('user_index');
    }

    /**
     * Overview of questions.
     */
    public function question_index()
    {
        $where = [];

        $query = $this->request->getQuery();

        if (is_array($query) && count($query)) {
            if (array_key_exists('title', $query)) {
                $where['title'] = '%' . trim($query['title']) . '%';
            }
            if (array_key_exists('status', $query)) {
                $where['status'] = $query['status'];
            }
        }

        $this->setTotalResults($this->question_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Vragen',
            'questions'  => $this->question_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('question_index');
    }

    /**
     * Edit a question.
     *
     * @param $id
     */
    public function question_edit($id)
    {
        $post_values = $_POST;

        if ('new' === $id) {
            $question = $this->question_model->get(1);
            foreach ($question as $field => $value) {
                $question[$field] = '';
            }
            $question['id'] = $id;
        } else {
            $question = $this->question_model->get($id);
        }

        if (is_array($post_values) && count($post_values)) {
            $values = [
                'title' => $post_values['title'],
                'featured_image' => $post_values['featured_image'],
                'thumbnail_image' => $post_values['thumbnail_image'],
                'excerpt' => $post_values['excerpt'],
                'status' => $post_values['status'],
            ];

            if ($values['status'] === 'publish') {
                $values['published'] = $post_values['published'];
            }

            if ('new' === $id) {
                $values['created'] = date('Y-m-d H:i:s');
                $values['status'] = 'draft';

                if (!$question['slug']) {
                    $values['slug'] = CoreHelper::slugify($post_values['title']);
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Question concept has been created'];
                $id = $this->question_model->create($values);
            } else {
                if (
                    $post_values['status'] === 'publish' &&
                    (
                        is_null($question['published']) ||
                        !strlen($question['published'])
                    )
                ) {
                    $values['published'] = date('Y-m-d H:i:s');
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Question has been updated'];

                $content = $post_values['content'];
                $content = str_replace('<img src="', '<img class="img-fluid mx-auto d-block" src="', $content);
                $values['content'] = $content;

                $this->question_model->update($id, $values);

                Core::invalidateCache('question/' . $question['slug']);
            }

            Core::redirect(Core::url('admin/question_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit question',
            'question'   => $question
        ]);

        $this->render('question_edit');
    }

    /**
     * Overview of blacklisted IP addresses.
     */
    public function blacklist_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (array_key_exists('ip_address', $query) && strlen($query['ip_address'])) {
            $where['ip_address'] = '%' . trim($query['ip_address']) . '%';
        }
        if (array_key_exists('reason', $query) && strlen($query['reason'])) {
            $where['reason'] = '%' . trim($query['reason']) . '%';
        }

        $blacklist_model = new BlacklistModel();

        $this->setTotalResults($blacklist_model->getRecords($where, [], [], true));
        $blacklist = $blacklist_model->getRecords($where, $this->getSortOrder('id', 'desc'), $this->getPagination());

        $this->set([
            'page_title' => 'Admin | Blacklist',
            'pagination' => $this->getPagination(),
            'blacklist'  => $blacklist,
            'query'      => $query
        ]);

        $this->render('blacklist_index');
    }

    /**
     * Edit blacklist item.
     *
     * @param $id
     */
    public function blacklist_edit($id)
    {
        $post_values = $_POST;

        $blacklist_model = new BlacklistModel();

        if ('new' === $id) {
            $blacklist_item = $blacklist_model->get(1);

            foreach ($blacklist_item as $field => $value) {
                $blacklist_item[$field] = '';
            }
            $blacklist_item['id'] = $id;
        } else {
            $blacklist_item = $blacklist_model->get($id);
        }

        if (is_array($post_values) && count($post_values)) {
            $values = [
                'ip_address' => trim($post_values['ip_address']),
                'reason' => trim($post_values['reason'])
            ];

            if ('new' === $id) {
                // Check if blacklis item exists.
                $blacklist_check = $blacklist_model->getRecords(['ip_address' => $values['ip_address']]);
                if (is_array($blacklist_check) && count($blacklist_check)) {
                    // Blacklist item already exists.
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Blacklist item bestaat al (' . $values['ip_address'] . ')'];
                    Core::redirect(Core::url('admin/blacklist_edit/' . $id));
                }

                $values['created'] = date('Y-m-d H:i:s');

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Blacklist item is aangemaakt'];
                $id = $blacklist_model->create($values);
            } else {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Blacklist item is bijgewerkt'];
                $blacklist_model->update($blacklist_item['id'], $values);
            }

            Core::redirect(Core::url('admin/blacklist_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit blacklist item',
            'blacklist_item'   => $blacklist_item
        ]);

        $this->render('blacklist_edit');
    }

    /**
     * Overview of categories.
     */
    public function category_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('update-counts', $query)) {
                $this->category_model->updateCounts();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Aantallen zijn bijgewerkt'];
                Core::redirect(Core::url('admin/category_index'));
            }
        }

        if (is_array($query) && count($query)) {
            if (array_key_exists('title', $query)) {
                $where['title'] = '%' . trim($query['title']) . '%';
            }
        }

        $this->setTotalResults($this->category_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Categorieën',
            'categories' => $this->category_model->getRecords($where,$this->getSortOrder('id', 'asc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('category_index');
    }

    /**
     * Edit a category.
     *
     * @param $id
     */
    public function category_edit($id)
    {
        if ('new' === $id) {
            $category = $this->category_model->get(1);
            foreach ($category as $field => $value) {
                $category[$field] = '';
            }
            $category['id'] = $id;
        } else {
            $category = $this->category_model->get($id);
        }

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            $values = [
                'title'          => $post_values['title'],
                'seo_title'      => $post_values['seo_title'],
                'featured_image' => $post_values['featured_image'],
                'content'        => $post_values['content'],
                'type'           => $post_values['type'],
            ];

            if (!$category['slug'] || !strlen($category['slug'])) {
                $values['slug'] = CoreHelper::slugify($post_values['title']);
            }

            if ('new' === $id) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Categorie is aangemaakt'];
                $id = $this->category_model->create($values);
            } else {
                $this->category_model->update($id, $values);

                Core::invalidateCache($category['type'] . '/' . $category['slug']);
            }


            Core::redirect(Core::url('admin/category_edit/' . $id));
        }

        $this->set([
            'page_title'   => 'Admin | Edit category',
            'category'     => $category
        ]);

        $this->render('category_edit');
    }

    /**
     * Overview of tags.
     */
    public function tag_index()
    {
        $where = [];

        $query = $this->request->getQuery();

        if (is_array($query) && count($query)) {
            if (array_key_exists('name', $query)) {
                $where['name'] = '%' . trim($query['name']) . '%';
            }
        }

        $this->setTotalResults($this->tag_model->getRecords($where,[],[],true));

        $this->set([
            'page_title' => 'Admin | Tags',
            'tags'       => $this->tag_model->getRecords($where,$this->getSortOrder('name', 'asc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('tag_index');
    }

    /**
     * Edit a tag.
     *
     * @param $id
     */
    public function tag_edit($id)
    {
        $tag = $this->tag_model->get($id);

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            $this->tag_model->update($id,
                [
                    'name' => $post_values['name'],
                    'description' => $post_values['description'],
                    // @todo: slug
                ]);

            Core::invalidateCache('tag/' . $tag['slug']);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tag has been saved'];
            Core::redirect(Core::url('admin/tag_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit tag',
            'tag'     => $tag
        ]);

        $this->render('tag_edit');
    }

    /**
     * Overview of collections.
     */
    public function collection_index()
    {
        $where = [];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('name', $query)) {
                $where['name'] = '%' . trim($query['name']) . '%';
            }
        }

        $this->setTotalResults($this->collection_model->getRecords($where,[],[],true));

        $this->admin_stats_model->clear('collection', 'new');

        $collections = $this->collection_model->getRecords($where,$this->getSortOrder('updated', 'desc'),$this->getPagination());

        $user_ids = [];
        $users = [];
        foreach ($collections as $collection) {
            if (!in_array($collection['user_id'], $user_ids)) {
                $user_ids[] = $collection['user_id'];
            }
        }

        if (is_array($user_ids) && count($user_ids)) {
            foreach ($this->user_model->getRecords(['in' => ['id' => implode(',', $user_ids)]]) as $user) {
                $users[$user['id']] = $user;
            }
        }

        $this->set([
            'page_title'  => 'Admin | Collecties',
            'collections' => $collections,
            'pagination'  => $this->getPagination(),
            'users'       => $users,
            'query'       => $query
        ]);

        $this->render('collection_index');
    }

    /**
     * Edit a collection.
     *
     * @param $id
     */
    public function collection_edit($id)
    {
        $collection = $this->collection_model->get($id);

        $post_values = $_POST;
        if (is_array($post_values) && count($post_values)) {
            $this->collection_model->update($id,
                [
                    'name' => $post_values['name'],
                    'description' => $post_values['description']
                ]);

            Core::invalidateCache('community/collection/' . $collection['unique_id']);

            Core::redirect(Core::url('admin/collection_edit/' . $id));
        }

        $this->set([
            'page_title' => 'Admin | Edit collection',
            'collection' => $collection
        ]);

        $this->render('collection_edit');
    }

    /**
     * Overview of contact submissions.
     */
    public function contact_index()
    {
        $where = [
            'spam' => 0
        ];

        $query = $this->request->getQuery();
        if (is_array($query) && count($query)) {
            if (array_key_exists('delete-spam', $query)) {
                $this->contact_model->deleteSpam();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Spam is no more.'];
                Core::redirect(Core::url('admin/contact_index?spam=1'));
            }

            if (array_key_exists('spam', $query) && strlen($query['spam'])) {
                $where['spam'] = (int) $query['spam'];
            }
        }

        $this->setTotalResults($this->contact_model->getRecords($where,[],[],true));

        $this->admin_stats_model->clear('feedback', 'new');

        $this->set([
            'page_title' => 'Admin | Contactaanvragen',
            'contacts'   => $this->contact_model->getRecords($where,$this->getSortOrder('id', 'desc'),$this->getPagination()),
            'pagination' => $this->getPagination(),
            'query'      => $query
        ]);

        $this->render('contact_index');
    }

    public function sure(string $model, $id, $sure = false)
    {
        if (true === $sure || (!is_bool($sure) && strlen($sure) )) {
            if ('yup' === $sure) {
                if ($model === 'search') {

                    $entity = $this->{$model . '_model'}->get($id);
                    if (is_array($entity) && array_key_exists('ip_address', $entity)) {
                        $blacklist_model = new BlacklistModel();
                        $blacklist_item = $blacklist_model->getRecords([
                            'ip_address' => $entity['ip_address']
                        ],[],[0,1]);

                        if (is_array($blacklist_item) && !count($blacklist_item)) {
                            $blacklist_model->create([
                                'created' => $entity['created'],
                                'ip_address' => $entity['ip_address'],
                                'reason' => 'search spammer (blacklisted by removal)'
                            ]);
                        }
                    }
                }

                $this->{$model . '_model'}->delete($id);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'DELETED!<br><img src="' . Core::asset('img/deleted.gif') . '">'];
            } else {
                $_SESSION['flash'] = ['type' => 'info', 'message' => 'Oho! You chickin!<br><img src="' . Core::asset('img/chickin.gif') . '"><br>Good for you to check if you REALLY wanted to delete it!'];
            }
            Core::redirect(Core::url('admin/' . $model . '_index'));
        }

        $this->set([
            'page_title' => 'Admin | Confirm deletion',
            'model'      => $model,
            'id'         => $id
        ]);

        $this->render('are_you_sure');
    }

    public function xhr_tags()
    {
        header('Content-Type: application/json');

        $query = $this->request->getQuery();
        $existing_tags = [];
        $term = '';
        $return = [];
        $suggestions = [];

        if (
            array_key_exists('existingTags', $query) &&
            is_array($query['existingTags'])
        ) {
            $existing_tags = $query['existingTags'];
        }

        if (array_key_exists('term', $query)) {
            $term = trim($query['term']);

            $tag_model = new TagModel();
            $tags = $tag_model->getRecords(['name' => '~' . $term], ['name', 'asc'], [0,25]);

            if (is_array($tags) && count($tags)) {
                foreach ($tags as $tag) {
                    $suggestions[] = [
                        'tag'   => $tag['name'],
                        'value' => $tag['slug']
                    ];
                }
            }
        }

        $return['suggestions'] = $suggestions;

        if (strlen($term)) {
            echo json_encode($return);
            return true;
        }
    }

    public function hreflang()
    {
        $file = ROOT . 'hreflang.csv';

        $english_prefix = 'https://ohmydish.com/recipe/';
        $dutch_prefix = 'https://ohmydish.nl/recept/';

        $hreflangs = [];

        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++) {
                    $e = explode(';', $data[$c]);
                    $mapping = [];

                    foreach ($e as $i => $part) {
                        if ($i <= 4) {
                            switch ($i) {
                                case 0:
                                    if (!intval(trim($part))) {
                                        $part = 1; // strange.. string(4) "1" O_o;
                                    }
                                    $mapping['dutch_id'] = trim($part);
                                    break;
                                case 1:
                                    $mapping['dutch_url'] = $dutch_prefix . $part;
                                    break;
                                case 2:
                                    $mapping['english_id'] = $part;
                                    break;
                                case 3:
                                    $mapping['english_url'] = $english_prefix . $part;
                                    break;
                            }
                        }
                    }

                    $hreflangs[] = $mapping;
                }
            }
            fclose($handle);
        }

        $skipped = [];
        foreach ($hreflangs as $hreflang) {
            if (strlen($hreflang['english_id'])) {
                $recipe = $this->recipe_model->get($hreflang['english_id']);

                if (is_array($recipe)) {
                    if (count($recipe)) {
                        if (!strlen($recipe['dutch_url'])) {
                            $this->recipe_model->update($recipe['id'], [
                                'dutch_url' => $hreflang['dutch_url']
                            ]);
                        }
                    } else {
                        $skipped[] = $hreflang;
                    }
                }
            } else {
                $skipped[] = $hreflang;
            }
        }

        dd($skipped);
    }
}
