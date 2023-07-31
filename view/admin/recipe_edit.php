<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit recipe</h3>
            <form action="<?php echo Core::url('admin/recipe_edit/' . $recipe['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <?php if($recipe['slug']): ?>
                            <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" target="_blank"><?php echo Core::url('recipe/' . $recipe['slug']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-right">
                        <?php if($recipe['slug']): ?>
                            <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <input type="submit" value="Save" class="btn btn-lg btn-success">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="title">Recipe name</label>
                            <input type="text" name="title" id="title" value="<?php echo trim(htmlentities($recipe['title'])); ?>" class="form-control" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <?php $statusses = ['draft','publish']; ?>
                                    <select name="status" id="status" class="form-control">
                                        <?php foreach($statusses as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo ($recipe['status'] === $status)?'selected':''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <?php if($recipe['status'] === 'publish'): ?>
                                <div class="form-group">
                                    <label for="published">Published</label>
                                    <input type="text" name="published" id="published" value="<?php echo $recipe['published']; ?>" class="form-control" autocomplete="off" required>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <input type="text" name="excerpt" id="excerpt" value="<?php echo trim(htmlentities($recipe['excerpt'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="categories">Course / theme</label><br>
                                    <?php $recipe_categories = explode(',',$recipe['categories']); ?>
                                    <select id="categories" name="categories[]" class="form-control multiselect" multiple="multiple" required>
                                        <?php foreach($categories as $category): ?>
                                            <option <?php echo (in_array($category['id'], $recipe_categories))?'selected="selected"':''; ?> value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cuisines">Kitchen</label><br>
                                    <?php $recipe_cuisines = explode(',',$recipe['cuisines']); ?>
                                    <select id="cuisines" name="cuisines[]" class="form-control multiselect" multiple="multiple" required>
                                        <?php foreach($cuisines as $cuisine): ?>
                                            <option <?php echo (in_array($cuisine['id'], $recipe_cuisines))?'selected="selected"':''; ?> value="<?php echo $cuisine['id']; ?>"><?php echo $cuisine['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="dishtypes">Dish type</label><br>
                                    <?php $recipe_dishtypes = explode(',',$recipe['dishtypes']); ?>
                                    <select id="dishtypes" name="dishtypes[]" class="form-control multiselect" multiple="multiple" required>
                                        <?php foreach($dishtypes as $dishtype): ?>
                                            <option <?php echo (in_array($dishtype['id'], $recipe_dishtypes))?'selected="selected"':''; ?> value="<?php echo $dishtype['id']; ?>"><?php echo $dishtype['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="recipe_tags">Tags</label><br>
                            <input data-url="<?php echo Core::url('admin/xhr_tags'); ?>" type="text" name="recipe_tags" id="recipe_tags" value="<?php echo $recipe_tags_slugs; ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ingredients">Ingredients</label>
                            <textarea name="ingredients" id="ingredients" cols="30" rows="10" class="form-control"><?php echo htmlentities($recipe['ingredients']); ?></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="kitchen_equipment">Kitchen equipment</label>
                            <textarea name="kitchen_equipment" id="kitchen_equipment" cols="30" rows="10" class="form-control"><?php echo htmlentities($recipe['kitchen_equipment']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="seo_text">SEO text</label>
                    <textarea name="seo_text" id="seo_text" cols="30" rows="20" class="form-control"><?php echo htmlentities($recipe['seo_text']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="20" class="form-control"><?php echo htmlentities($recipe['content']); ?></textarea>
                    <div class="mt-2 p-2 rounded" style="border: 1px solid lightgrey; border-left: 6px solid #339966;">Total words: <span id="total-words">0</span>, total characters: <span id="total-characters">0</span></div>
                </div>
                <div class="form-group">
                    <label for="content">FAQ</label>
                    <textarea name="faq" id="faq" cols="30" rows="10" class="form-control"><?php echo htmlentities($recipe['faq']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="featured_image">Featured image</label>
                    <div class="row">
                        <div class="col-2">
                            <img src="<?php echo $recipe['featured_image']; ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-10">
                            <input type="text" name="featured_image" id="featured_image" value="<?php echo $recipe['featured_image']; ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ingredients_image">Ingredients photo</label>
                            <div class="row">
                                <div class="col-2">
                                    <img src="<?php echo $recipe['ingredients_image']; ?>" class="img-fluid" alt="">
                                </div>
                                <div class="col-10">
                                    <input type="text" name="ingredients_image" id="ingredients_image" value="<?php echo $recipe['ingredients_image']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="thumbnail_image">Square image</label>
                            <div class="row">
                                <div class="col-2">
                                    <img src="<?php echo $recipe['thumbnail_image']; ?>" class="img-fluid" alt="">
                                </div>
                                <div class="col-10">
                                    <input type="text" name="thumbnail_image" id="thumbnail_image" value="<?php echo $recipe['thumbnail_image']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="dutch_url">Dutch URL <span class="text-muted">(For example: https://ohmydish.nl/recept/abcdef)</span></label>
                            <input type="text" name="dutch_url" id="dutch_url" value="<?php echo trim(htmlentities($recipe['dutch_url'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preptime">Preparation time <span class="text-muted">(Example: 15M)</span></label>
                            <input type="text" name="preptime" id="preptime" value="<?php echo trim(htmlentities($recipe['preptime'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="cooktime">Cooking time <span class="text-muted">(Example: 10M)</span></label>
                            <input type="text" name="cooktime" id="cooktime" value="<?php echo trim(htmlentities($recipe['cooktime'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="totaltime">Total time <span class="text-muted">(Example: 25M)</span></label>
                            <input type="text" name="totaltime" id="prepttotaltimeime" value="<?php echo trim(htmlentities($recipe['totaltime'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="displaytime">Display time <span class="text-muted">(Example: 20)</span></label>
                            <input type="text" name="displaytime" id="displaytime" value="<?php echo trim(htmlentities($recipe['displaytime'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="yield">Yield <span class="text-muted">(Example: 4)</span></label>
                            <input type="text" name="yield" id="yield" value="<?php echo trim(htmlentities($recipe['yield'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="calories">Total calories <span class="text-muted">(For example: 200)</span></label>
                            <input type="number" name="calories" id="calories" value="<?php echo trim(htmlentities($recipe['calories'])); ?>" min="0" max="9999999999" minlength="1" maxlength="10" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if($recipe['created']): ?>
    <div class="row">
        <div class="col-12 text-center p-4">
            Created: <?php echo TemplateHelper::formatDate($recipe['created']); ?>, updated: <?php echo TemplateHelper::formatDate($recipe['updated']); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<script>
    let tag_suggestions = '<?php echo $recipe_tags; ?>';
</script>