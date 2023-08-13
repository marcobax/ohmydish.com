<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Blogs</h3>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <a href="<?php echo Core::url('admin/blog_index?update-counts=1'); ?>" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure? This can take a while.');">Update counts</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/blog_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
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
                    <a href="<?php echo Core::url('admin/blog_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_index', 'title'); ?>">Title</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_index', 'status'); ?>">Status</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/blog_index', 'created'); ?>">Created</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($blogs as $blog): ?>
                    <tr>
                        <td><?php echo $blog['id']; ?></td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td style="width: 100px;">
                                        <a href="<?php echo Core::url('admin/blog_edit/' . $blog['id']); ?>"><img style="width: 100px;" src="<?php echo TemplateHelper::getFeaturedImage($blog); ?>" alt="<?php echo $blog['title']; ?>"></a>
                                    </td>
                                    <td>
                                        <?php echo $blog['title']; ?><br>
                                        <span class="text-muted"><?php echo $blog['excerpt']; ?></span><br>
                                        <a href="<?php echo Core::url('admin/blog_edit/' . $blog['id']); ?>">Edit</a> |
                                        <a href="<?php echo Core::url('blog/' . $blog['slug']); ?>" target="_blank">View blog</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-<?php echo ('publish' === $blog['status'])?'success':'dark'; ?>"><?php echo $blog['status']; ?></td>
                        <td><?php echo TemplateHelper::formatDate($blog['created']); ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/blog/' . $blog['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>
