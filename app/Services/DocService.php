<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Services;

use Symfony\Component\String\Slugger\AsciiSlugger;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\TableExtension;
use Highlight\Highlighter;

class DocService
{
    public function PageDescription($content)
    {
        if (empty($content)) {
            return null;
            exit();
        }
        $content = html_entity_decode($this->bbcode($content));
        $content = strip_tags(html_entity_decode($content));
        $content = str_replace(["\r", "\n"], '', $content);
        $content = mb_substr($content, 0, 150);
        return $content;
    }

    public function PageKeyword($content)
    {
        if (empty($content)) {
            return null;
            exit();
        }
        $content = explode(' ', $content);
        $content = array_filter($content);
        $content = implode(', ', $content);
        return $content;
    }

    public function TrimContent($content = null)
    {
        if ($content === null) {
            return null;
        }
        $content = str_replace('ㅤ', ' ', $content);
        $content = trim($content);
        return $content;
    }

    public function slug($string = null)
    {
        if (empty($string)) {
            return null;
            exit();
        }
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($string)->lower();
        return $slug;
    }

    public function markdown_to_html($string = null)
    {
        if (empty($string)) {
            return null;
            exit();
        }
        // cấu hình đối tượng
        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => true,
            'allow_unsafe_protocol_links' => true,
            'allow_unsafe_protocols' => ['http', 'https'],
            'allow_unsafe_line_breaks' => true,
            'extensions' => [
                new AutolinkExtension(),
                new HeadingPermalinkExtension(),
                new TableExtension(),
            ],
        ]);
        // chuyển đối tượng sang html
        $html = $converter->convertToHtml($string);
        return $html;
    }

    public function bbcode($string)
    {
        $code_blocks = [];
        $string = preg_replace_callback(
            '#\[code(?:=(.+?))?\](.+?)\[\/code\]#is',
            function ($matches) use (&$code_blocks) {
                $highlighter = new Highlighter();
                $lang = $matches[1] ? mb_strtolower($matches[1]) : 'plaintext';
                $code = html_entity_decode($matches[2]);
                $highlighted = $highlighter->highlight($lang, $code);
                $placeholder = '<!-- CODE_BLOCK_' . count($code_blocks) . ' -->';
                $code_blocks[$placeholder] = "<pre><code class=\"hljs {$highlighted->language}\">{$highlighted->value}</code></pre>";
                return $placeholder;
            },
            $string
        );

        // Các thẻ bb đơn giản
        $type1 = 'b|u|s|i|strong|em';
        $string = preg_replace('/\[(' . $type1 . ')\](.*?)\[\/\1\]/is', '<$1>$2</$1>', $string);

        // Các thẻ bb nhiều tham số
        $type2 = [
            '/\[color=(.*?)\](.*?)\[\/color\]/is' => '<span style="color:$1">$2</span>',
            '/\[bcolor=(.*?)\](.*?)\[\/bcolor\]/is' => '<span style="color:$1;font-weight:700">$2</span>',
            '/\[quote=(.*?)\](.*?)\[\/quote\]/is' => '<div class="quote"><div class="quote-title">Quote from <b>$1</b></div><div class="quote-content">$2</div></div>',
            '/\[size=(.*?)\](.*?)\[\/size\]/is' => '<span style="font-size:$1px">$2</span>',
        ];
        $type3 = [
            '/\[red\](.*?)\[\/red\]/is' => '<span style="color:red">$1</span>',
            '/\[blue\](.*?)\[\/blue\]/is' => '<span style="color:blue">$1</span>',
            '/\[green\](.*?)\[\/green\]/is' => '<span style="color:green">$1</span>',
            '/\[yellow\](.*?)\[\/yellow\]/is' => '<span style="color:yellow">$1</span>',
            '/\[orange\](.*?)\[\/orange\]/is' => '<span style="color:orange">$1</span>',
            '/\[purple\](.*?)\[\/purple\]/is' => '<span style="color:purple">$1</span>',
            '/\[pink\](.*?)\[\/pink\]/is' => '<span style="color:pink">$1</span>',
            '/\[brown\](.*?)\[\/brown\]/is' => '<span style="color:brown">$1</span>',
            '/\[gray\](.*?)\[\/gray\]/is' => '<span style="color:gray">$1</span>',

            '/\[center\](.*?)\[\/center\]/is' => '<div style="text-align:center">$1</div>',
            '/\[right\](.*?)\[\/right\]/is' => '<div style="text-align:right">$1</div>',
            '/\[left\](.*?)\[\/left\]/is' => '<div style="text-align:left">$1</div>',
            '/\[justify\](.*?)\[\/justify\]/is' => '<div style="text-align:justify">$1</div>',
        ];
        $sim = array_merge($type2, $type3);
        $string = preg_replace(array_keys($sim), array_values($sim), $string);

        // tag @nick
        $string = preg_replace_callback('/@([a-zA-Z0-9_]+)/', function ($matches) {
            $UserModel = new \App\Models\User;
            $user = mb_strtolower($matches[1]);
            $UserDetail = $UserModel->UserDetailWithFields('nick', $user);
            if ($UserDetail) {
                $string = '<span class="tagnick">@' . RoleColor($UserDetail) . '</span>';
            } else {
                $string = '@' . $matches[1];
            }
            return $string;
        }, $string);

        // smiley
        $arr_emo_name = ['ami', 'anya', 'aru', 'aka', 'dauhanh', 'dora', 'le', 'menhera', 'moew', 'nam', 'pepe', 'qoobee', 'qoopepe', 'thobaymau', 'troll', 'dui', 'firefox', 'conan'];
        foreach ($arr_emo_name as $emo_name) {
            if (strpos($string, ':' . $emo_name) !== false) {
                $parttern = '/[:]' . $emo_name . '([0-9]*):/';
                $replacement = '<img loading="lazy" src="https://dorew-site.github.io/assets/smileys/' . $emo_name . '/' . $emo_name . '$1.png" alt="$1"/>';
                $string = preg_replace($parttern, $replacement, $string);
            }
        }

        // xử lý hình ảnh
        $parttern = '/\[img\](.*?)\[\/img\]/';
        $loaderror = 'https://i.imgur.com/806SpRu.png';
        $replacement = '<center><a href="$1" class="swipebox"><img loading="lazy" class="bb_img LoadImage" src="$1" border="2" onerror="this.onerror=null;this.src=' . $loaderror . '" style="border-radius:1%;display:block;margin:0 auto;max-width:70%;max-height:70%"/></a></center>';
        $string = preg_replace($parttern, $replacement, $string);

        // xử lý video
        $parttern = '/\[vid\](.*?)\[\/vid\]/';
        $replacement = '<div class="video-wrapper" style="text-align:center;"><iframe loading="lazy" src="/plugin/video_embed?link=$1" height="315" width="560" scrolling="no" allowfullscreen="" frameborder="0"></iframe></div>';
        $string = preg_replace($parttern, $replacement, $string);

        // xử lý thẻ download
        $parttern = '/\[d\](.*?)\[\/d\]/';
        $replacement = '<center><a href="$1"><button class="btn btn-primary"><i class="fa fa-download"></i> Download</button></a></center>';
        $string = preg_replace($parttern, $replacement, $string);

        // xử lý link
        $parttern = '/\[url=(.*?)\](.*?)\[\/url\]/';
        $replacement = '<i class="fa fa-link fa-spin"></i><a rel="nofollow" target="_blank" href="$1">$2</a>';
        $string = preg_replace($parttern, $replacement, $string);
        # url trực tiếp
        $string = preg_replace_callback('/(?:^|\s)(https?:\/\/\S+)/i', function ($matches) {
            $url = htmlspecialchars($matches[1]);
            return "<i class='fa fa-link fa-spin'></i><a rel='nofollow' target='_blank' href='$url'>$url</a>";
        }, $string);

        // xử lý xuống dòng
        $string = nl2br($string);
        $string = str_replace(array_keys($code_blocks), array_values($code_blocks), $string);

        // trả về kết quả sau khi biên dịch từ bbcode sang html
        return $string;
    }
}