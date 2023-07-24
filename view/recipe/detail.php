<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if('draft' === $recipe['status']): ?>
                <h3 class="bg-danger text-white text-center p-2">Not yet published (concept)</h3>
            <?php endif; ?>
            <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin') && 'publish' === $recipe['status']): ?>
                <a class="btn btn-warning btn-lg d-print-none" href="<?php echo Core::url('admin/recipe_edit/' . $recipe['id']); ?>">Edit</a>
            <?php endif; ?>
            <h1 class="mb-0 mt-3 font-weight-bold"><?php echo ucwords($recipe['title']); ?></h1>
            <?php if($recipe['average_rating']): ?>
                <div class="mt-2 starrating">
                    <span class="nowrap">
                        <?php for($i=0; $i<$recipe['average_rating'];$i++): ?>
                            <span class="oi oi-star h4" style="color: orange;" title="Average score" aria-hidden="true"></span>
                        <?php endfor; ?>
                        <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                            <span class="oi oi-star h4" style="color: lightgrey;" title="Average score" aria-hidden="true"></span>
                        <?php endfor; ?>
                    </span>
                    <span class="text-muted small">(<?php echo $recipe['total_votes']; ?>)</span>
                </div>
            <?php else: ?>
                <div class="mt-2 starrating" onclick="document.location.href='#ranking';">
                    <span class="nowrap">
                        <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                            <span class="oi oi-star h4" style="color: lightgrey;" title="Average score" aria-hidden="true"></span>
                        <?php endfor; ?>
                    </span>
                    <span class="text-muted small">(0)</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <p class="text-left mt-2"><i><?php echo $recipe['excerpt']; ?></i></p>
        </div>
        <div class="col-12">
            <a href="#preparation" class="mb-4 d-block">
                <div style="text-transform: uppercase;" class="bg-grey d-block text-center p-2">
                &darr; Jump To Recipe
                </div>
            </a>

            <div class="row">
                <div class="col-12 col-md-6">
                    <h3 class="mb-2 text-green font-weight-bold">In this recipe:</h3>
                    <p class="my-1">The main thing:</p>
                    <div class="basic-list bottom-links">
                        <ul>
                            <li class="pl-0 pl-md-3">
                                <a href="#preparation">Take Me To The Recipe</a>
                            </li>
                            <?php if($recipe['seo_text'] && strlen($recipe['seo_text'])): ?>
                                <li class="pl-0 pl-md-3">
                                <a href="#about">About <?php echo ucwords($recipe['title']); ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <p class="my-1 mt-3">Other stuff:</p>
                    <div class="basic-list bottom-links">
                        <ul>
                            <li class="pl-0 pl-md-3">
                                <a href="#ingredients">Ingredients</a>
                            </li>
                            <li class="pl-0 pl-md-3">
                                <a href="#kitchen_equipment">Kitchen Equipment</a>
                            </li>
                            <li class="pl-0 pl-md-3">
                                <a href="#faq"><?php echo $recipe['title']; ?>: Frequently Asked Questions</a>
                            </li>
                            <?php if(isset($disqusEnabled) && true === $disqusEnabled): ?>
                                <li class="pl-0 pl-md-3">
                                    <a href="#disqus_thread">Reviews</a>
                                </li>
                            <?php endif; ?>
                            <li class="pl-0 pl-md-3">
                                <a href="#ranking">Rank This Recipe</a>
                            </li>
                            <li class="pl-0 pl-md-3">
                                <a href="#related-recipes">Other Recipes You Can Try</a>
                            </li>
                        </ul>
                    </div>
                    <script type="application/ld+json">
                        {
                            "@context": "https://schema.org",
                            "@type": "BreadcrumbList",
                            "itemListElement": [{
                                "@type": "ListItem",
                                "position": 1,
                                "name": "Take Me To The Recipe",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#preparation"
                            },
                            <?php if($recipe['seo_text'] && strlen($recipe['seo_text'])): ?>
                            {
                                "@type": "ListItem",
                                "position": 2,
                                "name": "About",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#about"
                            },
                            <?php endif; ?>
                            {
                                "@type": "ListItem",
                                "position": 3,
                                "name": "Ingredients",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#ingredients"
                            },{
                                "@type": "ListItem",
                                "position": 4,
                                "name": "Kitchen Equipment",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#kitchen_equipment"
                            },{
                                "@type": "ListItem",
                                "position": 5,
                                "name": "<?php echo $recipe['title']; ?>: Frequently Asked Questions",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#faq"
                            },
                            <?php if(isset($disqusEnabled) && true === $disqusEnabled): ?>
                            {
                                "@type": "ListItem",
                                "position": 6,
                                "name": "Reviews",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#disqus_thread"
                            },
                            <?php endif; ?>
                            {
                                "@type": "ListItem",
                                "position": 7,
                                "name": "Rank This Recipe",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#ranking"
                            },{
                                "@type": "ListItem",
                                "position": 8,
                                "name": "Other Recipes You Can Try",
                                "item": "<?php echo Core::url('recipe/' . $recipe['slug']); ?>#related-recipes"
                            }]
                        }
                    </script>
                </div>
                <div class="col-12 col-md-6 d-none d-md-block">
                    <figure>
                        <picture>
                            <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="featured-image-sm img-fluid shadow rounded border">
                        </picture>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($recipe['seo_text'] && $recipe['seo_text'] !== ''): ?>
    <div class="container" id="about">
        <div class="row">
            <div class="col-12">
                <div id="the-content" class="unselectable"><?php echo Core::fixHeadings(Core::nl2p($recipe['seo_text'])); ?></div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="row m-0 mb-2 position-relative featured-image-container d-print-none text-center">
    <div class="mx-2 mx-md-0 w-100">
        <?php TemplateHelper::includeHeart($recipe); ?>
        <figure>
            <picture>
                <source media="(max-width:768px)" srcset="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>">
                <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="featured-image-sm img-fluid shadow rounded border">
            </picture>
        </figure>
    </div>
</div>
<div class="row m-0 mb-2 d-md-none">
    <div class="col-12 text-center pt-2">
        <strong class="mr-2"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> <?php echo Template::displayTimeToDescription($recipe['displaytime']); ?></span></strong>
        <strong><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> <?php echo Template::displayYield($recipe['yield']); ?></span></strong>
        <strong class="ml-2"><span class="nowrap"><span class="oi oi-layers h6" title="Categorie" aria-hidden="true"></span> <a href="<?php echo Core::url($main_category['type'] . '/' . $main_category['slug']); ?>" class="text-dark"><?php echo $main_category['title']; ?></a></span></strong>
        <?php if ($recipe['calories'] && (int) $recipe['calories']): ?>
            <strong class="ml-2" data-toggle="tooltip" title="Estimated calories per person"><span class="nowrap"><span class="oi oi-graph h6" aria-hidden="true"></span> <?php echo $recipe['calories']; ?></span> calories p.p.</strong>
        <?php endif; ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="d-none d-md-block col-12 text-center d-print-none overflow-hidden">
            <?php require(ROOT . '/view/_advertenties/728x90_1.php'); ?>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-8">
            <div class="d-none d-print-block mb-2 pt-3">
                <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid" loading="lazy">
            </div>
            <?php if($recipe['ingredients']): ?>
                <div class="basic-list ingredients" id="ingredients">
                    <h3 class="fancy mt-0 mb-0 with-stripe">Ingredients</h3>
                    <div class="text-center d-print-none">
                        <span class="h6 m0 p0 text-center text-success">(&check; Tip: click to check off)</span>
                    </div>
                    <hr class="d-md-none mb-0">
                    <?php echo str_replace('<li>', '<li class="pl-0 pl-md-3">', $recipe['ingredients']); ?>
                </div>
            <?php endif; ?>
            <?php if($recipe['ingredients_image']): ?>
                <div class="text-center d-print-none">
                    <img data-remote="<?php echo Core::upload_path($recipe['ingredients_image']); ?>" data-gallery="recipe-gallery" data-toggle="lightbox" data-title="<?php echo ucwords($recipe['title']); ?> Ingredients" width="1024" height="768" src="<?php echo Core::upload_path($recipe['ingredients_image']); ?>" class="img-fluid mt-2 rounded" alt="<?php echo $recipe['title']; ?> ingredients" loading="lazy">
                    <br>
                    <span class="h6 m0 p0">&uarr; click on the photo to enlarge</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-8">
            <?php if($recipe['kitchen_equipment']): ?>
                <div class="basic-list kitchen_equipment" id="kitchen_equipment">
                    <hr class="d-md-none">
                    <h3 class="fancy with-stripe mb-0">Kitchen equipment</h3>
                        <div class="text-center d-print-none">
                            <span class="h6 m0 p0 text-center text-success">(&check; Tip: click to check off)</span>
                        </div>
                    <hr class="d-md-none mb-0">
                    <?php echo str_replace('<li>', '<li class="pl-0 pl-md-3">', $recipe['kitchen_equipment']); ?>
                </div>
            <?php endif; ?>
            <hr class="d-print-none">
            <div class="text-center d-none d-md-block d-print-none">
                <div class="btn-group btn-block d-print-none" role="group">
                    <a href="#" onclick="window.print(); return false;" class="btn btn-outline-success mb-1">Print recipe</a>
                    <a href="<?php echo Core::url('recipe/comments/' . $recipe['slug']); ?>" class="btn btn-outline-success mb-1">Comments (<?php echo $recipe['total_comments']; ?>)</a>
                </div>
                <div class="d-print-none">
                    <a href="<?php echo Core::url('community/save-recipe/' . $recipe['slug']); ?>" class="btn btn-block btn-warning text-dark mb-1">Save to collection</a>
                </div>
            </div>
            <div class="adspace text-center d-print-none">
                <?php require(ROOT . '/view/_advertenties/300x250_1.php'); ?>
                <br>
                <?php require(ROOT . '/view/_advertenties/300x250_3.php'); ?>
            </div>
        </div>
        <div class="col-12" id="preparation">
            <div class="text-center"><span class="h2 fancy with-stripe"><?php echo ucwords($recipe['title']); ?></span></div>
            <div class="d-none d-md-block">
                <hr class="d-print-none">
                <div class="row">
                    <div class="col-12 text-center">
                        <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> <?php echo Template::displayTimeToDescription($recipe['displaytime']); ?></span></strong>
                        <strong><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> <?php echo Template::displayYield($recipe['yield']); ?></span></strong>
                        <?php if (isset($main_category) && count($main_category)): ?>
                            <strong class="ml-4"><span class="nowrap"><span class="oi oi-layers h6" title="Categorie" aria-hidden="true"></span> <a href="<?php echo Core::url($main_category['type'] . '/' . $main_category['slug']); ?>" class="text-dark"><?php echo $main_category['title']; ?></a></span></strong>
                        <?php endif; ?>
                        <?php if ($recipe['calories'] && (int) $recipe['calories']): ?>
                            <strong class="ml-2" data-toggle="tooltip" title="Estimated calories per person"><span class="nowrap"><span class="oi oi-graph h6" aria-hidden="true"></span> <?php echo $recipe['calories']; ?></span> calories p.p.</strong>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
            </div>
            <div class="text-center d-none d-print-block">
                <p>View the original recipe via:<br>
                    <strong><?php echo Core::url('recipe/' . $recipe['slug']); ?></strong></p>
                <hr>
            </div>
	    <div id="the-content" class="unselectable"><?php echo Core::fixHeadings(Core::nl2p($recipe['content'])); ?></div>
        <div class="row rating d-print-none" id="ranking">
            <div class="col-12 text-center">
                <hr>
                <strong>Rank this recipe!</strong>
                <div class="star-rating" data-rating="<?php echo $recipe['average_rating']; ?>" data-url="<?php echo Core::url('rating/xhr_rating?pt=recipe&slug=' . $recipe['slug']); ?>"></div>
                <span id="total-votes"><?php echo $recipe['rating_label']; ?></span>
            </div>
        </div>
        <div class="d-none d-lg-block col-lg-2">
            <div class="adspace text-center d-print-none mb-3">
                <?php require(ROOT . '/view/_advertenties/300x250_2.php'); ?>
                <br>
                <?php require(ROOT . '/view/_advertenties/300x250_4.php'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-none d-md-block col-12 text-center d-print-none overflow-hidden">
            <?php require(ROOT . '/view/_advertenties/728x90_2.php'); ?>
        </div>
    </div>
<!--    <div class="row d-print-none">-->
<!--        <div class="col-12">-->
<!--            <p class="text-center d-md-none">-->
<!--                <a href="--><?php //echo Core::url('recipe/comments/' . $recipe['slug']); ?><!--" class="text-dark text-muted">View comments (--><?php //echo $recipe['total_comments']; ?><!--)</a>-->
<!--            </p>-->
<!--            <hr class="d-md-none">-->
<!--        </div>-->
<!--    </div>-->
            <?php if($recipe['faq']): ?>
                <div class="row d-print-none mb-3 faq" id="faq">
                    <div class="col-12">
                        <div class="bg-grey rounded border shadow-sm p-4">
                            <h3 class="mb-0 text-green font-weight-bold"><?php echo ucwords($recipe['title']); ?>: frequently asked questions</h3>
                            <?php echo Core::nl2p($recipe['faq']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-print-none row w-100">
                <?php if(is_array($category_dish_types) && count($category_dish_types)): ?>
                    <div class="col-12 text-center mb-4">
                        <?php foreach($category_dish_types as $category_dish_type): ?>
                            <a href="<?php echo Core::url('dishtype/' . strtolower($category_dish_type['slug'])); ?>" class="badge badge-primary"><?php echo ucfirst($category_dish_type['title']); ?></a>
                        <?php endforeach; ?>
    </div>
                <?php endif; ?>
<?php if(false): ?>
                <p class="bg-dark text-white text-center p-2 rounded">
                    Did you make this recipe or are you planning to?<br>
                    Mention <a href="https://www.instagram.com/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">@ohmydish</a>, tag <a href="https://www.instagram.com/explore/tags/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">#ohmydish</a> on Instagram or <a class="text-green" href="<?php echo Core::url('community/save-recipe/' . $recipe['slug']); ?>">save this recipe</a>!
                </p>
                <hr>
                <?php endif; ?>
            </div>
        </div>
    <?php if(isset($disqusEnabled) && true === $disqusEnabled): ?>
	<div id="disqus_thread"></div>
	<script>
	    var disqus_config = function () {
	    this.page.url = '<?php echo $page_canonical; ?>';
	    this.page.identifier = <?php echo $pageId; ?>;
	    };

	    (function() {
		var d = document, s = d.createElement('script');
		s.src = 'https://ohmydish-com.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
	    })();
	</script>
    <?php endif; ?>
</div>
