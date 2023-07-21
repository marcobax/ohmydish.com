<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center">Recipe index</h1>
            <p class="text-center">Here you will find all recipes in one grand overview sectioned by course, theme or kitchen.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="text-center with-stripe">Recipes by course</h4>
        </div>
    </div>
    <div class="row">
        <?php foreach($categories_by_course as $category): ?>
            <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="213" height="142" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="text-center with-stripe">Latest recipes</h4>
        </div>
    </div>
    <?php if (isset($latest_recipes) && is_array($latest_recipes) && count($latest_recipes)): ?>
        <div class="row">
            <?php foreach($latest_recipes as $latest_recipe): ?>
                <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                    <a href="<?php echo Core::url('recipe/' . $latest_recipe['slug']); ?>">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($latest_recipe); ?>" alt="<?php echo $latest_recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                    </a>
                    <?php TemplateHelper::includeHeart($latest_recipe, 'smaller'); ?>
                    <h4 class="mt-2 text-center"><a href="<?php echo Core::url('recipe/' . $latest_recipe['slug']); ?>" class="text-dark"><?php echo $latest_recipe['title']; ?></a></h4>
                    <p class="text-muted text-left"><?php echo $latest_recipe['excerpt']; ?></p>
                </div>
            <?php endforeach; ?>
            <div class="col-12 text-center mb-2">
                <a class="btn btn-warning" href="<?php echo Core::url('latest-recipes'); ?>">More new recipes</a>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="text-center with-stripe">Recipes by season</h4>
        </div>
    </div>
    <?php if (isset($categories_by_season) && is_array($categories_by_season) && count($categories_by_season)): ?>
        <div class="row">
            <?php foreach($categories_by_season as $category): ?>
                <?php if($category): ?>
                    <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                        <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                        <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                        <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="text-center with-stripe">Recipes by theme</h4>
        </div>
    </div>
    <?php if (isset($categories_by_theme) && is_array($categories_by_theme) && count($categories_by_theme)): ?>
        <div class="row">
            <?php foreach($categories_by_theme as $category): ?>
                <?php if($category): ?>
                    <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                        <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                        <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                        <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="text-center with-stripe">Recipes by kitchen</h4>
        </div>
    </div>
    <?php if (isset($categories_by_cuisine) && is_array($categories_by_cuisine) && count($categories_by_cuisine)): ?>
        <div class="row">
            <?php foreach($categories_by_cuisine as $category): ?>
                <?php if($category): ?>
                    <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                        <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                        <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                        <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>