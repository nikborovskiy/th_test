<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "user".
 *
 * @property int $id #
 * @property string|null $username Никнейм
 * @property float|null $balance Баланс
 * @property int|null $status Статус
 * @property string|null $created_at Время создания
 * @property string $updated_at Время изменения
 *
 * @property UserTransaction[] $userTransactions
 * @property UserTransaction[] $userTransactions0
 */
class User extends \yii\db\ActiveRecord
{
    const SCENARIO_NEW_USER = 'new_user';

    // максимально допустимый минус
    const USER_MAX_CREDIT = -1000;

    const STATUS_ACTIVE = 1;

    /**
     * Получение названий статсов для выпадающего списка
     * или получение названия статуса по его идентификатору
     * @param null $status
     * @return array|mixed|null
     */
    public static function getStatus($status = null)
    {
        if (null !== $status) {
            $data = self::getStatus();
            return isset($data[$status]) ? $data[$status] : null;
        }

        return [
            self::STATUS_ACTIVE => 'active'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance'], 'number', 'min' => -1000],
            [['balance'], 'default', 'value' => 0, 'on' => self::SCENARIO_NEW_USER],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE, 'on' => self::SCENARIO_NEW_USER],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'min' => 3, 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'username' => 'Никнейм',
            'balance' => 'Баланс',
            'status' => 'Статус',
            'created_at' => 'Время создания',
            'updated_at' => 'Время изменения',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Поиск пользователя по username
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        $result = null;
        $model = User::find()->where(['username' => $username])->one();
        if (!$model) {
            $model = self::addNewUserByUsername($username);
        }
        if ($model) {
            $result = [
                'id' => $model->id,
                'username' => $model->username,
                'status' => $model->status,
                'balance' => $model->balance,
            ];
        }
        return $result;
    }

    /**
     * Добавление нового пользователя с $username
     * @param $username
     * @return User|null
     */
    public static function addNewUserByUsername($username)
    {
        $model = new self();
        $model->setScenario(self::SCENARIO_NEW_USER);
        $model->username = $username;
        if (!$model->save()) {
            Yii::$app->getSession()->setFlash('error', implode("\r\n", $model->getFirstErrors()));
            return null;
        }
        return $model;
    }

    /**
     * Gets query for [[UserTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTransactions()
    {
        return $this->hasMany(UserTransaction::className(), ['from_user' => 'id']);
    }

    /**
     * Gets query for [[UserTransactions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTransactions0()
    {
        return $this->hasMany(UserTransaction::className(), ['to_user' => 'id']);
    }
}
