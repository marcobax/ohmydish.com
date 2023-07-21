<?php

/**
 * Class recipeController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class recipeController extends Controller
{
    var $comment_model;

    /**
     * recipeController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->comment_model = new CommentModel();
    }

    /**
     * Overview of recipes.
     */
    public function index(): void
    {
        $categories_by_season = [
            $this->category_model->getBySlug('winter-recipes'),
            $this->category_model->getBySlug('spring-recipes'),
            $this->category_model->getBySlug('summer-recipes'),
            $this->category_model->getBySlug('autumn-recipes')
        ];

        $this->set([
            'meta_description'      => 'Overview of all our recipes, sorted by newest to oldest.',
            'page_title'            => 'Recipe index',
            'page_canonical'        => Core::url('recipe-index'),
            'categories_by_course'  => $this->category_model->getRecords(['type' => 'course', 'total_entities' => '> 0'],['title','asc']),
            'categories_by_theme'   => $this->category_model->getRecords(['type' => 'theme', 'total_entities' => '> 0'],['title','asc']),
            'categories_by_season'  => $categories_by_season,
            'categories_by_cuisine' => $this->category_model->getRecords(['type' => 'kitchen', 'parent_id' => '0', 'total_entities' => '> 3'],['title','asc']),
            'latest_recipes'        => $this->recipe_model->getRecords([],['published','desc'],[0,4])
        ]);

        $this->render('index');
    }

    /**
     * Recipe comments.
     *
     * @param string $slug
     */
    public function comments(string $slug): void
    {
        if (!$recipe = $this->recipe_model->getBySlug($slug)) {
            $this->show404();
        }

        $post_values = $_POST;

        if (is_array($post_values) && count($post_values)) {
            if (
                (array_key_exists('author', $post_values) && strlen($post_values['author'])) &&
                (array_key_exists('email', $post_values) && strlen($post_values['email'])) &&
                (array_key_exists('content', $post_values) && strlen($post_values['content']))
            ) {
                $ip_address = Core::get_client_ip();

                // Spam check.
                $akismet_helper = new AkismetHelper();
                $spam = $akismet_helper->comment_check([
                    'user_ip'              => $ip_address?:'unknown',
                    'comment_type'         => 'comment',
                    'comment_author'       => trim($post_values['author']),
                    'comment_author_email' => trim($post_values['email']),
                    'comment_content'      => trim($post_values['content']),
                    'permalink'            => Core::url('recipe/comments/' . $recipe['slug'])
                ]);

                $admin_stats_model = new AdminStatsModel();

                // Create comment.
                $data = [
                    'created' => date('Y-m-d H:i:s'),
                    'entity_id' => $recipe['id'],
                    'page_type' => 'recipe',
                    'author' => trim($post_values['author']),
                    'content' => trim($post_values['content']),
                    'email' => trim($post_values['email']),
                    'status' => 'pending',
                    'wordpress_post_id' => 0,
                    'spam' => (true===$spam)?1:0
                ];

                $comment_id = $this->comment_model->create($data);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thank you for submitting a comment, we will try to answer it as soon as possible.'];

                if (false === $spam) {
                    $admin_stats_model->increment('comment', 'new');
                } else {
                    $admin_stats_model->increment('spam', 'blocked');
                }

                // Update comment count.
                $this->recipe_model->updateCommentCount($recipe);

                Core::redirect(Core::url('recipe/comments/' . $recipe['slug']) . '#comment-' . $comment_id);
            }
        }

        $comment_records = $this->comment_model->getRecords([
            'page_type' => 'recipe',
            'entity_id' => $recipe['id'],
            'status' => 'approved'
        ], ['created', 'asc']);

        $comments = CoreHelper::buildTree($comment_records);

        $category_dish_types = $this->category_model->getRecords(['in' => ['id' => $recipe['dishtypes']]], ['title' => 'asc']);

        $meta_article_tags = [];
        if (is_array($category_dish_types) && count($category_dish_types)) {
            foreach ($category_dish_types as $category_dish_type) {
                $meta_article_tags[] = $category_dish_type['title'];
            }
        }

        $this->set([
            'meta_article_published_time' => substr($recipe['created'],0,10),
            'meta_article_section'        => 'Recipe',
            'meta_article_tags'           => $meta_article_tags,
            'meta_description'            => 'View the comments of the recipe ' . $recipe['title'] . ' or leave your own comment behind.',
            'page_title'                  => 'Comments on ' . $recipe['title'],
            'page_canonical'              => Core::url('recipe/comments/' . $recipe['slug']),
            'og_type'                     => 'article',
            'og_image'                    => TemplateHelper::getFeaturedImage($recipe),
            'recipe'                      => $recipe,
            'comments'                    => $comments
        ]);

        $this->render('comments');
    }

    /**
     * Recipe detail.
     */
    public function detail(): void
    {
        if (!$recipe = $this->recipe_model->getBySlug($this->getSlug())) {
            $this->show404();
        } else {
            if (!SessionHelper::hasPermission('is_admin') && ('publish' !== $recipe['status'])) {
                $this->show404();
            }
        }

        if ($this->getSlug() !== $recipe['slug']) {
            Core::redirect(Core::url('recipe/' . $recipe['slug']));
        }

        // Update views.
        $this->recipe_model->incrementViews($recipe);

        $related_recipes = $this->recipe_model->getRecords([], ['RAND()', 'asc'], $this->getPagination(4));
        $category_cuisines = $this->category_model->getRecords(['type' => 'kitchen', 'in' => ['id' => $recipe['cuisines']]], ['title' => 'asc']);
        $category_dish_types = $this->category_model->getRecords(['type' => 'dishtype', 'in' => ['id' => $recipe['dishtypes']]], ['title' => 'asc']);

        $meta_article_tags = [];
        if (is_array($category_dish_types) && count($category_dish_types)) {
            foreach ($category_dish_types as $category_dish_type) {
                $meta_article_tags[] = $category_dish_type['title'];
            }
        }

        $this->set([
            'meta_description'            => $recipe['excerpt'],
            'meta_article_published_time' => date('c', strtotime($recipe['published'])),
            'meta_article_modified_time'  => date('c', strtotime($recipe['updated'])),
            'meta_article_section'        => 'Recipe',
            'meta_article_tags'           => $meta_article_tags,
            'recipe'                      => $recipe,
            'page_title'                  => $recipe['title'] . ' recipe',
            'page_canonical'              => Core::url('recipe/' . $recipe['slug']),
            'og_type'                     => 'article',
            'og_image'                    => TemplateHelper::getFeaturedImage($recipe),
            'structured_data'             => $this->recipe_model->getStructuredData(['recipe' => $recipe, 'category_cuisines' => $category_cuisines]),
            'related_recipes'             => $related_recipes,
            'previous_recipe'             => $this->recipe_model->getPreviousRecipe($recipe),
            'next_recipe'                 => $this->recipe_model->getNextRecipe($recipe),
            'main_category'               => $this->category_model->getMainCategory($recipe),
            'category_dish_types'         => $category_dish_types,
            'english_url'                 => Core::url('recipe/' . $recipe['slug']),
            'dutch_url'                   => $recipe['dutch_url'],
            'preload_image'               => TemplateHelper::getFeaturedImage($recipe),
            'pageId'                      => 'recipe-' . $recipe['id'],
            'disqusEnabled'               => true,
        ]);

        $this->render('detail');
    }

    /**
     * The latest recipes.
     */
    public function latest(): void
    {
        $this->setTotalResults($this->recipe_model->getRecords([],[],[],true));

        $this->set([
            'meta_description' => 'Find the latest recipes from Ohmydish. So many great and delicious dishes to choose, from all over the world.',
            'page_title'       => 'Latest recipes',
            'page_canonical'   => Core::url('latest-recipes'),
            'recipes'          => $this->recipe_model->getRecords([],['published','desc'],$this->getPagination()),
            'pagination'       => $this->getPagination()
        ]);

        $this->render('latest');
    }
}
