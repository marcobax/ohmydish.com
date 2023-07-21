<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-2 text-center with-stripe">Recept opslaan</h1>
            <p class="text-center">In which collection do you want to save the recipe "<?php echo $recipe['title']; ?>"?</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($recipe);?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded-top mb-2" loading="lazy" data-pin-nopin="true">
            <p class="text-center"><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="btn btn-sm btn-warning">Back to recipe</a></p>
        </div>
        <div class="col-12 col-sm-6">
            <form action="<?php echo Core::url('community/save-recipe/' . $recipe['slug']); ?>" method="post">
                <div class="form-group">
                    <select name="collection_id" id="collection_id" class="form-control">
                        <?php if(is_array($collections) && count($collections)): ?>
                            <?php foreach($collections as $collection): ?>
                                <option value="<?php echo $collection['unique_id']; ?>" <?php echo ('favourites' === $collection['unique_id'])?'selected':''; ?>><?php echo ucfirst($collection['name']); ?> (<?php echo $collection['total_recipes']; ?> <?php echo ($collection['total_recipes']>1 || !$collection['total_recipes'])?'recipes':'recipe'; ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="new_collection_name" name="new_collection_name" placeholder="Or create a new collection">
                </div>
                <div class="form-group">
                    <textarea name="new_collection_description" id="new_collection_description" rows="5" class="form-control" placeholder="Give your collection a description"></textarea>
                </div>
                <div class="form-group text-right">
                    <input type="submit" class="btn btn-success btn-lg" value="Save this recipe">
                </div>
            </form>
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
</div>
