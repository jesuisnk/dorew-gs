<?php $this->layout('layout'); ?>
<link href="<?= $this->asset('/css/in-profile.css') ?>" rel="stylesheet" />
<link href="<?= $this->asset('/css/in-post.css') ?>" rel="stylesheet" />

<div class="main-blog cl" style="margin-bottom:20px">
    <div class="breadcrumbs">
        <span><a href="<?= url('/users') ?>" title="Danh sách thành viên">Thành viên</a></span>
        &#187;
        <span><a href="<?= url('/user/' . $MyDetail['nick']) ?>" title="Trang cá nhân">Hồ sơ của tôi</a></span>
        &#187;
        <span>Chỉnh sửa thông tin</span>
    </div>
    <div class="main-blg section" id="edit-info">
        <?php if ($error): ?>
            <div class="alert alert-warning mb-3"><?= $error ?></div>
        <?php endif ?>
        <form id="form" method="post">
            <?php if ($action == 'info'): ?>
                <div class="text-center">
                    [ <a href="<?= url('/user/' . $MyDetail['nick']) . '/password' ?>">Thay đổi mật khẩu</a> |
                    <a href="<?= url('/user/' . $MyDetail['nick']) . '/blocklist' ?>">Danh sách chặn</a> ]
                    <hr />
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Tâm trạng:</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="<?= $status ?>" type="text" name="status"
                            value="<?= $status ?>" maxlength="100" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">
                        Ảnh đại diện:
                        <a href="<?= url('/user/' . $MyDetail['nick'] . '/avatar') ?>" title="Tải lên ảnh đại diện">[ Tải
                            lên
                            ]</a>
                    </label>
                    <div class="col-sm-8" style="padding:15px">
                        <?php for ($i = 1; $i <= 29; $i++): ?>
                            <input type="radio" name="avatar" value="<?= $i ?>" <?= trim($avatar) == $i ? 'checked' : '' ?> />
                            <img src="<?= showAvtUser($i) ?>" id="edit-avt">
                        <?php endfor; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Tên hiển thị:</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="<?= $name ?>" type="text" name="name" value="<?= $name ?>"
                            maxlength="32" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Giới tính:</label>
                    <div class="col-sm-8" style="line-height:150%">
                        <input type="radio" name="sex" value="boy" <?php if ($sex == 'boy'): ?> checked<?php endif ?>> Con
                        trai<br />
                        <input type="radio" name="sex" value="girl" <?php if ($sex == 'girl'): ?> checked<?php endif ?>> Con
                        gái<br />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                    <input type="submit" class="btn btn-success btn-block" value="Lưu lại" />
                </div>
            <?php elseif (in_array($action, ['avatar', 'cover', 'waifu-header', 'waifu-rleft', 'waifu-rright'])): ?>
                <div class="row">
                    <input style="display:none" type="file" id="SelectImage" accept="image/*">
                    <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                    <input type="hidden" name="<?= $action ?>" id="AvtCoverValue" value="" />
                    <button type="button" id="AvtCoverUpload" class="btn btn-success btn-block">
                        <i class="fa fa-upload" aria-hidden="true"></i> Chọn
                        <?php switch ($action) {
                            case 'avatar':
                                echo 'ảnh đại diện';
                                break;
                            case 'cover':
                                echo 'ảnh bìa';
                                break;
                            case 'waifu-header':
                                echo 'ảnh trên header';
                                break;
                            case 'waifu-rleft':
                                echo 'background bên trái';
                                break;
                            case 'waifu-rright':
                                echo 'background bên phải';
                                break;
                        } ?>
                    </button>
                </div>
                <script src="/js/upload-imgur.js"></script>
                <script>
                    document.querySelector("#AvtCoverUpload").onclick = function () {
                        document.querySelector("#SelectImage").click();
                    }
                    imgur("#SelectImage", {
                        loading: function (load) {
                            document.querySelector("#AvtCoverUpload").innerHTML = '<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> Đang tải lên / ' + load
                        },
                        loaded: function (link, size, type, time) {
                            $("#AvtCoverValue").val(link);
                            //console.log($("#AvtCoverValue").val());
                            document.getElementById("form").submit();
                        }
                    })
                </script>
            <?php elseif ($action == 'password'): ?>
                <div class="text-center">
                    <h3>Thay đổi mật khẩu</h3>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Mật khẩu hiện tại:</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Nhập mật khẩu hiện tại" type="password" name="old_password"
                            maxlength="32" required />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Mật khẩu mới:</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Nhập mật khẩu mới muốn thay đổi" type="password"
                            name="new_password" maxlength="32" required />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <label class="col-form-label col-sm-4">Xác nhận mật khẩu:</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Nhập lại mật khẩu mới" type="password" name="re_password"
                            maxlength="32" required />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                    <input type="submit" class="btn btn-success btn-block" value="Lưu lại" />
                </div>
            <?php else: ?>
                <div class="text-center">
                    <h3>Danh sách chặn</h3>
                </div>
                <?php if ($count_blocked > 0): ?>
                    <?php foreach ($blocklist as $UserDetail): ?>
                        <div class="row" style="padding-left:20px">
                            <input type="checkbox" name="block[]" value="<?= $UserDetail['nick'] ?>">
                            <?= RoleColor($UserDetail) ?> (Level: <?= $UserDetail['level'] ?>)
                        </div>
                    <?php endforeach ?>
                    <div class="row">
                        <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                        <input type="submit" class="btn btn-success btn-block" value="Bỏ chặn" />
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Trống!</div>
                <?php endif ?>
            <?php endif ?>
        </form>
    </div>
</div>