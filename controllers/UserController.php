<?php

namespace app\controllers;

use app\models\SendMoneyForm;
use Yii;
use app\models\User;
use app\models\SearchUser;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['send-money'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['send-money'],
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * отправка денег другому пользователю
     * @param null $username
     * @return string|\yii\web\Response
     */
    public function actionSendMoney($username = null)
    {
        /** @var SendMoneyForm $model */
        $model = new SendMoneyForm();
        $model->to_user = $username;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }
        return $this->render('send-money', ['model' => $model]);
    }
}
