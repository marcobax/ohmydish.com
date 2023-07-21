<?php

/**
 * Class pageController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class pageController extends Controller
{
    var $page_model = false;

    /**
     * pageController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->page_model = new PageModel();
    }

    /**
     * Page detail.
     */
    public function detail()
    {
        if (!$page = $this->page_model->getBySlug($this->getSlug())) {
            $this->show404();
        }

        $this->page_model->incrementViews($page);

        $this->set([
            'meta_description'            => !strlen($page['excerpt'])?$page['title']:$page['excerpt'],
            'meta_article_published_time' => substr($page['created'],0,10),
            'meta_article_section'        => 'Page',
            'page'                        => $page,
            'page_title'                  => $page['title'],
            'page_canonical'              => Core::url($page['slug']),
            'og_type'                     => 'article',
            'og_image'                    => TemplateHelper::getFeaturedImage($page),
            'recent_pages'                => $this->page_model->getRecords([],['id','desc'],[0,5]),
            'previous_page'               => $this->page_model->getPreviousPage($page),
            'next_page'                   => $this->page_model->getNextPage($page)
        ]);

        $this->render('detail');
    }
}