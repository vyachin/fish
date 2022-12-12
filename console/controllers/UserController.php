<?php

namespace market\console\controllers;

use market\console\models\User;
use yii\console\Controller;
use yii\helpers\VarDumper;

class UserController extends Controller
{
    public function actionCreate(): void
    {
        $user = new User();
        $user->name = $this->prompt('Name: ');
        $user->email = $this->prompt('Email: ');
        $user->password = $this->prompt('Password: ');
        $user->generateAuthKey();
        if (!$user->save()) {
            $this->stdout("Error\n");
            $this->stderr(VarDumper::dumpAsString($user->errors) . "\n");
        } else {
            $this->stdout("Success\n");
        }
    }
}
