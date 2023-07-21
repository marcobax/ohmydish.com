<!doctype html>
<html amp lang="en">
<head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <title><?php echo isset($page_title)?$page_title:'Recipes and dishes'; ?><?php echo (!isset($force_page_title))?' - Ohmydish':''; ?></title>
    <?php if(isset($page_canonical)): ?>
    <link rel="canonical" href="<?php echo Core::url($this->request->getURI()); ?>"/>
    <link rel="amphtml" href="<?php echo $page_canonical; ?>"/>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <script type="application/ld+json"><?php echo (isset($structured_data))?$structured_data:TemplateHelper::getDefaultSchema(); ?></script>
</head>
<body>
<?php echo $the_content; ?>
</body>
</html>