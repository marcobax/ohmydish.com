<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit black list item</h3>
            <form action="<?php echo Core::url('admin/blacklist_edit/' . $blacklist_item['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-12 text-right">
                        <input type="submit" value="Opslaan" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ip_address">IP adres</label>
                            <input type="text" name="ip_address" id="ip_address" value="<?php echo trim(htmlentities($blacklist_item['ip_address'])); ?>" class="form-control" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="request_url">Reden</label>
                            <input type="text" name="reason" id="reason" value="<?php echo trim(htmlentities($blacklist_item['reason'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>