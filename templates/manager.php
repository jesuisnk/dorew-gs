<?php $this->layout('layout'); ?>

<div class="col-12 col-xs-12 col-mb-12" id="post-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog"
    role="main">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title"><i class="fa fa-tachometer" aria-hidden="true"></i><span>Bảng quản trị</span></b>
        <div class="main-blg section" id="main-blg">
            <div class="widget HTML" data-version="1" id="HTML4">
                <h2 class="title">Manager</h2>
                <div class="widget-content block">
                    <div class="block-item">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <a href="<?= url('/forum/post') ?>">Viết bài mới</a>
                    </div>
                    <div class="block-item">
                        <i class="fa fa-cube" aria-hidden="true"></i>
                        <a href="<?= url('/forum/category') ?>">Quản lý chuyên mục</a>
                    </div>
                    <div class="block-item">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <a href="<?= url('/users') ?>">Quản lý thành viên</a>
                    </div>
                    <div class="block-item">
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                        <a href="<?= url('/forum?category=' . env('NewsID')) ?>">Quản lý tin tức</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>