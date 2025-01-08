<div class="container">
    <h1>Chats</h1>
    <div class="box">
        <section class="discussion">
            <?php foreach ($this->messages["messages"] as $item) { ?>
                <?php if ($item->senderId == Session::get('user_id')) { ?>
                    <div class="bubble sender middle">
                        <p class="chat-message"><?= $item->message ?></p>
                        <p class="chat-message-date"><?= $item->createTime ?></p>
                    </div>
                <?php } else { ?>
                    <div class="bubble recipient middle">
                        <p class="chat-message"><?= $item->message ?></p>
                        <p class="chat-message-date"><?= $item->createTime ?> <?= $item->user_name ?></p>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
        <div class="discussion-footer">
            <form method="post" action="<?php echo Config::get('URL'); ?>message/create">
                <input type="hidden" name="chat_id" value="<?= $this->messages["chat_id"] ?>" />
                <input type="text" name="message" class="chat-input" />
                <input type="submit" name="submit" class="chat-send" value="Send" />
            </form>
        </div>
    </div>
</div>