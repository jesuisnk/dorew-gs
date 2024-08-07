<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title">
            <?php if ($UserListConfig['order_by'] == 'level'): ?>
                <span><?= $page_title ?></span>
            <?php else: ?>
                <a href="<?= url('/users') ?>" style="font-size:1em;text-transform:uppercase">Thành viên</a> /
                <span><?= $page_title ?></span>
            <?php endif ?>
        </b>
        <div class="main-blog cl" style="margin-bottom:20px">
            <div class="r-col" id="filter">
                <div class="col-lg-6">
                    <select class="form-control" id="order_by" tabindex="-98">
                        <option>Bộ lọc</option>
                        <?php
                        foreach ($UserListConfig['allow_order_by'] as $order_by):
                            $array = [
                                'post' => 'Top diễn đàn',
                                'comment' => 'Top bình luận'
                            ];
                            ?>
                            <option value="<?= $order_by ?>">
                                <?= $array[$order_by] ?>
                                <?php if ($UserListConfig['order_by'] == $order_by): ?>
                                    (Đang chọn)
                                <?php endif ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <?php foreach ($UserList as $UserDetail): ?>
                <div class="postItem">
                    <div class="memInfo">
                        <table id="<?= $UserDetail['id'] ?>" cellpadding="0" cellspacing="1">
                            <tr>
                                <td width="auto"><img class="avt" src="<?= getAvtUser($UserDetail) ?>" /></td>
                                <td>
                                    <?= RoleColor($UserDetail) ?>
                                    <br /><?= $UserDetail['UserStatsInfo'] ?></b>
                                </td>
                                <div style="float:right">
                                    <?= ForumRank($UserDetail['UserStats']['count_post'] + $UserDetail['UserStats']['count_chapter']) ?>
                                </div>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endforeach ?>
            <?php if ($UserListConfig['order_by'] == 'level'): ?>
                <?= $UserListPaging ?>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include (dirname(dirname(__FILE__)) . '/sidebar.php') ?>