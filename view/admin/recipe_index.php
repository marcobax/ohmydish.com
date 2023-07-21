<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Recipes</h3>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <a href="<?php echo Core::url('admin/recipe_index?update-counts=1'); ?>" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure? This can take a while.');">Update counts</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/recipe_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo (isset($query['title']))?$query['title']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="id">ID</label>
                    <input type="text" name="id" id="id" value="<?php echo (isset($query['id']))?$query['id']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <?php $statusses = ['publish', 'draft']; ?>
                    <select name="status" id="status" class="form-control">
                        <?php foreach($statusses as $status): ?>
                            <option value="<?php echo $status; ?>" <?php echo (isset($query['status']) && $query['status'] === $status)?'selected':''; ?>><?php echo $status; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating">Votes</label>
                    <select name="rating" id="rating" class="form-control">
                        <option value="" readonly="readonly">Make a choice</option>
                        <?php for($i=1;$i<=5;$i++): ?>
                            <option value="<?php echo (6-$i); ?>" <?php echo (isset($query['rating']) && (int) $query['rating'] === (6-$i))?'selected':''; ?>>
                                <?php for($j=$i;$j<=5;$j++): ?>
                                    &starf;
                                <?php endfor; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/recipe_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/recipe_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/recipe_index', 'title'); ?>">Title</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/recipe_index', 'status'); ?>">Status</a></th>
                    <th>Dates</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($recipes as $recipe): ?>
                    <tr>
                        <td>
                            <?php echo $recipe['id']; ?>
                        </td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td style="width: 100px;">
                                        <a href="<?php echo Core::url('admin/recipe_edit/' . $recipe['id']); ?>"><img style="width: 100px;" src="<?php echo TemplateHelper::getFeaturedImage($recipe); ?>" alt="<?php echo $recipe['title']; ?>"></a>
                                    </td>
                                    <td>
                                        <h4 class="m-0"><?php echo $recipe['title']; ?></h4>
                                        <?php for($i=0; $i<$recipe['average_rating'];$i++): ?>
                                            <span class="oi oi-star p-0" style="color: orange;"></span>
                                        <?php endfor; ?>
                                        <?php for($i=$recipe['average_rating']; $i<5;$i++): ?>
                                            <span class="oi oi-star p-0" style="color: grey;"></span>
                                        <?php endfor; ?>
                                        <span class="text-muted"><?php echo $recipe['rating_label']; ?></span>
                                        <br>
                                        <?php echo $recipe['excerpt']; ?><br>
                                        <a href="<?php echo Core::url('admin/recipe_edit/' . $recipe['id']); ?>">Edit</a> |
                                        <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" target="_blank">View recipe</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-<?php echo ('publish' === $recipe['status'])?'success':'dark'; ?>"><?php echo $recipe['status']; ?></td>
                        <td style="width: 350px;">
                            Created: <?php echo TemplateHelper::formatDate($recipe['created']); ?><br>
                            <?php if($recipe['updated']): ?>
                                Last edited: <?php echo TemplateHelper::formatDate($recipe['updated']); ?><br>
                            <?php endif; ?>
                            <?php if($recipe['published']): ?>
                                <span class="text-success">Published: <?php echo TemplateHelper::formatDate($recipe['published']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/recipe/' . $recipe['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>
