<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy with-stripe mb-0 text-center">Convert volume</h1>
            <p class="text-center">For example: from cups to litres</p>
        </div>
    </div>
    <div class="row">
        <div class="d-none col-md-2 d-md-block"></div>
        <div class="col-12 col-md-8 mb-2">

            <?php if($result): ?>
                <div class="alert alert-success mb-2 mt-2 text-center" role="alert">
                    <h4 class="m-0"><?php echo $quantity; ?> <?php echo strtolower($units[$from]); ?></h4>
                    <span class="h1">=</span>
                    <h4><?php echo $result; ?> <?php echo strtolower($units[$to]); ?></h4>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo Core::url('converter/volume'); ?>" method="get">
                        <div class="form-group">
                            <label for="from">From:</label>
                            <select name="from" id="from" required="required" class="form-control form-control-lg">
                                <?php foreach($units as $unit => $label): ?>
                                    <option value="<?php echo $unit; ?>" <?php echo ($unit === $from)?'selected':''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="to">To:</label>
                            <select name="to" id="to" required="required" class="form-control form-control-lg">
                                <?php foreach($units as $unit => $label): ?>
                                    <option value="<?php echo $unit; ?>" <?php echo ($unit === $to)?'selected':''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="text-center"><a href="#" onclick="switchInputs(); return false;"><span class="oi oi-loop-circular"></span> Switch from and to</a></div>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="text" required="required" class="form-control form-control-lg" name="quantity" id="quantity" placeholder="For example: 2" value="<?php echo $quantity?$quantity:''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Convert">
                            <a class="btn btn-lg btn-block btn-secondary" href="<?php echo Core::url('converter/volume'); ?>">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="d-none col-md-2 d-md-block"></div>
    </div>
    <div class="row mb-4">
        <div class="col-12 text-center">
            <a href="<?php echo Core::url('converter'); ?>">Show all converter tools</a>
        </div>
    </div>
</div>
<script>
    function switchInputs()
    {
        let from = $('#from').val();
        let to = $('#to').val();
        $('#from').val(to);
        $('#to').val(from);
    }
</script>