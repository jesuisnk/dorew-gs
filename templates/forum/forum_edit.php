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
                <span>Chỉnh sửa bài viết</span>
            <?php else: ?>
                &#187;
                <span>
                    <a href="<?= url('/view-chap/' . $ChapterDetail['id'] . '-' . $ChapterDetail['slug'] . '.html') ?>"
                        itemprop="url">
                        <span itemprop="title">Chương: <?= $ChapterDetail['title'] ?></span>
                    </a>
                </span>
                &#187;
                <span>Chỉnh sửa nội dung chương</span>
            <?php endif; ?>
        </div>
        <?php if ($error): ?>
            <div style="margin-top:5px;margin-bottom:5px">
                <div class="alert alert-warning mb-3"><?= $error ?></div>
            </div>
        <?php endif ?>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Edit</h2>
                <div class="widget-content">
                    <form name="form" action method="post">
                        <div>
                            <b><i class="fa fa-gg" aria-hidden="true"></i> Tiêu đề:</b>
                            <p><input value="<?= $inputTitle ?>" class="form-control" type="text" name="title" value
                                    maxlength="300" required></p>
                        </div>
                        <?php if ($action == 'post' && $CategoryDetail['id'] != env('NewsID')): ?>
                            <div>
                                <b><i class="fa fa-bars"></i> Chuyên mục:</b>
                                <p><select name="category" class="form-control">
                                        <?php foreach ($CategoryList as $CategoryItem): ?>
                                            <option value="<?= $CategoryItem['id'] ?>"
                                                <?= ($PostDetail['category'] == $CategoryItem['id'] ? ' selected' : '') ?>>
                                                <?= $CategoryItem['name'] ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </p>
                            </div>
                        <?php endif ?>
                        <div>
                            <b><i class="fa fa-newspaper-o" aria-hidden="true"></i> Nội dung:</b>
                            <?php include (dirname(dirname(__FILE__)) . '/toolbar.php') ?>
                            <textarea class="form-control" name="content" rows="15"
                                required><?= $inputContent ?></textarea>
                        </div>
                        <p class="text-center">
                            <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                            <button type="submit" class="btn btn-primary btn-block">Tái bản</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>