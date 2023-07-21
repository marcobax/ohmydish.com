<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Recipes -->
    <?php foreach($recipes as $recipe): ?>
        <url>
            <loc><?php echo Core::url('recipe/' . $recipe['slug']); ?></loc>
            <lastmod><?php echo substr($recipe['updated'],0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>1.0</priority>
            <image:image>
                <image:loc><?php echo TemplateHelper::getFeaturedImage($recipe); ?></image:loc>
                <image:title><![CDATA[<?php echo $recipe['title']; ?>]]></image:title>
                <image:caption><![CDATA[<?php echo $recipe['excerpt']; ?>]]></image:caption>
            </image:image>
        </url>
    <?php endforeach; ?>
</urlset>