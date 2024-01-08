<?php
$show_seo_text = true;
$query = $this->request->getQuery();
if (is_array($query) && count($query)) {
    if (array_key_exists('p', $query) && (intval($query['p']) > 1)) {
        $show_seo_text = false;
    }
}
$section = match ($category['type']) {
    'course' => 'course',
    'kitchen' => 'kitchen',
    'dishtype' => 'dish type',
    'theme' => 'theme',
    default => 'category',
};
?>
<div class="golden mb-2 shadow-sm">
    <div class="d-flex justify-content-center">
        <div style="<?php if(array_key_exists('featured_image', $category) && strlen($category['featured_image'])): ?>background: url('<?php echo TemplateHelper::getFeaturedImage($category); ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;<?php else: ?>background: #333;<?php endif; ?>" class="w-100 p-4">
            <h1 class="fancy text-white text-center m-0 mt-4" style="font-size: 3em; text-shadow: 3px 3px #000;">
                <?php echo ucfirst($section) . ': ' . strtolower($category['title']); ?>
            </h1>
            <h2 class="h5 m-0 text-white text-center" style="text-shadow: 2px 2px #000;"><?php echo TemplateHelper::formatNumber($category['total_entities']); ?> recipes</h2>
        </div>
    </div>
</div>
<div class="container">
    <div class="row text-muted mb-2">
        <div class="col-12 col-md-10 offset-md-1 align-items-center">
            <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin')): ?>
                <a class="btn btn-warning btn-lg" href="<?php echo Core::url('admin/category_edit/' . $category['id']); ?>">Edit</a>
            <?php endif; ?>
            <?php if(array_key_exists('content', $category) && strlen($category['content'])): ?>
                <?php echo $category['content']; ?>
            <?php else: ?>
                <p>&nbsp;</p>
            <?php endif; ?>
        </div>
        <?php if ($show_seo_text && array_key_exists('seo_content', $category) && strlen($category['seo_content'])): ?>
            <div class="col-12 col-md-10 offset-md-1 text-center">
                <a href="#more-information" class="btn btn-primary btn-lg">More information</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="container">
    <?php if(isset($max_rating)): ?>
        <?php require(ROOT . '/view/course/_filters.php'); ?>
    <?php endif; ?>
    <?php if(isset($recipes) && is_array($recipes) && count($recipes)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <?php foreach($recipes as $recipe): ?>
                <div class="row">
                    <div class="col-12 col-md-2">
                        <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>">
                            <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                        </a>
                        <?php TemplateHelper::includeHeart($recipe, 'smaller'); ?>
                    </div>
                    <div class="col-12 col-md-10">
                        <div class="row m-0">
                            <div class="col-12 col-sm-8 col-md-9 m-0 p-0">
                                <h3><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="text-dark"><?php echo $recipe['title']; ?></a></h3>
                                <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> <?php echo Template::displayTimeToDescription($recipe['displaytime']); ?></span></strong>
                                <strong class="mr-4"><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> <?php echo Template::displayYield($recipe['yield']); ?></span></strong>
                                <?php if($recipe['average_rating']): ?>
                                <span class="nowrap">
                                    <?php for($i=0; $i<$recipe['average_rating'];$i++): ?>
                                        <span class="oi oi-star h6" style="color: orange;" title="Average rating" aria-hidden="true"></span>
                                    <?php endfor; ?>
                                    <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                                        <span class="oi oi-star h6" style="color: lightgrey;" title="Average rating" aria-hidden="true"></span>
                                    <?php endfor; ?>
                                    </span>
                                    <span class="text-muted small">(<?php echo $recipe['total_votes']; ?>)</span>
                                <?php endif; ?>
                                <p class="text-muted"><?php echo $recipe['excerpt']; ?></p>
                            </div>
                            <div class="col-sm-4 col-md-3 m-0">
                                <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="btn btn-warning btn-block">View recipe</a>
                                <a href="<?php echo Core::url('community/save-recipe/' . $recipe['slug']); ?>" class="btn btn-outline-secondary btn-block text-dark">Save to collection</a>
                                <a href="<?php echo Core::url('recipe/comments/' . $recipe['slug']); ?>" class="btn d-none d-md-block btn-outline-secondary btn-block text-dark">View comments (<?php echo $recipe['total_comments']; ?>)</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <?php endif; ?>
</div>

<?php if($show_seo_text): ?>
    <?php if(array_key_exists('seo_content', $category) && strlen($category['seo_content'])): ?>
        <div class="container" id="more-information">
            <div class="row mb-2">
                <div class="col-12 my-2 align-items-center">
                    <?php echo $category['seo_content']; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
