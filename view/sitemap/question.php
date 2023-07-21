<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Questions -->
    <?php foreach($questions as $question): ?>
        <url>
            <loc><?php echo Core::url('question/' . $question['slug']); ?></loc>
            <lastmod><?php echo substr($question['updated'],0,10); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.3</priority>
            <image:image>
                <image:loc><?php echo TemplateHelper::getFeaturedImage($question); ?></image:loc>
                <image:title><![CDATA[<?php echo $question['title']; ?>]]></image:title>
            </image:image>
        </url>
    <?php endforeach; ?>
</urlset>