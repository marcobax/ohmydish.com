<?php
require_once(ROOT . 'controller/courseController.php');

/**
 * Class dishtypeController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class dishtypeController extends courseController
{
    /**
     * Recipe category detail.
     */
    public function detail($type = 'dishtype'): void
    {
        $this->category_model = new CategoryModel();

        if (!$category = $this->category_model->getBySlug($this->getSlug(), ['type' => 'dishtype'])) {
            if ($category_by_cuisine = $this->category_model->getBySlug($this->getSlug(), ['type' => 'kitchen'])) {
                Core::redirect(Core::url('kitchen/' . $category_by_cuisine['slug']));
            }

            $this->show404();
        }

        $this->setTotalResults($this->recipe_model->getRecords(['find_in_set' => ['dishtypes' => $category['id']]],[],[],true));

        $this->set([
            'page_title'            => (isset($category['seo_title'])&&strlen($category['seo_title']))?$category['seo_title']:ucfirst($category['type']) . ' ' . strtolower($category['title']),
            'meta_description'      => (!$category['content'])?'Category: ' . $category['type'] . ', subject: ' . $category['title']:strip_tags($category['content']),
            'page_canonical'        => Core::url($category['type'] . '/' . $category['slug']),
            'og_image'              => TemplateHelper::getFeaturedImage($category),
            'category'              => $category,
            'recipes'               => $this->recipe_model->getRecords(['find_in_set' => ['dishtypes' => $category['id']]],['published','desc'],$this->getPagination()),
            'pagination'            => $this->getPagination()
        ]);

        $this->render('detail');
    }
}