<?php foreach ($ChatList as $ChatDetail): ?>
    <div class="postItem">
        <div class="memInfo">
            <table id="<?= $ChatDetail['id'] ?>" cellpadding="0" cellspacing="1">
                <tr>
                    <td width="auto" style="position:relative">
                        <img class="avt" src="<?= getAvtUser($ChatDetail['UserDetail']) ?>" />
                        <span class="status-indicator <?=$ChatDetail['UserDetail']['online_status']?>"></span>
                    </td>
                    <td>
                        <?= RoleColor($ChatDetail['UserDetail']) ?> <a href="javascript:tag('@<?=$ChatDetail['UserDetail']['nick']?> ', '')"><small><i class="fa fa-tag" aria-hidden="true"></i></small></a>
                        <br /><time>lúc <?= ago($ChatDetail['time']) ?></time>
                    </td>
                </tr>
            </table>
        </div>
        <div class="chatbox">
            <span class="textchat"> </span>
            <?= $ChatDetail['comment'] ?>
        </div>
    </div>
<?php endforeach ?>