<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="fancy mb-0 with-stripe">Search results</h1>
            <?php if($total_results): ?>
            <?php if (isset($alternative_results) && is_array($alternative_results) && count($alternative_results)): ?>
                <p>There are <strong>no</strong> results for "<?php echo $searchterm; ?>"</p>
            <?php else: ?>
                    <p>There <?php echo ($total_results <= 1)?'is':'are'; ?> <strong><?php echo $total_results; ?></strong> <?php echo ($total_results <= 1)?'result':'results'; ?> found for "<?php echo $searchterm; ?>"</p>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <?php if(isset($results) && count($results)): ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
        <?php foreach($results as $result): ?>
            <?php include(ROOT . 'view/search/_result.php'); ?>
        <?php endforeach; ?>
        <?php require(ROOT . '/view/main/_pagination.php'); ?>
    <?php else: ?>
        <div class="row">
            <div class="col-12 text-center ">
                <span class="text-danger">Unfortunately no recipes, blogs or pages are found with "<?php echo $searchterm; ?>".</span><br>
                <?php if (isset($alternative_results) && is_array($alternative_results) && count($alternative_results)): ?>
                    <p>Try again using a different search term, or take a look at the suggestions below.</p>
                <?php else: ?>
                    <p>Try again using a different search term:</p>
                    <div class="row">
                        <div class="col-2">&nbsp;</div>
                        <div class="col-8">
                            <form class="mb-4" action="<?php echo Core::url('search'); ?>" method="get" name="zoekformulier2" id="zoekformulier2">
                                <div class="input-group input-group-lg">
                                    <label for="s" class="sr-only">Find recipe</label>
                                    <input type="text" name="s" id="s" placeholder="Find recipe" class="form-control rounded" aria-describedby="btnGroupSearch" value="<?php echo isset($searchterm)?trim($searchterm):''; ?>" autocomplete="off">
                                    <div class="text-left d-none border w-100 bg-white rounded shadow suggestionbox"></div>
                                    <div class="input-group-append">
                                        <input type="submit" class="btn btn-success" value="Search" id="btnGroupSearch">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-2">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Op zoek naar Nederlandse recepten?
                        </div>
                        <div class="card-body">
                            <p>Je bekijkt momenteel onze Engelstalige website. Mocht je op zoek zijn naar onze Nederlandse recepten, lees dan hieronder verder.</p>
                            <a href="https://ohmydish.nl/zoeken?s=<?php echo $searchterm; ?>&ref=omdcom"><img data-pin-nopin="true" src="<?php echo Core::asset('img/dutch.jpg') ?>" alt="Vind Nederlandse recepten"></a>
                            <p>Zoek naar recepten op onze Nederlandse website via de onderstaande knop:</p>
                            <a href="https://ohmydish.nl/zoeken?s=<?php echo $searchterm; ?>&ref=omdcom" class="btn btn-lg btn-warning">Vind "<?php echo $searchterm; ?>" recepten</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (isset($alternative_results) && is_array($alternative_results) && count($alternative_results)): ?>
            <div class="col-12">
                <h2 class="with-stripe text-center fancy">Suggestions</h2>
                <?php foreach($alternative_results as $result): ?>
                    <?php include(ROOT . 'view/search/_result.php'); ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
