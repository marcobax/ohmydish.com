<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit question</h3>
            <form action="<?php echo Core::url('admin/question_edit/' . $question['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <?php if($question['slug']): ?>
                        <a href="<?php echo Core::url('question/' . $question['slug']); ?>" target="_blank"><?php echo Core::url('question/' . $question['slug']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-right">
                        <?php if($question['slug']): ?>
                            <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url('question/' . $question['slug']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <input type="submit" value="Save" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" value="<?php echo trim(htmlentities($question['title'])); ?>" class="form-control" autocomplete="off">
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
                                            <option value="<?php echo $status; ?>" <?php echo ($question['status'] === $status)?'selected':''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <?php if($question['status'] === 'publish'): ?>
                                    <div class="form-group">
                                        <label for="published">Published</label>
                                        <input type="text" name="published" id="published" value="<?php echo $question['published']; ?>" class="form-control" autocomplete="off" required>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <input type="text" name="excerpt" id="excerpt" value="<?php echo trim(htmlentities($question['excerpt'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="20" class="htmleditor form-control"><?php echo htmlentities($question['content']); ?></textarea>
                    <div class="mt-2 p-2 rounded" style="border: 1px solid lightgrey; border-left: 6px solid #339966;">Aantal woorden: <span id="total-words">0</span>, aantal karakters: <span id="total-characters">0</span></div>
                </div>
                <div class="form-group">
                    <label for="featured_image">Featured image</label>
                    <div class="row">
                        <div class="col-2">
                            <img src="<?php echo $question['featured_image']; ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-10">
                            <input type="text" name="featured_image" id="featured_image" value="<?php echo $question['featured_image']; ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="thumbnail_image">Square image</label>
                            <div class="row">
                                <div class="col-2">
                                    <img src="<?php echo $question['thumbnail_image']; ?>" class="img-fluid" alt="">
                                </div>
                                <div class="col-10">
                                    <input type="text" name="thumbnail_image" id="thumbnail_image" value="<?php echo $question['thumbnail_image']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if($question['created']): ?>
        <div class="row">
            <div class="col-12 text-center p-4">
                Created: <?php echo TemplateHelper::formatDate($question['created']); ?>, updated: <?php echo TemplateHelper::formatDate($question['updated']); ?>
            </div>
        </div>
    <?php endif; ?>
</div>