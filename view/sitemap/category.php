<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Categories -->
    <?php foreach($categories as $category): ?>
        <?php $category['type'] = ($category['type'] === 'hulp-in-de-keuken')?'keukenhulp':$category['type']; ?>
        <url>
            <loc><?php echo Core::url($category['type'] . '/' . $category['slug']); ?></loc>
            <changefreq>monthly</changefreq>
            <priority>0.1</priority>
            <image:image>
                <image:loc><?php echo TemplateHelper::getFeaturedImage($category); ?></image:loc>
                <image:title><![CDATA[<?php echo $category['title']; ?>]]></image:title>
            </image:image>
        </url>
    <?php endforeach; ?>
</urlset>
