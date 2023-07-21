<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Gebruikers</h3>
    <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/user_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo (isset($query['email']))?$query['email']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filteren">
                </div>
            </form>
        </div>
        <div class="col-10">
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'created'); ?>">Created</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'first_name'); ?>">Name</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'email'); ?>">E-mail address</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'newsletter'); ?>">Newsletter</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'fb_access_token'); ?>">Facebook account</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/user_index', 'last_logged_in'); ?>">Last logged in</a></th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user): ?>
                    <?php $recipes_in_collection = $this->saved_recipe_model->getRecords(['user_id' => $user['id'], 'collection_id' => 0], [], [], true); ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo TemplateHelper::formatDate($user['created']); ?></td>
                        <td>
                            <?php echo $user['first_name'] . ' ' . $user['last_name']; ?><br>
                            <a href="<?php echo Core::url('community/collection/favourites/' . $user['id'] . '#my-favourites'); ?>" target="_blank">Favourites collection <span class="badge badge-primary"><?php echo $recipes_in_collection; ?></span></a>
                        </td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['newsletter']?'Ja':'Nee'; ?></td>
                        <td><?php echo !is_null($user['fb_access_token'])?'Ja':'Nee'; ?></td>
                        <td><?php echo $user['last_logged_in']?TemplateHelper::formatDate($user['last_logged_in']):'Nooit'; ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/user/' . $user['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>