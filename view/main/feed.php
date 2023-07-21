<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:georss="http://www.georss.org/georss"
     xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
>
    <channel>
        <title>Ohmydish RSS feed</title>
        <atom:link href="<?php echo Core::url('feed'); ?>" rel="self" type="application/rss+xml" />
        <link><?php echo Core::url('/'); ?></link>
        <description><?php echo $count; ?>+ delicious recipes for free at Ohmydish</description>
        <lastBuildDate><?php echo date('D, d M Y H:i:s'); ?> +0100</lastBuildDate>
        <language>nl</language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <generator><?php echo Core::url('/') ?>?v=3.0</generator>
        <?php foreach($items as $item): ?>
        <item>
            <title><?php echo $item['title']; ?></title>
            <link><?php echo Core::url($item['url_prefix'] . '/' . $item['slug']); ?></link>
            <comments><?php echo Core::url($item['url_prefix'] . '/comments/' . $item['slug']); ?></comments>
            <pubDate><?php echo date('D, d M Y H:i:s', strtotime($item['published'])); ?> +0100</pubDate>
            <?php foreach ($item['rss_categories'] as $category): ?>
                <category><![CDATA[<?php echo $category; ?>]]></category>
            <?php endforeach; ?>
            <guid isPermaLink="false"><?php echo $item['slug']; ?></guid>
            <description><![CDATA[<a href="<?php echo Core::url($item['url_prefix'] . '/' . $item['slug']); ?>"><img src="<?php echo TemplateHelper::getFeaturedImage($item); ?>" width="564" height="373" alt="<?php echo $item['title']; ?>"></a><br><?php echo $item['excerpt']; ?><br> This recipe <a href="<?php echo Core::url($item['url_prefix'] . '/' . $item['slug']); ?>"><?php echo $item['title']; ?></a> is found at <a href="<?php echo Core::url('/'); ?>">ohmydish.com</a>.<br>]]></description>
            <content:encoded><![CDATA[<?php echo TemplateHelper::truncate($item['content'], 80); ?> <a href="<?php echo Core::url($item['url_prefix'] . '/' . $item['slug']); ?>?bron=rss&actie=continue-reading">Continue reading</a>]]></content:encoded>
            <slash:comments><?php echo $item['total_comments']; ?></slash:comments>
            <enclosure url="<?php echo TemplateHelper::getFeaturedImage($item); ?>" length="<?php echo Core::get_filesize($item['featured_image']); ?>" type="image/jpg" />
        </item>
        <?php endforeach; ?>
    </channel>
</rss>