<div class="container">
    <div class="row mb-2">
        <div class="col-12">
            <h1 class="fancy mb-2 text-center with-stripe">Saved recipes</h1>
            <?php if(1===count($collections) && (0 ===(int) $collections[0]['total_recipes'])): ?>
                <p class="text-center">You haven't yet saved any recipes. Start by saving recipes, you can store and sort these in your own created collections. You can save a recipe by clicking on the heart icon. You can find these at every photo of a recipe.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <?php if(is_array($collections) && count($collections)): ?>
                <div class="row">
                <?php foreach($collections as $collection): ?>
                    <?php $recipe_count = 0;
                    if (array_key_exists($collection['id'], $recipes)) {
                        $recipe_count = count($recipes[$collection['id']]);
                    } ?>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-header text-center">
                                <a href="<?php echo Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])); ?>" class="text-dark"><?php echo ucfirst($collection['name']); ?> <span class="text-muted">(<?php echo $collection['total_recipes']; ?>)</span></a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php if(array_key_exists($collection['id'], $recipes) && is_array($recipes[$collection['id']]) && $recipe_count): ?>
                                        <?php foreach($recipes[$collection['id']] as $recipe): ?>
                                            <div class="col-6 mb-2">
                                                <div class="img-hover-zoom">
                                                    <a href="<?php echo Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])); ?>">
                                                        <img height="550" width="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded" loading="lazy" data-pin-nopin="true">
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if((4 - $recipe_count) > 0): ?>
                                            <?php for($i = 0; $i < (4 - $recipe_count); $i++): ?>
                                                <div class="col-6 mb-2">
                                                    <div class="img-hover-zoom">
                                                        <a href="<?php echo Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])); ?>">
                                                            <img height="550" width="550" src="<?php echo TemplateHelper::getThumbnailImage([]); ?>" class="img-fluid rounded" loading="lazy" data-pin-nopin="true">
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php for($i = 0; $i < 4; $i++): ?>
                                            <div class="col-6 mb-2">
                                                <div class="img-hover-zoom">
                                                    <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage([]); ?>" class="img-fluid rounded" loading="lazy">
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="<?php echo Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])); ?>" class="btn btn-sm btn-secondary">View recipe</a>
                                <?php if($collection['unique_id'] !== 'favourites'): ?>
                                    <a href="<?php echo Core::url('community/collectie_delete/' . $collection['unique_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you really sure you want to delete the collection \'<?php echo $collection['name']; ?>\'?');">Delete collection</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-danger">You haven't created collections or saved any recipes yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>