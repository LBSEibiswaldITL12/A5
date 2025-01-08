<?php

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    public function index()
    {
        $chats = MessageModel::getAllChats();

        foreach ($chats as $item) {
            if (!isset($item->name)) {
                $moreChats = MessageModel::getChatByChatId($item->chatId);
                foreach ($moreChats as $chat) {
                    if ($chat->userId == Session::get('user_id')) {
                        if (($key = array_search($chat, $moreChats)) !== false) {
                            unset($moreChats[$key]);
                        }
                    }
                }
                foreach ($moreChats as $chat) {
                    $name = $chat->user_name;
                    break;
                }
                $item->name = $name;
            }
        }

        $this->View->render('message/index', array(
            'messages' => $chats
        ));
    }

    public function chat($id)
    {
        $messages = MessageModel::getMessagesByChatId($id);

        $data = [
            "chat_id" => $id,
            "messages" => $messages,
        ];

        $this->View->render('message/chat', array(
            'messages' => $data
        ));
    }

    public function create()
    {
        $id = Request::post('chat_id');
        MessageModel::createMessage($id, Request::post('message'));
        Redirect::to('message/chat/' . $id);
    }
}
