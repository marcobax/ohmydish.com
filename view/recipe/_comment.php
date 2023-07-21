<?php $dt = new DateTime($comment['created']); ?>
<div class="card mb-2 <?php echo ($comment['parent_id']>0)?'ml-5':''; ?> shadow-sm" id="reactie-<?php echo $comment['id']; ?>">
    <div class="card-header">
        <strong class="text-success"><?php echo ucfirst($comment['author']); ?></strong> commented at <?php echo $dt->format('Y-m-d'); ?> <?php echo $dt->format('H:i:s'); ?>
    </div>
    <div class="card-body">
        <img src="<?php echo TemplateHelper::gravatarURL($comment['email']); ?>" alt="Gravatar van <?php echo ucfirst($comment['author']); ?>" class="float-left mr-3" loading="lazy">
        <?php echo $comment['content']; ?>
    </div>
</div>
<?php if(array_key_exists('children', $comment) && is_array($comment['children']) && count($comment['children'])): ?>
    <?php foreach($comment['children'] as $comment): ?>
        <?php include(ROOT . 'view/recipe/_comment.php'); ?>
    <?php endforeach; ?>
<?php endif; ?>

