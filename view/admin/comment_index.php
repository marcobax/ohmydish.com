<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Comments</h3>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <a href="<?php echo Core::url('admin/comment_index?delete-spam=1'); ?>" class="btn btn-sm btn-primary">Delete spam</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/comment_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="name">ID</label>
                    <input type="text" name="id" id="id" value="<?php echo (isset($query['id']))?$query['id']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="status">Spam status</label>
                    <?php $statusses = [0 => 'Geen spam', 1 => 'Spam']; ?>
                    <select name="spam" id="spam" class="form-control">
                        <?php foreach($statusses as $status => $label): ?>
                            <option value="<?php echo $status; ?>" <?php echo (isset($query['spam']) && ((int) $query['spam'] === (int) $status))?'selected':''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <?php $statusses = ['approved' => 'Approved', 'pending' => 'Pending']; ?>
                    <select name="status" id="status" class="form-control">
                        <option value="" readonly="readonly">Make a choice</option>
                        <?php foreach($statusses as $status => $label): ?>
                            <option value="<?php echo $status; ?>" <?php echo (isset($query['status']) && ($query['status'] === $status))?'selected':''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filteren">
                    <a href="<?php echo Core::url('admin/comment_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm m-0">
                <thead>
                <tr>
                    <th colspan="2">Content</th>
                    <th><a href="<?php echo $this->request->filterURI('admin/comment_index', 'created'); ?>">Created</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/comment_index', 'id'); ?>">ID</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/comment_index', 'status'); ?>">Status</a></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($comments as $comment): ?>
                    <?php
                    $entity_model = $comment['page_type'] . 'Model';
                    $em = new $entity_model;
                    $entity = $em->get($comment['entity_id']);
                    switch ($comment['page_type']) {
                        case 'recipe':
                            $prefix = 'recipe';
                            break;
                        case 'blog':
                            $prefix = 'blog';
                            break;
                        default:
                            $prefix = '';
                            break;
                    }
                    ?>
                    <tr>
                        <td style="width: 100px;">
                            <a href="<?php echo Core::url($prefix . '/' . $entity['slug']); ?>" target="_blank"><img style="width: 100px;" class="rounded" src="<?php echo TemplateHelper::getFeaturedImage($entity); ?>" alt="<?php echo $entity['title']; ?>"></a>
                        </td>
                        <td>
                            <table class="table m-0">
                                <tr>
                                    <td>
                                        <h5>(<?php echo $prefix; ?>) <?php echo $entity['title']; ?></h5>
                                        <span class="text-dark"><?php echo $comment['author']; ?> (<?php echo $comment['email']; ?>) says:</span><br>
                                        <span class="text-muted"><?php echo htmlspecialchars($comment['content']); ?></span>
                                        <br>
                                        <?php if($comment['status'] === 'pending'): ?>
                                            <a href="<?php echo Core::url('admin/comment_index?approve=' . $comment['id']); ?>" class="btn btn-sm btn-success">Approve</a>
                                        <?php elseif($comment['status'] === 'approved'): ?>
                                            <a href="<?php echo Core::url($prefix . '/comments/' . $entity['slug']); ?>#comment-<?php echo $comment['id']; ?>" class="btn btn-primary btn-sm">View</a>
                                            <a href="<?php echo Core::url('admin/comment_index?respond=' . $comment['id']); ?>" class="btn btn-sm btn-success">Reply</a>
                                        <?php endif; ?>
                                        <?php if((int) $comment['spam'] === 0): ?>
                                            <a href="<?php echo Core::url('admin/comment_index?tag-spam=' . $comment['id']); ?>" class="btn btn-sm btn-outline-warning">Mark as spam</a>
                                        <?php else: ?>
                                            <a href="<?php echo Core::url('admin/comment_index?not-spam=' . $comment['id']); ?>" class="btn btn-sm btn-outline-warning">Mark as normal comment</a>
                                        <?php endif; ?>
                                        <a href="<?php echo Core::url('admin/sure/comment/' . $comment['id']); ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td><?php echo TemplateHelper::formatDate($comment['created']); ?></td>
                        <td><?php echo $comment['id']; ?></td>
                        <td><?php echo ($comment['status']==='approved')?'Approved':'Pending'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>