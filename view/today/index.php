<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy with-stripe mb-0 text-center">What do you want to cook today?</h1>
            <p class="text-center">Discover new, fun and delicious recipes you can cook your next meal with. Think of what you want to cook and eat today or even for the whole week! You can click on the button "Surprise me" to view one of our favourite recipes.</p>

            <p class="text-center">
                <a href="<?php echo Core::url('recipe-index'); ?>" class="btn btn-success btn-lg">Recipe index</a>
                <a href="<?php echo Core::url('recipe/verrassing'); ?>" class="btn btn-dark btn-lg">Surprise me</a>
            </p>
            <p class="text-center">&dash; or &dash;</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="mb-0 text-center with-stripe">Empty your refrigerator</h2>
            <p class="text-center">Save lots of money by emptying your refrigerator. Choose what kind of ingredients you still have and we'll show you relevant recipes using these ingredients. This way you can easily compose a week menu plan!</p>
            <form action="<?php echo Core::url('today'); ?>" method="get">
                <div class="card shadow">
                    <div class="card-header">What ingredients do you still have?</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="ingredient1">First ingredient</label>
                                <input type="text" name="ingredient1" id="ingredient1" class="form-control form-control-lg" placeholder="For example: chicken" value="<?php echo isset($ingredient1)?$ingredient1:''; ?>" required autocomplete="off">
                                <?php if(isset($query) && is_array($query) && count($query) && array_key_exists('ingredient1-suggestie', $query)): ?>
                                    <?php $e = explode(',', $query['ingredient1-suggestie']); ?>
                                    <?php if(is_array($e) && count($e) && strlen($e[0])): ?>
                                        <span class="text-muted h6">Suggestion:</span><br>
                                        <?php foreach($e as $suggestion): ?>
                                            <a href="#" onclick="document.getElementById('ingredient1').value = '<?php echo $suggestion; ?>'; return false;" class="badge badge-dark"><?php echo $suggestion; ?></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="ingredient2">Second ingredient <span class="text-muted">(optional)</span></label>
                                <input type="text" name="ingredient2" id="ingredient2" class="form-control form-control-lg" placeholder="For example: garlic" value="<?php echo isset($ingredient2)?$ingredient2:''; ?>" autocomplete="off">
                                <?php if(isset($query) && is_array($query) && count($query) && array_key_exists('ingredient2-suggestie', $query)): ?>
                                    <?php $e = explode(',', $query['ingredient2-suggestie']); ?>
                                    <?php if(is_array($e) && count($e) && strlen($e[0])): ?>
                                        <span class="text-muted h6">Suggestion:</span><br>
                                        <?php foreach($e as $suggestion): ?>
                                            <a href="#" onclick="document.getElementById('ingredient2').value = '<?php echo $suggestion; ?>'; return false;" class="badge badge-dark"><?php echo $suggestion; ?></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="ingredient2">Third ingredient <span class="text-muted">(optional)</span></label>
                                <input type="text" name="ingredient3" id="ingredient3" class="form-control form-control-lg" placeholder="For example: onion" value="<?php echo isset($ingredient3)?$ingredient3:''; ?>" autocomplete="off">
                                <?php if(isset($query) && is_array($query) && count($query) && array_key_exists('ingredient3-suggestie', $query)): ?>
                                    <?php $e = explode(',', $query['ingredient3-suggestie']); ?>
                                    <?php if(is_array($e) && count($e) && strlen($e[0])): ?>
                                        <span class="text-muted h6">Suggestion:</span><br>
                                        <?php foreach($e as $suggestion): ?>
                                            <a href="#" onclick="document.getElementById('ingredient3').value = '<?php echo $suggestion; ?>'; return false;" class="badge badge-dark"><?php echo $suggestion; ?></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="submit" class="btn btn-lg btn-primary" value="View recipes">
                        <a href="<?php echo Core::url('today'); ?>" class="btn btn-lg btn-link">Start over</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if(isset($recipes) && is_array($recipes) && count($recipes)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <?php foreach($recipes as $recipe): ?>
            <div class="row">
                <div class="col-12 col-md-2">
                    <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                    </a>
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
                            <a href="<?php echo Core::url('recipe/comments/' . $recipe['slug']); ?>" class="btn d-none d-md-block btn-outline-secondary btn-block text-dark">View comments (<?php echo $recipe['total_comments']; ?>)</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <div class="row">
            <div class="col-12 text-center mb-2">
                <a class="btn btn-warning" href="<?php echo Core::url('latest-recipes'); ?>">View all latest recipes</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <h3 class="text-center with-stripe">Popular themes</h3>
            </div>
        </div>
        <div class="row mb-2">
            <?php foreach($popular_categories as $category): ?>
                <div class="col-6 col-md-3 text-center mb-2 up-on-hover">
                    <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>"><img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" class="img-fluid rounded-top" alt="<?php echo $category['title']; ?>" loading="lazy" data-pin-nopin="true"></a>
                    <h4 class="mt-2 mb-0"><a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" class="text-dark"><?php echo $category['title']; ?></a></h4>
                    <span class="text-muted h6"><?php echo $category['total_entities']; ?> recipes</span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>