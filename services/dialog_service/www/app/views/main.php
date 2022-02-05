<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1>Социальная сеть</h1>

            <p>
                <?php if ( ! empty($data['users']) ): ?>
                    <h4>Список пользователей</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Фамилия</th>
                            <th scope="col">Возраст</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $num = 1; foreach ($data['users'] as $user): ?>
                            <tr>
                                <th scope="row"><?= htmlentities($user['id']); ?></th>
                                <td><?= htmlentities($user['name']); ?></td>
                                <td><?= htmlentities($user['surname']); ?></td>
                                <td><?= htmlentities($user['age']); ?></td>
                                <td><?php if (isset($data['currentUser']) && !empty($data['currentUser']) && $user['id'] == $data['currentUser']['id'] ): ?>
                                        <a href="/user/profile">Ваша страница</a>
                                    <?php else: ?>
                                        <a href="/user/profile?id=<?= $user['id']; ?>">Посмотреть профиль</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php $num++; endforeach; ?>
                        </tbody>
                    </table>

            <p>Страница <?= $data['page']; ?></p>
            <p><?php $prev = $data['page'] > 1 ? $data['page']-1 : 0; ?>
                <?php if ( $prev > 0 ): ?>
                    <a href="/?page=<?= $prev; ?>">Назад</a>
                <?php endif; ?> |
                <?php $next = $data['page']+1; ?>
                <?php if ( $next > 0 ): ?>
                    <a href="/?page=<?= $next; ?>">Вперед</a>
                <?php endif; ?>
            </p>
                <?php else: ?>
                    <h6>Зарегистрируйтесь, чтобы увидеть список пользователей. </h6>
                <?php endif; ?>
            </p>

        </div>
    </div>
</div>