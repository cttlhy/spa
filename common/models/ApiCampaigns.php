<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "api_campaigns".
 *
 * @property integer $id
 * @property integer $adv_id
 * @property string $url
 * @property string $key
 * @property string $param
 * @property string $json_offers_param
 * @property string $adv_update_time
 * @property string $effective_time
 * @property string $adv_campaign_id
 * @property string $campaign_uuid
 * @property string $campaign_name
 * @property string $pricing_mode
 * @property string $promote_start
 * @property string $end_time
 * @property string $platform
 * @property string $daily_cap
 * @property string $adv_price
 * @property string $daily_budget
 * @property string $target_geo
 * @property string $adv_link
 * @property string $traffice_source
 * @property string $note
 * @property string $preview_link
 * @property string $icon
 * @property string $package_name
 * @property string $app_name
 * @property string $app_size
 * @property string $category
 * @property string $version
 * @property string $app_rate
 * @property string $description
 * @property string $creative_link
 * @property string $creative_type
 * @property string $creative_description
 * @property string $carriers
 * @property string $conversion_flow
 * @property string $status
 * @property integer $create_time
 * @property integer $update_time
 *
 * @property Advertiser $adv
 */
class ApiCampaigns extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_campaigns';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adv_id', 'create_time', 'update_time'], 'integer'],
            [['url'], 'required'],
            [['note'], 'string'],
            [['url', 'key', 'param', 'json_offers_param', 'adv_update_time', 'effective_time', 'adv_campaign_id', 'campaign_uuid', 'campaign_name', 'pricing_mode', 'promote_start', 'end_time', 'platform', 'daily_cap', 'adv_price', 'daily_budget', 'target_geo', 'adv_link', 'traffice_source', 'preview_link', 'icon', 'package_name', 'app_name', 'app_size', 'category', 'version', 'app_rate', 'description', 'creative_link', 'creative_type', 'creative_description', 'carriers', 'conversion_flow', 'status'], 'string', 'max' => 255],
            [['adv_id'], 'unique'],
            [['adv_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advertiser::className(), 'targetAttribute' => ['adv_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'adv_id' => 'Adv ID',
            'url' => 'Url',
            'key' => 'Key',
            'param' => 'Param',
            'json_offers_param' => 'Json Offers Param',
            'adv_update_time' => 'Adv Update Time',
            'effective_time' => 'Effective Time',
            'adv_campaign_id' => 'Adv Campaign ID',
            'campaign_uuid' => 'Campaign Uuid',
            'campaign_name' => 'Campaign Name',
            'pricing_mode' => 'Pricing Mode',
            'promote_start' => 'Promote Start',
            'end_time' => 'End Time',
            'platform' => 'Platform',
            'daily_cap' => 'Daily Cap',
            'adv_price' => 'Adv Price',
            'daily_budget' => 'Daily Budget',
            'target_geo' => 'Target Geo',
            'adv_link' => 'Adv Link',
            'traffice_source' => 'Traffice Source',
            'note' => 'Note',
            'preview_link' => 'Preview Link',
            'icon' => 'Icon',
            'package_name' => 'Package Name',
            'app_name' => 'App Name',
            'app_size' => 'App Size',
            'category' => 'Category',
            'version' => 'Version',
            'app_rate' => 'App Rate',
            'description' => 'Description',
            'creative_link' => 'Creative Link',
            'creative_type' => 'Creative Type',
            'creative_description' => 'Creative Description',
            'carriers' => 'Carriers',
            'conversion_flow' => 'Conversion Flow',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdv()
    {
        return $this->hasOne(Advertiser::className(), ['id' => 'adv_id']);
    }
}
