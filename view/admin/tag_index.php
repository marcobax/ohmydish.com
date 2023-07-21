<div class="container-fluid">
    <h2 class="with-stripe">Tags</h2>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/tag_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" name="name" id="name" value="<?php echo (isset($query['name']))?$query['name']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filteren">
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/tag_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/tag_index', 'name'); ?>">Naam</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/tag_index', 'description'); ?>">Omschrijving</a></th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($tags as $tag): ?>
                    <tr>
                        <td><?php echo $tag['id']; ?></td>
                        <td><?php echo $tag['name']; ?></td>
                        <td>
                            <?php echo $tag['description']; ?><br>
                            <a href="<?php echo Core::url('admin/tag_edit/' . $tag['id']); ?>">Edit</a> |
                            <a href="<?php echo Core::url('tag/' . $tag['slug']); ?>" target="_blank">View tag</a>
                        </td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/tag/' . $tag['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>