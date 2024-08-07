<?php if ($ChatDetail && $UserDetail): ?>
    <div class="postItem">
        <div class="memInfo">
            <table id="<?= $ChatDetail['id'] ?>" cellpadding="0" cellspacing="1">
                <tr>
                    <td width="auto" style="position:relative">
                        <img class="avt" src="<?= getAvtUser($UserDetail) ?>" />
                        <span class="status-indicator <?=$UserDetail['online_status']?>"></span>
                    </td>
                    <td>
                        <?= RoleColor($UserDetail) ?> <a href="javascript:tag('@<?=$UserDetail['nick']?> ', '')"><small><i class="fa fa-tag" aria-hidden="true"></i></small></a>
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
<?php endif ?>