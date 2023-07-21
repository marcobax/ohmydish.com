<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
              xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach(['main','blog','blog-comments','page','recipe','recipe-comments','category','tag','question','collection','forum'] as $type): ?>
    <sitemap>
        <loc><?php echo Core::url('sitemap-' . $type . '.xml'); ?></loc>
    </sitemap>
    <?php endforeach; ?>
</sitemapindex>
