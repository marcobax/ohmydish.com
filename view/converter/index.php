<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy with-stripe mb-0 text-center">Converter</h1>
            <p class="text-center">Quick and easily convert units like weight, temperature and volume using these handy tools.</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Convert weight
                </div>
                <div class="card-body">
                    For example: from kilogram to milligram.
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success" href="<?php echo Core::url('converter/weight'); ?>">Convert weight</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Convert volume
                </div>
                <div class="card-body">
                    For example: from cups to litres
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success" href="<?php echo Core::url('converter/volume'); ?>">Convert volume</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Convert temperature
                </div>
                <div class="card-body">
                    For example: from Fahrenheit to Celsius
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success" href="<?php echo Core::url('converter/temperature'); ?>">Convert temperature</a>
                </div>
            </div>
        </div>
    </div>
</div>