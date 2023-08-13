<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Vragen</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/question_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo (isset($query['title']))?$query['title']:''; ?>" class="form-control" autocomplete="off">
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
                    <a href="<?php echo Core::url('admin/question_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/question_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/question_index', 'title'); ?>">Title</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/question_index', 'status'); ?>">Status</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/question_index', 'created'); ?>">Created</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($questions as $question): ?>
                    <tr>
                        <td><?php echo $question['id']; ?></td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td style="width: 100px;">
                                        <a href="<?php echo Core::url('admin/question_edit/' . $question['id']); ?>"><img style="width: 100px;" src="<?php echo TemplateHelper::getThumbnailImage($question); ?>" alt="<?php echo $question['title']; ?>"></a>
                                    </td>
                                    <td>
                                        <?php echo $question['title']; ?><br>
                                        <span class="text-muted"><?php echo $question['excerpt']; ?></span><br>
                                        <a href="<?php echo Core::url('admin/question_edit/' . $question['id']); ?>">Edit</a> |
                                        <a href="<?php echo Core::url('question/' . $question['slug']); ?>" target="_blank">View question</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-<?php echo ('publish' === $question['status'])?'success':'dark'; ?>"><?php echo $question['status']; ?></td>
                        <td><?php echo TemplateHelper::formatDate($question['created']); ?></td>
                        <td>
                            <a href="<?php echo Core::url('admin/sure/question/' . $question['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>