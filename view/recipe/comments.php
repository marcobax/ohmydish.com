<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy text-center mb-0 with-stripe">Comments on <?php echo $recipe['title']; ?></h1>
        </div>
        <div class="col-12">
            <p class="text-center text-muted mt-2"><?php echo $recipe['excerpt']; ?></p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <p class="text-center">
                <a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" class="btn btn-warning">Back to recipe</a>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php if(is_array($comments) && count($comments)): ?>
                <?php foreach($comments as $comment): ?>
                    <?php include(ROOT . 'view/recipe/_comment.php'); ?>
                <?php endforeach; ?>
            <?php else: ?>
            <p class="text-center">No comments have been made yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <form action="<?php echo Core::url('recipe/comments/' . $recipe['slug']); ?>" method="post">
        <div class="row">
            <div class="col-12">
                <h2 class="with-stripe">Do you want to place comment?</h2>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="author">Wat is je naam?</label>
                    <input type="text" name="author" id="author" placeholder="Your name" required class="form-control">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="email">What is your e-mail address?</label>
                    <input type="email" name="email" id="email" placeholder="email@provider.com" required class="form-control">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="content">Your comment</label>
                    <textarea name="content" id="content" cols="30" rows="3" required class="form-control" placeholder="Write down your comment here"></textarea>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group text-center">
                    <input type="submit" value="Send comment" class="btn btn-lg btn-success">
                </div>
            </div>
        </div>
    </form>
</div>