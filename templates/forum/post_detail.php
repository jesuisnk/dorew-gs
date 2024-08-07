<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-post.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope"
    itemtype="http://schema.org/Blog" role="main">
    <div class="main-blog cl">
        <div class="main section" id="main">
            <div class="widget Blog" data-version="1" id="Blog1">
                <?php if ($AdminActionResult): ?>
                    <div class="alert alert-success"><?= $AdminActionResult ?></div>
                <?php endif ?>
                <!-- Breadcrumbs -->
                <div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?= url('/') ?>" itemprop="item">
                            <span itemprop="name">Diễn đàn</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </span>
                    &#187;
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?= url('/index.html?category=' . $CategoryDetail['id']) ?>" itemprop="item">
                            <span itemprop="name"><?= $CategoryDetail['name'] ?></span>
                        </a>
                        <meta itemprop="position" content="2" />
                    </span>
                    &#187;
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span itemprop="name"><?= $PostDetail['title'] ?></span>
                        <meta itemprop="position" content="3" />
                    </span>
                </div>
                <!-- Post Details -->
                <div class="blog-posts row hfeed">
                    <div class="post-inner col-md-12 col-sm-12 col-xs-12 col-mb-12">
                        <article class="post hentry post-item cl" itemprop="blogPost" itemscope="itemscope"
                            itemtype="http://schema.org/BlogPosting">
                            <meta content="<?= $PostDetail['thumbnail'] ?>" itemprop="image" />
                            <a name="<?= $PostDetail['title'] ?>"></a>
                            <h1 class="post-title entry-title" itemprop="name headline">
                                <?= ($PostDetail['blocked'] == 1 ? '<b style="color:red"><i class="fa fa-lock" aria-hidden="true"></i></b> ' : '')?>
                                <?= ($PostDetail['sticked'] == 1 ? '<i class="fa fa-thumb-tack" aria-hidden="true"></i> ' : '')?>
                                <?= $PostDetail['title'] ?>
                            </h1>
                            <header class="post-header">
                                <span class="post-auth"><i class="fa fa-user"></i> By <?= RoleColor($UserDetail) ?>,
                                </span>
                                <span class="post-date"><i class="fa fa-calendar"></i> Released at <span
                                        class="date-block"><abbr class="updated published" itemprop="datePublished">
                                            <?= ago($PostDetail['time']) ?>
                                        </abbr></span></span>
                                <span class="post-view"><i class="fa fa-eye"></i>
                                    <span class="viewers">
                                        <i class="wpviewload wpview" id="postviews"><?= $PostDetail['view'] ?></i>
                                        View</span>
                                </span>
                            </header>
                            <?php if ($isPersonCanAction): ?>
                                <div class="text-right">
                                    <b><i class="fa fa-wrench" aria-hidden="true"></i> Công cụ:</b> &emsp;&emsp;
                                    <a href="/forum/post-<?= $PostDetail['id'] ?>/edit">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa
                                    </a>
                                    / <a href="/forum/post-<?= $PostDetail['id'] ?>/add-chap">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Thêm chương
                                    </a>
                                    <?php if ($isAdminForum): ?>
                                        / <a href="?mod=<?= $ActionLock ?>"><?= $ActionLockName ?></a>
                                        / <a href="?mod=<?= $ActionPin ?>"><?= $ActionPinName ?></a>
                                        / <a href="/forum/post-<?= $PostDetail['id'] ?>/delete">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i> Xóa bài viết
                                        </a>
                                    <?php endif ?>
                                </div>
                            <?php endif ?>
                            <div class="shr-btn shr-hd cl">
                                <a class="shr-fb" href="http://www.facebook.com/sharer.php?u=<?= $PostDetail['url'] ?>"
                                    target="_blank" title="Share to Facebook">
                                    <i class="fa fa-facebook"></i> Share on Facebook
                                </a>
                                <a class="shr-tw" href="http://twitter.com/share?url=<?= $PostDetail['url'] ?>"
                                    target="_blank" title="Share to Twitter">
                                    <i class="fa fa-twitter"></i> Share on Tweet
                                </a>
                                <a class="shr-gp" href="https://plus.google.com/share?url=<?= $PostDetail['url'] ?>"
                                    target="_blank" title="Share to Google+">
                                    <i class="fa fa-google-plus"></i> Share on Google+
                                </a>
                            </div>
                            <div class="animbox-synopsis">
                                <b id="top">#TOP</b>
                            </div>
                            <?= $PostDetail['content'] ?>
                            <!-- Danh sách like -->
                            <?= $PostLikeListDisplay ?>
                            <?php if ($isLogin && !$isInLikeList): ?>
                                <div class="text-right">
                                    <a href="?mod=like" class="btn btn-default">Like</a>
                                </div>
                            <?php endif ?>
                            <?php if ($ForumStats['count_chapter_in_post'] > 0): ?>
                                <!-- Chapter List -->
                                <div class="animbox-download">
                                    <ul class="animbox-dl cl">
                                        <li> <span>Danh sách chương (<?= $ForumStats['count_chapter_in_post'] ?>)</span>
                                            <span>Link</span>
                                        </li>
                                        <?php foreach ($ChapterList as $ChapterDetail): ?>
                                            <li>
                                                <span><?= $ChapterDetail['title'] ?></span>
                                                <a
                                                    href="<?= url('/view-chap/' . $ChapterDetail['id'] . '-' . $ChapterDetail['slug']) ?>">
                                                    Đọc ngay</a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif ?>
                        </article>
                    </div>
                    <div class="clear"></div>
                    <!-- Comment List -->
                    <div class="comments comment-section" id="comments">
                        <h4 class="comments-title main-title"><?= $ForumStats['count_comment_in_post'] ?> comments:</h4>
                        <!-- Form comments -->
                        <?php if ($isLogin): ?>
                            <?php if ($isPersonCanComment): ?>
                                <?php if ($errorComment): ?>
                                    <div style="margin-top:5px;margin-bottom:5px">
                                        <div class="alert alert-warning mb-3"><?= $errorComment ?></div>
                                    </div>
                                <?php endif ?>
                                <form id="form" method="POST">
                                    <div class="comment-box">
                                        <div class="user-info">
                                            <img src="<?= getAvtUser($MyDetail) ?>" alt="<?= $MyDetail['nick'] ?>"
                                                class="avatar">
                                            <span>Nhận xét với tên: <strong><?= RoleColor($MyDetail) ?></strong></span>
                                        </div>
                                        <div class="cl"><?php include (dirname(dirname(__FILE__)) . '/toolbar.php') ?></div>
                                        <textarea id="postText" rows="3" class="form-control" name="content"
                                            style="margin-bottom:5px" placeholder="Nhập nhận xét"></textarea>
                                        <div class="actions">
                                            <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                                            <button class="publish-btn" style="padding:8px">Xuất bản</button>
                                        </div>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning mb-3"><?= $ReasonCanNotComment ?></div>
                            <?php endif ?>
                        <?php endif ?>
                        <!-- List -->
                        <ol id="comment-list">
                            <?php if ($ForumStats['count_comment_in_post'] > 0): ?>
                                <?= $CommentListPaging ?>
                                <?php foreach ($CommentList as $CommentDetail): ?>
                                    <li class="comment-child blog-admin">
                                        <article class="comment-post comment-anony">
                                            <header class="comment-header cl">
                                                <div class="comment-author vcard">
                                                    <img alt="avatar" class="comment-thumb"
                                                        src="<?= getAvtUser($CommentDetail['UserDetail']) ?>" />
                                                    <b class="fn"><?= RoleColor($CommentDetail['UserDetail']) ?></b>
                                                    <span class="says">says:</span>
                                                </div>
                                                <div class="comment-metadata">
                                                    <time>lúc <?= ago($CommentDetail['time']) ?></time>
                                                </div>
                                            </header>
                                            <div class="comment-content">
                                                <p><?= $CommentDetail['comment'] ?></p>
                                            </div>
                                        </article>
                                    </li>
                                <?php endforeach ?>
                                <?= $CommentListPaging ?>
                            <?php else: ?>
                                <li>Chưa có bình luận nào, hãy là người đầu tiên!</li>
                            <?php endif ?>
                        </ol>
                    </div>

                    <!-- End Comment -->
                </div>
            </div>
        </div>

    </div>
</div>

<aside class="col-md-4 col-sm-4 col-xs-4 col-mb-4" id="sidebar-wrapper" itemscope="itemscope"
    itemtype="http://schema.org/WPSideBar">
    <div class="sidebar section" id="sidebar">
        <div class="widget HTML" data-version="1" id="HTML5">
            <h2 class="title">Chia sẻ bài viết</h2>
            <div class="widget-content block">
                <ul>
                    <li>
                        BBcode: <input class="form-control" type="text"
                            value="[url=<?= $PostDetail['url'] ?>]<?= $PostDetail['title'] ?>[/url]" />
                    </li>
                    <li>
                        Markdown: <input class="form-control" type="text"
                            value="[<?= $PostDetail['title'] ?>](<?= $PostDetail['url'] ?>)" />
                    </li>
                </ul>
            </div>
        </div>
        <div id="dorew-ads"></div>
        <?php if ($ForumStats['count_post_in_category'] > 4): ?>
            <div class="widget" data-version="1" id="Stats">
                <h2>Chủ đề tương tự</h2>
                <div class="widget-content block">
                    <ul>
                        <?php foreach ($PostListSimilar as $PostDetail): ?>
                            <li>
                                <a href="<?= url('/forum/' . $PostDetail['id'] . '-' . $PostDetail['slug']) . '.html' ?>">
                                    <?= $PostDetail['title'] ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
        <?php endif ?>
    </div>
</aside>