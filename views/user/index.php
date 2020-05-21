<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            [
                'attribute' => 'balance',
                'filter' => function ($data) {
                    return Yii::$app->getFormatter()->asDecimal($data->balance);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return \app\models\User::getStatus($data->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', ['show all'] + \app\models\User::getStatus(), ['class' => 'form-control'])
            ],

            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {send_balance}',
                'buttons' => [
                    'send_balance' => function ($url, $model) {
                        return !Yii::$app->getUser()->getIsGuest()
                            ? Html::a('send money', ['user/send-money', 'username' => $model->username], ['class' => 'btn btn-primary'])
                            : null;
                    },
                ]
            ],
        ],
    ]); ?>


</div>
