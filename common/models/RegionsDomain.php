<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regions_domain".
 *
 * @property integer $id
 * @property string $domain
 * @property string $country_regions
 * @property string $country_regions_cn
 *
 * @property Campaign[] $campaigns
 */
class RegionsDomain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions_domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'country_regions'], 'required'],
            [['domain'], 'string', 'max' => 10],
            [['country_regions', 'country_regions_cn'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'country_regions' => 'Country Regions',
            'country_regions_cn' => 'Country Regions Cn',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaigns()
    {
        return $this->hasMany(Campaign::className(), ['target_geo' => 'id']);
    }

    public static function getGeoListByName($name)
    {
        return static::find()->select(['domain'])->where(['like', 'domain', $name])->column();
    }

}
