<?php

class MessageModel
{
    public static function getMessagesByChatId($id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
            FROM messages
            INNER JOIN users ON messages.senderId=users.user_id;
            WHERE chatId = :chat_id
            ORDER BY createTime ASC";
        $query = $database->prepare($sql);
        $query->execute(array(':chat_id' => $id));

        return $query->fetchAll();
    }

    public static function getAllChats()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT u.id, u.userId, u.chatId, c.name
            FROM userToMsgChat as u
            INNER JOIN chat as c ON  u.chatId = c.id
            WHERE userId = :user_id";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        return $query->fetchAll();
    }

    public static function getChatByChatId($chatId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT c.id, c.userId, u.user_name 
            FROM userToMsgChat as c
            INNER JOIN users as u ON  c.userId = u.user_id
            WHERE chatId = :chat_id";
        $query = $database->prepare($sql);
        $query->execute(array(':chat_id' => $chatId));

        return $query->fetchAll();
    }

    public static function createMessage($id, $note_text)
    {
        if (!$note_text || strlen($note_text) == 0) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO messages (chatId, senderId, message) 
        VALUES (:chat_id, :sender_id, :message)";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':chat_id' => $id,
            ':sender_id' => Session::get('user_id'),
            ':message' => $note_text
        ));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    public static function updateMessage($id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE messages SET wasRead=1 WHERE id=$id";
        $query = $database->prepare($sql);
        $query->execute();

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }
}
