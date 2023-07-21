<div class="container-fluid pl-5 pr-5">
    <div class="row">
        <div class="col-12">
            <h3 class="with-stripe">Edit collection</h3>
            <p><a href="<?php echo Core::url('community/collection/' . $collection['unique_id']); ?>#<?php echo CoreHelper::slugify($collection['name']); ?>" target="_blank"><?php echo Core::url('community/collectie/' . $collection['unique_id']); ?>#<?php echo CoreHelper::slugify($collection['name']); ?></a></p>
            <form action="<?php echo Core::url('admin/collection_edit/' . $collection['id']); ?>" method="post" onsubmit="window.onbeforeunload = null;" id="content-form">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Naam</label>
                            <input type="text" name="name" id="name" value="<?php echo trim(htmlentities($collection['name'])); ?>" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="excerpt">Status</label>
                            <select name="deleted" id="deleted" class="form-control">
                                <option value="1" <?php echo (1 === (int) $collection['deleted'])?'selected':''; ?>>Ja</option>
                                <option value="0" <?php echo (0 === (int) $collection['deleted'])?'selected':''; ?>>Nee</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Omschrijving</label>
                    <textarea name="description" id="description" cols="30" rows="20" class="htmleditor form-control"><?php echo htmlentities($collection['description']); ?></textarea>
                </div>
                <hr>
                <div class="form-group text-center">
                    <input type="submit" value="Opslaan" class="btn btn-lg btn-success">
                </div>
            </form>
        </div>
    </div>
</div>