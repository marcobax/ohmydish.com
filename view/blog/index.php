<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if(is_array($category) && count($category)): ?>
                <h1 class="fancy mb-0 text-center with-stripe"><?php echo $category['title']; ?></h1>
                <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin')): ?>
                    <a class="btn btn-warning btn-lg" href="<?php echo Core::url('admin/category_edit/' . $category['id']); ?>">edit</a>
                <?php endif; ?>
                <?php if(array_key_exists('content', $category) && strlen($category['content'])): ?>
                    <div class="text-center">
                        <?php echo $category['content']; ?>
                    </div>
                <?php else: ?>
                <?php endif; ?>
            <?php else: ?>
                <h1 class="fancy mb-0 text-center with-stripe">Blog articles</h1>
                <p class="text-center">This is where we share cooking tips, tutorials and all things cooking. Take a look at all of the blog posts that are put on Ohmydish.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if(is_array($category) && count($category)): ?>
    <div class="row m-0 d-none d-md-block mb-2 featured-image-container">
        <img style="object-fit: cover; width: 100%; max-height: 200px;" width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($category); ?>" alt="<?php echo $category['title']; ?>" class="img-fluid shadow rounded">
    </div>
<?php endif; ?>
<div class="container">
    <?php if(isset($blogs) && is_array($blogs) && count($blogs)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <?php foreach($blogs as $blog): ?>
                <?php $dt = new DateTime($blog['published']); ?>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <a href="<?php echo Core::url('blog/' . $blog['slug']); ?>">
                            <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($blog); ?>" alt="<?php echo $blog['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                        </a>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="row m-0">
                            <div class="col-12 col-sm-8 col-md-9 m-0">
                                <h3><a href="<?php echo Core::url('blog/' . $blog['slug']); ?>" class="text-dark"><?php echo $blog['title']; ?></a></h3>
                                <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Published" aria-hidden="true"></span> <?php echo $dt->format('Y-m-d'); ?></span></strong>
                                <p class="text-muted"><?php echo $blog['excerpt']; ?></p>
                            </div>
                            <div class="d-none d-sm-block col-sm-4 col-md-3 m-0">
                                <a href="<?php echo Core::url('blog/' . $blog['slug']); ?>" class="btn btn-warning text-dark btn-block">View blog</a>
                                <a href="<?php echo Core::url('blog/comments/' . $blog['slug']); ?>" class="btn d-none d-md-block btn-outline-secondary btn-block text-dark">View comments (<?php echo $blog['total_comments']; ?>)</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <?php endif; ?>
</div>