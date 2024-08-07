<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-post.css') ?>" rel="stylesheet" />

<div class="col-12 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog"
    role="main">
    <div class="main-blog cl" style="margin-bottom:20px">
        <div class="breadcrumbs">
            <span>
                <a href="<?= url('/forum') ?>" itemprop="url">
                    <span itemprop="title">Diễn đàn</span>
                </a>
            </span>
            &#187;
            <span>
                <a href="<?= url('/forum?category=' . $CategoryDetail['id']) ?>" itemprop="url">
                    <span itemprop="title">Chuyên mục: <?= $CategoryDetail['name'] ?></span>
                </a>
            </span>
            &#187;
            <span>
                <a href="<?= url('/forum/' . $PostDetail['id'] . '-' . $PostDetail['slug'] . '.html') ?>"
                    itemprop="url">
                    <span itemprop="title">Chủ đề: <?= $PostDetail['title'] ?></span>
                </a>
            </span>
            <?php if ($action == 'post'): ?>
                &#187;
                <span>Xóa chủ đề</span>
            <?php else: ?>
                &#187;
                <span>
                    <a href="<?= url('/view-chap/' . $ChapterDetail['id'] . '-' . $ChapterDetail['slug'] . '.html') ?>"
                        itemprop="url">
                        <span itemprop="title">Chương: <?= $ChapterDetail['title'] ?></span>
                    </a>
                </span>
                &#187;
                <span>Xóa chương</span>
            <?php endif; ?>
        </div>
        <?php if ($error): ?>
            <div style="margin-top:5px;margin-bottom:5px">
                <div class="alert alert-warning mb-3"><?= $error ?></div>
            </div>
        <?php endif ?>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Delete</h2>
                <div class="widget-content">
                    <form name="form" action method="post">
                        <div>
                            Bạn có thực sự muốn xóa chủ đề/chương này không?
                        </div>
                        <p class="text-center">
                            <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>