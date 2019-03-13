<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
/**
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
    `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `status` smallint(6) NOT NULL DEFAULT '10',
    `created_at` int(11) NOT NULL,
    `updated_at` int(11) NOT NULL,
 */
?>

<div class="user-form">


    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'password_hash')->textInput() ?>
    <?= $form->field($model, 'password_reset_token')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <?php //$form->field($model, 'created_at')->textInput() ?>
    <?php //$form->field($model, 'updated_at')->textInput() ?>
    <?php //$form->field($model, 'email')->textInput() ?>
    <?php //$form->field($model, 'email')->textInput() ?>
    <?php //$form->field($model, 'status')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
