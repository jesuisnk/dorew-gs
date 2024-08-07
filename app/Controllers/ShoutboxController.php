<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Controllers;

use System\Classes\Controller;
use System\Classes\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Shoutbox;
use App\Services\DocService;

class ShoutboxController extends Controller
{
    /*
     * Note: giải thích cách hoạt động của cái chat này - éo ghi lại sao này éo nhớ nổi
     * * 1. load all chat ở chat_list
     * * 2. lấy tổng ID chat hiện tại - totalChat
     * * 3. Auto refesh kiểm tra tổng số chat hiện tại - nowChat - fetch ở chat_count
     * * 4. Nếu nowChat lớn hơn totalChat thì thêm các chat mới - từ chat_ele
     * * 5. xóa phần tử chat cuối cùng
     * * A. Ưu điểm của chat: có thể xem được video mới, có thể copy được nội dung chat trên điện thoại
     * * B. Bug đã biết: chat nhanh quá thì bị cập nhật sai =))
     * Dai (agwinao) 2-2-2022 ~ code từ mùng 1 đến mùng 2 Tết
     */

    protected $ChatCount = 0;

    public function __construct(
        // duyệt các lớp Model
        protected Category $CategoryModel,
        protected User $UserModel,
        protected Shoutbox $ShoutboxModel,

        // duyệt các lớp Service
        protected DocService $DocService,
    ) {
        $this->ChatCount = $this->CategoryModel->ForumStats()['count_chat'];
    }

    public function ChatSend(Request $request)
    {
        $result = [];
        $error = false;
        if (!$request->user()->isLogin) {
            $error = true;
            $result = ['status' => 'error', 'result' => 'Chức năng này chỉ dành cho người dùng đã đăng nhập!'];
        }

        $msg = $request->postVar('msg', '');
        $msg = $content = $this->DocService->TrimContent($msg);
        $msg_len = mb_strlen($msg);
        if (!$error) {
            $user_id = $request->user()->id;
            $user = $this->UserModel->UserDetailWithFields('id', $user_id);
            if (empty($msg)) {
                $result = ['status' => 'error', 'result' => 'Vui lòng nhập nội dung!'];
            } elseif ($msg_len < 5 || $msg_len > 300) {
                $result = ['status' => 'error', 'result' => 'Độ dài văn bản được nhập không hợp lệ'];
            } else {
                $this->ShoutboxModel->ChatSend($user, $msg);
                $result = ['status' => 'success', 'result' => 'Đã gửi'];
            }
        }

        return view('shoutbox/chat_send', [
            'chat_send_result' => json_encode($result)
        ]);
    }

    public function ChatHome(Request $request)
    {
        $this->UserModel->isLoginredirect($request);
        return view('shoutbox/chat_home', [
            'chat_count' => $this->ChatCount
        ]);
    }

    public function ChatCount(Request $request)
    {
        return view('shoutbox/chat_count', [
            'chat_count' => $this->ChatCount
        ]);
    }

    public function ChatEle(Request $request)
    {
        $id_chat = $request->getVar('chatID', 1);
        $ChatDetail = $this->ShoutboxModel->ChatDetail($id_chat);
        $ChatDetail['comment'] = $this->DocService->bbcode($ChatDetail['comment']);
        $UserDetail = $this->UserModel->UserDetailWithFields('nick', $ChatDetail['name']);
        $UserDetail['online_status'] = $this->UserModel->UserOnlineStatus($UserDetail);
        return view('shoutbox/chat_ele', [
            'ChatDetail' => $ChatDetail,
            'UserDetail' => $UserDetail
        ]);
    }

    public function ChatList(Request $request)
    {
        $ChatCount = $this->CategoryModel->ForumStats()['count_chat'];
        # số bài viết có trong 1 trang
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
            $page = 1;
        }

        $page_max = ceil($ChatCount / $per);
        $start = ($page - 1) * $per;
        $end = $start + $per;
        if ($end >= $ChatCount) {
            $end = $ChatCount;
        }
        # lấy danh sách comment
        $getChatList = $this->ShoutboxModel->ChatList($start, $end);
        $ChatList = [];
        foreach ($getChatList as $ChatDetail) {
            $name = mb_strtolower($ChatDetail['name']);
            $ChatDetail['UserDetail'] = $this->UserModel->UserDetailWithFields('nick', $name);
            $ChatDetail['UserDetail']['online_status'] = $this->UserModel->UserOnlineStatus($ChatDetail['UserDetail']);
            $ChatDetail['comment'] = $this->DocService->bbcode($ChatDetail['comment']);

            $ChatList[] = $ChatDetail;
        }

        return view('shoutbox/chat_list', [
            'ChatList' => $ChatList
        ]);
    }

}