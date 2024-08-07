<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

use System\Classes\Config;
use System\Classes\Container;
use System\Classes\Env;
use System\Classes\Request;
use System\Classes\Template;

if (!function_exists('_e')) {
    function _e(string $text)
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        return trim($text);
    }
}

if (!function_exists('app')) {
    /**
     * Get container or a class instance
     *
     * @param mixed $abstract
     * @param array $parameters
     * @return Container|mixed
     */
    function app($abstract = null, array $parameters = [])
    {
        $container = Container::getInstance();

        if ($abstract) {
            return $container->make($abstract, $parameters);
        }

        return $container;
    }
}

if (!function_exists('captchaSrc')) {
    /**
     * Get src of captcha image
     *
     * @return string
     */
    function captchaSrc()
    {
        return url('captcha') . '?v=' . time();
    }
}

if (!function_exists('config')) {
    /**
     * Get autoload config
     *
     * @param string|null $path
     * @param mixed $default
     * @return Config|mixed
     */
    function config(string $path = null, $default = null)
    {
        $config = app(Config::class);

        if (is_null($path)) {
            return $config;
        }

        return $config->get($path, $default);
    }
}

if (!function_exists('display_error')) {
    function display_error($error)
    {
        if (is_array($error)) {
            if (sizeof($error) === 1) {
                $error = array_pop($error);
            } else {
                $error = '- ' . implode('<br />- ', $error);
            }
        }

        return $error;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('display_layout')) {
    function display_layout()
    {
        $arrUA = mb_strtolower($_SERVER['HTTP_USER_AGENT']);
        if (preg_match('/windows|ipod|ipad|iphone|android|webos|blackberry|midp/', $arrUA) && preg_match('/mobile/', $arrUA)) {
            return 'mobile';
        } elseif (preg_match('/mobile/', $arrUA))
            return 'mobile';
        else
            return 'desktop';
    }
}

if (!function_exists('paging')) {
    function paging($url, $currentPage, $totalPages, $numPagesToShow = 5)
    {
        $currentPage = intval($currentPage);
        $totalPages = intval($totalPages);
        $numPagesToShow = intval($numPagesToShow);
        $pagination = '';

        if ($totalPages > 1) {
            $pagination .= '<nav class="pagination blog-pager"><ul class="pagination">';

            // First and Previous Links
            if ($currentPage > 1) {
                $pagination .= '<li class="first"><a href="' . $url . '1">First</a></li>';
                $pagination .= '<li><a href="' . $url . ($currentPage - 1) . '"><i class="fa fa-angle-left"></i></a></li>';
            }

            // Determine the start and end page numbers
            $startPage = max(1, $currentPage - intval($numPagesToShow / 2));
            $endPage = min($totalPages, $startPage + $numPagesToShow - 1);

            // Adjust the start page if we're near the end
            if ($endPage - $startPage < $numPagesToShow) {
                $startPage = max(1, $endPage - $numPagesToShow + 1);
            }

            // Page Numbers
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $currentPage) {
                    $pagination .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $pagination .= '<li><a href="' . $url . $i . '">' . $i . '</a></li>';
                }
            }

            // Next and Last Links
            if ($currentPage < $totalPages) {
                $pagination .= '<li><a href="' . $url . ($currentPage + 1) . '"><i class="fa fa-angle-right"></i></a></li>';
                $pagination .= '<li class="last"><a href="' . $url . $totalPages . '">Last</a></li>';
            }

            $pagination .= '</ul><div class="clear"></div></nav>';
        }

        return $pagination;
    }

}

if (!function_exists('redirect')) {
    function redirect(string $uri = '/')
    {
        header('Location: ' . SITE_PATH . $uri);
        exit;
    }
}

if (!function_exists('request')) {
    /**
     * Get request instance
     *
     * @return Request
     */
    function request()
    {
        return app(Request::class);
    }
}

if (!function_exists('url')) {
    function url(string $path = '', $absulute = true)
    {
        if ($absulute) {
            return SITE_URL . '/' . ltrim($path, '/');
        }

        return (SITE_PATH ? '/' . ltrim(SITE_PATH, '/') : '')
            . '/' . ltrim($path, '/');
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @param  mixed  ...$args
     * @return mixed
     */
    function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('view')) {
    /**
     * Get template instance or render a view
     *
     * @param string|null $template
     * @param array $data
     * @return Template|string
     */
    function view(string $template = null, array $data = [])
    {
        /** @var Template */
        $view = app(Template::class);

        if (is_null($template)) {
            return $view;
        }

        return $view->render($template, $data);
    }
}

if (!function_exists('ago')) {
    function ago($time_ago)
    {
        $time_ago = intval($time_ago);
        $time_current = date('U');
        $time_seconds = $time_current - $time_ago;
        $time_minutes = floor($time_seconds / 60);
        $time_day = date('z', $time_current) - date('z', $time_ago);
        $fulltime = date('d.m.Y - H:i', $time_ago);
        $minitime = date('H:i', $time_ago);

        if ($time_day == 0) {
            if ($time_seconds <= 60) {
                return $time_seconds . ' seconds ago';
            } elseif ($time_minutes <= 60) {
                return $time_minutes . ' minutes ago';
            } else {
                return 'Today, ' . $minitime;
            }
        } elseif ($time_day == 1) {
            return 'Yesterday, ' . $minitime;
        } else {
            return $fulltime;
        }
    }
}

if (!function_exists('RoleColor')) {
    function RoleColor($UserDetail = [])
    {
        //print_r($user);
        $output = '';
        $color = null;
        $user_name = htmlspecialchars($UserDetail['name']);
        $user_level = intval($UserDetail['level']);
        if ($user_level < '0') { // người bị cấm
            $color = '#D3D3D3'; // LightGray
            $output .= '<a href="/user/' . htmlspecialchars($UserDetail['nick']) . '" style="font-weight:500;color:' . $color . ';text-decoration:line-through;">' . $user_name . '</a>';
        } elseif ($user_level == '0' || $user_level <= '80') { // người bình thường
            $color = '#2F4F4F'; // DarkSlateGrey
        } elseif ($user_level <= '90') { // người đăng bài
            $color = '#4169E1'; // RoyalBlue
        } elseif ($user_level <= '100') { // mod
            $color = '#228B22'; // ForestGreen
        } elseif ($user_level <= '110') { // smod
            $color = '#663399'; // RebeccaPurple
        } elseif ($user_level <= '120') { // admin
            $color = '#663399'; // RebeccaPurple
            $class = 'lv-120';
        } elseif ($user_level <= '126') { // thành viên vippro
            $class = 'lv-126';
        } elseif ($user_level > '126') { // sáng lập viên
            $class = 'lv-127';
        }
        if ($user_level >= '0') {
            $output .= '<b';
            if (isset($class)) {
                $output .= ' class="' . $class . '"';
            }
            $output .= '><a href="/user/' . htmlspecialchars($UserDetail['nick']) . '" style="' . (isset($color) ? 'color: ' . $color . ';' : '') . 'border-radius:5px;display:inline-block;margin:1px;text-shadow:0 0 0.7em ' . (isset($color) ? $color : '#ff6699') . ';font-weight:500">';
            $output .= $user_name . '</a></b>';
        }
        return $output;
    }
}

if (!function_exists('getAvtUser')) {
    function getAvtUser($UserDetail = [])
    {
        $avatarBaseURL = 'https://cdn.jsdelivr.net/gh/jesuisnk/dorew-assets/avatar/';
        $avatar0 = $UserDetail['avatar'];
        $userlv = $UserDetail['level'];

        // Mapping of avatar numbers to filenames
        $avatarMap = [
            '1' => 'badger.png',
            '2' => 'bear.png',
            '3' => 'bull.png',
            '4' => 'camel.png',
            '5' => 'cat.png',
            '6' => 'dog.png',
            '7' => 'dolphin.png',
            '8' => 'duck.png',
            '9' => 'hamster.png',
            '10' => 'hippo.png',
            '11' => 'kangaroo.png',
            '12' => 'koala.png',
            '13' => 'lama.png',
            '14' => 'monkey.png',
            '15' => 'moose.png',
            '16' => 'mouse.png',
            '17' => 'owl.png',
            '18' => 'penguin.png',
            '19' => 'pig.png',
            '20' => 'rabbit.png',
            '21' => 'raven.png',
            '22' => 'rooster.png',
            '23' => 'seal.png',
            '24' => 'sheep.png',
            '25' => 'snake.png',
            '26' => 'turtle.png',
            '27' => 'unicorn.png',
            '28' => 'vulture.png',
            '29' => 'zebra.png',
        ];

        // Check if avatar0 is within range
        if ($avatar0 > '0' && $avatar0 < '30') {
            $avatarFilename = isset($avatarMap[$avatar0]) ? $avatarMap[$avatar0] : '';
            $avatar0 = $avatarBaseURL . $avatarFilename;
        } else {
            $avatar0 = str_replace(['.jpg', '.png'], ['b.jpg', 'b.png'], $avatar0);
        }
        if ($userlv < '0') {
            return 'https://i.imgur.com/COuyZhV.jpg';
        } else {
            return $avatar0;
        }
    }
}

if (!function_exists('getCoverUser')) {
    function getCoverUser($UserDetail = [])
    {
        $coverBaseURL = 'https://dorew-site.github.io/assets/cover/';
        $cover0 = $UserDetail['cover'];
        if ($cover0 > '0' && $cover0 <= '18') {
            $cover0 = $coverBaseURL . $cover0 . '.jpg';
        }
        return $cover0;
    }
}

if (!function_exists('showAvtUser')) {
    function showAvtUser($avatar0)
    {
        $avatars = [
            '1' => 'badger',
            '2' => 'bear',
            '3' => 'bull',
            '4' => 'camel',
            '5' => 'cat',
            '6' => 'dog',
            '7' => 'dolphin',
            '8' => 'duck',
            '9' => 'hamster',
            '10' => 'hippo',
            '11' => 'kangaroo',
            '12' => 'koala',
            '13' => 'lama',
            '14' => 'monkey',
            '15' => 'moose',
            '16' => 'mouse',
            '17' => 'owl',
            '18' => 'penguin',
            '19' => 'pig',
            '20' => 'rabbit',
            '21' => 'raven',
            '22' => 'rooster',
            '23' => 'seal',
            '24' => 'sheep',
            '25' => 'snake',
            '26' => 'turtle',
            '27' => 'unicorn',
            '28' => 'vulture',
            '29' => 'zebra'
        ];

        return isset($avatars[$avatar0])
            ? 'https://cdn.jsdelivr.net/gh/jesuisnk/dorew-assets/avatar/' . $avatars[$avatar0] . '.png'
            : $avatar0;
    }
}

if (!function_exists('ForumRank')) {
    function ForumRank($current_point = 0)
    {
        $list = ['9', '14', '19', '24', '29', '39', '49', '69', '89', '99', '109', '119', '129', '139', '149', '159', '169', '179', '189', '199', '209', '219', '229', '239', '249', '259', '269', '279', '289', '299', '309', '319', '329', '339', '349', '359', '369', '379', '389', '399', '409', '419', '429', '439', '449', '459', '469', '479', '489', '499', '699', '799', '999', '1399', '1599', '1799', '2199', '2599', '2899', '3199', '3699', '4099', '4599', '4999', '5599', '6099', '6599', '7199', '7899', '8599'];

        $level = null;
        foreach ($list as $index => $point) {
            $location = $index + 1;
            if ($current_point >= 8599) {
                $level = '70';
                break;
            } elseif ($current_point <= $point) {
                $level = $location;
                break;
            }
        }

        $level = $level ?? '1';
        $img_url = "https://cdn-static-nivx7f94b-moleys.vercel.app/assets/images/icon/lv/{$level}.png";

        return '<img width="40px" src="' . htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') . '"/>';
    }
}

if (!function_exists('e_pass')) {
    function e_pass($string = null, $type = 0)
    {
        $encrypt = null;
        $e1_map = array(
            '0' => '9',
            '1' => '8',
            '2' => '7',
            '3' => '6',
            '4' => '5',
            '9' => '0',
            '8' => '1',
            '7' => '2',
            '6' => '3',
            '5' => '4',
            'a' => 'z',
            'b' => 'y',
            'c' => 'xx',
            'd' => 'w',
            'e' => 'v',
            'f' => 'u',
            'g' => 't',
            'h' => 's',
            'i' => 'r',
            'j' => 'q',
            'k' => 'p',
            'l' => 'o',
            'm' => 'n',
            'z' => 'a',
            'y' => 'b',
            'x' => 'c',
            'w' => 'd',
            'v' => 'e',
            'u' => 'f',
            't' => 'g',
            's' => 'h',
            'r' => 'i',
            'q' => 'j',
            'p' => 'k',
            'o' => 'l',
            'n' => 'm',
            'A' => 'Z',
            'B' => 'Y',
            'C' => 'XX',
            'D' => 'W',
            'E' => 'V',
            'F' => 'U',
            'G' => 'T',
            'H' => 'S',
            'I' => 'R',
            'J' => 'Q',
            'K' => 'P',
            'L' => 'O',
            'M' => 'N',
            'Z' => 'A',
            'Y' => 'B',
            'X' => 'C',
            'W' => 'D',
            'V' => 'E',
            'U' => 'F',
            'T' => 'G',
            'S' => 'H',
            'R' => 'I',
            'Q' => 'J',
            'P' => 'K',
            'O' => 'L',
            'N' => 'M'
        );
        $e1 = strtr($string, $e1_map);
        $e2 = md5($e1);
        if ($type == 1) {
            $encrypt = $e1;
        } else {
            $encrypt = $e2;
        }
        return $encrypt;
    }
}

if (!function_exists('generateCSRFToken')) {
    function generateCSRFToken()
    {
        $encrypt = bin2hex(random_bytes(32));
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = $encrypt;
        }
    }
}

if (!function_exists('isCSRFTokenValid')) {
    function isCSRFTokenValid($token)
    {
        $output = null;
        $isCSRFTokenValid = isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
        if (!$isCSRFTokenValid) {
            $output = 'error';
        }
        return $output;
    }
}

if (!function_exists('unsetCSRFToken')) {
    function unsetCSRFToken()
    {
        if (isset($_SESSION['csrf_token'])) {
            unset($_SESSION['csrf_token']);
        }
        return true;
    }
}

if (!function_exists('getCSRFToken')) {
    function getCSRFToken()
    {
        return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null;
    }
}

if (!function_exists('checkExtension')) {
    function checkExtension($one)
    {
        $extension = pathinfo($one, PATHINFO_EXTENSION);

        if (in_array($extension, ['jpg', 'png', 'webp', 'psd', 'heic'])) {
            return 'file-image-o';
        } elseif (in_array($extension, ['mp4', 'mkv', 'webm', 'flv', '3gp'])) {
            return 'file-video-o';
        } elseif (in_array($extension, ['mp3', 'mkv', 'm4a', 'flac', 'wav'])) {
            return 'file-audio-o';
        } elseif (in_array($extension, ['docx', 'doc', 'txt', 'md', 'odt'])) {
            return 'file-text-o';
        } elseif (in_array($extension, ['txt', 'md'])) {
            return 'file-text-o';
        } elseif (in_array($extension, ['docx', 'doc', 'odt'])) {
            return 'file-word-o';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return 'file-excel-o';
        } elseif (in_array($extension, ['ppt', 'pptx'])) {
            return 'file-powerpoint-o';
        } elseif ($extension === 'pdf') {
            return 'file-pdf-o';
        } elseif (in_array($extension, ['zip', 'rar', '7z', 'tar'])) {
            return 'file-archive-o';
        } elseif (in_array($extension, ['cpp', 'cs', 'php', 'html', 'js', 'py'])) {
            return 'file-code-o';
        } elseif ($extension === 'sql') {
            return 'database';
        } else {
            return 'file-o';
        }
    }
}

if (!function_exists('FileSizeFormat')) {
    function FileSizeFormat($byte)
    {
        if ($byte >= 1073741824) {
            $show = round($byte / 1073741824, 2, PHP_ROUND_HALF_DOWN) . ' GB';
        } elseif ($byte >= 1048576) {
            $show = round($byte / 1048576, 2, PHP_ROUND_HALF_DOWN) . ' MB';
        } elseif ($byte >= 1024) {
            $show = round($byte / 1024, 2, PHP_ROUND_HALF_DOWN) . ' Kb';
        } else {
            $show = $byte . ' byte';
        }
        return $show;
    }
}

if (!function_exists('checkDarkMode')) {
    function checkDarkMode()
    {
        if (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled') {
            echo ' class="dark-mode"';
        }
    }
}