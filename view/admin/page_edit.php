<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit page</h3>
            <form action="<?php echo Core::url('admin/page_edit/' . $page['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <?php if($page['slug']): ?>
                            <a href="<?php echo Core::url($page['slug']); ?>" target="_blank"><?php echo Core::url($page['slug']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-right">
                        <?php if($page['slug']): ?>
                            <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url($page['slug']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <input type="submit" value="Save" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="title">Titel</label>
                            <input type="text" name="title" id="title" value="<?php echo trim(htmlentities($page['title'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="excerpt">Status</label>
                                    <?php $statusses = ['draft','publish']; ?>
                                    <select name="status" id="status" class="form-control">
                                        <?php foreach($statusses as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo ($page['status'] === $status)?'selected':''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <?php if($page['status'] === 'publish'): ?>
                                    <div class="form-group">
                                        <label for="published">Published</label>
                                        <input type="text" name="published" id="published" value="<?php echo $page['published']; ?>" class="form-control" autocomplete="off" required>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="excerpt">Inleiding</label>
                    <input type="text" name="excerpt" id="excerpt" value="<?php echo trim(htmlentities($page['excerpt'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="20" class="htmleditor form-control"><?php echo htmlentities($page['content']); ?></textarea>
                    <div class="mt-2 p-2 rounded" style="border: 1px solid lightgrey; border-left: 6px solid #339966;">Aantal woorden: <span id="total-words">0</span>, aantal karakters: <span id="total-characters">0</span></div>
                </div>
                <div class="form-group">
                    <label for="featured_image">Uitgelichte foto</label>
                    <div class="row">
                        <div class="col-2">
                            <img src="<?php echo $page['featured_image']; ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-10">
                            <input type="text" name="featured_image" id="featured_image" value="<?php echo $page['featured_image']; ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="thumbnail_image">Vierkante foto</label>
                            <div class="row">
                                <div class="col-2">
                                    <img src="<?php echo $page['thumbnail_image']; ?>" class="img-fluid" alt="">
                                </div>
                                <div class="col-10">
                                    <input type="text" name="thumbnail_image" id="thumbnail_image" value="<?php echo $page['thumbnail_image']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if($page['created']): ?>
        <div class="row">
            <div class="col-12 text-center p-4">
                Created: <?php echo TemplateHelper::formatDate($page['created']); ?>, updated: <?php echo TemplateHelper::formatDate($page['updated']); ?>
            </div>
        </div>
    <?php endif; ?>
</div>