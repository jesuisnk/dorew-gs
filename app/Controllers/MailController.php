<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace App\Controllers;

use System\Classes\Controller;
use System\Classes\Request;
use App\Models\User;
use App\Models\Mail;
use App\Services\DocService;

class MailController extends Controller
{
    public function __construct(
        // duyệt các lớp Model
        protected User $UserModel,
        protected Mail $MailModel,

        // duyệt các lớp Service
        protected DocService $DocService,
    ) {
    }

    public function MailList(Request $request)
    {
        if (!$request->user()->isLogin) {
            redirect('/');
        }
        $MyDetail = $request->user()->user;
        // lấy danh sách tin nhắn đã gửi đến các Receiver của Sender từ bảng `users`
        $getMailList = $this->MailModel->MailList($MyDetail);
        // phân trang
        $page_query = '?page=';
        $MailCount = count($getMailList);
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        $page_max = ceil($MailCount / $per);
        $start = ($page - 1) * $per;
        // lấy danh sách Receiver của Sender
        $getReceiverList = array_slice($getMailList, $start, $per);
        $MailList = [];
        foreach ($getReceiverList as $Receiver) {
            $Receiver = mb_strtolower($Receiver);
            $MailDetail['ReceiverDetail'] = $this->UserModel->UserDetailWithFields('nick', $Receiver);
            $LatestMailDetail = $this->MailModel->LatestMailDetail($MyDetail['nick'], $Receiver);
            $MailDetail['LatestMailDetail'] = $LatestMailDetail;
            $LastSender = mb_strtolower($LatestMailDetail['nick']);
            $MailDetail['SenderDetail'] = $this->UserModel->UserDetailWithFields('nick', $LastSender);
            $lastContent = $LatestMailDetail['content'];
            $slicedContent = substr($lastContent, 0, 150);
            if (strlen($lastContent) > 150) {
                $slicedContent .= '...';
            }
            $MailDetail['LatestMailDetail']['content'] = $slicedContent;
            $MailDetail['MailCount'] = $this->MailModel->MailCount($MyDetail['nick'], $Receiver);

            $MailList[] = $MailDetail;
        }
        $MailListPaging = paging($page_query, $page, $page_max);

        return view()
            ->setTitle('Tin nhắn')
            ->render('mail/mail_list', [
                'MyDetail' => $MyDetail,
                'MailList' => $MailList,
                'MailListPaging' => $MailListPaging,
                'MailCount' => $MailCount
            ]);
    }

    public function MailSend($uri_receiver, Request $request)
    {
        if (!$request->user()->isLogin) {
            redirect('/');
        }

        // lấy thông tin người gửi và người nhận
        $uri_receiver = mb_strtolower($uri_receiver);
        $SenderDetail = $request->user()->user;
        $ReceiverDetail = $this->UserModel->UserDetailWithFields('nick', $uri_receiver);
        if (!$ReceiverDetail || $SenderDetail['nick'] == $ReceiverDetail['nick']) {
            redirect('/404');
        }
        // lấy blocklist của người gửi và người nhận
        $SenderDetailBlockList = $this->UserModel->UserDetailBlockList($SenderDetail)['Get'];
        $ReceiverDetailBlockList = $this->UserModel->UserDetailBlockList($ReceiverDetail)['Get'];
        // block khi ngứa mắt
        $mod = $request->getVar('mod', '');
        $isSenderBlock = false;
        $isReceiverBlock = false;
        if (
            in_array($SenderDetail['nick'], $ReceiverDetailBlockList)
            || $ReceiverDetail['nick'] == env('UserBot')
        ) {
            $isReceiverBlock = true;
        }
        if (in_array($ReceiverDetail['nick'], $SenderDetailBlockList)) {
            $isSenderBlock = true;
        }
        if ($mod == 'blocklist') {
            $this->MailModel->MailBlocked($SenderDetail, $ReceiverDetail);
            redirect('/mail/send/' . $ReceiverDetail['nick']);
        }

        // phân trang
        $MailCount = $this->MailModel->MailCount($SenderDetail['nick'], $ReceiverDetail['nick']);
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
            $page = 1;
        }
        $page_max = ceil($MailCount / $per);
        $start = ($page - 1) * $per;

        $getMailList = $this->MailModel->MailDetailList($SenderDetail['nick'], $ReceiverDetail['nick'], $per, $start);
        $MailList = [];
        foreach ($getMailList as $MailDetail) {
            $nick = mb_strtolower($MailDetail['nick']);
            $MailDetail['UserDetail'] = $this->UserModel->UserDetailWithFields('nick', $nick);
            $MailDetail['content'] = $this->DocService->bbcode($MailDetail['content']);

            $MailList[] = $MailDetail;
        }
        $MailPaging = paging('?page=', $page, $page_max);

        // Xử lý tin nhắn được gửi đi
        $error = null;
        $content = $request->postVar('content', '');
        $content = $this->DocService->TrimContent($content);
        $content_len = mb_strlen($content);
        if (!$isSenderBlock && !$isReceiverBlock) {
            if ($request->getMethod() === 'POST') {
                // reset token
                $token = $request->postVar('csrf_token', '');
                $checktoken = isCSRFTokenValid($token);
                if ($checktoken) {
                    $error = 'Invalid token';
                }
                unsetCSRFToken();
                generateCSRFToken();

                if (empty($content)) {
                    $error = 'Tin nhắn gửi đi không được bỏ trống';
                }
                if ($content_len < 4 || $content_len > 1200) {
                    $error = 'Độ dài văn bản không hợp lệ';
                }
                if (!$error) {
                    $this->MailModel->MailSend($SenderDetail, $ReceiverDetail, $content);
                    redirect($_SERVER['REQUEST_URI']);
                }
            }
        }

        // đánh dấu là đã đọc
        $viewClosure = function ($mail_id) {
            $self = $this;
            return $self->MailModel->MailViewUpdate($mail_id);
        };

        return view()
            ->setTitle('Tin nhắn: ' . $ReceiverDetail['name'])
            ->render('mail/mail_send', [
                'SenderDetail' => $SenderDetail,
                'ReceiverDetail' => $ReceiverDetail,
                'SenderDetailBlockList' => $SenderDetailBlockList,
                'ReceiverDetailBlockList' => $ReceiverDetailBlockList,
                'MailList' => $MailList,
                'MailPaging' => $MailPaging,

                'error' => $error,
                'content' => $content,
                'view' => $viewClosure,

                'isSenderBlock' => $isSenderBlock,
                'isReceiverBlock' => $isReceiverBlock
            ]);
    }

    public function MailSystem(Request $request)
    {
        if (!$request->user()->isLogin) {
            redirect('/');
        }
        $MyDetail = $request->user()->user;

        // phân trang
        //$SystemNotifyCount = $request->user()->system_notify_count;
        $SystemNotifyCount = $this->MailModel->MailSystemCount($MyDetail);
        $per = 10;
        $page = $request->getVar('page', 1);
        $page = htmlspecialchars($page);
        $page = intval($page);
        if (preg_match('/[a-zA-Z]|%/', $page) || $page < 1) {
            $page = 1;
        }
        $page_max = ceil($SystemNotifyCount / $per);
        $start = ($page - 1) * $per;

        // lấy dữ liệu từ model
        $MailSystemListPaging = paging('?page=', $page, $page_max);
        $getMailSystemList = $this->MailModel->MailSystemList($MyDetail, $per, $start);
        $MailSystemList = [];
        foreach($getMailSystemList as $MailSystemDetail) {
            $MailSystemDetail['content'] = $this->DocService->bbcode($MailSystemDetail['content']);
            $MailSystemList[] = $MailSystemDetail;
        }

        // thao tác đánh dấu đã xem và xóa sạch
        $mod = $request->getVar('mod', '');
        if ($mod == 'view') {
            // đánh dấu là đã xem
            $this->MailModel->MailSystemView($MyDetail);
            redirect('/mail/system');
        } elseif ($mod == 'clear') {
            // xóa sạch
            $this->MailModel->MailSystemClear($MyDetail);
            redirect('/mail/system');
        }

        // return vỉew
        return view()
            ->setTitle('Thông báo')
            ->render('mail/mail_system', [
                'MyDetail' => $MyDetail,
                'MailSystemList' => $MailSystemList,
                'MailSystemListPaging' => $MailSystemListPaging,
                'MailSystemCount' => $SystemNotifyCount
            ]);
    }
}