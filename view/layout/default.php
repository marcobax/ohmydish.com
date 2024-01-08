<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        $htmlTitle = 'Recipes and dishes';
        if (isset($page_title)) {
            $htmlTitle = $page_title;
        }

        /**
         * HTML page title should be between 29 and 61 characters.
         * If "- Ohmydish" fits in the title, it will be added.
         */
        if (!isset($force_page_title)) {
            $append = ' - Ohmydish';
            if (strlen($htmlTitle) + strlen($append) < 61) {
                $htmlTitle .= $append;
            }
        }
        ?>
        <title><?php echo $htmlTitle; ?></title>
        <?php if(!SessionHelper::hasPermission('is_admin')): ?>
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','<?php echo GTM_KEY; ?>');</script>
        <?php endif; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:site_name" content="Ohmydish" />
        <meta property="og:locale" content="en_GB" />
        <meta property="fb:app_id" content="258381235468829" />
        <meta name="twitter:site" content="@ohmydish_nl">
        <meta property="og:type" content="<?php echo isset($og_type)?$og_type:'website'; ?>" />
        <?php if(isset($og_type) && 'article' === $og_type): ?>
            <meta property="article:author" content="https://www.facebook.com/ohmydishNL/">
        <?php endif; ?>
        <?php if(isset($meta_article_published_time)): ?>
        <meta property="article:published_time" content="<?php echo $meta_article_published_time; ?>">
        <?php endif; ?>
        <?php if(isset($meta_article_modified_time)): ?>
            <meta property="article:modified_time" content="<?php echo $meta_article_modified_time; ?>">
        <?php endif; ?>
        <?php if(isset($meta_article_section)): ?>
        <meta property="article:section" content="<?php echo $meta_article_section; ?>">
        <?php endif; ?>
        <?php if(isset($meta_article_tags) && is_array($meta_article_tags) && count($meta_article_tags)): ?>
        <?php foreach($meta_article_tags as $meta_article_tag): ?>
        <meta property="article:tag" content="<?php echo $meta_article_tag; ?>">
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if (isset($page_title)) : ?>
            <meta property="og:image:alt" content="<?php echo $page_title; ?>" />
            <meta name="twitter:image:alt" content="<?php echo $page_title; ?>">
            <?php
            $twitterTitle = $page_title;
            $facebookTitle = $page_title;
            /**
             * Twitter card titles should be 55 characters or less.
             */
            if (strlen($twitterTitle) > 55) {
                $twitterTitle = substr($twitterTitle, 0, 52) . '...';
            }

            /**
             * Facebook titles should be 60 characters or less.
             */
            if (strlen($facebookTitle) > 60) {
                $facebookTitle = substr($facebookTitle, 0, 57) . '...';
            }
            ?>
            <meta property="og:title" content="<?php echo $facebookTitle; ?>" />
            <meta name="twitter:title" content="<?php echo $twitterTitle; ?>">
            <meta name="twitter:card" content="summary_large_image">
        <?php endif; ?>
        <meta property="og:image" content="<?php echo isset($og_image)?$og_image:DEFAULT_IMAGE; ?>" />
        <meta name="twitter:image" content="<?php echo isset($og_image)?$og_image:DEFAULT_IMAGE; ?>">
        <?php if(isset($meta_description)): ?>
        <meta name="description" content="<?php echo $meta_description; ?>">
        <meta property="og:description" content="<?php echo $meta_description; ?>" />
        <meta name="twitter:description" content="<?php echo $meta_description; ?>">
        <?php endif; ?>
        <meta name="author" content="Ohmydish">
        <link rel="icon" href="<?php echo Core::asset('favicon.ico'); ?>">
        <?php if(isset($page_canonical)): ?>
        <link rel="canonical" href="<?php echo $page_canonical; ?>"/>
        <meta property="og:url" content="<?php echo $page_canonical; ?>" />
        <?php endif; ?>
        <style>#ingredients h3, .with-stripe { display: flex; }#ingredients h3:before, #ingredients h3:after, .with-stripe:before, .with-stripe:after { color: black; content:''; flex:1; border-bottom: 2px solid #212529; }#ingredients h3:before, .with-stripe:before { margin: auto 0.5em auto 0; }#ingredients h3:after, .with-stripe:after { margin: auto 0 auto 0.5em; }.with-stripe-white:before, .with-stripe-white:after { color: white !important; border-color: white !important; }</style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link rel="preload" as="font" href="<?php echo Core::asset('fonts/open-iconic.woff'); ?>" type="font/woff2" crossorigin="anonymous" onload="this.rel='stylesheet'">
        <link rel="preload" as="style" href="<?php echo Core::asset('css/ohmydish.css'); ?>?v=42" onload="this.rel='stylesheet'">
        <?php if(isset($preload_image) && strlen($preload_image)): ?>
            <link rel="preload" as="image" href="<?php echo $preload_image; ?>">
        <?php endif; ?>
        <link rel="stylesheet" media="print" href="<?php echo Core::asset('css/ohmydish-print.css'); ?>?v=5">
        <?php foreach(['https://adservice.google.com/', 'https://googleads.g.doubleclick.net/', 'https://www.googletagservices.com/', 'https://tpc.googlesyndication.com/', 'https://image6.pubmatic.com/', 'https://www.gstatic.com/'] as $url): ?>
        <link rel="preconnect" href="<?php echo $url; ?>">
        <link rel="dns-prefetch" href="<?php echo $url; ?>">
        <?php endforeach; ?>
        <script type="application/ld+json"><?php echo (isset($structured_data))?$structured_data:TemplateHelper::getDefaultSchema(); ?></script>
        <?php if(SessionHelper::hasPermission('is_admin') && $_request->isCurrentController('admin')): ?>
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/bootstrap-multiselect.css'); ?>">
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/fontawesome-5.15.css'); ?>">
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/amsify.suggestags.css'); ?>">
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/Chart-2.9.4.css'); ?>">
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/dropzone.min.css'); ?>">
            <link rel="stylesheet" href="<?php echo Core::asset('css/admin/admin.css'); ?>">

            <script src="<?php echo Core::asset('js/admin/Chart-2.9.4.bundle.js?v=1'); ?>"></script>
        <?php else: ?>
            <script>
                (function(m,a,i,l,e,r){ m['MailerLiteObject']=e;function f(){
                    var c={ a:arguments,q:[]};var r=this.push(c);return "number"!=typeof r?r:f.bind(c.q);}
                    f.q=f.q||[];m[e]=m[e]||f.bind(f.q);m[e].q=m[e].q||f.q;r=a.createElement(i);
                    var _=a.getElementsByTagName(i)[0];r.async=1;r.src=l+'?v'+(~~(new Date().getTime()/1000000));
                    _.parentNode.insertBefore(r,_);})(window, document, 'script', 'https://static.mailerlite.com/js/universal.js', 'ml');
                var ml_account = ml('accounts', '2774500', 'r3i5b6n4t3', 'load');
            </script>
        <?php endif; ?>
        <link rel="alternate" href="<?php echo Core::url('/'); ?>" hreflang="x-default" />
        <?php if(isset($page_canonical) && ((isset($dutch_url) && strlen($dutch_url)) || (isset($french_url) && strlen($french_url)))): ?>
            <?php if (strlen($page_canonical)) : ?>
                <link rel="alternate" href="<?php echo $page_canonical; ?>" hreflang="en" />
            <?php endif; ?>
            <?php if (isset($dutch_url) && strlen($dutch_url)) : ?>
                <link rel="alternate" href="<?php echo $dutch_url; ?>" hreflang="nl" />
            <?php endif; ?>
            <?php if ((isset($french_url) && strlen($french_url))) : ?>
                <link rel="alternate" href="<?php echo $french_url; ?>" hreflang="fr" />
            <?php endif; ?>
        <?php endif; ?>
    </head>
    <body style="font-family: sans-serif; font-size: 20px;" data-login-url="<?php echo Core::url('login'); ?>" data-search-url="<?php echo Core::url('search/xhr_suggest'); ?>">
        <?php if(!SessionHelper::hasPermission('is_admin')): ?>
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo GTM_KEY; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <?php endif; ?>
        <div class="container d-none d-print-block">
            <div class="row">
                <div class="col-12 text-center">
                    <img height="70" width="263" src="<?php echo Core::asset('img/Ohmydish_logo.png'); ?>" alt="Ohmydish.com" data-pin-nopin="true">
                </div>
            </div>
        </div>
        <header>
            <?php if(SessionHelper::hasPermission('is_admin')): ?>
                <?php require_once(ROOT . '/view/admin/_navbar.php'); ?>
            <?php endif; ?>
            <div class="container">
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 text-center <?php echo (false===$hide_top_search)?'col-md-4 text-md-left':''; ?>">
                        <a href="<?php echo Core::url('/'); ?>" aria-label="Navigate to the homepage"><img id="site-logo" height="70" width="263" src="<?php echo Core::asset('img/Ohmydish_logo_526x70.png'); ?>" class="img-fluid" alt="Ohmydish.com" data-pin-nopin="true"></a>
                    </div>
                    <?php if (false === $hide_top_search): ?>
                        <div class="col-12 col-md-8 text-right pt-md-2">
                            <form action="<?php echo Core::url('search'); ?>" method="get" name="zoekformulier" id="zoekformulier">
                                <div class="input-group input-group-lg">
                                    <label for="s" class="sr-only">Find a recipe</label>
                                    <input type="text" name="s" id="s" placeholder="Find a recipe" class="form-control rounded" aria-describedby="btnGroupSearch" value="<?php echo isset($searchterm)?trim($searchterm):''; ?>" autocomplete="off">
                                    <div class="text-left d-none border w-100 bg-white rounded shadow suggestionbox"></div>
                                    <div class="input-group-append">
                                        <input type="submit" class="btn btn-success" value="Search" id="btnGroupSearch">
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <hr class="m-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                <button class="navbar-toggler ml-1 border-0 w-100" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-brand d-lg-none"><span class="navbar-toggler-icon"></span> Open menu</span>
                </button>
                <div class="collapse navbar-collapse justify-content-between align-items-center w-100" id="navbarNavDropdown">
                    <ul class="navbar-nav mx-auto text-center">
                        <li class="nav-item <?php echo $_request->isCurrentController('main')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('/'); ?>" aria-label="Navigate to the homepage">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item <?php echo $_request->isCurrentController('recipe')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('recipe-index'); ?>">Recipe index</a>
                        </li>
                        <li class="nav-item <?php echo $_request->isCurrentController('today')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('today'); ?>">Inspiration</a>
                        </li>
                        <li class="nav-item d-md-none <?php echo $_request->isCurrentController('course')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('course'); ?>">Course</a>
                        </li>
                        <li class="nav-item d-md-none <?php echo $_request->isCurrentController('theme')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('theme'); ?>">Theme</a>
                        </li>
                        <li class="nav-item d-md-none <?php echo $_request->isCurrentController('kitchen')?'active text-green':''; ?>">
                            <a class="nav-link text-dark" href="<?php echo Core::url('kitchen'); ?>">Kitchens</a>
                        </li>
                        <li class="nav-item dropdown d-none d-md-block <?php echo $_request->isCurrentController('course')?'active text-green':''; ?>">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="courseMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Course</a>
                            <?php $categories = $this->category_model->getRecords(['type' => 'course', 'total_entities' => '> 3'], ['title', 'asc']); ?>
                            <?php if(is_array($categories) && count($categories)): ?>
                            <div class="dropdown-menu" aria-labelledby="courseMenuLink">
                                <?php foreach($categories as $category): ?>
                                    <a class="dropdown-item" href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><?php echo $category['title']; ?></a>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php $categories = $this->category_model->getRecords(['type' => 'theme', 'total_entities' => '> 3'], ['title', 'asc']); ?>
                        <li class="nav-item pl-2 pl-sm-0 dropdown dropdown-hover position-static d-none d-md-block <?php echo $_request->isCurrentController('theme')?'active text-green':''; ?>">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="themesSecondMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Themes</a>
                            <?php if(is_array($categories) && count($categories)): ?>
                            <div class="dropdown-menu dropdown-menu-full" aria-labelledby="themesSecondMenuLink">
                                <div class="container">
                                    <div class="row w-100">
                                        <div class="col-3">
                                            <?php $results_per_column = (int) ceil((count($categories) / 4)); $i = 1; ?>
                                            <?php foreach($categories as $category): ?>
                                                <a class="dropdown-item" href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><?php echo $category['title']; ?></a>
                                                <?php if($i === $results_per_column): ?>
                                                    <?php echo "</div><div class=\"col-3\">"; $i = 0; ?>
                                                <?php endif; ?>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php $categories = $this->category_model->getRecords(['type' => 'kitchen', 'parent_id' => '0', 'total_entities' => '> 3'], ['title', 'asc']); ?>
                        <li class="nav-item pl-2 pl-sm-0 dropdown dropdown-hover position-static d-none d-md-block <?php echo $_request->isCurrentController('kitchen')?'active text-green':''; ?>">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="kitchenSecondMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Kitchen</a>
                            <?php if(is_array($categories) && count($categories)): ?>
                                <div class="dropdown-menu dropdown-menu-full" aria-labelledby="kitchenSecondMenuLink">
                                    <div class="container">
                                        <div class="row w-100">
                                            <div class="col-2">
                                                <?php $results_per_column = (int) ceil((count($categories) / 6)); $i = 1; ?>
                                                <?php foreach($categories as $category): ?>
                                                    <a class="dropdown-item" href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><?php echo $category['title']; ?></a>
                                                    <?php if($i === $results_per_column): ?>
                                                        <?php echo "</div><div class=\"col-2\">"; $i = 0; ?>
                                                    <?php endif; ?>
                                                    <?php $i++; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                        <?php $categories = $this->category_model->getRecords(['type' => 'blog', 'total_entities' => '> 3'], ['title', 'asc']); ?>
                        <?php if(is_array($categories) && count($categories) > 1): ?>
                            <li class="nav-item dropdown <?php echo $_request->isCurrentController('blog')?'active text-green':''; ?>">
                                <a class="nav-link dropdown-toggle text-dark" href="<?php echo Core::url('blog'); ?>" id="blogMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog</a>
                                <div class="dropdown-menu" aria-labelledby="blogMenuLink">
                                    <a class="dropdown-item" href="<?php echo Core::url('blog'); ?>">Alle blogs</a>
                                    <div class="dropdown-divider"></div>
                                    <?php foreach($categories as $category): ?>
                                        <?php if('blog' !== $category['slug']): ?>
                                            <a class="dropdown-item" href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><?php echo $category['title']; ?></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item <?php echo $_request->isCurrentController('blog')?'active text-green':''; ?>">
                                <a class="nav-link text-dark" href="<?php echo Core::url('blog'); ?>">Blog</a>
                            </li>
                        <?php endif; ?>
                        <?php $categories = $this->category_model->getRecords(['type' => 'hulp-in-de-keuken', 'total_entities' => '> 3'], ['title', 'asc']); ?>
                        <li class="nav-item dropdown <?php echo $_request->isCurrentController(['kennisbank', 'forum', 'kitchenhelp', 'question', 'converter'])?'active text-green':''; ?>">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="kennisbankVoorKokenMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Help</a>
                            <div class="dropdown-menu" aria-labelledby="kennisbankVoorKokenMenuLink">
                                <?php if (FORUM_ENABLED): ?>
                                <a class="dropdown-item" href="<?php echo Core::url('forum'); ?>">Forum</a>
                                <div class="dropdown-divider"></div>
                                <?php endif; ?>
<!--                                <a class="dropdown-item" href="--><?php //echo Core::url('cooking-questions'); ?><!--">Cooking questions</a>-->
<!--                                <a class="dropdown-item" href="--><?php //echo Core::url('blog/cooking-knowledge-base'); ?><!--">Kennisbank voor koken</a>-->
                                <?php if(is_array($categories) && count($categories)): ?>
                                    <?php foreach($categories as $category): if ($category['slug']==='kennisbank'){continue;} ?>
                                        <a class="dropdown-item" href="<?php echo Core::url('kitchenhelp/' . $category['slug']); ?>"><?php echo $category['title']; ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
<!--                                <div class="dropdown-divider"></div>-->
                                <a class="dropdown-item" href="<?php echo Core::url('converter'); ?>">Converter</a>
                            </div>
                        </li>
                        <?php if(!SessionHelper::isLoggedIn()): ?>
                            <li class="nav-item <?php echo $_request->isCurrentController('community')?'active text-green':''; ?>">
                                <a class="nav-link text-dark" href="<?php echo Core::url('login'); ?>">Log in</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown <?php echo $_request->isCurrentController('community')?'active text-green':''; ?>">
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="communityMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My favourites</a>
                                <div class="dropdown-menu" aria-labelledby="communityMenuLink">
                                    <a class="dropdown-item" href="<?php echo Core::url('community/saved-recipes'); ?>">Saved recipes</a>
                                    <div class="dropdown-divider"></div>
        <!--                            <a class="dropdown-item" href="--><?php //echo Core::url('community/weekmenu'); ?><!--">Weekmenu</a>-->
                                    <a class="dropdown-item" href="<?php echo Core::url('community/overzicht'); ?>">Community</a>
                                    <a class="dropdown-item" href="<?php echo Core::url('contact-us'); ?>">Feedback</a>
                                    <div class="dropdown-divider"></div>
    <!--                                <a class="dropdown-item" href="--><?php //echo Core::url('community/mijn-profiel'); ?><!--">Mijn profiel</a>-->
                                    <a class="dropdown-item" href="<?php echo Core::url('logout'); ?>">Log out</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <hr class="m-0">
            <div class="container">
                <?php require_once(ROOT . 'view/main/_flash.php'); ?>
            </div>
        </header>
        <?php echo $the_content; ?>
        <hr class="m-0 d-print-none">
        <?php if(!SessionHelper::hasPermission('is_admin') | !$_request->isCurrentController('admin')): ?>
            <?php
            $category_model = new CategoryModel();
            $featured_category = $category_model->getBySlug(TemplateHelper::getSeason(true) . '-recipes');
            $background_class = 'bg-green';
            ?>
            <?php if(isset($featured_category) && is_array($featured_category) && count($featured_category)): ?>
                <div class="text-white d-print-none <?php echo $background_class; ?>">
                    <div class="container pt-4 pb-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h3 class="font-weight-bold mb-2 with-stripe with-stripe-white"><?php echo $featured_category['title']; ?></h3>
                                <p><?php echo $featured_category['content']; ?></p>
                            </div>
                            <?php
                            $recipe_model = new RecipeModel();
                            $featured_category_recipes = $recipe_model->getRecords(['find_in_set' => ['categories' => $featured_category['id']]], ['published', 'desc'], [0,4]);
                            ?>
                            <?php if (is_array($featured_category_recipes) && count($featured_category_recipes)): ?>
                                <?php foreach($featured_category_recipes as $featured_category_recipe): ?>
                                    <div class="col-6 col-md-3 text-center">
                                        <div class="img-hover-zoom">
                                            <a href="<?php echo Core::url('recipe/' . $featured_category_recipe['slug']); ?>">
                                                <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($featured_category_recipe); ?>" alt="<?php echo $featured_category_recipe['title']; ?>" class="img-fluid" loading="lazy" data-pin-nopin="true">
                                            </a>
                                            <?php TemplateHelper::includeHeart($featured_category_recipe, 'smaller'); ?>
                                        </div>
                                        <h4 class="mt-2"><a href="<?php echo Core::url('recipe/' . $featured_category_recipe['slug']); ?>" class="text-white"><?php echo $featured_category_recipe['title']; ?></a></h4>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="col-12 text-center pt-2">
                                <a class="btn btn-warning" href="<?php echo Core::url('theme/' . $featured_category['slug']); ?>">More <?php echo strtolower($featured_category['title']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <footer class="bg-light p-sm-4 d-print-none">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-9">
                            <h4>About Ohmydish</h4>
                            <p>Hi! My name is Véronique, I'm from Limburg (The Netherlands) and live in the south of France. I'm passionate about cooking, my kitchen garden, sharing recipes and our three dogs!</p>
                            <p>Ohmydish has been around since 2014 and normally 5x a week I will publish a new recipe or any other cooking related content. Recipes at Ohmydish are not specifically healthy, vegan, easy or cheap but have all been made with love and passion. You can <a href="<?php echo Core::url('contact-us'); ?>">contact me</a> if you have any questions.</p>
                            <p class="text-center">
                                <a href="https://www.instagram.com/ohmydish/" target="_blank" rel="noopener">@ohmydish on Instagram</a> |
                                <a href="https://www.pinterest.com/ohmydish/" target="_blank" rel="noopener">@ohmydish on Pinterest</a> |
                                <a href="<?php echo Core::url('about-us'); ?>">About us</a>
                            </p>
                        </div>
                        <div class="col-12 col-md-3 text-right">
                            <img width="550" height="550" src="<?php echo Core::url('uploads/2023/veronique-2023-square.jpg'); ?>" class="img-fluid rounded-top" alt="Veronique from Ohmydish" data-pin-nopin="true" loading="lazy">
                        </div>
                    </div>
                </div>
            </footer>
            <?php if(isset($related_recipes)): ?>
            <div id="related-recipes" class="text-white bg-green d-none d-sm-block d-print-none">
                <div class="container pt-4 pb-4">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="font-weight-bold mb-2 text-center with-stripe with-stripe-white">Perhaps you will like this too</h3>
                        </div>
                        <?php if (is_array($related_recipes) && count($related_recipes)): ?>
                            <?php foreach($related_recipes as $related_recipe): ?>
                                <div class="col-6 col-md-3 text-center">
                                    <div class="img-hover-zoom">
                                        <a href="<?php echo Core::url('recipe/' . $related_recipe['slug']); ?>">
                                            <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($related_recipe); ?>" alt="<?php echo $related_recipe['title']; ?>" class="img-fluid" data-pin-nopin="true" loading="lazy">
                                        </a>
                                        <?php TemplateHelper::includeHeart($related_recipe, 'smaller'); ?>
                                    </div>
                                    <h4 class="mt-2"><a href="<?php echo Core::url('recipe/' . $related_recipe['slug']); ?>" class="text-white"><?php echo $related_recipe['title']; ?></a></h4>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <footer class="bg-grey pt-4 pb-4 mt-4 mt-sm-0 d-print-none">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-12 col-lg-6 text-center">
                            <div id="mlb2-3448615" class="ml-form-embedContainer ml-subscribe-form ml-subscribe-form-3515386 mb-2">
                                <div class="ml-form-align-center">
                                    <div class="ml-form-embedWrapper embedForm">
                                        <div class="ml-form-embedBody ml-form-embedBodyDefault row-form">
                                            <div class="ml-form-embedContent">
                                                <h3 class="font-weight-bold">Get the latest recipes</h3>
                                                <p>Would you like to receive the latest recipes straight into your mailbox, just like 15.000+ others?</p>
                                            </div>
                                            <form class="ml-block-form" action="https://static.mailerlite.com/webforms/submit/f8w5o5" data-code="f8w5o5" method="post" target="_blank">
                                                <div class="ml-form-formContent">
                                                    <div class="ml-form-fieldRow ml-last-item">
                                                        <div class="ml-field-group ml-field-email ml-validate-email ml-validate-required">
                                                            <input type="email" class="form-control form-control-lg" data-inputmask="" name="fields[email]" placeholder="What is your e-mail address?" autocomplete="email" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="ml-submit" value="1">
                                                <div class="ml-form-embedSubmit mt-2">
                                                    <button type="submit" class="btn btn-success btn-lg btn-block">Send me the recipes</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="ml-form-successBody row-success" style="display:none">
                                            <div class="ml-form-successContent">
                                                <h4>Success!</h4>
                                                <p>Twice a month you will now get the latest recipes.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script> function ml_webform_success_3448615(){var r=ml_jQuery||jQuery;r(".ml-subscribe-form-3515386 .row-success").show(),r(".ml-subscribe-form-3515386 .row-form").hide()}</script>
                            <img src="https://track.mailerlite.com/webforms/o/3448615/f0u3e9?v1611829735" width="1" height="1" style="max-width:1px;max-height:1px;visibility:hidden;padding:0;margin:0;display:block;border:0;" alt=".">
                            <script src="https://static.mailerlite.com/js/w/webforms.min.js?v52c1aad546f96d894e3716ba78e7fa42"></script>
                        </div>
                    </div>
                    <div class="row mb-4 bottom-links">
                        <div class="col-12 text-center mb-4">
                            <h3 class="fancy with-stripe">Easily find recipes and dishes</h3>
                            <h4>We'd love to help you cook amazing dishes with these handy recipes</h4>
                        </div>
                        <?php $recipes = $this->recipe_model->getRecords([],['views','desc'], [0,5]); ?>
                        <div class="col-12 col-sm-3">
                            <h5>Most viewed recipes</h5>
                            <ul class="basic-list">
                                <?php if(is_array($recipes) && count($recipes)): ?>
                                    <?php foreach($recipes as $recipe): ?>
                                        <li><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="text-dark"><?php echo $recipe['title']; ?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="d-none d-sm-block col-sm-3">
                            <h5>Course</h5>
                            <ul class="basic-list">
                                <li><a href="<?php echo Core::url('course/main-course'); ?>" class="text-dark">Main courses</a></li>
                                <li><a href="<?php echo Core::url('course/salads'); ?>" class="text-dark">Salads</a></li>
                                <li><a href="<?php echo Core::url('course/desserts'); ?>" class="text-dark">Desserts</a></li>
                                <li><a href="<?php echo Core::url('course/lunch'); ?>" class="text-dark">Lunch</a></li>
                                <li><a href="<?php echo Core::url('course/soup'); ?>" class="text-dark">Soup</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-3 mt-4 mt-md-0">
                            <h5 class="d-sm-none">Dish</h5>
                            <h5 class="d-none d-sm-block">Type of dish</h5>
                            <ul class="basic-list">
                                <li><a href="<?php echo Core::url('theme/meat'); ?>" class="text-dark">Meat</a></li>
                                <li><a href="<?php echo Core::url('theme/fish'); ?>" class="text-dark">Fish</a></li>
                                <li><a href="<?php echo Core::url('theme/chicken'); ?>" class="text-dark">Chicken</a></li>
                                <li><a href="<?php echo Core::url('theme/vegetarian'); ?>" class="text-dark">Vegetarian</a></li>
                                <li><a href="<?php echo Core::url('theme/game'); ?>" class="text-dark">Game</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-3 mt-4 mt-md-0">
                            <h5>Kitchen</h5>
                            <ul class="basic-list">
                                <li><a href="<?php echo Core::url('kitchen/world'); ?>" class="text-dark">World</a></li>
                                <li><a href="<?php echo Core::url('kitchen/italian'); ?>" class="text-dark">Italian</a></li>
                                <li><a href="<?php echo Core::url('kitchen/french'); ?>" class="text-dark">French</a></li>
                                <li><a href="<?php echo Core::url('kitchen/spanish'); ?>" class="text-dark">Spanish</a></li>
                                <li><a href="<?php echo Core::url('kitchen/asian'); ?>" class="text-dark">Asian</a></li>
                            </ul>
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <a href="<?php echo Core::url('recipe-index'); ?>" class="btn btn-large btn-warning">View all recipes</a>
                        </div>
                    </div>
                    <hr class="d-none d-sm-block">
                    <div class="row">
                        <div class="col-12 text-center bg-dark text-white rounded">
                            <p class="m-0">
                                <a href="<?php echo Core::url('about-us'); ?>" class="h6 text-white">About us</a> -
                                <a href="<?php echo Core::url('contact-us'); ?>" class="h6 text-white">Question or comment?</a> -
                                <a href="<?php echo Core::url('contact-us'); ?>" class="h6 text-white">Advertise on Ohmydish</a> -
                                <a href="<?php echo Core::url('privacystatement'); ?>" class="h6 text-white">Privacy statement</a> -
                                <a href="<?php echo Core::url('contact-us'); ?>" class="h6 text-white">Contact us</a>
                            </p>
                        </div>
                    </div>
                    <hr class="d-none d-sm-block">
                </div>
            </footer>
        <?php else: ?>
        <footer class="mb-5"></footer>
        <?php endif; ?>
        <a id="back-to-top" href="#" class="btn btn-secondary btn-lg shadow-lg d-print-none" role="button"><i class="oi oi-chevron-top"></i></a>
        <link rel="stylesheet" href="<?php echo Core::asset('css/open-iconic-bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo Core::asset('css/ekko-lightbox.css'); ?>">
        <?php if(!SessionHelper::hasPermission('is_admin') || !$_request->isCurrentController('admin')): ?>
            <script src="<?php echo Core::asset('js/jquery.min.js'); ?>" defer></script>
            <script src="<?php echo Core::asset('js/bootstrap.min.js'); ?>" defer></script>
            <?php if(false): ?>
            <script src="<?php echo Core::asset('js/darkmode.js?v=1'); ?>"></script>
            <?php endif; ?>
            <?php if (!SessionHelper::hasPermission('is_admin') && !$_request->isCurrentAction('show404')): ?>
            <script type='text/javascript'>
                let adsense_loaded = false;
                function loadAdsense() {
                    if (false === adsense_loaded) {
                        var e = document.createElement("script");
                        e.type = "text/javascript", e.async = !0, e.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9317326179491638";
                        var a = document.getElementsByTagName("script")[0];
                        a.parentNode.insertBefore(e, a)
                        adsense_loaded = true;
                    }
                }
                let la = !1;
                window.addEventListener("scroll", function () {
                    if (false === adsense_loaded) {
                        (0 != document.documentElement.scrollTop && !1 === la || 0 != document.body.scrollTop && !1 === la) && (!function () {
                            loadAdsense();
                        }(), la = !0)
                    }
                }, !0);
                window.addEventListener('mousemove', function() {
                    if (false === adsense_loaded) {
                        loadAdsense();
                    }
                });
            </script>
            <?php endif; ?>
        <?php endif; ?>
        <script src="<?php echo Core::asset('js/ekko-lightbox.min.js'); ?>" defer></script>
        <script src="<?php echo Core::asset('js/jquery.star-rating-svg.min.js?v='); ?>" defer></script>
        <script src="<?php echo Core::asset('js/ohmydish.js?v=12'); ?>" defer></script>
        <script async defer src="//assets.pinterest.com/js/pinit.js" data-pin-hover="true" data-pin-tall="true"></script>
        <?php if(SessionHelper::hasPermission('is_admin') && $_request->isCurrentController('admin')): ?>
            <script src="<?php echo Core::asset('js/admin/popper.js'); ?>"></script>
            <script src="<?php echo Core::asset('js/jquery.min.js'); ?>"></script>
            <script src="<?php echo Core::asset('js/bootstrap.min.js'); ?>"></script>
            <script src="<?php echo Core::asset('js/admin/bootstrap-multiselect.js'); ?>"></script>
            <script src="<?php echo Core::asset('js/admin/ckeditor5-build-classic/ckeditor.js?v=1'); ?>"></script>
            <script src="<?php echo Core::asset('js/admin/jquery.amsify.suggestags.js?v=1'); ?>"></script>
            <script src="<?php echo Core::asset('js/admin/dropzone.min.js'); ?>"></script>
            <script src="<?php echo Core::asset('js/admin/admin.js?v=8'); ?>"></script>
        <?php endif; ?>
    <?php if(isset($disqusEnabled) && true === $disqusEnabled): ?>
        <script id="dsq-count-scr" src="//ohmydish-com.disqus.com/count.js" async></script>
    <?php endif; ?>
    </body>
</html>
