<?php

namespace common\models;

use DateInterval;
use DateTime;
use DateTimeZone;
use yii\data\ActiveDataProvider;


class ReportChannelSearch extends ReportChannelHourly
{
    public $time_zone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_id', 'channel_id', 'time', 'clicks', 'unique_clicks', 'installs', 'match_installs', 'campaign_name', 'channel_name'], 'safe'],
            [['time_format', 'daily_cap', 'type', 'start', 'end', 'time_zone', 'om'], 'safe'],
            [['pay_out', 'adv_price'], 'number'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function hourlySearch($params)
    {

        $query = ReportChannelHourly::find();
        $query->alias('clh');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        var_dump($this->type);
//        die();
        if (!$this->validate()) {
            return $dataProvider;
        }
//        $a = strtotime($this->start);
        $start = new DateTime($this->start, new DateTimeZone($this->time_zone));
        $end = new DateTime($this->end, new DateTimeZone($this->time_zone));
        $end = $end->add(new DateInterval('P1D'));
        $start = $start->getTimestamp();
        $end = $end->getTimestamp();
        $query->select([
            'ch.username channel_name',
            'cam.campaign_name campaign_name',
            'clh.campaign_id',
            'clh.channel_id',
            'clh.time',
            'clh.time_format',
            'clh.clicks',
            'clh.unique_clicks',
            'clh.installs',
            'clh.match_installs',
            'clh.pay_out',
            'clh.adv_price',
            'clh.daily_cap',
            'u.username om',
//            'campaign_name',

        ]);
        $query->joinWith('channel ch');
        $query->joinWith('campaign cam');
        $query->leftJoin('user u', 'ch.om = u.id');
        // grid filtering conditions
        $query->andFilterWhere([
            'campaign_id' => $this->campaign_id,
            'channel_id' => $this->channel_id,
            'pay_out' => $this->pay_out,
            'adv_price' => $this->adv_price,
            'ch.username' => $this->channel_name,
        ]);

        $query->andFilterWhere(['like', 'time_format', $this->time_format])
            ->andFilterWhere(['like', 'ch.username', $this->channel_name])
            ->andFilterWhere(['like', 'cam.campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'u.username', $this->om])
            ->andFilterWhere(['>=', 'time', $start])
            ->andFilterWhere(['<', 'time', $end]);
//        $query->andWhere(['>', 'clicks', 0]);
        $query->orderBy(['ch.username' => SORT_ASC, 'cam.campaign_name' => SORT_ASC, 'time' => SORT_DESC]);
        return $dataProvider;
    }

    public function dailySearch($params)
    {

        $query = ReportChannelDaily::find();
        $query->alias('clh');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
//        var_dump(strtotime($this->start . '-1 day'));
//        var_dump(strtotime($this->end . '+1 day'));
        $start = new DateTime($this->start, new DateTimeZone($this->time_zone));
        $end = new DateTime($this->end, new DateTimeZone($this->time_zone));
        $start = $start->sub(new DateInterval('P1D'));
        $end = $end->add(new DateInterval('P1D'));
        $start = $start->getTimestamp();
        $end = $end->getTimestamp();
        $query->select([
            'ch.username channel_name',
            'cam.campaign_name campaign_name',
            'clh.campaign_id',
            'clh.channel_id',
            'clh.time',
            'clh.time_format',
            'clh.clicks',
            'clh.unique_clicks',
            'clh.installs',
            'clh.match_installs',
            'clh.pay_out',
            'clh.adv_price',
            'clh.daily_cap',
            'u.username om',
//            'campaign_name',

        ]);
        $query->joinWith('channel ch');
        $query->joinWith('campaign cam');
        $query->leftJoin('user u', 'ch.om = u.id');
        // grid filtering conditions
        $query->andFilterWhere([
            'campaign_id' => $this->campaign_id,
            'channel_id' => $this->channel_id,
            'pay_out' => $this->pay_out,
            'adv_price' => $this->adv_price,
            'ch.username' => $this->channel_name,
        ]);

        $query->andFilterWhere(['like', 'time_format', $this->time_format])
            ->andFilterWhere(['like', 'ch.username', $this->channel_name])
            ->andFilterWhere(['like', 'cam.campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'u.username', $this->om])
            ->andFilterWhere(['>', 'time', $start])
            ->andFilterWhere(['<', 'time', $end]);
        $query->orderBy(['ch.username' => SORT_ASC, 'cam.campaign_name' => SORT_ASC, 'time' => SORT_DESC]);
//        var_dump(strtotime($this->start));
//        var_dump(strtotime($this->end));
//        var_dump($query->createCommand()->sql);
//        die();
        return $dataProvider;
    }
}
