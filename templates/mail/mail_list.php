<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title"><i class="fa fa-envelope-o" aria-hidden="true"></i><span>Hộp thư đến</span></b>
        <?php if ($MailCount > 0) : ?>
            <div class="main-blg section" id="main-blg">
                <?php foreach ($MailList as $MailDetail) : ?>
                    <div class="postItem ribbon">
                        <?php if ($MailDetail['LatestMailDetail']['view'] != 'yes' && $MailDetail['LatestMailDetail']['nick'] != $MyDetail['nick']) : ?>
                            <span class="ribbon_new"></span>
                        <?php endif ?>
                        <div class="memInfo">
                            <table id="receiver-<?= $MailDetail['ReceiverDetail']['nick'] ?>" cellpadding="0" cellspacing="1">
                                <tr>
                                    <td width="auto"><img class="avt" src="<?= getAvtUser($MailDetail['ReceiverDetail']) ?>" />
                                    </td>
                                    <td>
                                        <?= RoleColor($MailDetail['ReceiverDetail']) ?>
                                        <br /><time>lúc <?= ago($MailDetail['LatestMailDetail']['time']) ?></time>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="chatbox">
                            <span class="textchat"> </span>
                            <?= $MailDetail['LatestMailDetail']['content'] ?>
                        </div>
                        <div class="sub"></div>
                        <div style="margin-left:10px">
                            <a href="/mail/send/<?= $MailDetail['ReceiverDetail']['nick'] ?>" style="font-weight:700">Hội
                                thoại</a>
                            (<?= $MailDetail['MailCount'] ?>) / Người gửi cuối cùng:
                            <?php if ($MyDetail['id'] != $MailDetail['SenderDetail']['id']) : ?>
                                <?= RoleColor($MailDetail['SenderDetail']) ?>
                            <?php else : ?><b>tôi</b><?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <?= $MailListPaging ?>
            </div>
        <?php else : ?>
            <div class="alert alert-warning">Danh sách trống!</div>
        <?php endif ?>
    </div>
</div>

<?php include(dirname(dirname(__FILE__)) . '/sidebar.php') ?>