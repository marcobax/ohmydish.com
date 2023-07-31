<div class="container-fluid pl-5 pr-5">
    <div class="row border-bottom pb-3 mb-3">
        <div class="col-12 text-center">
            <h3 class="with-stripe">Filemanager</h3>
            <span class="text-muted">Current directory: <strong><?php echo $relative_directory; ?></strong></span>
            <div class="row">
                <div class="col-6"></div>
                <div class="col-6 text-right">
                    <a class="btn btn-lg btn-outline-secondary" href="<?php echo Core::url('admin/filemanager_create_directory?directory=' . $relative_directory); ?>">Create directory</a>
                    <a class="btn btn-lg btn-success" href="<?php echo Core::url('admin/filemanager_upload?directory=' . $relative_directory); ?>">Upload file</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if(strlen($relative_directory) && $relative_directory !== '/'): ?>
            <div class="col-6 mb-4 col-sm-3 col-md-2 text-center">
                <a href="<?php echo Core::url('admin/filemanager_index?directory=' . $upper_directory); ?>" class="text-info">
                    <img alt="" src="<?php echo Core::asset('open-iconic/svg/folder.svg') ?>" class="oi h1"><br>
                    Parent directory
                </a>
            </div>
        <?php endif; ?>
        <?php if($sub_directories): ?>
            <?php foreach($sub_directories as $sub_directory): ?>
            <div class="col-6 mb-4 col-sm-3 col-md-2 text-center">
                <a href="<?php echo Core::url('admin/filemanager_index?directory=' . $relative_directory . '/' . basename($sub_directory)); ?>">
                    <img alt="" src="<?php echo Core::asset('open-iconic/svg/folder.svg') ?>" class="oi h1"><br>
                    <?php echo basename($sub_directory); ?>
                </a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <hr>
    <div class="row">
        <?php foreach($images as $image): ?>
        <div class="col-6 col-sm-3 col-md-2 text-center">
            <a href="<?php echo Core::url($directory . DIRECTORY_SEPARATOR . $image); ?>" target="_blank">
                <?php
                $ext = '';
                if ($pos = strrpos($image, '.')) {
                    $ext = substr($image, $pos);
                }
                ?>
                <figure class="figure">
                    <?php if(!in_array($ext, $ignore_thumbnails)): ?>
                        <img style="max-width: 200px; max-height: 200px;" class="img-thumbnail img-fluid figure-img rounded up-on-hover" data-pin-nopin="true" loading="lazy" src="<?php echo Core::url($thumbnail_directory . DIRECTORY_SEPARATOR . $image); ?>" alt="">
                    <?php else: ?>
                        <img style="max-width: 200px; max-height: 200px;" class="img-thumbnail img-fluid figure-img rounded up-on-hover" data-pin-nopin="true" loading="lazy" src="<?php echo Core::asset('img/No-Thumbnail.png'); ?>" alt="">
                    <?php endif; ?>
                    <figcaption class="figure-caption"><?php echo basename($image); ?></figcaption>
                </figure>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>