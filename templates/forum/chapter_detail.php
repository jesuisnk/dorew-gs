<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-post.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope"
    itemtype="http://schema.org/Blog" role="main">
    <div class="main-blog cl">
        <div class="main section" id="main">
            <div class="widget Blog" data-version="1" id="Blog1">
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
                        <a href="<?= url('/forum/' . $PostDetail['id'] . '-' . $PostDetail['slug'] . '.html') ?>" itemprop="item">
                            <span itemprop="name"><?= $PostDetail['title'] ?></span>
                        </a>
                        <meta itemprop="position" content="3" />
                    </span>
                    &#187;
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span itemprop="name"><?= $ChapterDetail['title'] ?></span>
                        <meta itemprop="position" content="4" />
                    </span>
                </div>
                <!-- Post Details -->
                <div class="blog-posts row hfeed">
                    <div class="post-inner col-md-12 col-sm-12 col-xs-12 col-mb-12">
                        <article class="post hentry post-item cl" itemprop="blogPost" itemscope="itemscope"
                            itemtype="http://schema.org/BlogPosting">
                            <a name="<?= $ChapterDetail['title'] ?>"></a>
                            <h1 class="post-title entry-title" itemprop="name headline">
                                <?= $ChapterDetail['title'] ?>
                            </h1>
                            <header class="post-header">
                                <span class="post-auth"><i class="fa fa-user"></i> By <?= RoleColor($UserDetail) ?>,
                                </span>
                                <span class="post-date"><i class="fa fa-calendar"></i> Released at <span
                                        class="date-block"><abbr class="updated published" itemprop="datePublished">
                                            <?= ago($ChapterDetail['time']) ?>
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
                                    <a href="/forum/chapter-<?= $ChapterDetail['id'] ?>/edit">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa
                                    </a>
                                    / <a href="/forum/post-<?= $ChapterDetail['id'] ?>/add-chap">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Thêm chương
                                    </a>
                                    / <a href="/forum/chapter-<?= $ChapterDetail['id'] ?>/delete">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Xóa chương
                                    </a>
                                </div>
                            <?php endif ?>
                            <div class="shr-btn shr-hd cl">
                                <a class="shr-fb"
                                    href="http://www.facebook.com/sharer.php?u=<?= $ChaptertDetail['url'] ?>"
                                    target="_blank" title="Share to Facebook">
                                    <i class="fa fa-facebook"></i> Share on Facebook
                                </a>
                                <a class="shr-tw" href="http://twitter.com/share?url=<?= $ChapterDetail['url'] ?>"
                                    target="_blank" title="Share to Twitter">
                                    <i class="fa fa-twitter"></i> Share on Tweet
                                </a>
                                <a class="shr-gp" href="https://plus.google.com/share?url=<?= $ChapterDetail['url'] ?>"
                                    target="_blank" title="Share to Google+">
                                    <i class="fa fa-google-plus"></i> Share on Google+
                                </a>
                            </div>
                            <?= $ChapterDetail['content'] ?>
                            <?php if ($ForumStats['count_chapter_in_post'] > 0): ?>
                                <!-- Chapter List -->
                                <div class="animbox-download">
                                    <ul class="animbox-dl cl">
                                        <li> <span>Danh sách chương (<?= $ForumStats['count_chapter_in_post'] ?>)</span>
                                            <span>Link</span>
                                        </li>
                                        <?php foreach ($ChapterList as $ChapterItem): ?>
                                            <li>
                                                <span><?= $ChapterItem['title'] ?></span>
                                                <?php if ($ChapterDetail['id'] == $ChapterItem['id']): ?>
                                                    <a>[ Đang đọc ]</a>
                                                <?php else: ?>
                                                    <a
                                                        href="<?= url('/view-chap/' . $ChapterItem['id'] . '-' . $ChapterDetail['slug']) ?>">
                                                        Đọc ngay</a>
                                                <?php endif ?>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif ?>
                        </article>
                    </div>
                    <div class="clear"></div>
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
                            value="[url=<?= $ChapterDetail['url'] ?>]<?= $ChapterDetail['title'] ?>[/url]" />
                    </li>
                    <li>
                        Markdown: <input class="form-control" type="text"
                            value="[<?= $ChapterDetail['title'] ?>](<?= $ChapterDetail['url'] ?>)" />
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