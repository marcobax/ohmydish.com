<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="with-stripe text-center">About Ohmydish</h1>
            <p><strong>Who is behind the website?</strong> We are Marco and Véronique, both 35 years old. We are from The Netherlands, but emigrated to the south of France in December 2017, settling in the department of the Gers (32).
            <p>Here we have a substantial kitchen garden and a large orchard of fruit and nut trees. Seasonal ingredients are fundamental to our cooking. Good food is very important to us, which is one of the reasons why France is such a perfect fit for us both. You will begin to see an increasing number of recipes from this region appearing on the website, but rest assured that  we will continue to publish recipes from all over the globe.</p>
            <hr>
            <div class="row">
                <div class="col-6 col-md-2">
                    <img src="<?php echo Core::upload_path('uploads/2021/Veronique.jpg'); ?>" alt="Veronique from Ohmydish" class="img-fluid img-thumbnail" />
                </div>
                <div class="col-6 col-md-4">
                    <p><span class="text-success">Véronique</span>: I’m a qualified, professional chef. After many enjoyable years of working as a chef, I unfortunately had to leave the industry due to shoulder surgery. However, I can share all my cooking knowledge and passion with you on Ohmydish!</p>
                    <p><a href="https://www.instagram.com/ohmydish/">Follow Véronique on Instagram</a></p>
                </div>
                <div class="col-6 col-md-2">
                    <img src="<?php echo Core::upload_path('uploads/2021/Marco.jpg'); ?>" alt="Marco from Ohmydish" class="img-fluid img-thumbnail" />
                </div>
                <div class="col-6 col-md-4">
                    <p><span class="text-success">Marco</span>: I'm a full time web developer, and make sure everything keeps working on the site. A part-time Photoshop fanatic, I also provide a dedicated leftovers disposal service. I eat everything except avocados and the devil's herb coriander / cilantro.</p>
                </div>
            </div>
            <h3 class="with-stripe">Ohmydish crew</h3>
            <div class="row">
                <div class="col-6 col-md-2">
                    <img src="<?php echo Core::upload_path('uploads/2022/James.jpg'); ?>" alt="James from Ohmydish" class="img-fluid img-thumbnail" />
                </div>
                <div class="col-6 col-md-4">
                    <p><span class="text-success">James</span>: Head of translation since 2022, he makes sure our recipes are translated into English world wide! Vegetarian, owns the biggest dog in the world (Pyrenean Mountain Dog), and lives and breathes food and culture. Loves Indian food, hates aubergine.</p>
                    <p><a href="https://www.instagram.com/james_e_waine/">Follow James on Instagram</a></p>
                </div>
            </div>
            <h2 class="text-center with-stripe">In numbers</h2>
            <ul>
                <li>Created: <mark>2014</mark></li>
                <li>Average amount of visitors over the past 3 months: <mark>~<?php echo number_format($_monthly_users, 0, ',', '.'); ?></mark></li>
                <li>Total newsletter subscriptions: <mark><?php echo number_format($_newsletter_subscriptions, 0, ',', '.'); ?></mark></li>
                <li>Total recipes: <mark><?php echo number_format($recipe_count, 0, ',', '.'); ?></mark></li>
                <li>Total blog posts: <mark><?php echo number_format($blog_count, 0, ',', '.'); ?></mark></li>
                <li>Total pages: <mark><?php echo number_format($page_count, 0, ',', '.'); ?></mark></li>
                <li>Total votes: <mark><?php echo number_format($rating_count, 0, ',', '.'); ?></mark></li>
                <?php if($question_count): ?>
                <li>Total answers to cooking questions: <mark><?php echo number_format($question_count, 0, ',', '.'); ?></mark></li>
                <?php endif; ?>
                <li>Total categories: <mark><?php echo number_format($category_count, 0, ',', '.'); ?></mark></li>
                <li>Total tags: <mark><?php echo number_format($tag_count, 0, ',', '.'); ?></mark></li>
                <li>Total profiles: <mark><?php echo number_format($user_count, 0, ',', '.'); ?></mark></li>
                <li>Total collections: <mark><?php echo number_format($collection_count, 0, ',', '.'); ?></mark></li>
                <li>Total saved recipes: <mark><?php echo number_format($saved_recipe_count, 0, ',', '.'); ?></mark></li>
            </ul>
            <h3 class="text-center with-stripe">Any questions or comments?</h3>
            <p>You can always reach us by using our <a href="<?php echo Core::url('contact-us'); ?>">contact form</a>. We usually respond within 48 hours. All comments, tips and questions are welcome!</p>
        </div>
    </div>
</div>
