<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit redirect</h3>
            <form action="<?php echo Core::url('admin/redirect_edit/' . $redirect['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-12 text-right">
                        <input type="submit" value="Opslaan" class="btn btn-lg btn-success">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="request_url">Request URL</label>
                            <input type="text" name="request_url" id="request_url" value="<?php echo trim(htmlentities($redirect['request_url'])); ?>" class="form-control" autocomplete="off" placeholder="Voorbeeld: /basisrecept-kippenbouillon/" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="request_url">Destination URL</label>
                            <input type="text" name="destination_url" id="destination_url" value="<?php echo trim(htmlentities($redirect['destination_url'])); ?>" class="form-control" autocomplete="off" placeholder="Voorbeeld: /blog/basisrecept-kippenbouillon" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>