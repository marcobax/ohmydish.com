<?php

/**
 * Class forumController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class forumController extends Controller
{
    var $forum_board_model,
        $forum_category_model,
        $forum_post_model,
        $forum_topic_model;

    /**
     * forumController constructor.
     */
    public function __construct()
    {
        if (false === FORUM_ENABLED) {
            return Core::redirect('/');
        }

        parent::__construct();

        $this->forum_board_model    = new ForumBoardModel();
        $this->forum_category_model = new ForumCategoryModel();
        $this->forum_post_model     = new ForumPostModel();
        $this->forum_topic_model    = new ForumTopicModel();
    }

    /**
     * Overview of the forum.
     */
    public function index()
    {
        $this->set([
            'page_title'       => 'Forum overzicht',
            'meta_description' => 'Forum voor kookliefhebbers, stel je vragen of deel bijvoorbeeld je eigen recepten!',
            'page_canonical'   => Core::url('forum')
        ]);

        $forum_categories = $this->forum_category_model->getRecords([
            'status' => 1
        ], [
            'position' => 'asc'
        ]);

        $this->set([
            'forum_categories' => $forum_categories
        ]);

        $this->render('index');
    }
}