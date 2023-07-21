<?php

/**
 * Class sitemapController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class sitemapController extends Controller
{
    /**
     * Sitemap index.
     */
    public function index()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $this->render('index', 'xml');
    }

    /**
     * Main pages, mainly URL rewrites.
     */
    public function main()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $this->render('main', 'xml');
    }

    /**
     * Blogs
     */
    public function blog()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = ['status' => 'publish'];
        $orderby = ['published','desc'];

        $blog_model = new BlogModel;

        $this->set([
            'blogs' => $blog_model->getRecords($where, $orderby)
        ]);

        $this->render('blog', 'xml');
    }

    /**
     * Blog comments
     */
    public function blog_comments()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = ['status' => 'publish'];
        $orderby = ['id','desc'];

        $blog_model = new BlogModel;

        $this->set([
            'blogs' => $blog_model->getRecords($where, $orderby)
        ]);

        $this->render('blog_comments', 'xml');
    }

    /**
     * Pages
     */
    public function page()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = ['status' => 'publish'];
        $orderby = ['published','desc'];

        $page_model = new PageModel();

        $this->set([
            'pages' => $page_model->getRecords($where, $orderby)
        ]);

        $this->render('page', 'xml');
    }

    /**
     * Recipes
     */
    public function recipe()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = [];
        $orderby = ['published','desc'];

        $recipe_model = new RecipeModel();

        $this->set([
            'recipes' => $recipe_model->getRecords($where, $orderby)
        ]);
        $this->render('recipe', 'xml');
    }

    /**
     * Recipe comments.
     */
    public function recipe_comments()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = [];
        $orderby = ['published','desc'];

        $recipe_model = new RecipeModel();

        $this->set([
            'recipes' => $recipe_model->getRecords($where, $orderby)
        ]);
        $this->render('recipe_comments', 'xml');
    }

    /**
     * Categories
     */
    public function category()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = [];
        $orderby = ['id','desc'];

        $category_model = new CategoryModel();

        $this->set([
            'categories' => $category_model->getRecords($where, $orderby)
        ]);
        $this->render('category', 'xml');
    }

    /**
     * Tags
     */
    public function tag()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = [];
        $orderby = ['id','desc'];

        $tag_model = new TagModel();

        $this->set([
            'tags' => $tag_model->getRecords($where, $orderby)
        ]);
        $this->render('tag', 'xml');
    }

    /**
     * Questions
     */
    public function question()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = ['status' => 'publish'];
        $orderby = ['id','desc'];

        $question_model = new QuestionModel();

        $this->set([
            'questions' => $question_model->getRecords($where, $orderby)
        ]);
        $this->render('question', 'xml');
    }

    /**
     * Collections
     */
    public function collection()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $where   = ['public' => 1, 'deleted' => 0];
        $orderby = ['id','desc'];

        $collection_model = new CollectionModel();

        $user_model = new UserModel();

        $users = $user_model->getRecords([], ['id', 'desc']);

        $this->set([
            'collections' => $collection_model->getRecords($where, $orderby),
            'users'       => $users
        ]);
        $this->render('collection', 'xml');
    }

    public function forum()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $forum_board_model = new ForumBoardModel();
        $forum_boards = $forum_board_model->getRecords([], ['last_post_date', 'desc']);

        $forum_category_model = new ForumCategoryModel();
        $forum_categories = $forum_category_model->getRecords([], ['id', 'desc']);

        $forum_topic_model = new ForumTopicModel();
        $forum_topics = $forum_topic_model->getRecords([], ['last_post_date', 'desc']);

        $this->set([
            'forum_boards'     => $forum_boards,
            'forum_categories' => $forum_categories,
            'forum_topics'     => $forum_topics,
        ]);

        $this->render('forum', 'xml');
    }
}