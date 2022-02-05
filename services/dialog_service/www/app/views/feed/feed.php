<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="/feed/add">Добавить новость</a>
        </div>
        <div class="col-md-12">
            <h1>Новости</h1>
            <p id="mesto" >
                <?php if ( ! empty($data['posts']) ): ?>
                    <?php foreach ($data['posts'] as $post): ?>
                        <div class="card mb-8" style="max-width: 940px; margin-top: 50px;">
                            <div class="row g-0">
                                <div class="col-md-12">
                                    <b><?= $post ?></b> <br>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h6>Новостей друзей пока нет. </h6>
                <?php endif; ?>
            </p>


        </div>
    </div>
</div>
<script>
    let ws = new WebSocket('ws://127.0.0.1:61523');

    ws.addEventListener('message', (event) => {
        var div = document.createElement("div");
        div.innerHTML = '<div class="card mb-8" style="max-width: 940px; margin-top: 50px;"> \
        <div class="row g-0"> \
        <div class="col-md-12"><b>'+event.data+'</b><br></div> \
        </div> \
        </div>';
        document.getElementById("mesto").prepend(div);

        console.info('Got message: ' + event.data); // get from server
    })

    const func = () => {
        ws.send('<?= $data['currentUser']['id']; ?>'); //send on server
    };
    setTimeout(func, 2 * 1000);
</script>