<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy text-center with-stripe">Contact us</h1>
            <p>Do you have a food related question, feedback or comment? Contact us by filling out the form on this page. We usually respond within 48 hours. Its also possible to simply send us an e-mail to:
                <a href="mailto:veronique@ohmydish.com">veronique@ohmydish.com</a></p>
            <p class="text-danger text-center"><strong>Please note:</strong> at this moment in time we do not accept requests for link building or guest posts for free.</p>
            <p>Publishing sponsored articles, links or a request for writing an food related article are on a payed basis. Contact us to find out what our rates are.</p>
        </div>
    </div>
    <form action="<?php echo Core::url('contact-us') ?>" method="post">
    <div class="card mb-4">
        <div class="card-header">Send your message to Ohmydish</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="naam">What is your name? *</label>
                        <input type="text" class="form-control" name="naam" id="naam" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="email">What is your e-mail address? *</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="my@email.com" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="aanvraag">What is your question? *</label>
                        <textarea name="aanvraag" id="aanvraag" cols="30" rows="10" class="form-control" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 col-md-6">
                    <span class="text-muted">* = required field.</span>
                </div>
                <div class="col-12 col-md-6 text-right">
                    <div class="form-group">
                        <input type="submit" class="btn btn-lg btn-success" value="Send this message">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <div class="row">
        <div class="col-12 text-center">
            <a href="<?php echo Core::url('privacystatement'); ?>">Our privacy statement</a></p>
        </div>
    </div>
</div>