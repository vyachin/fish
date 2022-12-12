<?php

namespace {

    use market\common\components\FileStorageComponent;
    use market\common\components\Formatter;
    use market\common\components\GoogleGeoCoder;
    use market\common\components\GoogleReCaptcha;
    use market\common\components\SmsPilot;
    use yii\BaseYii;
    use yii\web\User;

    require __DIR__ . '/../vendor/yiisoft/yii2/BaseYii.php';

    /**
     * @property-read Formatter $formatter
     * @property-read WebUser $user
     * @property-read FileStorageComponent $fileStorage
     */
    trait BaseApplication
    {
    }

    class Yii extends BaseYii
    {
        /**
         * @var WebApplication|ConsoleApplication the application instance
         */
        public static $app;
    }

    /**
     * Class WebUser
     *
     * @property-read \market\common\models\User $identity
     */
    class WebUser extends User
    {
    }

    class WebApplication extends yii\web\Application
    {
        use BaseApplication;
    }

    class ConsoleApplication extends yii\console\Application
    {
        use BaseApplication;
    }
}
