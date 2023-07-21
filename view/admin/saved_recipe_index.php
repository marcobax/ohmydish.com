<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Recently saved recipes</h3>
    <div class="row">
        <div class="col-12">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm table-hover">
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/saved_recipe_index', 'created'); ?>">Saved</a></th>
                    <th>Recipe</th>
                    <th>User</th>
                </tr>
                <?php foreach($latest_saved_recipes as $latest_saved_recipe): ?>
                    <?php
                    $user = $this->user_model->get($latest_saved_recipe['user_id']);
                    $recipe = $this->recipe_model->get($latest_saved_recipe['recipe_id']);
                    if ($latest_saved_recipe['collection_id']) {
                        $collection = $this->collection_model->get($latest_saved_recipe['collection_id']);
                    }
                    ?>
                    <tr>
                        <td><?php echo TemplateHelper::formatDate($latest_saved_recipe['created']); ?></td>
                        <td><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" target="_blank"><?php echo $recipe['title']; ?></a><br>
                            &rarrhk;
                            <?php if($latest_saved_recipe['collection_id']): ?>
                                <a href="<?php echo Core::url('community/collection/' . $collection['unique_id']); ?>#<?php echo CoreHelper::slugify($collection['name']); ?>" target="_blank"><?php echo CoreHelper::slugify($collection['name']); ?></a>
                            <?php else: ?>
                                <a href="<?php echo Core::url('community/collection/favourites/' . $latest_saved_recipe['user_id']); ?>#favourites" target="_blank">Favourites</a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>
