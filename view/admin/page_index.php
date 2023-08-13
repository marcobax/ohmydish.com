<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Pagina's</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/page_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Title</label>
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
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/page_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/page_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/page_index', 'title'); ?>">Title</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/page_index', 'status'); ?>">Status</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/page_index', 'created'); ?>">Created</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($pages as $page): ?>
                    <tr>
                        <td><?php echo $page['id']; ?></td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td style="width: 100px;">
                                        <a href="<?php echo Core::url('admin/page_edit/' . $page['id']); ?>"><img style="width: 100px;" src="<?php echo TemplateHelper::getFeaturedImage($page); ?>" alt="<?php echo $page['title']; ?>"></a>
                                    </td>
                                    <td>
                                        <?php echo $page['title']; ?><br>
                                        <span class="text-muted"><?php echo $page['excerpt']; ?></span><br>
                                        <a href="<?php echo Core::url('admin/page_edit/' . $page['id']); ?>">Edit</a> |
                                        <a href="<?php echo Core::url($page['slug']); ?>" target="_blank">View page</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-<?php echo ('publish' === $page['status'])?'success':'dark'; ?>"><?php echo $page['status']; ?></td>
                        <td><?php echo TemplateHelper::formatDate($page['created']); ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/page/' . $page['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>