<?php

class migrationController extends Controller
{
    public function wp_to_omd()
    {
        $recipes = $this->recipe_model->getRecords([], ['id', 'asc']);

        $wp_term_relationships_model = new WpTermRelationshipsModel();
        $wp_term_taxonomy_model = new WpTermTaxonomyModel();

        // Cuisines
//        foreach ($recipes as $recipe) {
//
//            $update = true;
//
//            if ($recipe['published']) {
//                $new_ids = [];
//                $omd_dish_types = $recipe['omdcuisines'];
//
//                $old_ids = explode(',', $omd_dish_types);
//
//                foreach ($old_ids as $old_id) {
//                    $wp_term_taxonomy = $wp_term_taxonomy_model->get($old_id, 'term_taxonomy_id');
//
//                    if (
//                        is_array($wp_term_taxonomy) &&
//                        count($wp_term_taxonomy) &&
//                        array_key_exists('term_id', $wp_term_taxonomy)
//                    ) {
//                        $term_id = $wp_term_taxonomy['term_id'];
//
//                        // Get category by term_id -> wp_taxonomy_id
//                        $category = $this->category_model->get($term_id, 'wp_taxonomy_id');
//
//                        if (is_array($category) && array_key_exists('id', $category)) {
//                            array_push($new_ids, (int)$category['id']);
//                        } else {
//                            $this->recipe_model->update((int) $recipe['id'], ['omdcuisines' => 'error']);
//                            $update = false;
//                        }
//                    }
//                }
//
//                if (true === $update) {
//                    $new_omd_dish_types = implode(',', $new_ids);
//
//                    // Update recipe.
//                    $this->recipe_model->update((int)$recipe['id'], ['omdcuisines' => $new_omd_dish_types]);
//                }
//            }
//        }

        // Dish types
        foreach ($recipes as $recipe) {

            $update = true;

            if ($recipe['published'] && strlen($recipe['omddishtypes'])) {
                $new_ids = [];
                $omd_dish_types = $recipe['omddishtypes'];

                $old_ids = explode(',', $omd_dish_types);

                foreach ($old_ids as $old_id) {
                    $category = [];
                    // Get category by term_id -> wp_taxonomy_id
                    //$category = $this->category_model->get($old_id, 'wp_taxonomy_id');
                    $categories = $this->category_model->getRecords([
                        'wp_taxonomy_id' => $old_id,
                        'type' => 'dishtype'
                    ]);

                    if (is_array($categories) && count($categories)) {
                        $category = $categories[0];
                    }

                    if (is_array($category) && array_key_exists('id', $category)) {
                        array_push($new_ids, (int)$category['id']);
                    } else {
                        dd($category, $old_id, $old_ids);
                        $this->recipe_model->update((int) $recipe['id'], ['omddishtypes' => 'error']);
                        $update = false;
                    }

//                    $wp_term_taxonomy = $wp_term_taxonomy_model->get($old_id, 'term_taxonomy_id');
//
//                    if (
//                        is_array($wp_term_taxonomy) &&
//                        count($wp_term_taxonomy) &&
//                        array_key_exists('term_id', $wp_term_taxonomy)
//                    ) {
//                        $term_id = $wp_term_taxonomy['term_id'];
//
//                        // Get category by term_id -> wp_taxonomy_id
//                        $category = $this->category_model->get($term_id, 'wp_taxonomy_id');
//
//                        if (is_array($category) && array_key_exists('id', $category)) {
//                            array_push($new_ids, (int)$category['id']);
//                        } else {
//                            $this->recipe_model->update((int) $recipe['id'], ['omddishtypes' => 'error']);
//                            $update = false;
//                        }
//                    }
                }

                if (true === $update) {
                    $new_omd_dish_types = implode(',', $new_ids);

                    // Update recipe.
                    $this->recipe_model->update((int)$recipe['id'], ['omddishtypes' => $new_omd_dish_types]);
                }
            }
        }

        die('done');
    }

    /**
     * Update all recipe ingredient and kitchen equipment lists.
     */
    public function recipe_update_ingredients_and_kitchen_equipment()
    {
        $recipes = $this->recipe_model->getRecords([
            'ingredients' => 'NULL'
        ], [
            'id', 'asc'
        ]);

        $output = '';

        if (is_array($recipes) && count($recipes)) {
            foreach($recipes as $recipe) {
                if (is_null($recipe['ingredients'])) {
                    $doc = new DOMDocument();
                    $doc->loadHTML('<?xml encoding="utf-8mb4" ?>' . $recipe['content']);

                    $list_items = $doc->getElementsByTagName('ul');

                    if (!$list_items->length) {
                        $output .= "<span class=\"text-danger\">Geen ingrediënten gevonden voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>)</span><br>";
                        continue;
                    } elseif($list_items->length >= 3) {
                        $output .= "<span class=\"text-danger\">Meer dan 2 lijsten gevonden voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>). Deze met de hand controleren!</span><br>";
                        continue;
                    }

                    for ($i = 0; $i < $list_items->length; $i++) {
                        $new_html = "<ul>";
                        foreach ($list_items->item($i)->getElementsByTagName('li') as $list_item) {
                            $node_value = $list_item->nodeValue;

                            $new_html .= "<li>" . $node_value . "</li>";
                        }
                        $new_html .= "</ul>";

//                        if ($recipe['id'] === "449") {
//                            Core::dd(htmlspecialchars($new_html));
//                        }
                        // First list is always ingredients.
                        if (0 === $i) {
                            $this->recipe_model->update($recipe['id'], ['ingredients' => $new_html]);
                            $output .= "<span class=\"text-success\">Ingrediënten bijgewerkt voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>)</span><br>";
                        } elseif(1 === $i) {
                            // Second list are usually the kitchen equipments.
                            $this->recipe_model->update($recipe['id'], ['kitchen_equipment' => $new_html]);
                            $output .= "<span class=\"text-success\">Kookgerei bijgewerkt voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>)</span><br>";
                        }

                        //var_dump($new_html);
                        //break;
                    }
                }
            }
        }

        $this->set(['output' => $output]);
        $this->render('output');
    }

    public function convert_wp_caption_images()
    {
        $output = '';

//        $page_model = new PageModel();
//        $pages = $page_model->getRecords([]);
//
//        foreach ($pages as $page) {
//            $page_content = $page['content'];
//            $new_page_content = $page_content;
//
//            if (true) {
//                $new_page_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $new_page_content); // remove stupid WP shortcodes.
//                $new_page_content = preg_replace('/(\<img[^>]+)(class\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $new_page_content);
//                $new_page_content = str_replace('<img  ', '<img class="img-fluid mx-auto d-block" ', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="800" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="900" height="1200" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="720" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="1600" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="720" height="960" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="717" ', '', $new_page_content);
//            }
//
//            $page_model->update($page['id'], [
//                'content' => $new_page_content
//            ]);
//
//            $output .= "<span class=\"success\">Pagina heeft geen  <span class=\"text-danger\">S T U P I D</span>  Wordpress shortcodes meer. <strong>" . $page['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url($page['slug']) . "\">linkje</a>)<br>";
//        }

//        $blog_model = new BlogModel();
//        $blogs = $blog_model->getRecords();
//
//        foreach ($blogs as $blog) {
//            $blog_content = $blog['content'];
//            $new_page_content = $blog_content;
//
//            if (true) {
//                $new_page_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $new_page_content); // remove stupid WP shortcodes.
//                $new_page_content = preg_replace('/(\<img[^>]+)(class\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $new_page_content);
//                $new_page_content = str_replace('<img  ', '<img class="img-fluid mx-auto d-block" ', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="800" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="900" height="1200" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="720" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="1600" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="720" height="960" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="717" ', '', $new_page_content);
//            }
//
//            $blog_model->update($blog['id'], [
//                'content' => $new_page_content
//            ]);
//
//            $output .= "<span class=\"success\">Blog heeft geen  <span class=\"text-danger\">S T U P I D</span>  Wordpress shortcodes meer. <strong>" . $blog['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('blog/' . $blog['slug']) . "\">linkje</a>)<br>";
//        }

//        $recipe_model = new RecipeModel();
//        $recipes = $recipe_model->getRecords();
//
//        foreach ($recipes as $recipe) {
//            $recipe_content = $recipe['content'];
//            $new_page_content = $recipe_content;
//
//            if (true) {
//                $new_page_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $new_page_content); // remove stupid WP shortcodes.
//                $new_page_content = preg_replace('/(\<img[^>]+)(class\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $new_page_content);
//                $new_page_content = str_replace('<img  ', '<img class="img-fluid mx-auto d-block" ', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="800" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="900" height="1200" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="720" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="1200" height="1600" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="720" height="960" ', '', $new_page_content);
//                $new_page_content = str_replace(' width="960" height="717" ', '', $new_page_content);
//            }
//
//            $recipe_model->update($recipe['id'], [
//                'content' => $new_page_content
//            ]);
//
//            $output .= "<span class=\"success\">Recept heeft geen  <span class=\"text-danger\">S T U P I D</span>  Wordpress shortcodes meer. <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recept/' . $recipe['slug']) . "\">linkje</a>)<br>";
//        }

        $this->set(['output' => $output]);
        $this->render('output');
    }

    public function migrate_wp_categories_to_normal_categories()
    {
        die('nope');
        $output = '';

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getRecords();
        $category_mapping = [];

        foreach ($categories as $category) {
            $category_mapping[$category['wp_taxonomy_id']] = $category['id'];
        }

//        $blogModel = new BlogModel();
//        $blogs = $blogModel->getRecords();
//
//        foreach ($blogs as $blog) {
//            $blog_categories = explode(',', $blog['categories']);
//            $new_blog_categories = [];
//
//            foreach ($blog_categories as $blog_category) {
//                if (array_key_exists((int) $blog_category, $category_mapping)) {
//                    $new_category = $category_mapping[(int)$blog_category];
//                    $output .= "<span class=\"success\">Blog post heeft category wp_taxonomy_id <span class=\"text-danger\">" . $blog_category . "</span> en gaat overgezet worden naar: <strong>" . $category_mapping[$blog_category] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('blog/' . $blog['slug']) . "\">linkje</a>)<br>";
//                    $new_blog_categories[] = $new_category;
//                }
//            }
//            $new_blog_categories_string = '';
//            if (is_array($new_blog_categories) && count($new_blog_categories)) {
//                $new_blog_categories_string = implode(',', $new_blog_categories);
//            }
//
//            if (strlen($new_blog_categories_string)) {
//                $blogModel->update($blog['id'], [
//                    'categories' =>  $new_blog_categories_string
//                ]);
//            }
//        }

        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->getRecords();

        foreach ($recipes as $recipe) {
//            $recipe_categories = explode(',', $recipe['categories']);
//            $new_recipe_categories = [];
//
//            foreach ($recipe_categories as $recipe_category) {
//                if (array_key_exists((int) $recipe_category, $category_mapping)) {
//                    $new_category = $category_mapping[(int)$recipe_category];
//                    // $output .= "<span class=\"success\">Recept heeft category wp_taxonomy_id <span class=\"text-danger\">" . $recipe_category. "</span> en gaat overgezet worden naar: <strong>" . $category_mapping[$recipe_category] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recept/' . $recipe['slug']) . "\">linkje</a>)<br>";
//                    $new_recipe_categories[] = $new_category;
//                }
//            }
//            $new_recipe_categories_string = '';
//            if (is_array($new_recipe_categories) && count($new_recipe_categories)) {
//                $new_recipe_categories_string = implode(',', $new_recipe_categories);
//            }
//
//            if (strlen($new_recipe_categories_string)) {
//
//                $recipeModel->update($recipe['id'], [
//                    'categories' =>  $new_recipe_categories_string
//                ]);
//            }

            $recipe_categories = explode(',', $recipe['dishtypes']);
            $new_recipe_categories = [];

            foreach ($recipe_categories as $recipe_category) {
                if (array_key_exists((int) $recipe_category, $category_mapping)) {
                    $new_category = $category_mapping[(int)$recipe_category];
                    // $output .= "<span class=\"success\">Recept heeft category wp_taxonomy_id <span class=\"text-danger\">" . $recipe_category. "</span> en gaat overgezet worden naar: <strong>" . $category_mapping[$recipe_category] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recept/' . $recipe['slug']) . "\">linkje</a>)<br>";
                    $new_recipe_categories[] = $new_category;
                }
            }
            $new_recipe_categories_string = '';
            if (is_array($new_recipe_categories) && count($new_recipe_categories)) {
                $new_recipe_categories_string = implode(',', $new_recipe_categories);
            }

            if (strlen($new_recipe_categories_string)) {
                $recipeModel->update($recipe['id'], [
                    'dishtypes' =>  $new_recipe_categories_string
                ]);
            }

            $recipe_categories = explode(',', $recipe['cuisines']);
            $new_recipe_categories = [];

            foreach ($recipe_categories as $recipe_category) {
                if (array_key_exists((int) $recipe_category, $category_mapping)) {
                    $new_category = $category_mapping[(int)$recipe_category];
                    // $output .= "<span class=\"success\">Recept heeft category wp_taxonomy_id <span class=\"text-danger\">" . $recipe_category. "</span> en gaat overgezet worden naar: <strong>" . $category_mapping[$recipe_category] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recept/' . $recipe['slug']) . "\">linkje</a>)<br>";
                    $new_recipe_categories[] = $new_category;
                }
            }
            $new_recipe_categories_string = '';
            if (is_array($new_recipe_categories) && count($new_recipe_categories)) {
                $new_recipe_categories_string = implode(',', $new_recipe_categories);
            }

            if (strlen($new_recipe_categories_string)) {
                $recipeModel->update($recipe['id'], [
                    'cuisines' =>  $new_recipe_categories_string
                ]);
            }
        }

        $this->set(['output' => $output]);
        $this->render('output');
    }

    /**
     * Update all recipe ingredient images.
     */
    public function recipe_update_ingredients_image()
    {
        $recipes = $this->recipe_model->getRecords([
            'ingredients_image' => 'NULL'
        ]);
        $output = '';

        if (is_array($recipes) && count($recipes)) {
            foreach($recipes as $recipe) {
                if (is_null($recipe['ingredients_image'])) {
                    $doc = new DOMDocument();
                    $doc->loadHTML('<?xml encoding="utf-8mb4" ?>' . $recipe['content']);

                    $images = $doc->getElementsByTagName('img');

                    for ($i = 0; $i < $images->length; $i++) {
                        $node_value = $images->item($i)->attributes->getNamedItem('src')->nodeValue;
                        if (false !== strpos($node_value, 'ingredi')) {
                            $this->recipe_model->update($recipe['id'], ['ingredients_image' => $node_value]);
                            $output .= "<span class=\"text-success\">Ingrediënten foto bijgewerkt voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>)</span><br>";
                        } else {
                            $output .= "<span class=\"text-danger\">Geen ingrediënten foto gevonden voor <strong>" . $recipe['title'] . "</strong> (<a target=\"_blank\" href=\"" . Core::url('recipe/' . $recipe['slug']) . "\">linkje</a>)</span><br>";
                        }
                        break;
                    }
                }
            }
        }

        $this->set(['output' => $output]);
        $this->render('output');
    }

    public function migrate_301_redirects()
    {
//        $this->redirect_model = new RedirectModel();
//
//        // wp_options -> option_name = "301_redirects" -> option_value.
//        $blob = 'a:91:{s:14:"/pie-and-cacke";s:13:"/pie-and-cake";s:5:"/pies";s:13:"/pie-and-cake";s:54:"/recipe/crayfish-and-surimi-with-spicey-mango-chutney/";s:1:"/";s:47:"/wp-content/uploads/2014/07/www.lisbetheats.com";s:26:"http://www.lisbetheats.com";s:45:"/wp-content/uploads/2014/07/rockinredblog.com";s:24:"http://rockinredblog.com";s:17:"/blog/hello-world";s:9:"/about-us";s:11:"/tag/beef-2";s:9:"/tag/beef";s:8:"/tag/pig";s:9:"/tag/pork";s:23:"/2011/08/25/hello-world";s:9:"/about-us";s:17:"/tag/ohmydish-com";s:9:"/about-us";s:9:"/tag/news";s:5:"/blog";s:11:"/tag/news-2";s:5:"/blog";s:44:"/wp-content/uploads/2014/07/Anthony_Bourdain";s:20:"/?s=Anthony+Bourdain";s:50:"/recipe/mac-and-cheese/attachment/submit-photo-sm/";s:22:"/recipe/mac-and-cheese";s:19:"/appetizers/page/2/";s:17:"/starters/page/2/";s:11:"/appetizers";s:9:"/starters";s:14:"/blog/recipe/*";s:9:"/recipe/*";s:19:"/blog/author/marco/";s:14:"/author/marco/";s:45:"/blog/2015/07/16/how-to-make-bearnaise-sauce/";s:29:"/how-to-make-bearnaise-sauce/";s:55:"/blog/2015/07/13/how-to-make-chinese-five-spice-powder/";s:39:"/how-to-make-chinese-five-spice-powder/";s:36:"/blog/2015/07/08/july-2015-giveaway/";s:20:"/july-2015-giveaway/";s:49:"/blog/2015/07/05/how-to-make-a-basic-vinaigrette/";s:33:"/how-to-make-a-basic-vinaigrette/";s:54:"/blog/2015/07/31/pie-vs-tart-whats-the-big-difference/";s:38:"/pie-vs-tart-whats-the-big-difference/";s:49:"/blog/2015/05/11/how-to-make-basic-chicken-stock/";s:33:"/how-to-make-basic-chicken-stock/";s:40:"/blog/2015/03/13/how-to-make-mayonnaise/";s:24:"/how-to-make-mayonnaise/";s:41:"/blog/2014/08/27/viewed-dishes-july-2014/";s:25:"/viewed-dishes-july-2014/";s:42:"/blog/2015/04/18/how-to-make-tomato-sauce/";s:26:"/how-to-make-tomato-sauce/";s:43:"/blog/2015/05/13/how-to-make-veloute-sauce/";s:27:"/how-to-make-veloute-sauce/";s:50:"/blog/2015/05/25/how-to-make-perfect-bacon-strips/";s:34:"/how-to-make-perfect-bacon-strips/";s:76:"/blog/2015/05/13/how-to-make-veloute-sauce/how-to-make-veloute-sauce-square/";s:27:"/how-to-make-veloute-sauce/";s:78:"/blog/2015/03/31/how-to-make-bechamel-sauce/how-to-make-bechamel-sauce-square/";s:28:"/how-to-make-bechamel-sauce/";s:71:"/blog/2015/05/02/how-to-make-hollandaise-sauce/holandaise-sauce-square/";s:31:"/how-to-make-hollandaise-sauce/";s:80:"/blog/2015/04/07/how-to-make-espagnole-sauce/how-to-make-sauce-espagnole-square/";s:29:"/how-to-make-espagnole-sauce/";s:44:"/blog/2015/03/31/how-to-make-bechamel-sauce/";s:28:"/how-to-make-bechamel-sauce/";s:47:"/blog/2015/05/02/how-to-make-hollandaise-sauce/";s:31:"/how-to-make-hollandaise-sauce/";s:74:"/blog/2015/04/18/how-to-make-tomato-sauce/how-to-make-tomato-sauce-square/";s:26:"/how-to-make-tomato-sauce/";s:51:"/blog/2015/05/16/how-to-make-basic-vegetable-stock/";s:35:"/how-to-make-basic-vegetable-stock/";s:45:"/blog/2015/04/07/how-to-make-espagnole-sauce/";s:29:"/how-to-make-espagnole-sauce/";s:51:"/blog/2015/02/19/food-myths-that-just-wont-go-away/";s:35:"/food-myths-that-just-wont-go-away/";s:38:"/blog/2014/07/27/properly-dice-onions/";s:22:"/properly-dice-onions/";s:32:"/blog/2015/01/12/frugal-kitchen/";s:16:"/frugal-kitchen/";s:49:"/blog/2015/05/04/5-irresistible-oriental-recipes/";s:33:"/5-irresistible-oriental-recipes/";s:34:"/blog/2015/01/14/make-pasta-dough/";s:18:"/make-pasta-dough/";s:45:"/blog/2015/04/20/the-perfect-date-night-menu/";s:29:"/the-perfect-date-night-menu/";s:46:"/blog/2015/06/02/how-to-make-basic-beef-stock/";s:30:"/how-to-make-basic-beef-stock/";s:27:"/blog/2014/09/12/dry-basil/";s:11:"/dry-basil/";s:38:"/blog/2014/09/02/10-foods-refrigerate/";s:22:"/10-foods-refrigerate/";s:50:"/blog/2014/11/24/beat-winter-blues-easy-food-tips/";s:34:"/beat-winter-blues-easy-food-tips/";s:56:"/blog/2014/09/11/10-great-ways-can-use-limit-food-waste/";s:40:"/10-great-ways-can-use-limit-food-waste/";s:36:"/blog/2014/08/14/ohmydish-optimized/";s:20:"/ohmydish-optimized/";s:72:"/blog/2015/05/19/five-mother-sauces-every-serious-cook-should-know/feed/";s:51:"/five-mother-sauces-every-serious-cook-should-know/";s:54:"/blog/2014/12/29/instantly-increase-traffic-food-blog/";s:38:"/instantly-increase-traffic-food-blog/";s:43:"/blog/2014/09/11/viewed-dishes-august-2014/";s:27:"/viewed-dishes-august-2014/";s:43:"/blog/2014/10/31/video-bacon-comes-graphic/";s:27:"/video-bacon-comes-graphic/";s:26:"/blog/author/marco/page/2/";s:21:"/author/marco/page/2/";s:32:"/blog/2014/07/31/liebster-award/";s:16:"/liebster-award/";s:34:"/blog/2014/10/02/make-pizza-dough/";s:18:"/make-pizza-dough/";s:56:"/2011/08/25/duck-magret-a-l’orange-with-grand-marnier/";s:51:"/recipe/pan-fried-duck-breast-with-creamed-cabbage/";s:10:"/cat/blog/";s:6:"/blog/";s:13:"/hello-world/";s:9:"/about-us";s:30:"/sitemap-pt-recipe-2014-09.xml";s:24:"/recipe_type-sitemap.xml";s:67:"/blog/2015/05/19/five-mother-sauces-every-serious-cook-should-know/";s:51:"/five-mother-sauces-every-serious-cook-should-know/";s:36:"/blog/recipe/trout-with-herb-butter/";s:31:"/recipe/trout-with-herb-butter/";s:41:"/wp-content/uploads/2014/07/peanutbrittle";s:1:"/";s:21:"/author/admin/page/2/";s:13:"/author/marco";s:13:"/author/admin";s:13:"/author/marco";s:69:"/recipe/iceberg-quarters-with-grilled-bacon-and-croutons/&disp=403159";s:57:"/recipe/iceberg-quarters-with-grilled-bacon-and-croutons/";s:119:"/how-to-make-espagnole-sauce/&sa=U&ved=0ahUKEwjVr8vhnOnKAhWFfhoKHQBpCyUQwW4IKDAJ&usg=AFQjCNGn-2rLp2vXgfQFL7_NTksGkjez5g";s:29:"/how-to-make-espagnole-sauce/";s:11:"_page1.html";s:1:"/";s:39:"/recipe/spaghetti-carbonara/?ref=fridgg";s:28:"/recipe/spaghetti-carbonara/";s:35:"/vegetarian-romanesco-risotto/olife";s:37:"/recipe/vegetarian-romanesco-risotto/";s:42:"/recipe/bcorn-on-the-cob/b-broasted/b-oven";s:37:"/recipe/corn-on-the-cob-roasted-oven/";s:36:"/recipe/corn-on-the-cob-roasted-oven";s:37:"/recipe/corn-on-the-cob-roasted-oven/";s:30:"/b100/b-bbest/b-thanksgiving-b";s:33:"/100-best-christmas-recipes-2015/";s:17:"/sponsored-posts/";s:12:"/contact-us/";s:33:"/100-best-halloween-recipes-2016/";s:32:"/50-best-halloween-recipes-2016/";s:76:"/100-best-halloween-recipes-2016/100-best-halloween-recipes-2016-appetizers/";s:32:"/50-best-halloween-recipes-2016/";s:43:"/recipe/beetroot-pearl-couscous-salad/Click";s:38:"/recipe/beetroot-pearl-couscous-salad/";s:18:"/recipe/7699/feed/";s:43:"/recipe/warming-winter-apple-aperitif/feed/";s:23:"/recipe_cuisine/africa/";s:24:"/recipe_cuisine/african/";s:24:"/recipe_cuisine/belgium/";s:24:"/recipe_cuisine/belgian/";s:23:"/recipe_cuisine/hawaii/";s:25:"/recipe_cuisine/hawaiian/";s:25:"/recipe_cuisine/morrocan/";s:25:"/recipe_cuisine/moroccan/";s:31:"/?s=Gordon Ramsey shepherds pie";s:37:"/recipe/gordon-ramsays-shepherds-pie/";s:9:"/?s=soups";s:6:"/soup/";s:8:"/?s=soup";s:6:"/soup/";s:4:"/?s=";s:1:"/";s:29:"/wp-content/uploads/sites/2/*";s:21:"/wp-content/uploads/*";s:29:"/wp-content/uploads/sites/3/*";s:21:"/wp-content/uploads/*";s:29:"/wp-content/uploads/sites/4/*";s:21:"/wp-content/uploads/*";s:29:"/wp-content/uploads/sites/5/*";s:21:"/wp-content/uploads/*";}';
//        $values = unserialize($blob);
//
//        foreach ($values as $request_url => $destination_url) {
//            $entity = $this->redirect_model->get($request_url, 'request_url');
//
//            if (is_array($entity) && !count($entity)) {
//                // Insert.
//                $entity_id = $this->redirect_model->create([
//                    'request_url' => $request_url,
//                    'destination_url' => $destination_url
//                ]);
//            }
//        }
//
//        $rules = $this->redirect_model->getRecords([],['id','asc']);
//
//        foreach($rules as $rule)
//        {
//            $request_url = $rule['request_url'];
//            $destination_url = $rule['destination_url'];
//
//            if (substr($request_url, 0, 4) === '/?s=') {
//                $new_request_url = '/search' . substr($request_url, 1, strlen($request_url));
//                $entity = $this->redirect_model->get($new_request_url, 'request_url');
//
//                if (is_array($entity) && !count($entity)) {
//                    $data = [
//                        'request_url' => $new_request_url,
//                        'destination_url' => $destination_url
//                    ];
//                    $this->redirect_model->create($data);
//                }
//            }
//        }
//
//        die;
    }
}