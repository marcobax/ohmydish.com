<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Pages -->
    <?php foreach($pages as $page): ?>
        <url>
            <loc><?php echo Core::url($page['slug']); ?></loc>
            <lastmod><?php echo substr($page['updated'],0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.2</priority>
            <image:image>
                <image:loc><?php echo TemplateHelper::getFeaturedImage($page); ?></image:loc>
                <image:title><![CDATA[<?php echo $page['title']; ?>]]></image:title>
            </image:image>
        </url>
    <?php endforeach; ?>
</urlset>