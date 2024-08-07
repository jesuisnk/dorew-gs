<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-post.css') ?>" rel="stylesheet" />

<div class="col-12 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog"
    role="main">
    <div class="main-blog cl" style="margin-bottom:20px">
        <div class="breadcrumbs">
            <span>
                <a href="<?= url('/manager') ?>" itemprop="url">
                    <span itemprop="title">Bảng quản trị</span>
                </a>
            </span>
            &#187;
            <span>Quản lý chuyên mục</span>
        </div>
        <?php if ($error): ?>
            <div style="margin-top:5px;margin-bottom:5px">
                <div class="alert alert-warning mb-3"><?= $error ?></div>
            </div>
        <?php endif ?>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Category Manager</h2>
                <div class="widget-content block">
                    <div class="block-item-hover-none">
                        <form method="post">
                            <p>
                                <b>Tạo chuyên mục</b> (Tối đa 70 kí tự)<br />
                                <input class="form-control" type="text" name="name" maxlength="50" required>
                            </p>
                            <p>
                                Mô tả:<br />
                                <textarea class="form-control" name="content" required></textarea>
                            </p>
                            <p>
                                Từ khoá:<br />
                                <textarea class="form-control" name="keyword" required></textarea>
                            </p>
                            <p class="text-center">
                                <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                                <button type="submit" class="btn btn-primary btn-block" name="submit"
                                    value="create">Tạo</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($CategoryCount > 0): ?>
        <div class="main-blog cl" style="margin-bottom:20px">
            <b class="main-title" style="font-size:1em;margin:5px">
                <i class="fa fa-bars" aria-hidden="true"></i><span>Danh sách</span>
            </b>
            <div class="main-blg section" id="main-blg">
                <div class="widget HTML" data-version="1" id="HTML4">
                    <h2 class="title">List</h2>
                    <form method="post">
                        <div class="widget-content block">
                            <?php foreach ($CategoryList as $CategoryItem): ?>
                                <div class="block-item">
                                    <input type="radio" name="category_id" valeue="<?= $CategoryItem['id'] ?>" />
                                    » <a href="<?= url('/forum/?category=' . $CategoryItem['id']) ?>">
                                        <?= $CategoryItem['name'] ?>
                                    </a> (<?= $CategoryItem['count_post'] ?>)
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="widget-content" style="padding-top:10px">
                            <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                            <button type="submit" class="btn btn-block btn-primary" name="submit" value="delete">Xóa chuyên
                                mục</button>
                            Lưu ý: thao tác này cũng đồng nghĩa với việc <b>toàn bộ dữ liệu có trong chuyên mục cũng bị dọn
                                sạch</b>, bao gồm: bài viết, các chương, bình luận, và các tập tin đính kèm.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>