<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Redirects</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/redirect_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Request URL</label>
                    <input type="text" name="request_url" id="request_url" value="<?php echo (isset($query['request_url']))?$query['request_url']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="name">Destination URL</label>
                    <input type="text" name="destination_url" id="destination_url" value="<?php echo (isset($query['request_url']))?$query['destination_url']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/redirect_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm table-hover">
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/redirect_index', 'request_url'); ?>">Request URL</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/redirect_index', 'destination_url'); ?>">Destination URL</a></th>
                    <th>Actions</th>
                </tr>
                <?php foreach($redirects as $redirect): ?>
                    <tr>
                        <td><?php echo $redirect['request_url']; ?></td>
                        <td><?php echo $redirect['destination_url']; ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/redirect_edit/' . $redirect['id']); ?>" class="btn btn-primary">Edit</a>
                            <a href="<?php echo Core::url('admin/sure/redirect/' . $redirect['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>