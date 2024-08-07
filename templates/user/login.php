<?php $this->layout('layout'); ?>

<div class="col-12 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog"
    role="main">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title"><i class="fa fa-sign-in" aria-hidden="true"></i><span>Đăng nhập</span></b>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Login</h2>
                <div class="widget-content">
                    <!-- Login Form -->
                    <?php if ($error): ?>
                        <div class="alert alert-warning mb-3"><?= $error ?></div>
                    <?php endif ?>
                    <form action="<?= url('/login') ?>" method="post" class="mb-2">
                        <p><input type="text" name="account" pattern="^[a-zA-Z0-9]{3,15}$" class="form-control" id="inputAccount"
                                placeholder="Tên tài khoản" value="<?= $inputAccount ?>" /></p>
                        <p><input type="password" name="password" pattern="^.{3,32}$" class="form-control" id="inputPassword"
                                placeholder="Mật khẩu" /></p>
                        <div class="form-login-remember">
                            <input type="checkbox" name="remember" value="1" <?php echo ($inputRemember ? ' checked="checked"' : ''); ?> /> Ghi nhớ
                        </div>
                        <input type="hidden" name="csrf_token" value="<?=getCSRFToken()?>">
                        <script src="<?= $this->asset('/js/doomcaptcha.js') ?>" countdown="on" label="Captcha" enemies="3" type="text/javascript"></script>
                        <input id="dai-check" style="display:none" type="checkbox" name="doomcaptcha" value="1" <?php echo ($inputCaptcha ? ' checked="checked"' : ''); ?> />
                        <button type="submit" class="btn btn-success btn-block" id="dai">Đăng nhập</button>
                    </form>
                    <p class="text-center"><a href="<?= url('/register') ?>">Chưa có tài khoản? Đăng ký</a></p>
                    <!-- End Login -->
                </div>
            </div>
        </div>
    </div>
</div>