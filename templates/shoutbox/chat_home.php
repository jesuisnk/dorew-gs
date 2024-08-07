<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-shoutbox.css') ?>" rel="stylesheet" />

<div class="col-md-8 col-sm-8 col-xs-12 col-mb-12" id="post-wrapper">
    <div class="main-blog cl" style="margin-bottom:20px">
        <b class="main-title"><i class="fa fa-weixin" aria-hidden="true"></i><span>Trò chuyện</span></b>
        <div id="chat-place">
            <form id="form" action method="POST" name="form">
                <?php include (dirname(dirname(__FILE__)) . '/toolbar.php') ?>
                <textarea id="postText" rows="3" class="form-control"></textarea>
                <button id="submit" type="submit" class="btn btn-success">Chat</button>
            </form>
        </div>
        <div class="overflow-chatbox">
            <div id="postText"></div>
            <div id="idChat"></div>
        </div>
        <center>
            <div class="pagination" id="phan-trang"></div>
            <div style="margin-top: 3px; margin-bottom: 10px;">
                <input style="padding: 0px; margin-right: 15px; width: 60px;" type="number" id="ano" name="page" min="1"
                    max="10">
                <input type="submit" class="pagenav" value="Go" onclick="getPage(totalChat)">
            </div>
        </center>
    </div>
</div>
<script type="text/javascript">
    var totalChat = "<?= $chat_count ?>";
    var pageID = 1;
    var chatbox = "<?= url('/shoutbox/list') ?>",
        chat_send = "<?= url('/shoutbox/send') ?>",
        chat_count = "<?= url('/shoutbox/count') ?>",
        chat_ele = "<?= url('/shoutbox/ele?chatID=') ?>",
        chat_list = "<?= url('/shoutbox/list?page=') ?>";
</script>
<script type="text/javascript" src="<?= url('/js/shoutbox.js') ?>"></script>

<?php include (dirname(dirname(__FILE__)) . '/sidebar.php') ?>