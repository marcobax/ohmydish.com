<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Contactaanvragen</h3>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <a href="<?php echo Core::url('admin/contact_index?delete-spam=1'); ?>" class="btn btn-sm btn-primary">Delete spam</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/contact_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="status">Status</label>
                    <?php $statusses = [0 => 'Geen spam', 1 => 'Spam']; ?>
                    <select name="spam" id="spam" class="form-control">
                        <?php foreach($statusses as $status => $label): ?>
                            <option value="<?php echo $status; ?>" <?php echo (isset($query['spam']) && ((int) $query['spam'] === (int) $status))?'selected':''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filteren">
                    <a href="<?php echo Core::url('admin/contact_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/contact_index', 'id'); ?>">ID</a></th>
                    <th>Content</th>
                    <th><a href="<?php echo $this->request->filterURI('admin/contact_index', 'created'); ?>">Aangemaakt</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/contact_index', 'name'); ?>">Naam</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/contact_index', 'email'); ?>">Email</a></th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($contacts as $contact): ?>
                    <tr>
                        <td><?php echo $contact['id']; ?></td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td>
                                        <span class="text-muted"><?php echo htmlspecialchars($contact['content']); ?></span><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td><?php echo TemplateHelper::formatDate($contact['created']); ?></td>
                        <td><?php echo $contact['name']; ?></td>
                        <td><?php echo $contact['email']; ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/contact/' . $contact['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>