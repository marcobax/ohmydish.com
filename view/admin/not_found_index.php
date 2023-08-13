<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">404: niet gevonden</h3>
    <div class="row">
        <div class="col-12">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/not_found_index', 'created'); ?>">Created</a></th>
                    <th>URL</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($not_founds as $not_found): ?>
                        <tr>
                            <td><a href="<?php echo Core::url('admin/not_found_detail/' . $not_found['id']) ?>"><?php echo TemplateHelper::formatDate($not_found['created']); ?></a></td>
                            <td><?php echo $not_found['request']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>