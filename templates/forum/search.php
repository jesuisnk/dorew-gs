<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-search.css') ?>" rel="stylesheet" />

<div class="main-post cl">
    <b class="main-title">
        <i class="fa fa-bars"></i><span>Kết quả tìm kiếm</span>
    </b>
    <div class="main section" id="main">
        <div class="widget Blog" data-version="1" id="Blog1">
            <div class="blog-posts row hfeed">
                <div class="status-message fx">
                    <div class="pagetitle">
                        <?php if ($SearchResultCount > 0): ?>
                            Hiển thị bài đăng được sắp xếp theo mức độ liên quan cho truy vấn: <b><?= $SearchQuery ?></b>.
                        <?php else: ?>
                            Không có bài đăng nào phù hợp với truy vấn: <b><?= $SearchQuery ?></b>.
                        <?php endif ?>
                        <a href="<?= url('/') ?>">Hiển thị tất cả bài đăng</a>
                    </div>
                </div>
                <?php if ($SearchResultCount > 0): ?>
                    <div class="search-result">
                        <?php foreach ($SearchResultList as $SearchResultDetail): ?>
                            <div class="item">
                                <a href="<?= url($SearchResultDetail['url']) ?>" class="dorew-postforum-title">
                                    <?= $SearchResultDetail['title'] ?>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <?=$SearchPaging?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>