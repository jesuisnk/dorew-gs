<?php $this->layout('layout'); ?>

<?php if ($isLogin && $ForumStats['count_online'] > 0) : ?>
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title">
            Online [<?= $ForumStats['count_online'] ?>]
            <a class="fr animated" href="/users">View All</a>
        </b>
        <div class="main-blg section">
            <?php foreach ($UserListOnline as $UserDetail) : ?>
                <a href="/user/<?= $UserDetail['nick'] ?>" title="<?= $UserDetail['name'] ?>">
                    <img src="<?= getAvtUser($UserDetail) ?>" loading="lazy" class="imgAvtUser" width="25px" />
                </a>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog" role="main">
    <?php if ($isLogin && display_layout() == 'mobile' && !$isForum) : ?>
        <!-- Shoubox -->
        <link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />
        <div class="main-blog cl" style="margin-bottom:20px">
            <b class="main-title"><i class="fa fa-weixin" aria-hidden="true"></i><span>Trò chuyện</span></b>
            <div id="chat-place">
                <form id="form" action method="POST" name="form">
                    <?php include('toolbar.php') ?>
                    <textarea id="postText" rows="3" class="form-control" id="msg"></textarea>
                    <button id="submit" type="submit" class="btn btn-success">Chat</button>
                </form>
            </div>
            <div class="overflow-chatbox">
                <div id="postText"></div>
                <div id="idChat"></div>
            </div>
            <center>
                <div class="pagination" id="phan-trang"></div>
            </center>
        </div>
        <script type="text/javascript">
            var totalChat = "<?= $ForumStats['count_chat'] ?>";
            var pageID = 1;
            var chatbox = "<?= url('/shoutbox/list') ?>",
                chat_send = "<?= url('/shoutbox/send') ?>",
                chat_count = "<?= url('/shoutbox/count') ?>",
                chat_ele = "<?= url('/shoutbox/ele?chatID=') ?>",
                chat_list = "<?= url('/shoutbox/list?page=') ?>";
        </script>
        <script type="text/javascript" src="<?= url('/js/shoutbox.js') ?>"></script>
    <?php endif ?>
    <!-- Post List -->
    <div class="main-blog cl">
        <b class="main-title">
            <?php if ($NewsBool) : ?>
                <i class="fa fa-newspaper-o" aria-hidden="true"></i><span>Tin tức</span>
            <?php else : ?>
                <?php if ($isLogin) : ?>
                    <i class="fa fa-gg" aria-hidden="true"></i>
                <?php else : ?>
                    <i class="fa fa-weixin" aria-hidden="true"></i>
                <?php endif ?>
                <?php if ($isInCategory) : ?>
                    <span><?= $CategoryName ?></span>
                <?php else : ?>
                    <span>Thảo luận gần đây</span>
                <?php endif ?>
            <?php endif ?>
        </b>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Diễn đàn</h2>
                <div class="widget-content dorew-postforum">
                    <!-- Bộ lọc bài đăng -->
                    <div class="r-col" id="filter">
                        <div class="col-lg-6">
                            <select class="form-control" id="order_by" tabindex="-98">
                                <option>Bộ lọc</option>
                                <?php
                                foreach ($PostListConfig['allow_order_by'] as $order_by) :
                                    $array = [
                                        'id' => 'Số thứ tự',
                                        'time' => 'Thời gian cập nhật',
                                        'update_time' => 'Cập nhật mới nhất',
                                        'view' => 'Lượt xem'
                                    ];
                                ?>
                                    <option value="<?= $order_by ?>">
                                        <?= $array[$order_by] ?>
                                        <?php if ($PostListConfig['order_by'] == $order_by) : ?>
                                            (Đang chọn)
                                        <?php endif ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" id="sort" tabindex="-98">
                                <option>Sắp xếp</option>
                                <?php
                                foreach ($PostListConfig['allow_sort'] as $sort) :
                                    $array = [
                                        'asc' => 'Tăng dần',
                                        'desc' => 'Giảm dần'
                                    ];
                                ?>
                                    <option value="<?= $sort ?>">
                                        <?= $array[$sort] ?>
                                        <?php if ($PostListConfig['sort'] == $sort) : ?>
                                            (Đang chọn)
                                        <?php endif ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($CanPostPublish) : ?>
                        <div style="margin-bottom:20px">
                            <a href="<?= url('/forum/' . $CategorySlug . '/post') ?>" title="Viết bài">
                                <button type="button" class="btn btn-primary btn-block">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> Tạo chủ đề mới
                                </button>
                            </a>
                        </div>
                    <?php endif ?>
                    <?php if ($PostCount > 0) : ?>
                        <!-- Danh sách mới nhất -->
                        <?php foreach ($PostList as $PostItem) : ?>
                            <div class="dorew-postforum-post ribbon">
                                <?php if (date('dmY', $PostItem['update_time']) != date('dmY', $PostItem['time']) && date('dmY', $PostItem['update_time']) == date('dmY')) : ?>
                                    <span class="ribbon_hot"></span>
                                <?php elseif (date('dmY', $PostItem['update_time']) == date('dmY', $PostItem['time']) && date('dmY', $PostItem['update_time']) == date('dmY')) : ?>
                                    <span class="ribbon_new"></span>
                                <?php endif ?>
                                <div class="dorew-postforum-content">
                                    <a href="<?= url('/forum/' . $PostItem['id'] . '-' . $PostItem['slug']) ?>.html" class="dorew-postforum-title">
                                        <?= ($PostItem['blocked'] == 1 ? '<b style="color:red"><i class="fa fa-lock" aria-hidden="true"></i></b> ' : '') ?>
                                        <?= ($PostItem['sticked'] == 1 ? '<i class="fa fa-thumb-tack" aria-hidden="true"></i> ' : '') ?>
                                        <?= $PostItem['title'] ?>
                                    </a>
                                    <p class="dorew-postforum-meta">
                                        <b><?= $PostItem['category_name'] ?></b>,
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> <?= ago($PostItem['time']) ?>,
                                        <i class="fa fa-user" aria-hidden="true"></i> Posted by
                                        <?= RoleColor($PostItem['UserDetail']) ?>
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
                    <?php else : ?>
                        <div class="alert alert-warning">Danh sách trống!</div>
                    <?php endif ?>
                </div>
            </div>
            <?= $PostListPaging ?>
        </div>
    </div>
</div>

<aside class="col-md-4 col-sm-4 col-xs-4 col-mb-4" id="sidebar-wrapper" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
    <div class="sidebar section" id="sidebar">
        <div class="widget HTML" data-version="1" id="HTML5">
            <h2 class="title">Chuyên mục</h2>
            <?php if ($ForumStats['count_category'] > 0) : ?>
                <div class="widget-content block">
                    <ul>
                        <?php foreach ($CategoryList as $CategoryItem) : ?>
                            <li>
                                <a href="<?= url('/index.html?category=' . $CategoryItem['id']) ?>">
                                    <i class="fa fa-cube" aria-hidden="true"></i>
                                    <?= $CategoryItem['name'] ?>
                                </a> (<?= $CategoryItem['count_post'] ?>)
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php else : ?>
                <div class="alert alert-warning">Danh sách trống!</div>
            <?php endif ?>
        </div>
        <div id="dorew-ads"></div>
        <?php if ($ForumStats['count_user'] > 0) : ?>
            <div class="widget" data-version="1" id="Stats">
                <h2>Thống kê</h2>
                <div class="widget-content block">
                    <ul>
                        <li>- Số chủ đề: <b><?= $ForumStats['count_post'] ?></b></li>
                        <li>- Số bài viết: <b><?= $ForumStats['count_comment'] ?></b></li>
                        <li>
                            - Thành viên: <b><?= $ForumStats['count_user'] ?></b>, mới nhất:
                            <a href="/user/<?= $ForumStats['latest_user']['nick'] ?>">
                                <?= $ForumStats['latest_user']['name'] ?>
                            </a>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
        <?php endif ?>
    </div>
</aside>