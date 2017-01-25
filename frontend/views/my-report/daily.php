<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MyReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Daily report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <?php $form = ActiveForm::begin([
                            'action' => ['hourly'],
                            'method' => 'get',
                        ]); ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label class="control-label">Timezone:</label>
                                </div>
                                <div class="col-lg-9">
                                    <?= Html::dropDownList('MyReportSearch[timezone]', '8', ModelsUtil::timezone, ['class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">

                                <div class="col-lg-3">
                                    <label class="control-label">Time:</label>
                                </div>
                                <div class="col-lg-9">
                                    <?php
                                    echo DatePicker::widget([
                                        'name' => 'MyReportSearch[start_time]',
                                        'value' => Yii::$app->formatter->asDate('now', 'php:Y-mm-d'),
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name2' => 'MyReportSearch[end_time]',
                                        'value2' => Yii::$app->formatter->asDate('now', 'php:Y-m-d'),
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-MM-dd'
                                        ]
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-1"></div>

                                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                                <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php Pjax::begin(); ?>    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'columns' => [
                                'time',
                                'clicks',
                                'installs',
                                'campaign_id',
                                'campaign_uuid',
//                                'pricing_mode',
                                'pay_out',
                                // 'daily_cap',
                                // 'actual_discount',
                                // 'discount',
                                // 'is_run',
                                // 'creator',
                                // 'create_time:datetime',
                                // 'update_time:datetime',
                                // 'track_url:url',
                                // 'click',
                                // 'unique_click',
                                // 'install',
                                // 'cvr',
                                // 'cost',
                                // 'match_install',
                                // 'match_cvr',
                                // 'revenue',
                                // 'def',
                                // 'deduction_percent',
                                // 'profit',
                                // 'margin',
                                // 'note',

                            ],
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>