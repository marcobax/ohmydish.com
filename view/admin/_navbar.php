<nav class="navbar navbar-expand-md navbar-light py-0 border-bottom">
    <div class="justify-content-between align-items-center w-100">
        <ul class="navbar-nav mx-auto text-center">
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['index'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Admin
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin') ?>">Overview</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/search_index') ?>">Recent searches</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/saved_recipe_index') ?>">Recently saved recipes</a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['recipe_index','recipe_edit'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Recipes <span class="badge badge-primary"><?php echo $admin_recipe_count; ?></span> <?php if(isset($new_recipe_rating) && (int) $new_recipe_rating): ?><span class="text-success"> +<?php echo $new_recipe_rating; ?></span><?php endif; ?>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/recipe_index') ?>">Overview</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/recipe_index?status=publish') ?>">Published (<?php echo $admin_recipe_publish_count; ?>)</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/recipe_index?status=draft') ?>">Concepts (<?php echo $admin_recipe_draft_count; ?>)</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/recipe_edit/new') ?>">New</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/recipe_rating_index') ?>">Recipe votes <?php if(isset($new_recipe_rating) && (int) $new_recipe_rating): ?><span class="text-success"> +<?php echo $new_recipe_rating; ?></span><?php endif; ?></a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['blog_index','blog_edit'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Blogs <span class="badge badge-primary"><?php echo $admin_blog_count; ?></span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blog_index') ?>">Overview</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blog_index?status=publish') ?>">Published (<?php echo $admin_blog_publish_count; ?>)</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blog_index?status=draft') ?>">Concepts (<?php echo $admin_blog_draft_count; ?>)</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blog_edit/new') ?>">New</a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['page_index','page_edit'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Pages <span class="badge badge-primary"><?php echo $admin_page_count; ?></span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/page_index') ?>">Overview</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/page_index?status=publish') ?>">Published (<?php echo $admin_page_publish_count; ?>)</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/page_index?status=draft') ?>">Concepts (<?php echo $admin_page_draft_count; ?>)</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/page_edit/new') ?>">New</a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['question_index','question_edit'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Questions <span class="badge badge-primary"><?php echo $admin_question_count; ?></span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/question_index') ?>">Overview</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/question_index?status=publish') ?>">Published (<?php echo $admin_question_publish_count; ?>)</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/question_index?status=draft') ?>">Concepts (<?php echo $admin_question_draft_count; ?>)</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/question_edit/new') ?>">New</a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['user_index','collection_index'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Profiles <span class="badge badge-primary"><?php echo $admin_user_count; ?></span><?php if(isset($new_user_entities) && (int) $new_user_entities): ?><span class="text-success"> +<?php echo $new_user_entities; ?></span><?php endif; ?>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/user_index') ?>">Users<?php if(isset($new_users) && (int) $new_users): ?><span class="text-success"> +<?php echo $new_users; ?></span><?php endif; ?></a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/collection_index') ?>">Collections <span class="badge badge-primary"><?php echo $admin_collection_count; ?></span><?php if(isset($new_collections) && (int) $new_collections): ?><span class="text-success"> +<?php echo $new_collections; ?></span><?php endif; ?></a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['category_index','category_edit','tag_index','tag_edit'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Categories
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/category_index') ?>">Categories <span class="badge badge-primary"><?php echo $admin_category_count; ?></span></a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/category_edit/new') ?>">New</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/tag_index') ?>">Tags <span class="badge badge-primary"><?php echo $admin_tag_count; ?></span></a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/tag_edit/new') ?>">New</a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['comment_index','contact_index'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Feedback<?php if(isset($new_contact_entities) && (int) $new_contact_entities): ?><span class="text-success"> +<?php echo $new_contact_entities; ?></span><?php endif; ?>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/comment_index') ?>">Comments <span class="badge badge-primary"><?php echo $admin_comment_count; ?></span> <span class="badge badge-danger"><?php echo $admin_comment_spam_count; ?></span><?php if(isset($new_comment) && (int) $new_comment): ?><span class="text-success"> +<?php echo $new_comment; ?></span><?php endif; ?></a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/contact_index') ?>">Contact requests <span class="badge badge-primary"><?php echo $admin_contact_count; ?></span> <span class="badge badge-danger"><?php echo $admin_contact_spam_count; ?></span><?php if(isset($new_feedback) && (int) $new_feedback): ?><span class="text-success"> +<?php echo $new_feedback; ?></span> <?php endif; ?></a>
                </div>
            </li>
            <li class="h6 mb-0 nav-item">
                <a class="nav-link" href="<?php echo Core::url('admin/filemanager_index') ?>">Filemanager</a>
            </li>
            <li class="h6 mb-0 nav-item dropdown <?php echo $_request->isCurrentAction(['not_found_index'])?'active text-green':''; ?>">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    QoS
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo Core::url('admin/not_found_index') ?>">Pages not found</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blacklist_index') ?>">Blacklist</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/blacklist_edit/new') ?>">New blacklist addition</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/redirect_index') ?>">Redirects</a>
                    <a class="dropdown-item" href="<?php echo Core::url('admin/redirect_edit/new') ?>">New redirect</a>
                </div>
            </li>
        </ul>
    </div>
</nav>