<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center with-stripe">Login</h1>
            <p class="text-center">Do you want to save recipes to your own collections? Create a profile or log in if you already have one.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p class="text-center">
                <a href="<?php echo FacebookHelper::getLoginURL(); ?>" class="btn btn-primary btn-lg">Login with Facebook</a>
            </p>
            <p class="text-center">&dash; or &dash;</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="d-none d-md-block col-md-3"></div>
        <div class="col-12 col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <form action="<?php echo Core::url('login'); ?>" method="post">
                        <div class="form-group">
                            <label for="email">E-mail address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="me@email.com" required value="<?php echo (isset($post_values) && array_key_exists('email', $post_values))?$post_values['email']:''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <div class="input-group-append">
                                    <a href="<?php echo Core::url('forgot-password'); ?>" class="btn btn-outline-secondary" type="button">Forgot?</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="onthoud-mij" name="onthoud-mij">
                            <label class="form-check-label" for="onthoud-mij">Remember me</label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    No profile yet? <a href="<?php echo Core::url('create-profile'); ?>">Click here to create your own profile.</a>
                </div>
            </div>
        </div>
        <div class="d-none d-md-block col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <p class="text-center">Creating a profile is quick and easy by clicking on the "Login with Facebook" button. You do not need to remember a password and you can use all sorts of devices (mobile, tablet and computer) quickly and easy using your own Facebook profile!</p>
        </div>
    </div>
</div>
