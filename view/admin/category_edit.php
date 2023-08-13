<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit category</h3>
            <form action="<?php echo Core::url('admin/category_edit/' . $category['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <?php if($category['slug']): ?>
                            <a href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" target="_blank"><?php echo Core::url($category['type'] . '/' .$category['slug']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-right">
                        <?php if($category['slug']): ?>
                            <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url($category['type'] . '/' . $category['slug']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <input type="submit" value="Save" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo trim(htmlentities($category['title'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="title">SEO title <span class="text-muted">(optional)</span></label>
                    <input type="text" name="seo_title" id="seo_title" value="<?php echo trim(htmlentities($category['seo_title'])); ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="20" class="htmleditor form-control"><?php echo htmlentities($category['content']); ?></textarea>
                    <div class="mt-2 p-2 rounded" style="border: 1px solid lightgrey; border-left: 6px solid #339966;">Total words: <span id="total-words">0</span>, total characters: <span id="total-characters">0</span></div>
                </div>
                <div class="form-group">
                    <label for="excerpt">Type</label>
                    <?php $types = ['course','theme','difficulty','kitchen','dishtype','blog','question','kitchenhelp']; ?>
                    <select name="type" id="type" class="form-control">
                        <?php foreach($types as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo ($category['type'] === $type)?'selected':''; ?>><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="featured_image">Featured image</label>
                    <div class="row">
                        <div class="col-2">
                            <img src="<?php echo $category['featured_image']; ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-10">
                            <input type="text" name="featured_image" id="featured_image" value="<?php echo $category['featured_image']; ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>