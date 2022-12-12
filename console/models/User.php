<?php

namespace market\console\models;

class User extends \market\common\models\User
{
    public string $password = '';

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(static::getStatusList())],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    public function beforeSave($insert): bool
    {
        if ($this->password) {
            $this->updatePassword($this->password);
        }
        return parent::beforeSave($insert);
    }
}
