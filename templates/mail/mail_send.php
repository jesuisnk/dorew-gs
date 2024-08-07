<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />

<div class="main-blog cl" style="margin-bottom:20px">
    <b class="main-title"><i class="fa fa-envelope-o" aria-hidden="true"></i><span>Hộp thư đến</span></b>
    <div class="main-blg section" id="main-blg">
        <!--Thông tin người nhận-->
        <div style="font-size:14px;margin-bottom:20px">
            <span style="float:left">
                <button type="button" class="btn btn-sm btn-success">
                    <?= RoleColor($ReceiverDetail) ?>
                    <?php if ($ReceiverDetail['on'] < (date('U') - 300)): ?>
                        <span style="color:orange">(off)</span>
                    <?php endif ?>
                </button>
            </span>
            <div style="text-align:right">
                <a href="?mod=blocklist" class="btn btn-danger btn-sm" style="color:#fff">
                    <?php if (in_array($ReceiverDetail['nick'], $SenderDetailBlockList)): ?>
                        <i class="fa fa-unlock-alt" aria-hidden="true"></i> Mở chặn
                    <?php else: ?>
                        <i class="fa fa-ban" aria-hidden="true"></i> Chặn
                    <?php endif ?>
                </a>
            </div>
        </div>
        <?php if ($isSenderBlock): ?>
            <div class="alert alert-warning mb-3">
                Bạn đang chặn tin nhắn từ người dùng này. Hãy mở chặn để tiếp tục cuộc trò chuyện!
            </div>
        <?php elseif ($isReceiverBlock): ?>
            <div class="alert alert-warning mb-3">
            Bạn không thể nhắn tin cho người dùng này ngay lúc này!
            </div>
        <?php else: ?>
            <!--Form tin nhắn-->
            <form id="form" action method="POST" name="form">
                <?php include (dirname(dirname(__FILE__)) . '/toolbar.php') ?>
                <textarea id="postText" rows="3" class="form-control" name="content"
                    style="margin-bottom:5px"><?= $content ?></textarea>
                <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                <button id="submit" type="submit">Gửi đi</button>
            </form>
        <?php endif ?>
        <!--Danh sách tin nhắn-->
        <?php foreach ($MailList as $MailDetail): ?>
            <div class="postItem ribbon">
                <?php if ($MailDetail['view'] != 'yes'): ?>
                    <span class="ribbon_new"></span>
                    <?= $view($MailDetail['id']) ?>
                <?php endif ?>
                <div class="memInfo">
                    <table id="<?= $MailDetail['id'] ?>" cellpadding="0" cellspacing="1">
                        <tr>
                            <td width="auto"><img class="avt" src="<?= getAvtUser($MailDetail['UserDetail']) ?>" /></td>
                            <td>
                                <?= RoleColor($MailDetail['UserDetail']) ?>
                                <br /><time>lúc <?= ago($MailDetail['time']) ?></time>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="chatbox">
                    <span class="textchat"> </span>
                    <?= $MailDetail['content'] ?>
                </div>
            </div>
        <?php endforeach ?>
        <?= $MailPaging ?>
    </div>
</div>