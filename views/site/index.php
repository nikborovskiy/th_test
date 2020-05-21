<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>test task</h1>


        <p>
            <a class="btn btn-lg btn-success" href="<?= Url::to(['user/index']) ?>">go to users list</a>
            <?php if (Yii::$app->user->isGuest): ?>
                or
                <a class="btn btn-lg btn-success" href="<?= Url::to(['site/login']) ?>">login</a>
            <?php endif; ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>EN</h2>

                <p>The test task can be performed at any time during 14 days after applying for a vacancy. The test
                    task is not paid.

                    The user can only use a unique nickname without a password for authorization / registration. If
                    there is no such user, then create it automatically and authorize. There is no separate
                    registration. A public page should be made with a list of all users and their current balance,
                    accessible without authorization.

                    For authorized users available:

                    The user can transfer any positive amount to another user (identification by nickname). In this
                    case, the user's balance is reduced by the specified amount. The balance may be negative. Balance
                    can not be less than -1000. The balance of all new users is by default 0. You can transfer any
                    amount (with two decimal places for cents) only to an existing nickname. The user can not do the
                    translation itself. Users in the database in the user table must have separate fields for balance.
                    For transfers, be sure to use transactions.

                    Use yii2 (latest stable version, basic project template). Installing database from migrations,
                    installing external plugins from composer with minimal stable stability. Code design in accordance
                    with the coding style and directory structure in yii2. The code should not have bugs, security
                    holes, violations of the planned logic. For development speed, use crud to create / edit / delete
                    objects, as well as other features of yii2. The code must be professional, supported, and
                    understandable.

                    Estimated lead time: 4-8 hours

                    Put the code on some online git repository. Put the working site online so that you can see the
                    result without installing the project locally.</p>

            </div>
            <div class="col-lg-6">
                <h2>RU</h2>

                <p>Тестовое задание можно выполнить в любое время на протяжении 14 дней после подачи заявки на
                    вакансию. Тестовое задание не оплачивается. Как альтернативу тестовому заданию вы можете
                    предоставить ссылку на ваш GitHub профиль с любым выполненным проектом такой же сложности и обьема.

                    Пользователь для авторизации/регистрации может использовать только уникальный никнейм без пароля.
                    Если такого пользователя нет, то создать его автоматически и авторизовать. Отдельной регистрации
                    нет. Баланс у всех новых пользователей по умолчанию 0. Должна быть сделана публичная страница со
                    списком всех пользователей и их текущим балансом, доступная без авторизации.

                    Авторизованный пользователь может перевести любую положительную сумму (с двумя знаками после запятой
                    для центов) другому существующему пользователю (идентификация по никнейму). При этом баланс
                    пользователя уменьшается на указанную сумму. Баланс может быть отрицательным. Баланс не может стать
                    меньше чем -1000. Пользователь не может делать перевод себе. У пользователей в базе в таблице
                    пользователей должно быть отдельное поля для баланса. Для переводов обязательно использовать
                    транзакции.

                    Используйте yii2 (последнюю стабильную версию, basic project template). Установка базы данных из
                    миграций, установка внешних плагинов из composer с минимальной стабильностью stable. Оформление кода
                    в соответствии со стилем кодирования и структурой каталогов в yii2. Код не должен иметь багов, дыр
                    безопасности, нарушений работы запланированной логики. Для скорости разработки, использовать crud
                    для создания/редактирования/удаления обьектов, а также другие возможности yii2. Код должен быть
                    профессиональным, поддерживаемым, и понятным.

                    Ориентировочное время выполнения: 4-8 часов

                    Код выложите на какой нибудь онлайн репозиторий git. Рабочий сайт выложите в онлайн чтобы можно было
                    посмотреть результат без установки проекта локально.

                    В ответе:</p>

            </div>
        </div>

    </div>
</div>
