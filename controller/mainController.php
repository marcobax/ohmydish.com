<?php

/**
 * Class mainController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class mainController extends Controller
{
    var $blog_model;
    var $question_model;

    /**
     * The main action of the application.
     */
    public function index()
    {
        $blog_model = new BlogModel();

        $recipe_count = $this->recipe_model->getCount();
        $count = TemplateHelper::formatNumber(($recipe_count + (10 - fmod($recipe_count, 10))));

        $question_model = new QuestionModel();

        $this->set([
            'page_title'           => $count . '+ delicious recipes for free at Ohmydish',
            'force_page_title'     => true,
            'meta_description'     => 'Find more than ' . $count . '+ delicious and amazing recipes for each occasion. Cook an amazing lunch, quick meal, snacks or homemade pies.',
            'page_canonical'       => Core::url('/'),
            'hide_top_search'      => true,
            //'featured_recipe'      => $this->recipe_model->get(1033), // Rocky road pie
	    'latest_recipes'       => $this->recipe_model->getRecords(['status' => 'publish'],['published','desc'],[0,9]),
            'latest_blogs'         => $blog_model->getRecords([],['published','desc'],[0,6]),
            'other_recipes'        => $this->recipe_model->getRecords(['status' => 'publish'],['RAND()'],[0,4]),
            //'seasonal_ingredients' => $this->recipe_model->getRecords([],['id','desc'],[0,8]),
            'questions'            => $question_model->getRecords(['status' => 'publish'],['RAND()'],[0,8]),
            'recipe_count'         => $count
        ]);

        $this->render('index');
    }

    /**
     * Contact form
     */
    public function contact()
    {
        $post_values = $_POST;

        if (is_array($post_values) && count($post_values)) {
            if (
                (array_key_exists('naam', $post_values) && strlen($post_values['naam'])) &&
                (array_key_exists('email', $post_values) && strlen($post_values['email'])) &&
                (array_key_exists('aanvraag', $post_values) && strlen($post_values['aanvraag']))
            ) {
                $ip_address = Core::get_client_ip();

                // Spam check.
                $akismet_helper = new AkismetHelper();
                $spam = $akismet_helper->comment_check([
                    'user_ip'              => $ip_address?$ip_address:'unknown',
                    'comment_type'         => 'contact-form',
                    'comment_author'       => trim($post_values['naam']),
                    'comment_author_email' => trim($post_values['email']),
                    'comment_content'      => trim($post_values['aanvraag']),
                    'permalink'            => Core::url('contact-us')
                ]);

                $admin_stats_model = new AdminStatsModel();
                $contact_model = new ContactModel();
                $contact_model->create([
                    'created' => date('Y-m-d H:i:s'),
                    'name' => trim($post_values['naam']),
                    'email' => trim($post_values['email']),
                    'content' => trim($post_values['aanvraag']),
                    'spam' => (true===$spam)?1:0
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thank you for contacting us. We will try to respond to your message as soon as possible.'];

                if (false === $spam) {
                    $admin_stats_model->increment('feedback', 'new');
                } else {
                    $admin_stats_model->increment('spam', 'blocked');
                }
            }
            Core::redirect('contact-us');
        }

        $this->set([
            'page_title'           => 'Contact us',
            'meta_description'     => 'Do you have a food related question, noticed a mistake in our recipes or just a general question? Send us a message here.',
            'page_canonical'       => Core::url('contact-us')
        ]);

        $this->render('contact');
    }

    /**
     * About us.
     */
    public function about()
    {
        $this->blog_model = new BlogModel();
        $this->question_model = new QuestionModel();
        $this->category_model = new CategoryModel();
        $this->tag_model = new TagModel();
        $this->user_model = new UserModel();
        $this->collection_model = new CollectionModel();
        $this->page_model = new PageModel();
        $this->rating_model = new RatingModel();
        $this->saved_recipe_model = new SavedRecipeModel();

        $this->set([
            'page_title'           => 'About us - Who is behind the website?',
            'meta_description'     => 'Find out who is busy on a daily basis creating recipes, answers on cooking questions and food related blogs',
            'page_canonical'       => Core::url('about-us'),
            'recipe_count'         => $this->recipe_model->getCount(),
            'blog_count'           => $this->blog_model->getCount(),
            'question_count'       => $this->question_model->getCount(),
            'category_count'       => $this->category_model->getCount(),
            'tag_count'            => $this->tag_model->getCount(),
            'user_count'           => $this->user_model->getCount(),
            'collection_count'     => $this->collection_model->getCount(),
            'page_count'           => $this->page_model->getCount(),
            'rating_count'         => $this->rating_model->getCount(),
            'saved_recipe_count'   => $this->saved_recipe_model->getCount()
        ]);

        $this->render('about');
    }

    /**
     * 404 page not found.
     */
    public function show404()
    {
        http_response_code(404);

        $this->set([
            'page_title'       => 'Error 404, page not found.',
            'meta_description' => 'Unfortunately this page cannot be found.',
            'page_canonical'   => Core::url('404')
        ]);

        $this->render('404');
    }

    public function feed()
    {
        header('Content-Type: text/xml; charset=utf-8');

        // @todo: add categories for recipes and blogs.

        $recipe_model = new RecipeModel();
        $blog_model   = new BlogModel();
        $recipe_count = $this->recipe_model->getCount();
        $count = ($recipe_count + (10 - fmod($recipe_count, 10)));
        $items = [];

        $latest_recipes = $recipe_model->getRecords([], ['published', 'desc'], [0,50]);
        foreach ($latest_recipes as $latest_recipe) {
            $latest_recipe['url_prefix'] = 'recipe';
            $latest_recipe['rss_categories'] = [];

            $categories = $this->category_model->getRecords([
                'in' => [
                    'id' => $latest_recipe['categories']
                ]
            ], ['id', 'asc']);

            if (is_array($categories) && count($categories)) {
                foreach ($categories as $category) {
                    $latest_recipe['rss_categories'][] = $category['title'];
                }
            }

            $items[strtotime($latest_recipe['published'])] = $latest_recipe;
        }

        $last_blogs = $blog_model->getRecords(['status' => 'publish'], ['id', 'desc'], [0,50]);
        foreach ($last_blogs as $last_blog) {
            $last_blog['url_prefix'] = 'blog';
            $last_blog['rss_categories'] = [];

            $categories = $this->category_model->getRecords([
                'in' => [
                    'id' => $last_blog['categories']
                ]
            ], ['id', 'asc']);

            if (is_array($categories) && count($categories)) {
                foreach ($categories as $category) {
                    $last_blog['rss_categories'][] = $category['title'];
                }
            }

            $items[strtotime($last_blog['published'])] = $last_blog;
        }

        krsort($items);

        $this->set([
            'count' => $count,
            'items' => $items
        ]);

        $this->render('feed', 'rss');
    }

    /**
     * Snore..
     */
    public function privacy()
    {
        $this->set([
            'page_title'       => 'Privacy statement',
            'meta_description' => 'Read the Ohmydish privacy statement',
            'page_canonical'   => Core::url('privacystatement')
        ]);

        $this->render('privacystatement');
    }

    /**
     * Newsletter subscriptions
     */
    public function newsletter()
    {
        $this->set([
            'page_title'       => 'Sign up for our newsletter',
            'meta_description' => 'Do you want to get the Ohmydish newsletter? Subscribe here and twice a month receive the latest recipes via our newsletter.',
            'page_canonical'   => Core::url('newsletter')
        ]);

        $this->render('newsletter');
    }
}
