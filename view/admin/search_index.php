<div class="container-fluid pl-5 pr-5">
    <h3 class="with-stripe">Recent search queries</h3>
    <div class="row">
        <div class="col-2">
            <form action="<?php echo Core::url('admin/search_index'); ?>" method="get" onsubmit="window.onbeforeunload = null;">
                <div class="form-group">
                    <label for="term">Search query</label>
                    <input type="text" name="term" id="term" value="<?php echo (isset($query['term']))?$query['term']:''; ?>" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="total_results">Results</label>
                    <input type="number" name="total_results" id="total_results" value="<?php echo (isset($query['total_results']))?$query['total_results']:''; ?>" class="form-control" autocomplete="off" min="0">
                </div>
                <div class="form-group">
                    <label for="suggestion">Suggestion</label>
                    <?php $suggestions = [1 => 'Yes', 0 => 'No']; ?>
                    <select name="suggestion" id="suggestion" class="form-control">
                        <option value="">Make a choice</option>
                        <?php foreach($suggestions as $suggestion => $label): ?>
                            <option value="<?php echo $suggestion; ?>" <?php echo (isset($query['suggestion']) && ((int) $query['suggestion'] === (int) $suggestion))?'selected':''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="autosuggest">Auto suggestion</label>
                    <?php $autosuggestions = [1 => 'Ja', 0 => 'Nee']; ?>
                    <select name="autosuggest" id="autosuggest" class="form-control">
                        <option value="">Make a choice</option>
                        <?php foreach($autosuggestions as $autosuggestion => $label): ?>
                            <option value="<?php echo $autosuggestion; ?>" <?php echo (isset($query['autosuggest']) && ((int) $query['autosuggest'] === (int) $autosuggestion))?'selected':''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-success" value="Filter">
                    <a href="<?php echo Core::url('admin/search_index'); ?>" class="btn btn-block btn-link">Reset</a>
                </div>
            </form>
        </div>
        <div class="col-10">
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
            <table class="table table-sm table-hover">
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'created'); ?>">Created</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'term'); ?>">Search query</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'total_results'); ?>">Results</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'page'); ?>">Page</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'suggestion'); ?>">Suggestion</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin/search_index', 'autosuggest'); ?>">Auto suggestion</a></th>
                    <th>Actie</th>
                </tr>
                <?php foreach($latest_search_terms as $latest_search_term): ?>
                    <tr>
                        <td><?php echo date('d-m H:i', strtotime($latest_search_term['created'])); ?></td>
                        <td><?php echo Template::stripEmoji($latest_search_term['term']); ?></td>
                        <td class="text-<?php echo $latest_search_term['total_results']?'':'danger'; ?>"><?php echo $latest_search_term['total_results']; ?></td>
                        <td><?php echo $latest_search_term['page']; ?></td>
                        <td><?php echo $latest_search_term['suggestion']?'Yes':'No'; ?></td>
                        <td><?php echo $latest_search_term['autosuggest']?'Yes':'No'; ?></td>
                        <td>
                            <a href="<?php echo Core::url('search?s=' . htmlspecialchars($latest_search_term['term'])); ?>" class="btn btn-primary btn-sm" target="_blank"><span class="oi oi-magnifying-glass"></span></a>
                            <a href="<?php echo Core::url('admin/sure/search/' . $latest_search_term['id']); ?>" class="btn btn-danger btn-sm"><span class="oi oi-x"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php require(ROOT . '/view/main/_pagination.php'); ?>
        </div>
    </div>
</div>
