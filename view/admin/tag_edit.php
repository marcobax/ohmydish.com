<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit tag</h3>
            <form action="<?php echo Core::url('admin/tag_edit/' . $tag['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <?php if($tag['slug']): ?>
                            <a href="<?php echo Core::url('tag/' . $tag['slug']); ?>" target="_blank"><?php echo Core::url('tag/' .$tag['slug']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-right">
                        <?php if($tag['slug']): ?>
                            <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url('tag/' . $tag['slug']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <input type="submit" value="Save" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" name="name" id="name" value="<?php echo trim(htmlentities($tag['name'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="description">Omschrijving</label>
                    <textarea name="description" id="description" cols="30" rows="20" class="htmleditor form-control"><?php echo htmlentities($tag['description']); ?></textarea>
                </div>
            </form>
        </div>
    </div>
</div>