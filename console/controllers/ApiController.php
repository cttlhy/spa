<?php
/**
 * Created by PhpStorm.
 * User: Iven.Wu
 * Date: 2017-02-10
 * Time: 10:59
 */

namespace console\controllers;


use common\models\AdvertiserApi;
use common\models\ApiCampaign;
use common\utility\ApiUtil;
use linslin\yii2\curl\Curl;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class ApiController extends Controller
{

    public function actionGetHeadway()
    {
        $apiModel = AdvertiserApi::findOne(['id'=>5]);
        $data_key = $apiModel->json_offers_param;


        $curl = new Curl();
        $curl->post('http://api.mobra.in/v1/auth/login?user=boster@superads.cn&password=Mobrain123', false);

        $headers = $curl->responseHeaders;
        $cookies = explode(';', $headers["Set-Cookie"]);
        $cook = $cookies[0];

//        var_dump($cook);


        $curl = new Curl();
        $response = $curl->setOption(
            CURLOPT_HTTPHEADER,
            array(
                "cache-control:no-cache",
                "cookie:$cook",
            )
        )->get('https://api.mobra.in/v1/campaign/feed');
        $response = json_decode($response);
        $data = $response->$data_key;
        $camps = ApiUtil::genApiCampaigns($apiModel,$data);
        //ApiCampaign::deleteAll(['adv_id'=>$apiModel->adv_id]);
        foreach($camps as $item){
            $old = ApiCampaign::findOne(['adv_id'=>$item->adv_id,'campaign_id'=>$item->campaign_id]);
            if(isset($old)){
                $old->load(ArrayHelper::toArray($item));
                $old->save();
            }else {

                $item->adv_id=$apiModel->adv_id;
                $item->save();
            }
            var_dump($item->getErrors());
        }
    }

    private function transferApiModel($old,$new){
        ArrayHelper::toArray();
    }
}