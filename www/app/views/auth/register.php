<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Регистрация</h1>

            <form action="#" method="post">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Логин">
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password"  placeholder="Пароль">
                </div>
                <br><hr>

                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Имя">
                </div>
                <div class="form-group">
                    <label for="surname">Фамилия</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Фамилия">
                </div>
                <div class="form-group">
                    <label for="age">Возраст</label>
                    <input type="number" class="form-control" id="age" name="age" placeholder="Возраст">
                </div>
                <div class="form-group">
                    <label for="gender">Пол</label>
                    <select name="gender" class="form-control">
                        <option disabled>Выберите пол</option>
                        <option value="1">Мужской</option>
                        <option value="2">Женский</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="interests">Интересы</label>
                    <input type="text" class="form-control" id="interests" name="interests" placeholder="Интересы">
                </div>
                <div class="form-group">
                    <label for="city">Город</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="Город">
                </div>

                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>

        </div>
    </div>
</div>