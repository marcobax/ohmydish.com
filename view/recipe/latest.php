<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center">Latest recipes</h1>
            <p class="text-center">Here you will find the latest recipes from Ohmydish. Get inspiration on what you would like to cook today! Lots of choice with recipes from all over the world!</p>
        </div>
    </div>
    <?php if(isset($recipes) && count($recipes)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <?php foreach($recipes as $recipe): ?>
            <div class="row">
                <div class="col-12 col-md-2">
                    <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy">
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
    <?php endif; ?>
</div>