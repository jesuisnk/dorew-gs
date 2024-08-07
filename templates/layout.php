<?= generateCSRFToken() ?><!DOCTYPE html>
<html lang="vi-VN"<?= checkDarkMode() ?>>

<head>
    <meta charset="utf-8" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <title>
        <?= (isset($page_title) && $page_title != config('system.app.name') ? $page_title . ' | ' : '') . config('system.app.name'); ?>
    </title>
    <link rel="shortcut icon" href="<?= url('/favicon.ico') ?>" />
    <meta name="description" content="<?= isset($page_description) ? $page_description : config('system.app.description') ?>" />
    <meta name="keywords" content="<?= isset($page_keyword) ? $page_keyword : config('system.app.keyword') ?>" />
    <meta name="robots" content="index,follow" />
    <meta name="googlebot" content="index,follow" />
    <link rel="canonical" href="<?= url($_SERVER['REQUEST_URI']) ?>" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?= $this->asset('/css/styles.css') ?>" rel="stylesheet" />
    <link href="<?= $this->asset('/css/style.dark.css') ?>" rel="stylesheet" />
    <link href="<?= $this->asset('/css/bootstrap.css') ?>" rel="stylesheet" />
    <?php if (strpos($_SERVER['REQUEST_URI'], '/404') === false) : ?>
        <script type='application/ld+json'>
            {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "name": "<?= (isset($page_title) && $page_title != config('system.app.name') ? $page_title . ' | ' : '') . config('system.app.name'); ?>",
                "url": "<?= url($_SERVER['REQUEST_URI']) ?>"
                <?php if (strpos($_SERVER['REQUEST_URI'], '/forum/') !== false || strpos($_SERVER['REQUEST_URI'], '/view-chap/') !== false) : ?>,
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": "<?= url('/search?q=') ?>{search_term_string}",
                        "query-input": "required name=search_term_string"
                    }
                <?php endif ?>
            }
        </script>
    <?php endif ?>
</head>

<body class="loading">
    <header class="container" id="header-wrapper" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
        <div class="header section" id="header">
            <div class="widget Header" data-version="1" id="Header1">
                <div id="header-inner">
                    <div class="header-logo">
                        <a href="<?= url('/') ?>" style="display: block">
                            <img alt="<?= config('system.app.name') ?>" height="942" id="Header1_headerimg" src="<?= url('/images/header.png') ?>" style="display: block; width: 1000px;">
                        </a>
                    </div>
                    <div class="header-title">
                        <h1 class="title">Dorew</h1>
                        <div class="descriptionwrapper">
                            <p class="description"><span>Thích Ngao Du</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="/search" id="search-form" method="get">
            <input id="search-box" name="q" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" type="text" value="Nhập từ khóa...">
            <input id="search-button" type="submit" value="Tìm kiếm">
        </form>
        <div class="clear"></div>
    </header>

    <nav class="container" id="nav-wrapper" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
        <ul class="nav-menu cl">
            <li>
                <a href="<?= url('/') ?>" itemprop="url">
                    <span itemprop="name">Trang chủ</span>
                </a>
            </li>
            <?php if ($isLogin) : ?>
                <li><a href="<?= url('/shoutbox') ?>" itemprop="url"><span itemprop="name">Trò chuyện</span></a></li>
                <li>
                    <a href="<?= url('/mail') ?>" itemprop="url">
                        <span itemprop="name">Tin nhắn
                            <?php if ($new_mail_count > 0) : ?>(<?= $new_mail_count ?>)<?php endif ?>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('/mail/system') ?>" itemprop="url">
                        <span itemprop="name">Thông báo
                            <?php if ($system_notify_count > 0) : ?>(<?= $system_notify_count ?>)<?php endif ?>
                        </span>
                    </a>
                </li>
                <li><a href="<?= url('/user') ?>" itemprop="url"><span itemprop="name">Hồ sơ</span></a></li>
            <?php else : ?>
                <li><a href="<?= url('/login') ?>" itemprop="url"><span itemprop="name">Đăng nhập</span></a></li>
                <li><a href="<?= url('/register') ?>" itemprop="url"><span itemprop="name">Đăng ký</span></a></li>
            <?php endif ?>
        </ul>
        <div class="clear"></div>
    </nav>

    <ul class="mobile-navbar cl" style="z-index:1000">
        <?php if ($isLogin) : ?>
            <li class="item mobile-navbar-right" id="mobile-navbar-button">
                <a class="mobile-navbar-button mobile-hover-none mobile-navbar-text-hover">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        <?php endif ?>
        <li class="item" style="padding-left:2px"></li>
        <li class="item active">
            <a href="<?= url('/') ?>" title="Trang chủ"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
        </li>
        <?php if ($isLogin) : ?>
            <li class="item active">
                <a href="<?= url('/user') ?>" title="Trang cá nhân">
                    <i class="fa fa-user fa-lg" aria-hidden="true"></i>
                </a>
            </li>
            <li class="item active">
                <a href="<?= url('/mail') ?>" title="Tin nhắn riêng">
                    <i class="fa fa-envelope fa-lg" aria-hidden="true"></i>
                    <?php if ($new_mail_count > 0) : ?>
                        <span class="badge"><?= $new_mail_count ?></span>
                    <?php endif ?>
                </a>
            </li>
            <li class="item active">
                <a href="<?= url('/mail/system') ?>" title="Thông báo">
                    <i class="fa fa-bell fa-lg" aria-hidden="true"></i>
                    <?php if ($system_notify_count > 0) : ?>
                        <span class="badge"><?= $system_notify_count ?></span>
                    <?php endif ?>
                </a>
            </li>
        <?php else : ?>
            <li class="item active mobile-navbar-right">
                <a href="<?= url('/login') ?>" title="Đăng nhập"><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i></a>
            </li>
            <li class="item active mobile-navbar-right">
                <a href="<?= url('/register') ?>" title="Tạo tài khoản"><i class="fa fa-user-plus fa-lg" aria-hidden="true"></i></a>
            </li>
        <?php endif ?>
    </ul>

    <div class="container" id="wrapper">
        <div class="cl" id="content-wrapper">
            <?= $this->section('content') ?>
        </div>
        <div class="clear"></div>
    </div>

    <footer class="container cl" id="footer-wrapper">
        <div class="footer-left fl">
            Copyright &copy; 2018 -
            <?= date('Y') ?> <a href="<?= url('/') ?>" rel="copyright">Dorew</a>
        </div>
        <div class="footer-right fr">
            Made with ⌨️️ and 🖱️
        </div>
        <div class="clear"></div>
    </footer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/monokai-sublime.min.css" integrity="sha512-ade8vHOXH67Cm9z/U2vBpckPD1Enhdxl3N05ChXyFx5xikfqggrK4RrEele+VWY/iaZyfk7Bhk6CyZvlh7+5JQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="<?= $this->asset('/js/app.js') ?>"></script>
    <script>
        function loadCSS(filename, filetype) {
            if (filetype == 'css') {
                var fileref = document.createElement('link')
                fileref.setAttribute('rel', 'stylesheet')
                fileref.setAttribute('href', filename)
            }
            if (typeof fileref != 'undefined') {
                document.getElementsByTagName('head')[0].appendChild(fileref)
            }
        }
        $(function() {
            if ($('a.swipebox').length != '') {
                $('a.swipebox').each(function() {
                    $(this).attr('href', $(this).attr('href') + '?dl=1')
                })
                var is_load = 0
                function loadpl() {
                    if (is_load == 0) {
                        is_load = 1
                        loadCSS('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css',
                            'css')
                        loadCSS('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lg-transitions.min.css',
                            'css')
                        $.getScript('<?= url('/js/lightbox.js') ?>').done(function() {
                            $('body').lightGallery({
                                selector: 'a.swipebox',
                                thumbnail: true,
                                showThumbByDefault: false,
                                subHtmlSelectorRelative: true,
                                mode: 'lg-zoom-out'
                            })
                        })
                    }
                }
                $(window).scroll(function() {loadpl()})
                $(window).mousemove(function() {loadpl()})
                setTimeout(function() {loadpl()}, 3000)
            }
        })
    </script>

</body>

</html>