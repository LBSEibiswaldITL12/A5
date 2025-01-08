<div class="container">
    <h1>Chats</h1>
    <div class="box">
        <div class="list-container">
            <?php foreach ($this->messages as $item) { ?>
                <a href="<?= Config::get('URL') . 'message/chat/' . $item->chatId; ?>">
                    <button type="submit" class="list-item">
                        <?= htmlspecialchars($item->name ?? 'Unnamed') ?>
                    </button>
                </a>
            <?php } ?>
        </div>
    </div>
</div>