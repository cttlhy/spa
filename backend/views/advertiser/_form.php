<?php

use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertiser */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="col-lg-6">
    <div class="box box-info">
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'settlement_type')->dropDownList(ModelsUtil::settlement_type) ?>
            <?= $form->field($model, 'pm')->textInput(['maxlength' => true, 'readonly' => true]) ?>
            <?= $form->field($model, 'bd')->widget(Typeahead::classname(), [
                'pluginOptions' => ['highlight' => true],
                'options' => ['value' => isset($model->bd) ? $model->bd0->username : '',],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['advertiser/get_bd']) . '?bd=%QUERY',
                            'wildcard' => '%QUERY'
                        ]
                    ]],
            ]) ?>

            <?= $form->field($model, 'contacts')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'system')->dropDownList(ModelsUtil::system) ?>
            <?= $form->field($model, 'post_parameter')->textInput(['value' => 'click_id']) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'cc_email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList(ModelsUtil::advertiser_status) ?>
            <?= $form->field($model, 'pricing_mode')->dropDownList(ModelsUtil::pricing_mode) ?>
            <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'skype')->textInput() ?>
            <?= $form->field($model, 'phone1')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
