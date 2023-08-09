<?php

/**
 * Class blogController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class blogController extends Controller
{
    var $blog_model = false;
    var $comment_model = false;

    /**
     * blogController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->blog_model = new BlogModel();
        $this->comment_model = new CommentModel();
    }

    /**
     * Overview of the latest blog posts.
     *
     * @param array $category
     */
    public function index($category = [])
    {
        $where = ['status' => 'publish'];
        if (is_array($category) && count($category)) {
            if ('blog' === $category['slug']) {
                Core::redirect(Core::url('blog'));
            }
            $where['categories'] = $category['id'];
        }

        $this->setTotalResults($this->blog_model->getRecords($where,[],[],true));

        $this->set([
            'page_title'       => 'Blog - Recent blog posts',
            'meta_description' => 'Overview of all blog posts',
            'page_canonical'   => Core::url('blog'),
            'blogs'            => $this->blog_model->getRecords($where, ['published', 'desc'], $this->getPagination()),
            'pagination'       => $this->getPagination(),
            'category'         => $category
        ]);

        $this->render('index');
    }

    /**
     * Comments on a blog
     *
     * @param string $slug
     */
    public function comments(string $slug)
    {
        $this->category_model = new CategoryModel();

        if ($category = $this->category_model->getBySlug($slug, ['type' => 'blog'])) {
            return $this->index($category);
        } elseif (!$blog = $this->blog_model->getBySlug($slug)) {
            if (!SessionHelper::hasPermission('is_admin') && ('publish' !== $blog['status'])) {
                $this->show404();
            }
        }

        $post_values = $_POST;

        if (is_array($post_values) && count($post_values)) {
            if (
                (array_key_exists('author', $post_values) && strlen($post_values['author'])) &&
                (array_key_exists('email', $post_values) && strlen($post_values['email'])) &&
                (array_key_exists('content', $post_values) && strlen($post_values['content']))
            ) {
                $ip_address = Core::get_client_ip();

                // Spam check.
                $akismet_helper = new AkismetHelper();
                $spam = $akismet_helper->comment_check([
                    'user_ip'              => $ip_address?$ip_address:'unknown',
                    'comment_type'         => 'comment',
                    'comment_author'       => trim($post_values['author']),
                    'comment_author_email' => trim($post_values['email']),
                    'comment_content'      => trim($post_values['content']),
                    'permalink'            => Core::url('blog/comments/' . $blog['slug'])
                ]);

                $admin_stats_model = new AdminStatsModel();
                // Create comment.
                $data = [
                    'created' => date('Y-m-d H:i:s'),
                    'entity_id' => $blog['id'],
                    'page_type' => 'blog',
                    'author' => trim($post_values['author']),
                    'content' => trim($post_values['content']),
                    'email' => trim($post_values['email']),
                    'status' => 'pending',
                    'wordpress_post_id' => 0,
                    'spam' => (true===$spam)?1:0
                ];

                $comment_id = $this->comment_model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thank you for submitting a comment, we will try to answer it as soon as possible.'];

                if (false === $spam) {
                    $admin_stats_model->increment('comment', 'new');
                } else {
                    $admin_stats_model->increment('spam', 'blocked');
                }

                // Update comment count.
                $this->blog_model->updateCommentCount($blog);

                Core::redirect(Core::url('blog/comments/' . $blog['slug']) . '/#comment-' . $comment_id);
            }
        }

        $comment_records = $this->comment_model->getRecords([
            'page_type' => 'blog',
            'entity_id' => $blog['id'],
            'status'    => 'approved'
        ],['created', 'asc']);

        $this->blog_model->incrementViews($blog);

        $comments = CoreHelper::buildTree($comment_records);

        $this->set([
            'meta_description' => 'View comments on the blog ' . $blog['title'] . ' or leave behind your own comment.',
            'page_title'       => 'Comments on ' . $blog['title'],
            'og_image'         => TemplateHelper::getFeaturedImage($blog),
            'page_canonical'   => Core::url('blog/comments/' . $blog['slug']),
            'blog'             => $blog,
            'comments'         => $comments
        ]);

        $this->render('comments');
    }

    /**
     * Blog post detail.
     *
     */
    public function detail()
    {
        $this->category_model = new CategoryModel();

        if ($category = $this->category_model->getBySlug($this->getSlug(), ['type' => 'blog'])) {
            return $this->index($category);
        } elseif (!$blog = $this->blog_model->getBySlug($this->getSlug())) {
            if (!SessionHelper::hasPermission('is_admin') && ('publish' !== $blog['status'])) {
                $this->show404();
            }
        }

        if ($this->getSlug() !== $blog['slug']) {
            Core::redirect(Core::url('blog/' . $blog['slug']));
        }

        $this->blog_model->incrementViews($blog);

        $this->set([
            'meta_description'            => (!$blog['excerpt']) ? $blog['title'] . ' blog.' : $blog['excerpt'],
            'meta_article_published_time' => substr($blog['published'],0,10),
            'meta_article_section'        => 'Blog',
            'page_title'                  => $blog['title'] . ' - blog',
            'og_type'                     => 'article',
            'og_image'                    => TemplateHelper::getFeaturedImage($blog),
            'page_canonical'              => Core::url('blog/' . $blog['slug']),
            'blog'                        => $blog,
            'previous_blog'               => $this->blog_model->getPreviousBlog($blog),
            'next_blog'                   => $this->blog_model->getNextBlog($blog)
        ]);

        $this->render('detail');
    }
}