<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center with-stripe">Create a profile</h1>
            <p class="text-center">Create a profile using your Facebook account or e-mail address and access the Ohmydish community!</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p class="text-center">
                <a href="<?php echo FacebookHelper::getLoginURL(); ?>" class="btn btn-primary btn-lg">Create profile using my Facebook account</a>
            </p>
            <p class="text-center">&dash; or &dash;</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="d-none d-md-block col-md-3"></div>
        <div class="col-12 col-md-6">
            <p class="text-center">No Facebook? No problem! Write down your information and create a profile..</p>
            <div class="card shadow">
                <div class="card-body">
                    <form action="<?php echo Core::url('create-profile'); ?>" method="post">
                        <div class="form-group">
                            <label for="email">E-mail address *</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="my@email.com" required value="<?php echo (isset($post_values) && array_key_exists('email', $post_values))?$post_values['email']:''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_again">Repeat password *</label>
                            <input type="password" id="password_again" name="password_again" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="first_name">Your first name *</label>
                                    <input class="form-control" type="text" name="first_name" id="first_name" placeholder="What is your first name?" required value="<?php echo (isset($post_values) && array_key_exists('first_name', $post_values))?$post_values['first_name']:''; ?>">
                                </div>
                                <div class="col-6">
                                    <label for="last_name">Your last name</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name" placeholder="What is your last name?" value="<?php echo (isset($post_values) && array_key_exists('last_name', $post_values))?$post_values['last_name']:''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" <?php echo ((isset($post_values) && array_key_exists('newsletter', $post_values) || !isset($post_values)))?'checked':''; ?>>
                            <label class="form-check-label" for="newsletter">Sign me up for the newsletter</label>
                        </div>
                        <hr>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">Create profile</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <span class="text-muted">Fields that have an asterix (*) are required.</span>
                </div>
            </div>
        </div>
        <div class="d-none d-md-block col-md-3"></div>
    </div>
</div>