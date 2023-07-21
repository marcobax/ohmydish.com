<div class="row">
    <div class="col-12 col-md-3">
        <a href="<?php echo Core::url(($result['url_prefix']?$result['url_prefix']. '/':'') . $result['slug']); ?>">
            <img width="1200" height="800" src="<?php echo TemplateHelper::getFeaturedImage($result); ?>" alt="<?php echo $result['title']; ?>" class="img-fluid rounded-top" loading="lazy" data-pin-nopin="true">
        </a>
        <?php if('recipe' === $result['model']): ?>
            <?php TemplateHelper::includeHeart($result, 'smaller'); ?>
        <?php endif; ?>
    </div>
    <div class="col-12 col-md-9">
        <div class="row m-0">
            <div class="col-12 col-sm-8 col-md-9 m-0">
                <h3><a href="<?php echo Core::url(($result['url_prefix']?$result['url_prefix']. '/':'') . $result['slug']); ?>" class="text-dark"><?php echo $result['title']; ?></a></h3>
                <?php if('recipe' === $result['model']): ?>
                    <strong class="mr-4"><span class="nowrap"><span class="oi oi-clock h6" title="Kooktijd" aria-hidden="true"></span> <?php echo Template::displayTimeToDescription($result['displaytime']); ?></span></strong>
                    <strong class="mr-4"><span class="nowrap"><span class="oi oi-people h6" title="Aantal personen" aria-hidden="true"></span> <?php echo Template::displayYield($result['yield']); ?></span></strong>
                    <?php if($result['average_rating']): ?>
                        <span class="nowrap">
                                <?php for($i=0; $i<$result['average_rating'];$i++): ?>
                                    <span class="oi oi-star h6" style="color: orange;" title="Gemiddelde score" aria-hidden="true"></span>
                                <?php endfor; ?>
                            <?php for($i=$result['average_rating']; $i<5;$i++): ?>
                                <span class="oi oi-star h6" style="color: lightgrey;" title="Gemiddelde score" aria-hidden="true"></span>
                            <?php endfor; ?>
                                </span>
                    <?php endif; ?>
                <?php endif; ?>
                <p class="text-muted"><?php echo $result['excerpt']; ?></p>
            </div>
            <div class="col-sm-4 col-md-3 m-0">
                <a href="<?php echo Core::url($result['url_prefix'] . '/' . $result['slug']); ?>" class="btn btn-warning btn-block">View <?php echo (strlen($result['url_prefix'])?$result['url_prefix']:'page'); ?></a>
                <?php if('recipe' === $result['model']): ?>
                    <a href="<?php echo Core::url('community/save-recipe/' . $result['slug']); ?>" class="btn btn-outline-secondary btn-block text-dark">Save to collection</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<hr>