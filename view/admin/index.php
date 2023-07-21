<div class="container-fluid pl-5 pr-5">
    <?php if(false): ?>
    <div class="row">
        <div class="col-12">
            <canvas id="myChart" width="400" height="50"></canvas>
            <script>
                new Chart(document.getElementById("myChart"),
                    {
                        "type": "bar",
                        "data": {
                            "labels": ["Maandag 21-12","Dinsdag 22-12","Woensdag 23-12","Donderdag 24-12","Vrijdag 25-12","Zaterdag 26-12","Zondag 27-12"],
                            "datasets": [{
                                "label": "Statistieken",
                                "data": [65,59,80,81,56,55,40],
                                "fill": false,
                                "backgroundColor": [
                                    "rgba(255, 99, 132, 0.2)",
                                    "rgba(255, 159, 64, 0.2)",
                                    "rgba(255, 205, 86, 0.2)",
                                    "rgba(75, 192, 192, 0.2)",
                                    "rgba(54, 162, 235, 0.2)",
                                    "rgba(153, 102, 255, 0.2)",
                                    "rgba(201, 203, 207, 0.2)"
                                ],
                                "borderColor": [
                                    "rgb(255, 99, 132)",
                                    "rgb(255, 159, 64)",
                                    "rgb(255, 205, 86)",
                                    "rgb(75, 192, 192)",
                                    "rgb(54, 162, 235)",
                                    "rgb(153, 102, 255)",
                                    "rgb(201, 203, 207)"
                                ],
                                "borderWidth":1
                            }]
                        },
                        "options": {
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
    <?php endif; ?>
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
