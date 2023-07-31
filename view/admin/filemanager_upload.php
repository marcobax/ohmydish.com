<div class="container-fluid px-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Upload file(s)</h3>
            <span class="text-muted">Current directory: <strong><?php echo $relative_directory; ?></strong></span>
            <div class="row border-bottom pb-3 mb-3">
                <div class="col-6"></div>
                <div class="col-6 text-right">
                    <a class="btn btn-lg btn-success" href="<?php echo Core::url('admin/filemanager_index?directory=' . $relative_directory); ?>">Go back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="file_upload">
                        <form action="<?php echo Core::url('admin/filemanager_upload?directory=' . $relative_directory); ?>" class="dropzone">
                            <div class="dz-message needsclick">
                                <span class="note needsclick">Drop files here to upload</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>