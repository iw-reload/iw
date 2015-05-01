<?php

use tests\codeception\frontend\FunctionalTester;
use tests\codeception\frontend\_pages\UserListPage;

$I = new FunctionalTester($scenario);

$I->wantTo('ensure that reading the user-list works');

$I->amGoingTo('not filter the list of users');
$userListPage = UserListPage::openBy($I,[
  'term' => '',
]);
$I->expectTo('see all users');
$I->see('HSINC');
$I->see('bwoester');

$I->amGoingTo('filter the list of users');
$userListPage = UserListPage::openBy($I,[
  'term' => 'b',
]);
$I->expectTo('not see HSINC');
$I->expectTo('see bwoester');
$I->dontSee('HSINC');
$I->see('bwoester');
