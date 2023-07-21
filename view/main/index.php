<div class="text-white bg-green homepage-search golden">
    <div class="container py-4">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1 p-4 rounded" style="background: rgba(255, 255, 255, .7);">
                <form action="<?php echo Core::url('search'); ?>" method="get" name="zoekformulier" id="zoekformulier">
                    <div class="input-group input-group-lg">
                        <label for="s" class="sr-only">Find a recipe</label>
                        <input type="text" name="s" id="s" placeholder="Find a recipe" class="form-control rounded" aria-describedby="btnGroupSearch" autocomplete="off">
                        <div class="text-left d-none border w-100 bg-white rounded shadow suggestionbox"></div>
                        <div class="input-group-append">
                            <input type="submit" class="btn btn-success" value="Search" id="btnGroupSearch">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(is_array($latest_recipes) && count($latest_recipes)): ?>
    <div class="container">
        <h1 class="fancy mb-0 text-center with-stripe">What would you like to cook today?</h1>
        <p class="text-center text-muted mt-2 mb-4">Get inspiration with our delicious recipes from all over the world, choose from <strong>more than <?php echo $recipe_count; ?>+ recipes!</strong></p>
        <hr class="my-4">
        <div class="text-center">
            <?php
            $urls = [
                'community/saved-recipes'               => 'Saved recipes',
                'kitchen/dutch'                         => 'Dutch recipes',
                'kitchen/italian'                       => 'Italian recepten',
                'theme/tapas'                           => 'Tapas',
                'course/pie-and-cake'                   => 'Pie & cake recipes'
            ];
            ?>
            <span class="h3 d-block">
                <strong class="mr-3 d-block d-md-inline-block mb-2 mb-md-0">Quickly navigate to</strong>
                    <div class="d-none d-md-inline-block">
                        <?php $i = 0; ?>
                        <?php foreach($urls as $url => $label): ?>
                            <a href="<?php echo Core::url($url); ?>" class="btn <?php echo !$i?'btn-danger':'btn-outline-primary'; ?> mb-2"><?php echo $label; ?></a>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-md-none">
                        <?php foreach($urls as $url => $label): ?>
                            <a href="<?php echo Core::url($url); ?>" class="btn btn-sm btn-outline-primary btn-block"><?php echo $label; ?></a>
                        <?php endforeach; ?>
                    </div>
            </span>
        </div>
        <hr class="my-4">
        <div class="row">
            <?php foreach($latest_recipes as $latest_recipe): ?>
                <div class="col-12 col-md-4">
                    <a href="<?php echo Core::url('recipe/' . $latest_recipe['slug']); ?>">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($latest_recipe); ?>" alt="<?php echo $latest_recipe['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                    </a>
                    <?php TemplateHelper::includeHeart($latest_recipe, 'smaller'); ?>
                    <h4 class="mt-2 text-center"><a href="<?php echo Core::url('recipe/' . $latest_recipe['slug']); ?>" class="text-dark"><?php echo $latest_recipe['title']; ?></a></h4>
                    <p class="text-muted"><?php echo $latest_recipe['excerpt']; ?></p>
                </div>
            <?php endforeach; ?>
            <div class="col-12 text-center mb-4">
                <a class="btn btn-warning" href="<?php echo Core::url('recipe-index'); ?>">More recipes</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($other_recipes)): ?>
    <div class="text-white bg-green d-none d-sm-block">
        <div class="container pt-4 pb-4">
            <div class="row">
                <div class="col-12">
                    <h3 class="font-weight-bold mb-2 text-center with-stripe with-stripe-white">Perhaps you will like this too</h3>
                </div>
                <?php if (is_array($other_recipes) && count($other_recipes)): ?>
                    <?php foreach($other_recipes as $other_recipe): ?>
                        <div class="col-3 text-center">
                            <div class="img-hover-zoom">
                                <a href="<?php echo Core::url('recipe/' . $other_recipe['slug']); ?>">
                                    <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($other_recipe); ?>" alt="<?php echo $other_recipe['title']; ?>" class="img-fluid" loading="lazy" data-pin-nopin="true">
                                </a>
                                <?php TemplateHelper::includeHeart($other_recipe, 'smaller'); ?>
                            </div>
                            <h4 class="mt-2"><a href="<?php echo Core::url('recipe/' . $other_recipe['slug']); ?>" class="text-white"><?php echo $other_recipe['title']; ?></a></h4>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(is_array($latest_blogs) && count($latest_blogs)): ?>
    <div class="container">
        <h2 class="fancy mb-0 text-center with-stripe">Latest blog posts</h2>
        <p class="text-center text-muted mt-2 mb-4">On <a href="<?php echo Core::url('blog'); ?>">our blog</a> we publish fun overview, handy tips, reviews and a lot more! Below are the most recent blog posts.</strong></p>
        <div class="row">
            <?php foreach($latest_blogs as $i => $latest_blog): ?>
                <div class="col-12 col-sm-6 col-md-2 <?php echo $i>=3?'d-none d-sm-block':''; ?>">
                    <a href="<?php echo Core::url('blog/' . $latest_blog['slug']); ?>">
                        <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($latest_blog); ?>" alt="<?php echo $latest_blog['title']; ?>" class="img-fluid rounded-circle shadow-sm up-on-hover" loading="lazy" data-pin-nopin="true">
                    </a>
                    <?php if(false): ?>
                        <?php TemplateHelper::includeBlogHeart($latest_blog, 'smaller'); ?>
                    <?php endif; ?>
                    <span class="h5 mt-2 d-inline-block text-center"><a href="<?php echo Core::url('blog/' . $latest_blog['slug']); ?>" class="text-dark"><?php echo $latest_blog['title']; ?></a></span>
                </div>
            <?php endforeach; ?>
            <div class="col-12 text-center mb-4 mt-2">
                <a class="btn btn-warning" href="<?php echo Core::url('blog'); ?>">View all blogs</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($seasonal_ingredients)): ?>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="fancy with-stripe">De lekkerste seizoensgroenten van de <?php echo TemplateHelper::getSeason(true); ?></h2>
                <p class="text-muted">Waarom zou je producten eten die van de andere kant van de wereld komen als je met zo lokaal en seizoensgebonden mogelijke ingrediënten heerlijke gerechten op tafel kunt zetten?
                    <a href="#">&rarr; Bekijk alle <?php echo TemplateHelper::getSeason(true); ?> seizoensgroenten</a></p>
            </div>
        </div>
        <?php if (is_array($seasonal_ingredients) && count($seasonal_ingredients)): ?>
            <div class="row">
                <?php foreach($seasonal_ingredients as $seasonal_ingredient): ?>
                    <div class="col-12 col-md-3 text-center">
                        <a href="<?php echo Core::url('recipe/' . $seasonal_ingredient['slug']); ?>">
                            <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($seasonal_ingredient); ?>" alt="<?php echo $seasonal_ingredient['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                        </a>
                        <?php TemplateHelper::includeHeart($seasonal_ingredient, 'smaller'); ?>
                        <h4 class="mt-2"><a href="<?php echo Core::url('recipe/' . $seasonal_ingredient['slug']); ?>" class="text-dark"><?php echo $seasonal_ingredient['title']; ?></a></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12 mb-2 text-center">
                <a href="<?php echo Core::url('theme/' . TemplateHelper::getSeason(true) . '-recipes'); ?>" class="btn btn-warning">View all <?php echo TemplateHelper::getSeason(true); ?>recipes</a>
                <hr>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($questions) && count($questions)): ?>
    <div class="text-white bg-dark homepage-search py-2">
        <div class="row m-0" style="background: rgba(0, 0, 0, .5);">
            <div class="col-12 p-4 text-center">
                <div class="container">
                    <span class="h1">SOS: Kitchen aid!</span><br>
                    <p>No more guessing or secrets in the kitchen, thanks to our advice, tips and knowledge base. Learn to be cook like a professional chef in your own kitchen!</p>
                    <p class="text-center">
                        <a href="<?php echo Core::url('blog/cooking-knowledge-base'); ?>" class="btn btn-success">Need more help in the kitchen?</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php if (is_array($questions) && count($questions)): ?>
        <div class="container mt-4">
            <div class="row">
                <?php foreach($questions as $question): ?>
                    <div class="col-12 col-md-3 text-center img-hover-zoom">
                        <a href="<?php echo Core::url('question/' . $question['slug']); ?>">
                            <?php if($question['thumbnail_image']): ?>
                                <img width="550" height="550" src="<?php echo TemplateHelper::getThumbnailImage($question); ?>" alt="<?php echo $question['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                            <?php else: ?>
                                <img width="550" height="550" src="<?php echo TemplateHelper::getFeaturedImage($question); ?>" alt="<?php echo $question['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                            <?php endif; ?>
                        </a>
                        <h4 class="mt-2"><a href="<?php echo Core::url('question/' . $question['slug']); ?>" class="text-dark"><?php echo $question['title']; ?></a></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
