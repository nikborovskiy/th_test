<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SendMoneyForm
 */
class SendMoneyForm extends Model
{
    public $from_user;
    public $to_user;
    /** @var User|null */
    public $value;
    public $users;

    /**
     * Получение данных из запроса, установка данных
     * @param array $data
     * @param null $formName
     * @return bool
     * @throws \Throwable
     */
    public function load($data, $formName = null)
    {
        if ($this->to_user && $this->to_user !== Yii::$app->getUser()->getIdentity()->username) {
            /** @var User $model */
            $model = User::find()->where(['username' => $this->to_user])->one();
            if (!$model) {
                $this->to_user = null;
            }
        }

        $this->from_user = Yii::$app->getUser()->getIdentity()->username;

        $this->users = \app\models\User::find()
            ->select(['username as value', 'username as label'])
            ->where('id <> :id')
            ->params([':id' => Yii::$app->getUser()->getId()])
            ->asArray()
            ->all();
        return parent::load($data, $formName);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['to_user', 'value'], 'required'],
            ['to_user', 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'username'],
            [['value'], 'prepareBalanceValue'],
            [['value'], 'number'],
            [['from_user'], 'safe'],
        ];
    }

    /**
     * Замена запятой на точку
     * @param $attribute
     * @param $params
     */
    public function prepareBalanceValue($attribute, $params)
    {
        if (!empty($this->$attribute) || $this->$attribute == 0) {
            $this->$attribute = str_replace(',', '.', $this->$attribute);
            if ($this->$attribute <= 0) {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' must be > 0');
            }
            /** @var User $fromUserModel */
            $fromUserModel = User::find()->where(['id' => Yii::$app->getUser()->getId()])->one();
            if (($fromUserModel->balance - $this->$attribute) < User::USER_MAX_CREDIT) {
                $this->addError(
                    $attribute,
                    'Not enough money. Your maximum sum to send:' . ((User::USER_MAX_CREDIT * -1) + $fromUserModel->balance)
                );
            }
        }
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'to_user' => 'target',
            'value' => 'sum',
        ];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function attributeHints()
    {
        return [
            'to_user' => 'tape min 3 symbols for autocomplete'
        ];
    }

    /**
     * Сохранение данных формы перевода денег
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $errors = null;
            $db = Yii::$app->getDb();
            $db->beginTransaction();
            /** @var User $toUserModel */
            $toUserModel = User::find()->where(['username' => $this->to_user])->one();
            /** @var User $fromUserModel */
            $fromUserModel = User::find()->where(['username' => $this->from_user])->one();
            if ($toUserModel) {
                /** @var UserTransaction $userTransactionModel */
                $userTransactionModel = new UserTransaction();
                $userTransactionModel->setAttributes([
                    'from_user' => Yii::$app->getUser()->getId(),
                    'to_user' => $toUserModel->id,
                    'value' => $this->value
                ]);
                if ($userTransactionModel->save()) {
                    $toUserModel->balance = $toUserModel->balance + $this->value;
                    $fromUserModel->balance = $fromUserModel->balance - $this->value;
                    if (!$toUserModel->save() || !$fromUserModel->save()) {
                        $errors = $toUserModel->getFirstErrors() + $fromUserModel->getFirstErrors();
                    }
                } else {
                    $errors = 'Error: '.$userTransactionModel->getFirstErrors();
                }
            } else {
                $errors[] = 'Error: target user not found';
            }

            if ($errors) {
                $db->getTransaction()->rollBack();
                Yii::$app->getSession()->setFlash('error', implode("\r\n", $errors));
            } else {
                Yii::$app->getSession()->setFlash('success', 'operation completed successfully');
                $db->getTransaction()->commit();
                return true;
            }
        }
        return false;
    }
}
