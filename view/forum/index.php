<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-2 text-center with-stripe">Kookliefhebbers forum</h1>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <?php foreach ($forum_categories as $forum_category): ?>
                <?php $forum_boards = $this->forum_board_model->getRecords([], ['id', 'asc']); ?>
                <div class="card shadow">
                    <div class="card-header">
                        <?php echo $forum_category['title']; ?>
                    </div>
                    <div class="card-body">
                        <?php foreach ($forum_boards as $key => $forum_board): ?>
                            <div class="row">
                                <div class="col-7 h5">
                                    <a class="text-success" href=""><?php echo $forum_board['title']; ?></a><br>
                                    <span class="h6"><?php echo $forum_board['excerpt']; ?></span>
                                </div>
                                <div class="col-2 h6">
                                    &nbsp;<br>
                                    <?php echo $forum_board['total_posts']; ?> <?php echo ($forum_board['total_posts']>1|!$forum_board['total_posts'])?'berichten':'bericht'; ?><br>
                                    <?php echo $forum_board['total_topics']; ?> <?php echo ($forum_board['total_topics']>1|!$forum_board['total_topics'])?'onderwerpen':'onderwerp'; ?>
                                </div>
                                <div class="col-3 h6">
                                    &nbsp;<br>
                                    <?php if($forum_board['last_post_by']): ?>
                                    <strong>Laatste bericht</strong> door <?php echo $forum_board['last_post_by']; ?><br>
                                    in <a href="#">onderwerp hier</a><br>
                                    op 5 januari 2020 om 22:31
                                    <?php else: ?>
                                    &nbsp;
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (($key+1) < count($forum_boards)): ?>
                                <hr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>