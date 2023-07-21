<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if('draft' === $question['status']): ?>
                <h3 class="bg-danger text-white text-center p-2">Not yet published (concept)</h3>
            <?php endif; ?>
            <h1 class="fancy text-center mb-0 with-stripe"><?php echo $question['title']; ?></h1>
            <?php if(SessionHelper::isLoggedIn() && SessionHelper::hasPermission('is_admin') && 'publish' === $question['status']): ?>
                <a class="btn btn-warning btn-lg d-print-none" href="<?php echo Core::url('admin/question_edit/' . $question['id']); ?>">Edit</a>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <p class="text-center text-muted mt-2"><?php echo $question['excerpt']; ?></p>
        </div>
    </div>
</div>
<div class="row m-0 mb-4 d-none d-md-block position-relative shadow featured-image-container d-print-none">
    <img style="object-fit: cover; width: 100%; max-height: 500px;" width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($question); ?>" alt="<?php echo $question['title']; ?>" class="img-fluid shadow rounded">
</div>
<div class="row m-0 mb-4 d-md-none">
    <div class="col-12 rounded-sm">
        <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($question); ?>" alt="<?php echo $question['title']; ?>" class="featured-image-sm img-fluid shadow" loading="lazy">
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8">
            <hr class="d-print-none">
            <div id="the-content"><?php echo Core::nl2p($question['content']); ?></div>
            <div class="text-center d-none d-print-block">
                <hr>
                <p>View the original question via:<br>
                <strong><?php echo Core::url('question/' . $question['slug']) ?></strong></p>
            </div>
            <div class="d-print-none">
                <hr />
                <p class="text-center"><a href="<?php echo Core::url('cooking-questions'); ?>"><strong>View all cooking questions</strong></a></p>
                <hr />
                <p class="bg-dark text-white text-center p-2 rounded">
                    Did you think this question was interesting?<br>
                    Mention <a href="https://www.instagram.com/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">@ohmydish</a> or tag <a href="https://www.instagram.com/explore/tags/ohmydish/" target="_blank" class="text-green" rel="noreferrer noopener">#ohmydish</a> on Instagram!
                </p>
                <hr>
            </div>
        </div>
        <div class="col-12 col-md-4 d-print-none">
            <hr>
            <div class="adspace text-center d-print-none">
                <?php require(ROOT . '/view/_advertenties/300x250_1.php'); ?>
            </div>
        </div>
    </div>
    <div class="row m-0 mb-sm-2 mt-sm-2 d-print-none">
        <div class="col-6 text-left">
            <?php if ($previous_question): ?>
                <div class="row">
                    <div class="d-none d-md-block col-3">
                        <a href="<?php echo Core::url('question/' . $previous_question['slug']); ?>">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($previous_question); ?>" class="img-fluid rounded-circle non-blurry-boi" alt="<?php echo $previous_question['title']; ?>" data-pin-nopin="true" loading="lazy">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($previous_question); ?>" class="img-fluid rounded-circle blurry-boi" alt="<?php echo $previous_question['title']; ?>" data-pin-nopin="true" loading="lazy">
                        </a>
                    </div>
                    <div class="col-12 col-md-9 mt-3">
                        <span class="h6 text-muted">&larr; Previous question</span><br>
                        <span class="h5 font-weight-bold"><a href="<?php echo Core::url('question/' . $previous_question['slug']); ?>" class="text-dark"><?php echo $previous_question['title']; ?></a></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-6 text-right">
            <?php if ($next_question): ?>
                <div class="row">
                    <div class="col-12 col-md-9 mt-3">
                        <span class="h6 text-muted">Next question &rarr;</span><br>
                         <span class="h5 font-weight-bold"><a href="<?php echo Core::url('question/' . $next_question['slug']); ?>" class="text-dark"><?php echo $next_question['title']; ?></a></span>
                    </div>
                    <div class="d-none d-md-block col-3">
                        <a href="<?php echo Core::url('question/' . $next_question['slug']); ?>">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($next_question); ?>" class="img-fluid rounded-circle non-blurry-boi" alt="<?php echo $next_question['title']; ?>" data-pin-nopin="true" loading="lazy">
                            <img height="94" width="94" src="<?php echo TemplateHelper::getThumbnailImage($next_question); ?>" class="img-fluid rounded-circle blurry-boi" alt="<?php echo $next_question['title']; ?>" data-pin-nopin="true" loading="lazy">
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>