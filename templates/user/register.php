<?php $this->layout('layout'); ?>

<div class="col-12 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog"
    role="main">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title"><i class="fa fa-user-plus" aria-hidden="true"></i><span>Tạo tài khoản</span></b>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Login</h2>
                <div class="widget-content">
                    <!-- Register Form -->
                    <?php if ($error): ?>
                        <div class="alert alert-warning mb-3"><?= $error ?></div>
                    <?php endif ?>
                    <form action="<?= url('/register') ?>" method="post" class="mb-2">
                        <p><input type="text" name="account" class="form-control" id="inputAccount"
                                placeholder="Tên tài khoản" value="<?= $inputAccount ?>" required/></p>
                        <p><input type="password" name="password" class="form-control" id="inputPassword"
                                placeholder="Mật khẩu" required/></p>
                        <p><input type="password" name="re_password" class="form-control" id="inputPassword"
                                placeholder="Nhập lại mật khẩu" required/></p>
                        <p>
                            <select name="sex" class="form-control" required>
                                <option>Giới tính</option>
                                <option value="boy"<?= ($inputSex == 'boy' ? ' selected' : '')?>>Nam</option>
                                <option value="girl"<?= ($inputSex == 'girl' ? ' selected' : '')?>>Nữ</option>
                            </select>
                        </p>
                        <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                        <table width="100%">
                            <tr>
                                <td style="width:100px"><img src="<?= captchaSrc() ?>" alt="Captcha" /></td>
                                <td><input type="text" name="captcha" class="form-control" id="inputcaptcha"
                                        placeholder="Mã bảo vệ" /></td>
                            </tr>
                        </table>
                        <button type="submit" class="btn btn-success btn-block">Đăng ký</button>
                    </form>
                    <p class="text-center"><a href="<?= url('/login') ?>">Bạn đã có tài khoản? Đăng nhập tại đây!</a>
                    </p>
                    <!-- End Register -->
                </div>
            </div>
        </div>
    </div>
</div>