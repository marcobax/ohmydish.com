<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Comment</h3>
            Author:<br>
            <p><?php echo $comment['author']; ?> (<?php echo $comment['email']; ?>)</p>
            Create:<br>
            <p><?php echo TemplateHelper::formatDate($comment['created']); ?></p>
            On link:<br>
            <p><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></p>
            Comment<br>
            <p><?php echo $comment['content']; ?></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <form action="<?php echo Core::url('admin/comment_edit/' . $comment['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="form-group">
                    <label for="content">Comment</label>
                    <textarea name="content" id="contentz" cols="30" rows="10" class="htmleditor form-control"></textarea>
                </div>
                <div class="form-group text-center">
                    <input type="submit" value="Save" class="btn btn-lg btn-success">
                    <a href="<?php echo Core::url('admin/comment_index'); ?>" class="btn btn-lg btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>