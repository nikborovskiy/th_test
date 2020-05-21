<?php

namespace app\models;

class UserIdentity extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $status;
    public $balance;
    public $authKey;
    public $accessToken;

    /**
     * Используя модель User подготавливаем данные
     * @param $model
     * @return array|null
     */
    public static function prepareDataFromUserModel($model)
    {
        $result = null;
        if (null !== $model) {
            $result = [
                'id' => $model->id,
                'username' => $model->username,
                'status' => $model->status,
                'balance' => $model->balance,
                'authKey' => $model->username,
            ];
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $result = null;
        $model = User::find()->where(['id' => $id])->one();
        if ($model) {
            $result = static::prepareDataFromUserModel($model);
        }
        return $result ? new static($result) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = User::findByUsername($username);
        if (null !== $user) {
            $user['authKey'] = $username;
            return new static($user);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
