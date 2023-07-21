<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if('draft' === $recipe['status']): ?>
                <h3 class="bg-danger text-white text-center p-2">Not yet published (concept)</h3>
            <?php endif; ?>
            <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin') && 'publish' === $recipe['status']): ?>
                <a class="btn btn-warning btn-lg d-print-none" href="<?php echo Core::url('admin/recipe_edit/' . $recipe['id']); ?>">Edit</a>
            <?php endif; ?>
            <h1 class="fancy text-center mb-0 with-stripe"><?php echo $recipe['title']; ?></h1>
            <?php if($recipe['average_rating']): ?>
                <div class="text-center mt-2 starrating">
                    <span class="nowrap pl-4">
                        <?php for($i=0; $i<$recipe['average_rating'];$i++): ?>
                            <span class="oi oi-star h4" style="color: orange;" title="Average score" aria-hidden="true"></span>
                        <?php endfor; ?>
                        <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                            <span class="oi oi-star h4" style="color: lightgrey;" title="Average score" aria-hidden="true"></span>
                        <?php endfor; ?>
                    </span>
                    <span class="text-muted small">(<?php echo $recipe['total_votes']; ?>)</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <p class="text-left text-muted mt-2"><?php echo $recipe['excerpt']; ?>
                <?php if(isset($disqusEnabled) && true === $disqusEnabled): ?>
                    <a href="<?php echo $page_canonical; ?>#disqus_thread">Leave a comment</a>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-12 d-md-none text-center">
            <div class="btn-group text-center mb-4">
                <a href="#ingredients" class="btn btn-sm btn-outline-primary">Ingredients</a>
                <a href="#kitchen_equipment" class="btn btn-sm btn-outline-primary">Kitchen equipment</a>
                <a href="#preparation" class="btn btn-sm btn-outline-primary">Preparation</a>
            </div>
        </div>
    </div>
</div>
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
    <div class="row">
        <div class="col-12 col-md-4 col-lg-3">
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
                    <?php echo $recipe['ingredients']; ?>
                </div>
            <?php endif; ?>
            <?php if($recipe['ingredients_image']): ?>
                <div class="text-center d-print-none">
                    <img data-remote="<?php echo Core::upload_path($recipe['ingredients_image']); ?>" data-gallery="recipe-gallery" data-toggle="lightbox" data-title="<?php echo $recipe['title']; ?> ingredients" width="1024" height="768" src="<?php echo Core::upload_path($recipe['ingredients_image']); ?>" class="img-fluid mt-2 rounded" alt="<?php echo $recipe['title']; ?> ingredients" loading="lazy">
                    <br>
                    <span class="h6 m0 p0">&uarr; click on the photo to enlarge</span>
            </div>
            <?php endif; ?>
            <?php if($recipe['kitchen_equipment']): ?>
                <div class="basic-list kitchen_equipment" id="kitchen_equipment">
                    <hr class="d-md-none">
                    <h3 class="fancy with-stripe mb-0">Kitchen equipment</h3>
                        <div class="text-center d-print-none">
                            <span class="h6 m0 p0 text-center text-success">(&check; Tip: click to check off)</span>
                        </div>
                    <hr class="d-md-none mb-0">
                    <?php echo $recipe['kitchen_equipment']; ?>
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
        <div class="col-12 col-md-8 col-lg-7" id="preparation">
            <div class="text-center"><span class="h2 fancy with-stripe">Preparation</span></div>
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
                    <strong><?php echo Core::url('recipe/' . $recipe['slug']) ?></strong></p>
                <hr>
            </div>
	    <div id="the-content" class="unselectable"><?php echo Core::fixHeadings(Core::nl2p($recipe['content'])); ?></div>
            <?php if($recipe['faq']): ?>
                <div class="row d-print-none mb-3 faq">
                    <div class="col-12">
                        <div class="bg-grey rounded border shadow-sm p-4">
                            <h3 class="mb-0 text-green font-weight-bold"><?php echo $recipe['title']; ?>: frequently asked questions</h3>
                            <?php echo Core::nl2p($recipe['faq']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
<?php if(false): ?>
            <div class="row rating d-print-none">
                <div class="col-12 text-center">
                    <hr>
                    <strong>What do you think of this recipe?</strong>
                    <div class="star-rating" data-rating="<?php echo $recipe['average_rating']; ?>" data-url="<?php echo Core::url('rating/xhr_rating?pt=recipe&slug=' . $recipe['slug']); ?>"></div>
                    <span id="total-votes"><?php echo $recipe['rating_label']; ?></span>
                </div>
            </div>
<?php endif; ?>
            <div class="d-print-none">
                <hr>
                <?php if(is_array($category_dish_types) && count($category_dish_types)): ?>
                    <div class="text-center mb-4">
                        <?php foreach($category_dish_types as $category_dish_type): ?>
                            <a href="<?php echo Core::url('dishtype/' . strtolower($category_dish_type['slug'])); ?>" class="badge badge-primary"><?php echo ucfirst($category_dish_type['title']); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <hr>
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
    <div class="row d-print-none">
        <div class="col-12">
            <p class="text-center d-md-none">
                <a href="<?php echo Core::url('recipe/comments/' . $recipe['slug']); ?>" class="text-dark text-muted">View comments (<?php echo $recipe['total_comments']; ?>)</a>
            </p>
            <hr class="d-md-none">
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
