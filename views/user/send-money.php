<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendMoneyForm */

$this->title = 'Send money' . ($model->to_user ? ' to "' . $model->to_user . '"' : null);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'send money';
?>
<div class="send-money">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="send-money-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'to_user')->widget(\yii\jui\AutoComplete::classname(),[
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'source' => $model->users,
                        'minLength' => '3',
                        'autoFill' => true,
                        ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'value')->textInput(); ?>
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
