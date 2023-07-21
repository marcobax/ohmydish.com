<div class="container">
    <?php if(SessionHelper::isLoggedIn() && SessionHelper::getUserId() === (int) $collection['user_id']): ?>
        <div class="row">
            <div class="col-12 mt-3 text-center">
                <a href="<?php echo Core::url('community/saved-recipes'); ?>" class="btn btn-outline-primary">Back to saved recipes</a>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-12">
            <h1 class="fancy text-center mb-2 with-stripe"><?php echo ucfirst($collection['name']); ?></h1>
            <p class="text-muted text-center"><?php if(is_array($user) && count($user)): ?>Collection created by <?php echo $user['first_name']; ?>. <?php endif; ?><?php echo strlen($collection['description'])?$collection['description']:'&nbsp;'; ?></p>
        </div>
    </div>
    <?php if(isset($saved_recipes) && count($saved_recipes)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <?php foreach($saved_recipes as $saved_recipe): ?>
            <?php
            $recipe = $this->recipe_model->get($saved_recipe['recipe_id']);
            $dt = new DateTime($saved_recipe['created']);
            ?>
            <div class="row">
                <div class="col-12 col-md-2">
                    <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="d-block mb-2">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                    </a>
                </div>
                <div class="col-12 col-md-10">
                    <div class="row m-0">
                        <div class="col-12 col-sm-8 col-md-9 m-0 p-0">
                            <h3><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="text-dark"><?php echo $recipe['title']; ?></a></h3>
                            <p class="text-success h6"><span class="oi oi-heart"></span> Saved <?php echo $dt->format('d/m/Y'); ?> at <?php echo $dt->format('H:i'); ?></p>
                            <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> <?php echo Template::displayTimeToDescription($recipe['displaytime']); ?></span></strong>
                            <strong class="mr-4"><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> <?php echo Template::displayYield($recipe['yield']); ?></span></strong>
                            <?php if($recipe['average_rating']): ?>
                                <span class="nowrap">
                                <?php for($i=0; $i<$recipe['average_rating'];$i++): ?>
                                    <span class="oi oi-star h6" style="color: orange;" title="Gemiddelde score" aria-hidden="true"></span>
                                <?php endfor; ?>
                                    <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                                        <span class="oi oi-star h6" style="color: lightgrey;" title="Gemiddelde score" aria-hidden="true"></span>
                                    <?php endfor; ?>
                                </span>
                                <span class="text-muted small">(<?php echo $recipe['total_votes']; ?>)</span>
                            <?php endif; ?>
                            <p class="text-muted"><?php echo $recipe['excerpt']; ?></p>
                        </div>
                        <div class="col-sm-4 col-md-3 m-0">
                            <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="btn btn-warning btn-block">View recipe</a>
                                <a href="<?php echo Core::url('community/save-recipe/' . $recipe['slug']); ?>" class="btn btn-outline-secondary btn-block text-dark">Save to collection</a>
                            <?php if(SessionHelper::isLoggedIn() && SessionHelper::getUserId() === (int) $collection['user_id']): ?>
                                <a href="<?php echo Core::url('community/delete-recipe/' . $recipe['slug'] . '/' . $collection['unique_id']); ?>" class="btn btn-outline-danger btn-block" onclick="return confirm('Are you sure?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <?php else: ?>
        <div class="col-12 text-center mb-4">
            <p class="text-center">At this moment there are no recipes saved to the collection of <?php echo $user['first_name']; ?>.</p>
            <p class="text-center">Not sure yet about what to cook today? Take a look at the <a href="<?php echo Core::url('recipe-index'); ?>">recipe index</a> or go to the
                <a href="<?php echo Core::url('latest-recipes'); ?>">latest recipes</a> page and get inspired!</p>
            <hr>
            <a class="btn btn-warning" href="<?php echo Core::url('recipe-index'); ?>">Recipe index</a>
        </div>
    <?php endif; ?>
    <?php if(SessionHelper::isLoggedIn() && SessionHelper::getUserId() === (int) $collection['user_id']): ?>
        <div class="row">
            <div class="col-12 mb-3 text-center">
                <a href="<?php echo Core::url('community/saved-recipes'); ?>" class="btn btn-outline-primary">Back to saved recipes</a>
            </div>
        </div>
    <?php endif; ?>
</div>