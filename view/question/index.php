<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center with-stripe">Cooking questions</h1>
            <p class="text-center">Find answers on different kinds of subject that are all about cooking. Learn more about certain cookign techniques, tips and tricks.</p>
        </div>
    </div>
</div>
<div class="row m-0 d-none d-md-block mb-2 featured-image-container">
    <img style="object-fit: cover; width: 100%; max-height: 200px;" width="1200" height="800" src="<?php echo Core::url('wp-content/uploads/2016/12/Kennisbank-voor-koken.jpg'); ?>" alt="Cooking questions" class="img-fluid shadow rounded">
</div>
<div class="container">
    <?php if(isset($questions) && is_array($questions) && count($questions)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <?php foreach($questions as $question): ?>
            <?php $dt = new DateTime($question['created']); ?>
            <div class="row">
                <div class="col-12 col-md-3">
                    <a href="<?php echo Core::url('question/' . $question['slug']); ?>">
                        <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($question); ?>" alt="<?php echo $question['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
                    </a>
                </div>
                <div class="col-12 col-md-9">
                    <div class="row m-0">
                        <div class="col-12 col-sm-8 col-md-9 m-0">
                            <h3><a href="<?php echo Core::url('question/' . $question['slug']); ?>" class="text-dark"><?php echo $question['title']; ?></a></h3>
                            <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Published" aria-hidden="true"></span> <?php echo $dt->format('Y-m-d'); ?></span></strong>
                            <p class="text-muted"><?php echo $question['excerpt']; ?></p>
                        </div>
                        <div class="d-none d-sm-block col-sm-4 col-md-3 m-0">
                            <p class="text-right"><a href="<?php echo Core::url('question/' . $question['slug']); ?>" class="btn btn-warning text-dark">View question</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <?php endif; ?>
</div>