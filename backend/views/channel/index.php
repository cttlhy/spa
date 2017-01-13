<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Channel List';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
            'username',
//            'firstname',
//            'lastname',
            // 'type',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'settlement_type',
             'om',
             'master_channel',
            // 'account_name',
            // 'branch_name',
            // 'card_number',
            // 'contacts',
            // 'updated_at',
            // 'email:email',
            // 'country',
            // 'city',
            // 'address',
            // 'company',
            // 'phone1',
            // 'phone2',
            // 'wechat',
            // 'qq',
            // 'skype',
            // 'alipay',
            // 'lang',
            // 'timezone',
            // 'firstaccess',
            // 'lastaccess',
            // 'picture',
            // 'confirmed',
            // 'suspended',
            // 'deleted',
            // 'status',
            // 'traffic_source',
            // 'pricing_mode',
            // 'post_back',
             'total_revenue',
             'payable',
             'paid',
            // 'strong_geo',
            // 'strong_catagory',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
