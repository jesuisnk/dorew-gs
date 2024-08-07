<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />

<div class="main-blog cl" style="margin-bottom:20px">
    <b class="main-title"><i class="fa fa-bell-o" aria-hidden="true"></i><span>Thông báo</span></b>
    <div class="main-blg section" id="main-blg">
        <?php if ($MailSystemCount > 0): ?>
            <div class="cl" style="font-size:14px;margin-bottom:20px">
                <a href="?mod=view" class="btn btn-warning btn-sm" style="color:#000">
                    Đánh dấu là đã đọc
                </a>
                <a href="?mod=clear" class="btn btn-danger btn-sm" style="color:#fff">
                    Xóa danh sách
                </a>
            </div>
            <?php foreach ($MailSystemList as $MailSystemDetail): ?>
                <div class="postItem ribbon">
                    <?php if ($MailSystemDetail['view'] != 'yes'): ?>
                        <span class="ribbon_new"></span>
                    <?php endif ?>
                    <div class="memInfo">
                        <span style="color:gray">[<?=ago($MailSystemDetail['time'])?>]</span> 
                        <?= $MailSystemDetail['content'] ?>
                    </div>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-warning mb-3">
                Danh sách trống!
            </div>
        <?php endif ?>
    </div>
</div>