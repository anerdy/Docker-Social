<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1>Добавить сообщение</h1>

                <form action="#" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Сообщение</label>
                                <input type="text" class="form-control" id="message" name="message" placeholder="message" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>

        </div>
    </div>
    <div class="row">
        <h1>Чат</h1>
        <div class="col-md-12">
            <?php $num = 1; foreach ($data['messages'] as $message): ?>
                <?php if ( $message['author_id'] == $data['currentUser']['id'] ): ?>
                    <figure>
                <?php else: ?>
                    <figure class="text-right">
                <?php endif; ?>
                        <blockquote class="blockquote">
                            <p><?= $message['message'] ?></p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            <?= $message['author_id']?>
                        </figcaption>
                    </figure>
            <?php $num++; endforeach; ?>

        </div>


    </div>
</div>