<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if('draft' === $blog['status']): ?>
                <h3 class="bg-danger text-white text-center p-2">Nog niet gepubliceerd (concept)</h3>
            <?php endif; ?>
            <h1 class="fancy text-center mb-0 with-stripe"><?php echo $blog['title']; ?></h1>
            <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin')): ?>
                <a class="btn btn-warning btn-lg d-print-none" href="<?php echo Core::url('admin/blog_edit/' . $blog['id']); ?>">Edit</a>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <p class="text-center text-muted mt-2"><?php echo $blog['excerpt']; ?></p>
        </div>
    </div>
</div>
<?php if(array_key_exists('featured_image', $blog) && strlen($blog['featured_image'])): ?>
    <div class="row m-0 mb-2 position-relative featured-image-container d-print-none text-center">
        <div class="mx-2 mx-md-0 w-100">
            <figure>
                <picture>
                    <source media="(max-width:768px)" srcset="<?php echo TemplateHelper::getThumbnailImage($blog); ?>">
                    <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($blog); ?>" alt="<?php echo $blog['title']; ?>" class="featured-image-sm img-fluid shadow rounded border">
                </picture>
            </figure>
        </div>
    </div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <hr class="d-print-none">
            <p class="text-center d-none d-md-block d-print-none">
                <a href="#" onclick="window.print(); return false;" class="btn btn-outline-success mb-1">Print blog</a>
                <a href="<?php echo Core::url('blog/comments/' . $blog['slug']); ?>" class="btn btn-outline-success mb-1">View comments (<?php echo $blog['total_comments']; ?>)</a>
            </p>
            <hr class="d-print-none">
            <div id="the-content"><?php echo Core::nl2p($blog['content']); ?></div>
            <?php if($blog['faq']): ?>
                <div class="row d-print-none mb-3 faq">
                    <div class="col-12">
                        <div class="bg-grey rounded border shadow-sm p-4">
                            <h3 class="mb-0 text-green font-weight-bold"><?php echo $blog['title']; ?>: frequently asked questions</h3>
                            <?php echo Core::nl2p($blog['faq']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="text-center d-none d-print-block">
                <?php if(!$blog['faq']): ?>
                    <hr>
                <?php endif; ?>
                <p>View the original blog via:<br>
                <strong><?php echo Core::url('blog/' . $blog['slug']) ?></strong></p>
            </div>
            <hr class="d-print-none">
            <p class="bg-dark text-white text-center p-2 rounded d-print-none">
                Did you like this blog?<br>
                Mention <a href="https://www.instagram.com/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">@ohmydish</a> or tag <a href="https://www.instagram.com/explore/tags/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">#ohmydish</a> on Instagram!
            </p>
            <hr class="d-print-none">
        </div>
        <div class="col-12 d-print-none">
            <hr>
            <div class="adspace text-center d-print-none">
                <?php require(ROOT . '/view/_advertenties/300x250_1.php'); ?>
            </div>
        </div>
    </div>
    <div class="row m-0 mb-sm-2 mt-sm-2 d-print-none">
        <div class="col-6 text-left">
            <?php if ($previous_blog): ?>
                <div class="row">
                    <div class="d-none d-md-block col-3">
                        <a href="<?php echo Core::url('blog/' . $previous_blog['slug']); ?>">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($previous_blog); ?>" class="img-fluid rounded-circle blurry-boi" alt="<?php echo $previous_blog['title']; ?>" data-pin-nopin="true" loading="lazy">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($previous_blog); ?>" class="img-fluid rounded-circle non-blurry-boi" alt="<?php echo $previous_blog['title']; ?>" data-pin-nopin="true" loading="lazy">
                        </a>
                    </div>
                    <div class="col-12 col-md-9 mt-3">
                        <span class="h6 text-muted">&larr; Previous blog</span><br>
                        <span class="h5 font-weight-bold"><a href="<?php echo Core::url('blog/' . $previous_blog['slug']); ?>" class="text-dark"><?php echo $previous_blog['title']; ?></a></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-6 text-right">
            <?php if ($next_blog): ?>
                <div class="row">
                    <div class="col-12 col-md-9 mt-3">
                        <span class="h6 text-muted">Next blog &rarr;</span><br>
                        <span class="h5 font-weight-bold"><a href="<?php echo Core::url('blog/' . $next_blog['slug']); ?>" class="text-dark"><?php echo $next_blog['title']; ?></a></span>
                    </div>
                    <div class="d-none d-md-block col-3">
                        <a href="<?php echo Core::url('blog/' . $next_blog['slug']); ?>">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($next_blog); ?>" class="img-fluid rounded-circle blurry-boi" alt="<?php echo $next_blog['title']; ?>" data-pin-nopin="true" loading="lazy">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($next_blog); ?>" class="img-fluid rounded-circle non-blurry-boi" alt="<?php echo $next_blog['title']; ?>" data-pin-nopin="true" loading="lazy">
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
