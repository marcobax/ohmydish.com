<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Collections -->
    <?php foreach($collections as $collection): ?>
        <url>
            <loc><?php echo Core::url('community/collection/' . $collection['unique_id'] . '#' . CoreHelper::slugify($collection['name'])); ?></loc>
            <lastmod><?php echo substr($collection['updated'],0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    <?php endforeach; ?>
    <!-- User collections -->
    <?php foreach($users as $user): ?>
        <url>
            <loc><?php echo Core::url('community/collection/favourites/' . $user['id'] . '#my-favourites'); ?></loc>
            <lastmod><?php echo substr(($user['last_logged_in']?$user['last_logged_in']:$user['created']),0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    <?php endforeach; ?>
</urlset>