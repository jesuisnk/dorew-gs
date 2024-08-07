<?php
$code = ['php', 'css', 'js', 'html', 'sql', 'twig', 'lua'];
$color = ['bcbcbc', '708090', '6c6c6c', '454545', 'fcc9c9', 'fe8c8c', 'fe5e5e', 'fd5b36', 'f82e00', 'ffe1c6', 'ffc998', 'fcad66', 'ff9331', 'ff810f', 'd8ffe0', '92f9a7', '34ff5d', 'b2fb82', '89f641', 'b7e9ec', '56e5ed', '21cad3', '03939b', '039b80', 'cac8e9', '9690ea', '6a60ec', '4866e7', '173bd3', 'f3cafb', 'e287f4', 'c238dd', 'a476af', 'b53dd2'];

$path_list = [
    'nam' => 26,
    'pepe' => 444,
    'ami' => 48,
    'moew' => 19,
    'qoopepe' => 17,
    'menhera' => 24,
    'dauhanh' => 131,
    'troll' => 132,
    'qoobee' => 127,
    'dora' => 303,
    'aru' => 119,
    'thobaymau' => 98,
    'le' => 72,
    'anya' => 15,
    'aka' => 24,
    'dui' => 15,
    'firefox' => 11,
    'conan' => 18,
];
$data_path_default = 'nam';
?>

<div class="redactor_box">
    <ul class="redactor_toolbar">
        <li class="redactor_btn_group">
            <a href="javascript:show_hide('colorShow')"><i class="fa fa-paint-brush" aria-hidden="true"></i></a>
            <a href="javascript:tag('[b]', '[/b]')"><i class="fa fa-bold" aria-hidden="true"></i></a>
            <a href="javascript:tag('[i]', '[/i]')"><i class="fa fa-italic" aria-hidden="true"></i></a>
            <a href="javascript:tag('[u]', '[/u]')"><i class="fa fa-underline" aria-hidden="true"></i></a>
            <a href="javascript:tag('[s]', '[/s]')"><i class="fa fa-strikethrough" aria-hidden="true"></i></a>
        </li>
        <li class="redactor_btn_group">
            <a href="javascript:tag('[center]', '[/center]')"><i class="fa fa-align-center" aria-hidden="true"></i></a>
            <a href="javascript:tag('[right]', '[/right]')"><i class="fa fa-align-right" aria-hidden="true"></i></a>
            <a href="javascript:show_hide('codeShow')"><i class="fa fa-code" aria-hidden="true"></i></a>
            <a href="javascript:tag('[url=]', '[/url]')"><i class="fa fa-link" aria-hidden="true"></i></a>
            <a href="javascript:tag('[d]', '[/d]')"><i class="fa fa-download" aria-hidden="true"></i></a>
        </li>
        <li class="redactor_btn_group">
            <a href="javascript:tag('[img]', '[/img]', '')"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            <a id="upload" style="color: blue"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
            <a href="javascript:tag('[vid]', '[/vid]', '');"><i class="fa fa-play-circle" aria-hidden="true"></i></a>
        </li>
    </ul>
    <div id="codeShow" style="display:none">
        <div style="padding:2px">
            <?php
            foreach ($code as $val) {
                echo '<a href="javascript:tag(\'[code=' . $val . ']\', \'[/code]\', \'\'); show_hide(\'codeShow\');" tabindex="-1" class="btn btn-default">' . $val . '</a>';
            }
            ?>
        </div>
    </div>
    <div id="colorShow" style="display:none">
        <div style="padding:2px">Màu chữ:
            <?php
            foreach ($color as $val) {
                echo '<a href="javascript:tag(\'[color=#' . $val . ']\', \'[/color]\', \'\'); show_hide(\'colorShow\');" tabindex="-1" style="background-color:#' . $val . ';width:3px;height:3px">ㅤ</a>';
            }
            ?>
        </div>
    </div>
</div>

<input style="display:none" type="file" id="f" accept="image/*">
<script src="/js/upload-imgur.js"></script>
<script>
    document.querySelector("#upload").onclick = function () {
        document.querySelector("#f").click();
    }
    imgur("#f", {
        loading: function (load) { document.querySelector("#upload").innerHTML = load },
        loaded: function (link, size, type, time) {
            tag("[img]" + link, "[/img]");
            $("#upload").html('<i class="fa fa-upload" aria-hidden="true"></i>');
        }
    })
</script>