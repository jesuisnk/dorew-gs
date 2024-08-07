<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-profile.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper">
    <div class="main-blog cl" style="margin-bottom:20px">
        <div class="block">
            <div class="profileCard">
                <div class="profileCover" style="background-image:url('<?= getCoverUser($YourDetail) ?>')">
                    <?php if ($MyDetail['id'] == $YourDetail['id']): ?>
                        <div class="changeCoverButton">
                            <a href="/user/<?= $YourDetail['nick'] ?>/cover">
                                <i class="fa fa-camera fa-lg"></i><span class="changeText">Đổi ảnh bìa</span></a>
                        </div>
                    <?php endif ?>
                </div>
                <div class="profileInfo">
                    <div class="profileName"><b><?= RoleColor($YourDetail) ?></b></div>
                    <div
                        style="font-weight:300;font-size:11.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        <?= $YourDetail['status'] ?>
                    </div>
                </div>
                <?php if ($MyDetail['id'] == $YourDetail['id']): ?>
                    <a href="/user/<?= $YourDetail['nick'] ?>/avatar">
                    <?php endif ?>
                    <div class="profileAvatar" style="background-image: url('<?= getAvtUser($YourDetail) ?>')"></div>
                    <?php if ($MyDetail['id'] == $YourDetail['id']): ?>
                    </a>
                <?php endif ?>
                <div class="profileMenu menu">
                    <ul class="nav nav-tabs">
                        <li <?php if ($action != 'forum'): ?> class="active" <?php endif ?>><a
                                href="/user/<?= $YourDetail['nick'] ?>">Thông tin</a></li>
                        <li <?php if ($action == 'forum'): ?> class="active" <?php endif ?>><a
                                href="/user/<?= $YourDetail['nick'] ?>?action=forum">Hoạt động</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if ($action == 'forum'): ?>
            <div class="title-action">Chủ đề gần đây</div>
            <?php if ($PostCount > 0): ?>
                <!-- Danh sách mới nhất -->
                <?php foreach ($PostList as $PostItem): ?>
                    <div class="dorew-postforum-post">
                        <div class="dorew-postforum-content">
                            <a href="<?= url('/forum/' . $PostItem['id'] . '-' . $PostItem['slug']) ?>.html"
                                class="dorew-postforum-title">
                                <?= $PostItem['title'] ?>
                            </a>
                            <p class="dorew-postforum-meta">
                                <b><?= $PostItem['category_name'] ?></b>,
                                <i class="fa fa-clock-o" aria-hidden="true"></i> <?= ago($PostItem['time']) ?>,
                            </p>
                        </div>
                        <div class="dorew-postforum-stats">
                            <div class="dorew-postforum-stat">
                                <span>
                                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                                    <?= $PostItem['comment'] ?>
                                </span>
                                Replies
                            </div>
                            <div class="dorew-postforum-stat">
                                <span>
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <?= $PostItem['view'] ?>
                                </span> Views
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="menu-action text-center">Danh sách trống!</div>
            <?php endif ?>
        <?php else: ?>
            <div class="title-action">Trang cá nhân</div>
            <div class="menu-action">
                <?php if ($MyDetail['id'] == $YourDetail['id']): ?>
                    <div style="padding-right:10px;float:right">
                        <a href="/user/<?= $YourDetail['nick'] ?>/info" title="Chỉnh sửa hồ sơ">
                            <button><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        </a>
                    </div>
                <?php else: ?>
                    <div style="padding-right:10px;float:right">
                        <a href="/mail/send/<?= $YourDetail['nick'] ?>" title="tin nhắn">
                            <button><i class="fa fa-telegram" aria-hidden="true"></i> gửi tín nhắn</button>
                        </a>
                    </div> 
                <?php endif ?>
                <div>
                    - <span class="gray">Giới tính:</span> <span style="color:red;font-weight:600">
                        <?= ['boy' => 'Nam', 'girl' => 'Nữ'][$YourDetail['sex']] ?>
                    </span>
                </div>
                <div>- <span class="gray">Level:</span> <span style="font-weight:600"><?= $YourDetail['level'] ?></span>
                </div>
                <div>
                    - Ngày đăng ký:
                    <span style="font-weight:600"><?= date('d.m.Y', $YourDetail['reg']) ?></span>
                </div>
                <div>
                    - Nhìn thấy lần cuối:
                    <span style="font-weight:600">
                        <?= $YourDetail['on'] >= (date('U') - 300) ? 'Đang hoạt động' : ago($YourDetail['on']) ?>
                    </span>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<?php $isProfilePage = true ?>
<?php include (dirname(dirname(__FILE__)) . '/sidebar.php') ?>