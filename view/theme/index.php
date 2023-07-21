<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center with-stripe">Recipes by theme</h1>
            <p class="text-center">Here you'll find all recipes by theme.</p>
        </div>
    </div>
    <div class="row">
        <?php foreach($categories_by_theme as $category): ?>
            <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-12 mt-4 mb-4 text-center">
            <a href="<?php echo Core::url('recipe-index'); ?>" class="btn btn-large btn-warning">View all recipes</a>
        </div>
    </div>
</div>