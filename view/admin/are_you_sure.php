<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h2 class="with-stripe">Confirm deletion</h2>
            <p class="text-center">Are you sure you want to delete this item? #soscared</p>
        </div>
        <div class="col-12 text-center mb-2">
            <a href="<?php echo Core::url('admin/sure/' . $model . '/' . $id . '/yup'); ?>" class="btn btn-danger btn-lg">Yup</a>
            |
            <a href="<?php echo Core::url('admin/sure/' . $model . '/' . $id . '/noope'); ?>" class="btn btn-secondary btn-lg">No!</a>
        </div>
    </div>
</div>