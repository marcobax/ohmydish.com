<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Blogs -->
    <?php foreach($blogs as $blog): ?>
        <?php $date = $blog['updated']?$blog['updated']:$blog['created']; ?>
        <url>
            <loc><?php echo Core::url('blog/comments/' . $blog['slug']); ?></loc>
            <lastmod><?php echo substr($date,0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.3</priority>
        </url>
    <?php endforeach; ?>
</urlset>
