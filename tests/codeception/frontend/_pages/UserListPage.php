<?php

namespace tests\codeception\frontend\_pages;

use yii\codeception\BasePage;

/**
 * Represents userList page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class UserListPage extends BasePage
{
    public $route = 'site/userlist';
}
