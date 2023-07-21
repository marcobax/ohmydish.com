<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Blacklist</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/blacklist_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="ip_address">IP adres</label>
                    <input type="text" name="ip_address" id="ip_address" value="<?php echo (isset($query['ip_address']))?$query['ip_address']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="reason">Reden</label>
                    <input type="text" name="reason" id="reason" value="<?php echo (isset($query['reason']))?$query['reason']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filteren">
                    <a href="<?php echo Core::url('admin/blacklist_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm table-hover">
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/blacklist_index', 'ip_address'); ?>">IP adres</a></th>
                    <th>Aangemaakt</th>
                    <th>Reden</th>
                    <th>Acties</th>
                </tr>
                <?php foreach($blacklist as $item): ?>
                    <tr>
                        <td><a href="<?php echo Core::url('admin/recipe_rating_index'); ?>?ip_address=<?php echo $item['ip_address']; ?>"><?php echo $item['ip_address']; ?></a></td>
                        <td><?php echo TemplateHelper::formatDate($item['created']); ?></td>
                        <td><?php echo $item['reason']; ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/blacklist_edit/' . $item['id']); ?>" class="btn btn-primary">Edit</a>
                            <a href="<?php echo Core::url('admin/sure/blacklist/' . $item['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>