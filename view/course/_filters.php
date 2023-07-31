<div class="row mb-3">
    <div class="col-12 bg-grey border p-2 rounded shadow-sm">
        <form action="<?php echo $page_canonical; ?>" method="get">
            <div class="form-row">
                <div class="col-6 col-md mb-2 mb-md-0">
                    <select name="cooktime" id="cooktime" class="form-control" onchange="this.form.submit();">
                        <option value="" selected="selected">All cook times</option>
                        <option value="hour" <?php echo (array_key_exists('cooktime', $query) && $query['cooktime']==="hour")?'selected="selected"':''; ?>>More than an hour</option>
                        <?php if(isset($max_displaytime)): ?>
                            <?php foreach(range($max_displaytime, $min_displaytime, 5) as $displaytime): ?>
                                <option value="<?php echo $displaytime; ?>" <?php echo (array_key_exists('cooktime', $query) && (int) $query['cooktime']===$displaytime)?'selected="selected"':''; ?>>Maximum of <?php echo $displaytime; ?> minutes</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-6 col-md mb-2 mb-md-0">
                    <select name="total-yield" id="total-yield" class="form-control" onchange="this.form.submit();">
                        <option value="" selected="selected">All yields</option>
                        <?php if(isset($min_yield)): ?>
                            <?php foreach(range($min_yield, $max_yield) as $yield): ?>
                                <option value="<?php echo $yield; ?>" <?php echo (array_key_exists('total-yield', $query) && (int) $query['total-yield']===$yield)?'selected="selected"':''; ?>>Minimum of <?php echo $yield; ?> <?php echo (1===$yield)?'person':'persons'; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-6 col-md">
                    <select name="total-votes" id="total-votes" class="form-control" onchange="this.form.submit();">
                        <option value="" selected="selected">All votes</option>
                        <?php foreach(range($max_rating, $min_rating) as $i): ?>
                            <option value="<?php echo $i; ?>" <?php echo (array_key_exists('total-votes', $query) && (int) $query['total-votes']===$i)?'selected="selected"':''; ?>>
                                <?php for($j=1;$j<=$i;$j++): ?>
                                    &starf;
                                <?php endfor; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (is_array($query) && count($query)): ?>
                    <div class="col-12 mt-2 mt-md-0 col-md-2">
                        <a class="btn btn-success btn-block" href="<?php echo $page_canonical; ?>">Reset</a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<hr>
