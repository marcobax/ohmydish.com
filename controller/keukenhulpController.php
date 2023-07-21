<?php
/**
 * Class keukenhulpController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class keukenhulpController extends Controller
{
    var $blog_model;

    /**
     * Keukenhulp detail.
     */
    public function detail()
    {
        $this->category_model = new CategoryModel();
        $this->blog_model     = new BlogModel();

        if (!$category = $this->category_model->getBySlug($this->getSlug(), ['type' => 'hulp-in-de-keuken'])) {
            $this->show404();
        }

        $where = ['status' => 'publish', 'find_in_set' => ['categories' => $category['id']]];

        $this->setTotalResults($this->blog_model->getRecords($where,[],[],true));

        $this->set([
            'page_title'            => 'Keukenhulp ' . strtolower($category['title']),
            'meta_description'      => (!$category['content'])?'Keukenhulp, onderwerp: ' . $category['title']:strip_tags($category['content']),
            'page_canonical'        => Core::url('keukenhulp/' . $category['slug']),
            'og_image'              => TemplateHelper::getFeaturedImage($category),
            'category'              => $category,
            'blogs'                 => $this->blog_model->getRecords($where, ['id', 'desc'], $this->getPagination()),
            'pagination'            => $this->getPagination()
        ]);

        $this->render('detail');
    }
}