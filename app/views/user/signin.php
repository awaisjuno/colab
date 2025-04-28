<!-- SignIn Form -->
<div class="form-wrap">
    <div class="container">
        <div class="text-center">
            <h1>Sign In</h1>
        </div>

        <?= form_open() ?>

        <div class="form-group">
            <?= form_label('Email *') ?>
            <?= form_input('email', '', [
                'type' => 'email',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Enter your email'
            ]) ?>
        </div>

        <div class="form-group">
            <?= form_label('Password *') ?>
            <?= form_password('password', '', [
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Enter your password'
            ]) ?>
        </div>

        <div class="form-group text-center">
            <?= form_submit('submit', 'Sign In', ['class' => 'btn btn-custom btn-block']) ?>
        </div>

        <?= form_close() ?>
    </div>
</div>

</body>
</html>