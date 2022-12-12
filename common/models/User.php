<?php

namespace market\common\models;

use DateTime;
use market\common\components\IpBehaviour;
use market\common\helpers\DateHelper;
use market\common\queries\UserQuery;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\di\NotInstantiableException;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_ip
 * @property int $updated_ip
 * @property int $created_by
 * @property int $updated_by
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public static function find(): UserQuery
    {
        return Yii::$container->get(UserQuery::class, [static::class]);
    }

    /**
     * @return array<int, string>
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Активно'),
            self::STATUS_DELETED => Yii::t('app', 'Удалено'),
        ];
    }

    public static function tableName(): string
    {
        return 'user';
    }

    public static function findIdentity($id): ?static
    {
        static $result = [];
        if (!array_key_exists($id, $result)) {
            $result[$id] = static::findOne($id);
        }
        return $result[$id];
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Имя пользователя'),
            'email' => Yii::t('app', 'E-mail'),
            'status' => Yii::t('app', 'Статус'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    $timezone = DateHelper::getDefaultTimeZone();
                    return (new DateTime('now', $timezone))->format('Y-m-d H:i:s');
                }
            ],
            IpBehaviour::class => [
                'class' => IpBehaviour::class,
            ],
            AttributeTypecastBehavior::class => [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterValidate' => false,
                'attributeTypes' => [
                    'id' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'status' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'created_by' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'updated_by' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'created_ip' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'updated_ip' => AttributeTypecastBehavior::TYPE_INTEGER,
                ],
            ],
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString(64);
    }

    public function updatePassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(static::class, ['id' => 'created_by']);
    }

    public function getEditor(): ActiveQuery
    {
        return $this->hasOne(static::class, ['id' => 'updated_by']);
    }
}
