<div class="container-fluid pl-5 pr-5">
    <div class="col-12 text-center mt-2">
        <a href="<?php echo Core::url('admin?year=' . $previous_week->format('Y') . '&week=' . $previous_week->format('W')); ?>" class="btn btn-secondary btn-lg">&larr; week <?php echo intval($previous_week->format('W')); ?></a>
        <a href="<?php echo Core::url('admin?year=' . $dt->format('Y') . '&week=' . $dt->format('W')); ?>" class="btn btn-success btn-lg">week <?php echo intval($dt->format('W')); ?></a>
        <?php if ($dt->format('W') < date('W')): ?>
            <a href="<?php echo Core::url('admin?year=' . $next_week->format('Y') . '&week=' . $next_week->format('W')); ?>" class="btn btn-secondary btn-lg">week <?php echo intval($next_week->format('W')); ?> &rarr;</a>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-12">
            <canvas id="myChart" width="400" height="80"></canvas>
            <script>
                new Chart(document.getElementById("myChart"),
                    {
                        "type": "bar",
                        "data": {
                            "labels": <?php echo json_encode(array_values($labels)); ?>,
                            "datasets": [
                                <?php foreach($datasets as $dataset): ?>
                                {
                                    "label": "<?php echo $dataset['label']; ?>",
                                    "data": <?php echo json_encode(array_values($dataset['data'])); ?>,
                                    "fill": false,
                                    "backgroundColor": [
                                        <?php foreach($dataset['data'] as $data): ?>
                                        "<?php echo $dataset['color']; ?>",
                                        <?php endforeach; ?>
                                    ],
                                    "borderColor": [
                                        <?php foreach($dataset['data'] as $data): ?>
                                        "<?php echo $dataset['color']; ?>",
                                        <?php endforeach; ?>
                                    ],
                                    "borderWidth":<?php echo $dataset['borderWidth']; ?>
                                },
                                <?php endforeach; ?>
                            ],
                        },
                        "options": {
                            "animation": {
                                duration: 0
                            },
                            "scales": {
                                "yAxes": [
                                    {
                                        "ticks": {
                                            "beginAtZero": true
                                        }
                                    }
                                ]
                            }
                        }
                    }
                );
            </script>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <h3 class="with-stripe">Recent search queries</h3>
            <table class="table table-sm table-hover">
                <tr>
                    <th><a href="<?php echo $this->request->filterURI('admin', 'created'); ?>">Created</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin', 'term'); ?>">Search term</a></th>
                    <th><a href="<?php echo $this->request->filterURI('admin', 'total_results'); ?>">Results</a></th>
                    <th>Action</th>
                </tr>
                <?php foreach($latest_search_terms as $latest_search_term): ?>
                    <tr>
                        <td><?php echo date('d-m H:i', strtotime($latest_search_term['created'])); ?></td>
                        <td><?php echo Template::stripEmoji($latest_search_term['term']); ?></td>
                        <td class="text-<?php echo $latest_search_term['total_results']?'':'danger'; ?>"><?php echo $latest_search_term['total_results']; ?></td>
                        <td>
                            <a href="<?php echo Core::url('search?s=' . htmlspecialchars($latest_search_term['term'])); ?>" class="btn btn-primary btn-sm" target="_blank"><span class="oi oi-magnifying-glass"></span></a>
                            <a href="<?php echo Core::url('admin/sure/search/' . $latest_search_term['id']); ?>" class="btn btn-danger btn-sm"><span class="oi oi-x"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <h3 class="with-stripe">Recently saved recipes</h3>
            <table class="table table-sm table-hover">
                <tr>
                    <th>Saved</th>
                    <th>Recipe</th>
                    <th>User</th>
                </tr>
                <?php foreach($latest_saved_recipes as $latest_saved_recipe): ?>
                    <?php
                    $user = $this->user_model->get($latest_saved_recipe['user_id']);
                    $recipe = $this->recipe_model->get($latest_saved_recipe['recipe_id']);
                    if ($latest_saved_recipe['collection_id']) {
                        $collection = $this->collection_model->get($latest_saved_recipe['collection_id']);
                    }
                    ?>
                    <tr>
                        <td><?php echo TemplateHelper::formatDate($latest_saved_recipe['created']); ?></td>
                        <td><a href="<?php echo Core::url('recipe/' . $recipe['slug']); ?>" target="_blank"><?php echo $recipe['title']; ?></a><br>
                            &rarrhk;
                            <?php if($latest_saved_recipe['collection_id']): ?>
                                <a href="<?php echo Core::url('community/collection/' . $collection['unique_id']); ?>#<?php echo CoreHelper::slugify($collection['name']); ?>" target="_blank"><?php echo CoreHelper::slugify($collection['name']); ?></a>
                            <?php else: ?>
                                <a href="<?php echo Core::url('community/collection/favourites/' . $latest_saved_recipe['user_id']); ?>#favourites" target="_blank">Favourites</a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
