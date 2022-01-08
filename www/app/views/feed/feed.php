<?php die; $user = $data['user']; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Друзья</h1>
            <p>
                <?php if ( ! empty($data['friends']) ): ?>
                    <?php foreach ($data['friends'] as $friend): ?>
                        <div class="card mb-8" style="max-width: 940px; margin-top: 50px;">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <svg class="bd-placeholder-img img-fluid rounded-start" width="100%" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Avatar" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"/><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Avatar</text></svg>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="/user/profile?id=<?= $friend['id']; ?>"><?= $friend['name'] ?> <?= $friend['surname'] ?></a></h5> <br>
                                        <p class="card-text">
                                            <b>Возраст:</b> <?= $friend['age'] ?> <br>
                                            <b>Пол:</b> <?= $friend['gender'] == 1 ? 'Мужской' : 'Женский';  ?> <br>
                                            <b>Город:</b> <?= $friend['city'] ?> <br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h6>Друзей пока нет. </h6>
                <?php endif; ?>
            </p>


        </div>
    </div>
</div>