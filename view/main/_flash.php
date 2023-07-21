<?php if(isset($_SESSION['flash'])): ?>
    <div class="text-center alert alert-<?php echo isset($_SESSION['flash']['type'])?$_SESSION['flash']['type']:'success'; ?> alert-dismissible fade show mb-2 mt-2" role="alert">
        <?php echo $_SESSION['flash']['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php if(isset($_SESSION['flash'])){unset($_SESSION['flash']);} ?>
<?php endif; ?>
<?php if(isset($_flash)): ?>
    <div class="text-center alert alert-<?php echo isset($_flash['type'])?$_flash['type']:'success'; ?> alert-dismissible fade show mb-2 mt-2" role="alert">
        <?php echo $_flash['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="alert alert-warning alert-dismissible fade show mb-2 mt-2 recipe-alert" role="alert" style="display: none;">
    <strong>Success!</strong> This recipe has been saved to <a href="<?php echo Core::url('community/collection/favourites'); ?>" class="btn btn-success">my favourites</a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>