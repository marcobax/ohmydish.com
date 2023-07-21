<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-0 text-center with-stripe">Forgot password</h1>
            <?php if(!isset($hide)): ?>
                <?php if(!isset($reset_token)): ?>
                    <p class="text-center">Request a new password by entering in your e-mail address. You will receive a reset link via e-mail.</p>
                <?php else: ?>
                    <p class="text-center">Enter your new password and click on 'save password'.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-center">You have received an e-mail, please check the spam folder too.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!isset($hide)): ?>
        <div class="row mb-4">
            <div class="d-none d-md-block col-md-3"></div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo Core::url('forgot-password'); ?>" method="post">
                            <?php if(!isset($reset_token)): ?>
                                <div class="form-group">
                                    <label for="email">What is your e-mail address?</label>
                                    <input type="email" name="email" id="email" placeholder="my@email.com" class="form-control" required value="<?php echo (isset($post_values) && array_key_exists('email', $post_values))?$post_values['email']:''; ?>">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-lg">Request new password</button>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <input type="hidden" id="reset_token" name="reset_token" value="<?php echo $reset_token; ?>">
                                    <label for="email">New password</label>
                                    <input type="password" name="password" id="password" class="form-control" required value="<?php echo (isset($post_values) && array_key_exists('password', $post_values))?$post_values['password']:''; ?>">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-lg">Save password</button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <div class="d-none d-md-block col-md-3"></div>
            </div>
        </div>
    <?php endif; ?>
</div>