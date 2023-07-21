<h1><?php echo $recipe['title']; ?></h1>
<p><?php echo $recipe['excerpt']; ?></p>
<amp-img
    src="<?php echo TemplateHelper::getFeaturedImage($recipe); ?>"
    alt="<?php echo $recipe['title']; ?>"
    layout="responsive"
    height="800"
    width="1200">
</amp-img>
<p>
    Cook time: <?php echo Template::displayTimeToDescription($recipe['displaytime']); ?><br>
    Servings: <?php echo Template::displayYield($recipe['yield']); ?><br>
    Category: <a href="<?php echo Core::url($main_category['type'] . '/' . $main_category['slug']); ?>" class="text-dark"><?php echo $main_category['title']; ?></a
</p>
<hr>
<?php if($recipe['ingredients']): ?>
<h2>Ingredients</h2>
<?php echo $recipe['ingredients']; ?>
<hr>
<?php endif; ?>
<?php if($recipe['kitchen_equipment']): ?>
<h2>Kitchen equipment</h2>
<?php echo $recipe['kitchen_equipment']; ?>
<hr>
<?php endif; ?>
<?php echo Core::nl2p(TemplateHelper::linkifyText($recipe['content'])); ?>
