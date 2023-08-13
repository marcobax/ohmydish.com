<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Collecties</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/collection_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo (isset($query['name']))?$query['name']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/collection_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'user_id'); ?>">User</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'name'); ?>">Name</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'total_recipes'); ?>">Recipes</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'deleted'); ?>">Deleted</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'created'); ?>">Created</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/collection_index', 'updated'); ?>">Updated</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($collections as $collection): ?>
                    <tr>
                        <td><?php echo $collection['id']; ?></td>
                        <td>
                            <?php if(array_key_exists($collection['user_id'], $users)): ?>
                                <?php $user = $users[$collection['user_id']]; ?>
                                <?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?>
                            <?php else: ?>
                                <span class="text-danger">Deleted</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $collection['name']; ?><br>
                            <span class="text-muted"><?php echo $collection['description']; ?></span><br>
                            <a href="<?php echo Core::url('admin/collection_edit/' . $collection['id']); ?>">Edit</a> |
                            <a href="<?php echo Core::url('community/collection/' . $collection['unique_id']); ?>#<?php echo CoreHelper::slugify($collection['name']); ?>" target="_blank">View collection</a>
                        </td>
                        <td><?php echo $collection['total_recipes']; ?></td>
                        <td><?php echo ((int) $collection['deleted'])?'<span class="text-danger">Yes</span>':'<span class="text-success">No</span>'; ?></td>
                        <td><?php echo TemplateHelper::formatDate($collection['created']); ?></td>
                        <td><?php echo TemplateHelper::formatDate($collection['updated']); ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/collection/' . $collection['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>