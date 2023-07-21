<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo Core::url('forum'); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    <!-- Boards -->
    <?php foreach($forum_boards as $forum_board): ?>
        <url>
            <loc><?php echo Core::url('forum/bord/' . $forum_board['slug']); ?></loc>
            <lastmod><?php echo substr($forum_board['last_post_date'],0,10); ?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.4</priority>
        </url>
    <?php endforeach; ?>
    <!-- Categories -->
    <?php foreach($forum_categories as $forum_category): ?>
        <url>
            <loc><?php echo Core::url('forum/categorie/' . $forum_category['slug']); ?></loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.2</priority>
        </url>
    <?php endforeach; ?>
    <!-- Categories -->
    <?php foreach($forum_topics as $forum_topic): ?>
        <url>
            <loc><?php echo Core::url('forum/onderwerp/' . $forum_topic['slug']); ?></loc>
            <lastmod><?php echo substr($forum_board['last_post_date'],0,10); ?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
    <?php endforeach; ?>
</urlset>
