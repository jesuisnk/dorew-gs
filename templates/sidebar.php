<aside class="col-md-4 col-sm-4 col-xs-4 col-mb-4" id="sidebar-wrapper" itemscope="itemscope" itemtype="http://schema.org/WPSideBar" dorew-id="profile">
    <div class="sidebar section" id="sidebar">
        <?php if ($isLogin) : ?>
            <?php if (!$isProfilePage) : ?>
                <!--Ảnh đại diện-->
                <div class="block" style="margin-bottom:10px">
                    <div style="background:#E8FFFE;border:solid #e9e9e9;border-width:0 1px 1px;padding:5px;word-wrap:break-word;text-align: center;">
                        <img class="imgAvtUser" src="<?= getAvtUser($user) ?>" width="120" height="120" />
                        <br />
                        <?= RoleColor($user) ?>
                        <br />
                        <a href="<?= url('/user/' . $user['nick'] . '/info') ?>" title="Chỉnh sửa hồ sơ">
                            <button><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        </a>
                        <?php if ($user['level'] >= 126) : ?>
                            <a href="<?= url('/manager') ?>" title="Bảng quản trị">
                                <button><i class="fa fa-tachometer" aria-hidden="true"></i></button>
                            </a>
                        <?php endif ?>
                        <a href="<?= url('/logout') ?>" title="Đăng xuất">
                            <button><i class="fa fa-sign-out" aria-hidden="true"></i></button>
                        </a>
                    </div>
                    <ul>
                        <li>» ID: <?= $user['id'] ?></li>
                        <li>» <a href="<?= url('/user') ?>">Trang cá nhân</a></li>
                        <li>» <a href="<?= url('/users') ?>">Thành viên</a></li>
                    </ul>
                </div>
            <?php else : ?>
                <!--Menu-->
                <div class="widget HTML" data-version="1" id="HTML5">
                    <h2 class="title">Menu</h2>
                    <div class="widget-content block">
                        <ul>
                            <li>» <a href="<?= url('/user') ?>">Trang cá nhân</a></li>
                            <li>» <a href="<?= url('/users') ?>">Thành viên</a></li>
                            <li>» <a href="<?= url('/logout') ?>">Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif ?>
        <?php else : ?>
            <div id="dorew-ads"></div>
        <?php endif ?>
        <!---<div id="dorew-ads"></div>--->
    </div>
</aside>