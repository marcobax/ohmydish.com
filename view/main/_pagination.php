<?php
$uri = '?';
$query = $this->request->getQuery();
if (is_array($query) && count($query)) {
    if (array_key_exists('p', $query)) {
        unset($query['p']);
    }

    if (is_array($query) && count($query)) {
        $uri .= http_build_query($query) . '&';
    }

}
?>

<?php if(isset($pagination) && is_array($pagination) && $pagination['total_pages'] > 1): ?>
    <nav aria-label="Pagination">
        <ul class="pagination pagination-lg justify-content-center">
            <li class="page-item d-none d-md-inline-block <?php echo ($pagination['page'] <= 1) ? "disabled" : "" ?>">
                <?php $previous_page = (($pagination['page'] - 1))>0?($pagination['page'] - 1):1; ?>
                <a href="<?php echo $uri . 'p=' . $previous_page; ?>" class="page-link">Previous</a>
            </li>
            <?php if($pagination['page'] > ($pagination['max_steps_per_screen']+1)): ?>
                <li class="page-item">
                    <a href="<?php echo $uri . 'p=1'; ?>" class="page-link">1</a>
                </li>
                <?php if(($pagination['page'] - $pagination['max_steps_per_screen']) > 2): ?>
                <li class="page-item disabled d-none d-md-inline-block">
                    <a href="#" class="page-link">...</a>
                </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php for($page = max(1, $pagination['page'] - $pagination['max_steps_per_screen']); $page <= min($pagination['page'] + $pagination['max_steps_per_screen'], $pagination['total_pages']); $page++): ?>
                <li class="page-item <?php echo ($pagination['page'] == $page || (0 === $pagination['page'] && 1 === $page)) ? "active" : "" ?>">
                    <a href="<?php echo $uri . 'p=' . $page; ?>" class="page-link"><?php echo $page ?></a>
                </li>
            <?php endfor ?>
            <?php if(($pagination['total_pages'] - $pagination['page']) > $pagination['max_steps_per_screen']): ?>
                <li class="page-item disabled d-none d-md-inline-block">
                    <a href="#" class="page-link">...</a>
                </li>
                <li class="page-item">
                    <a href="<?php echo $uri . 'p=' . $pagination['total_pages']; ?>" class="page-link"><?php echo $pagination['total_pages'] ?></a>
                </li>
            <?php endif; ?>
            <li class="page-item d-none d-md-inline-block <?php echo ($pagination['page'] == $pagination['total_pages']) ? "disabled" : "" ?>">
                <?php
                $next_page = 2;
                if ($pagination['page'] > 1) {
                    $next_page = $pagination['page'] + 1;
                    if ($next_page > $pagination['total_pages']) {
                        $next_page = $pagination['total_pages'];
                    }
                }
                ?>
                <a href="<?php echo $uri . 'p=' . $next_page; ?>" class="page-link">Next</a>
            </li>
        </ul>
    </nav>
<?php endif ?>