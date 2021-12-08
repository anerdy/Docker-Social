<?php $user = $data['user']; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1>Профиль</h1>

            <div class="card mb-8" style="max-width: 940px; margin-top: 100px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <svg class="bd-placeholder-img img-fluid rounded-start" width="100%" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Avatar" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"/><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Avatar</text></svg>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $user['name'] ?> <?= $user['surname'] ?></h5> <br>
                            <p class="card-text">
                                <b>Возраст:</b> <?= $user['age'] ?> <br>
                                <b>Пол:</b> <?= $user['gender'] == 1 ? 'Мужской' : 'Женский';  ?> <br>
                                <b>Интересы:</b> <?= $user['interests'] ?> <br>
                                <b>Город:</b> <?= $user['city'] ?> <br>
                            </p>
                            <?php if ( ! $data['isCurrentUser']): ?>
                                <a href="/chat/dialog?id=<?= $user['id'] ?>" class="btn btn-primary" >Написать</a>
                                <?php if ( $data['isFriend']): ?>
                                    <form action="#" method="post">
                                        <p class="card-text">
                                            <small class="text-muted">У вас в друзьях.</small>
                                            <br><br>
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <input type="hidden" name="delete" value="1">
                                            <input class="btn btn-secondary" type="submit" value="Удалить из друзей">
                                        </p>
                                    </form>
                                <?php else: ?>
                                    <form action="#" method="post">
                                        <p class="card-text">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <input class="btn btn-primary" type="submit" value="Добавить в друзья">
                                        </p>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="card-text"><small class="text-muted">Это Вы.</small></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>