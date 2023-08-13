<div class="container-fluid">
    <h2 class="with-stripe">Blog votes</h2>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/blog_rating_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="ip_address">IP address</label>
                    <input type="text" name="ip_address" id="ip_address" value="<?php echo (isset($query['ip_address']))?$query['ip_address']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="rating">Vote</label>
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
                    <label for="original_rating">Original vote</label>
                    <select name="original_rating" id="original_rating" class="form-control">
                        <option value="" readonly="readonly">Make a choice</option>
                        <?php for($i=1;$i<=5;$i++): ?>
                            <option value="<?php echo (6-$i); ?>" <?php echo (isset($query['original_rating']) && (int) $query['original_rating'] === (6-$i))?'selected':''; ?>>
                                <?php for($j=$i;$j<=5;$j++): ?>
                                    &starf;
                                <?php endfor; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/blog_rating_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_rating_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_rating_index', 'created'); ?>">Created</th>
                    <th>Blog</th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_rating_index', 'rating'); ?>">Vote</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_rating_index', 'original_rating'); ?>">Original vote</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_rating_index', 'ip_address'); ?>">IP address</a></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($ratings as $i => $rating): ?>
                    <?php $blog = $this->recipe_model->get($rating['entity_id']); ?>
                    <tr>
                        <td class="text-center <?php echo ($i < (int) $new_blog_rating)?'bg-success text-white':''; ?>"><?php echo $rating['id']; ?></td>
                        <td><?php echo TemplateHelper::formatDate($rating['created']); ?></td>
                        <td>
                            <h4><a href="<?php echo Core::url('blog/' . $blog['slug']); ?>" target="_blank"><?php echo $blog['title']; ?></a></h4>
                            <?php for($i=0; $i<$blog['average_rating'];$i++): ?>
                                <span class="oi oi-star p-0" style="color: orange;"></span>
                            <?php endfor; ?>
                            <?php for($i=$blog['average_rating']; $i<5;$i++): ?>
                                <span class="oi oi-star p-0" style="color: grey;"></span>
                            <?php endfor; ?>
                            <span class="text-muted"><?php echo $blog['rating_label']; ?></span>
                        </td>
                        <td><?php echo $rating['rating']; ?></td>
                        <td><?php echo $rating['original_rating']?$rating['original_rating']:'-'; ?></td>
                        <td><a href="<?php echo Core::url('admin/blog_rating_index'); ?>?ip_address=<?php echo $rating['ip_address']; ?>"><?php echo $rating['ip_address']; ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>