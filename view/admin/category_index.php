<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Categorieën</h3>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <a href="<?php echo Core::url('admin/category_index?update-counts=1'); ?>" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure? This can take a while.');">Update counts</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/category_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo (isset($query['title']))?$query['title']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/category_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/category_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/category_index', 'title'); ?>">Title</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/category_index', 'type'); ?>">Type</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/category_index', 'total_entities'); ?>">Items</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td style="width: 100px;">
                                        <a href="<?php echo Core::url('admin/category_edit/' . $category['id']); ?>"><img style="width: 100px;" src="<?php echo $category['featured_image']; ?>" alt="<?php echo $category['title']; ?>"></a>
                                    </td>
                                    <td>
                                        <?php echo $category['title']; ?><br>
                                        <span class="text-muted"><?php echo $category['content']; ?></span><br>
                                        <a href="<?php echo Core::url('admin/category_edit/' . $category['id']); ?>">Edit</a> |
                                        <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" target="_blank">View category</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td><?php echo $category['type']; ?></td>
                        <td><?php echo $category['total_entities']; ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/category/' . $category['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>