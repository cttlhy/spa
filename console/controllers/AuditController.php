<?php
namespace console\controllers;

use common\models\Campaign;
use common\models\Channel;
use common\models\Deliver;
use common\models\Feed;
use common\models\Stream;
use linslin\yii2\curl\Curl;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: wh wu
 * Date: 1/15/2017
 * Time: 3:39 PM
 */
class AuditController extends Controller
{

    public function actionIndex()
    {
        echo "sdfadsf \n";
    }

    // 10分钟一次。
    public function actionCount_feed()
    {
        $needCounts = Feed::findNeedCounts();
        $this->echoMessage("Get feeds count " . count($needCounts));
        if (!empty($needCounts)) {

            $matchClicks = $this->getMatch_clicks($needCounts);
            //更新campaign的真实安装量。
            $this->updateMatchInstall($matchClicks);
            // 更新feed
            $this->updateFeedStatus($needCounts);
            //更新扣量
            $this->updatePost_status($matchClicks);
            //更新点击量
            $this->count_clicks();
        } else {
            $this->echoHead("No feed need to update");
        }
        //post
        $this->post_back();
    }

// 每20分钟post
    protected function post_back()
    {
        $this->echoHead("Post action start at " . time());
        $curl = new Curl();
        $needPosts = Stream::getNeedPosts();
        foreach ($needPosts as $k) {
            $this->echoMessage("Click  $k->click_uuid going to post ");
            $this->echoMessage("Post to " . $k->post_link);
            $response = $curl->get($k->post_link);
            var_dump($response);
            $k->post_status = 3; // 已经post
            $k->post_time = time();
            if (!$k->save()) {
                var_dump($k->getErrors());
            }
            $this->echoMessage("Wait 1 second");
            sleep(1);
        }
        $this->echoHead("Post action end at " . time());
    }

    /** 更新总的安装量
     * @param array|\common\models\Stream[] $matchClicks
     */
    protected function updateMatchInstall($matchClicks)
    {
        $this->echoHead("update match install start");
        $data = array();
        $feeds = array();
        foreach ($matchClicks as $k) {
            $campaign_uuid = $k->cp_uid;
            $campaign_id = Campaign::findByUuid($campaign_uuid)->id;
            $channel_id = $k->ch_id;
            if (isset($data[$campaign_id . ',' . $channel_id])) {
                $data[$campaign_id . ',' . $channel_id] += 1;
            } else {
                $data[$campaign_id . ',' . $channel_id] = 1;
            }
            $feeds[] = $k->click_uuid;
        }
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $camp_chanl = explode(',', $k);
                $deliver = Deliver::findIdentity($camp_chanl[0], $camp_chanl[1]);
                $deliver->match_install += $v; //累加
                if(!$deliver->save()){
                    var_dump($deliver->getErrors());
                }
                $this->echoMessage("deliver $camp_chanl[0]-$camp_chanl[1] match install update to $v");
            }
        }

        $this->echoHead("end update match install");
    }

    /**
     * @param \common\models\feed[] $feeds
     */
    protected function updateFeedStatus($feeds)
    {
        $this->echoHead("start update feed status to counted");
        foreach ($feeds as $feed) {
            $feed->is_count = 1;
            if(!$feed->save()){
                var_dump($feed->getErrors());
            }
            $this->echoMessage("update feed {$feed->id} to counted");
        }
        $this->echoHead("end update feed status");
    }

    /**
     * @param array|\common\models\Stream[] $matchClicks
     */
    protected function updatePost_status($matchClicks)
    {
        $this->echoHead("update post status start");
        foreach ($matchClicks as $k) {

            $k->post_status = 2; //默认不post back；
            $campaign_uuid = $k->cp_uid;
            $channel_id = $k->ch_id;
            $this->echoMessage("");
            $deliver = Deliver::findIdentityByCpUuidAndChid($campaign_uuid, $channel_id);
            if ($deliver !== null) {
                $this->echoMessage("find deliver $campaign_uuid-$channel_id");
            } else {
                $this->echoMessage("can not find deliver $campaign_uuid-$channel_id");
                continue;
            }
            $actual_install_percent = (($deliver->install + 1) / $deliver->match_install) * 100;
            $discount = 100 - $deliver->discount;
            $this->echoMessage("this deliver install is $deliver->install");
            $this->echoMessage("this deliver match install is $deliver->match_install");
            if (($deliver->install < 5) || $actual_install_percent <= $discount) { //还没达到扣标准。
                $this->echoMessage("this click will be post back");
                $deliver->install += 1;
                $deliver->actual_discount = $actual_install_percent;
                $k->post_status = 1; // ready to send
                $post_back = $deliver->channel->post_back;
                $k->post_link = "";
                if (!empty($post_back)) {
                    $k->post_link = $this->genPost_link($post_back, $k->all_parameters);
                }
                $this->echoMessage("post link is $k->post_link");
            } else {
                $this->echoMessage("this click will not post back");
            }
            if ($k->save() === false) {
                $this->echoMessage("click update error");
                var_dump($k->getErrors());
            }
            if ($deliver->save() === false) {
                $this->echoMessage("deliver update error");
                var_dump($deliver->getErrors());
            }
        }
        $this->echoHead("end update post status");
    }

    /** 有效点击
     * @param array|\common\models\Feed[] $needCounts
     * @return array|\common\models\Stream[]
     */
    protected function getMatch_clicks($needCounts)
    {
        $matchClicks = array();
        foreach ($needCounts as $k) {
            $stream = Stream::findOne(['click_uuid' => $k->click_id]);
            if ($stream == null)
                continue;
            $matchClicks[] = $stream;
        }
        $this->echoMessage("Get match clicks " . count($matchClicks));
        return $matchClicks;
    }

    protected function count_clicks()
    {
        $this->echoHead("start to count clicks");
        $streams = Stream::getCountClicks();
        $camps = array();
        if (!empty($streams)) {
            foreach ($streams as $stream) {
                if (isset($camps[$stream->cp_uid . ',' . $stream->ch_id])) {
                    $camps[$stream->cp_uid . ',' . $stream->ch_id] += 1;
                } else {
                    $camps[$stream->cp_uid . ',' . $stream->ch_id] = 1;
                }
                $stream->is_count = 1;
                if ($stream->save()) {
                    $this->echoMessage("update stream {$stream->cp_uid}-{ $stream->ch_id} is counted");
                } else {
                    var_dump($stream->getErrors());
                }
            }
        }

        if (!empty($camps)) {
            foreach ($camps as $k => $v) {
                $ids = explode(',', $k);
                $deliver = Deliver::findIdentityByCpUuidAndChid($ids[0], $ids[1]);
                $deliver->click += $v;
                $deliver->unique_click = Stream::getDistinctIpClick($ids[0], $ids[1]);
                if ($deliver->save()) {
                    $this->echoMessage("deliver $deliver->campaign_id - $deliver->channel_id");
                    $this->echoMessage("update click to $deliver->click ");
                    $this->echoMessage("update unique click to $deliver->unique_click ");
                } else {
                    var_dump($deliver->getErrors());
                }
            }
        }
        $this->echoHead("end to count clicks");
    }

    // 每天清理一遍click——log
    public function actionClear_click_log()
    {
        return parent::actions(); // TODO: Change the autogenerated stub
    }

    protected function genPost_link($postback, $allParams)
    {
        $this->echoMessage("channel post back is " . $postback);
        $this->echoMessage("click params are " . $allParams);
        $homeurl = substr($postback, 0, strpos($postback, '?'));
        $paramstring = substr($postback, strpos($postback, '?') + 1, strlen($postback) - 1);
        $params = explode("&", $paramstring);
        $returnParams = "";
        $paramsTemp = array();
        if (!empty($params)) {
            foreach ($params as $k) {
                $temp = explode('=', $k);
                $paramsTemp[$temp[0]] = isset($temp[1]) ? $temp[1] : "";
            }
        }
        if (!empty($paramsTemp)) {
            foreach ($paramsTemp as $k => $v) {
                if (strpos($allParams, $k) !== false) {
                    $startCut = strpos($allParams, $k);
                    $cutLen = (strlen($allParams) - $startCut);
                    if (strpos($allParams, '&', $startCut)) {
                        $cutLen = strpos($allParams, '&', $startCut) - $startCut;
                    }
                    $returnParams .= substr($allParams, $startCut, $cutLen) . "&";
                }
            }
        }
        if (!empty($returnParams)) {
            $returnParams = chop($returnParams, '&');
            $homeurl .= "?" . $returnParams;
        } else {
            $this->echoMessage("can not found post back params");
        }
        $this->echoMessage("generate url: " . $homeurl);
        return $homeurl;
    }

    protected function genPostBack($postback, $track, $allParams)
    {
        echo "genarate url start \n";
        $homeurl = substr($postback, 0, strpos($postback, '?'));
        $paramstring = substr($postback, strpos($postback, '?') + 1, strlen($postback) - 1);
        $params = explode("&", $paramstring);
        $returnParams = "";
        $paramsTemp = array();
        if (!empty($params)) {
            foreach ($params as $k) {
                $temp = explode('=', $k);
                $paramsTemp[$temp[0]] = isset($temp[1]) ? $temp[1] : "";
            }
        }
        if (!empty($paramsTemp)) {
            foreach ($paramsTemp as $k => $v) {
                if (strpos($allParams, $k)) {
                    $startCut = strpos($allParams, $k);
                    $cutLen = (strlen($allParams) - $startCut);
                    if (strpos($allParams, '&', $startCut)) {
                        $cutLen = strpos($allParams, '&', $startCut) - $startCut;
                    }
                    $returnParams .= substr($allParams, $startCut, $cutLen) . "&";
                }
            }
        }
        if (!empty($returnParams)) {
            $returnParams = chop($returnParams, '&');
            $homeurl .= "?" . $returnParams;
        }
        echo "\n genarate url : " . $homeurl . "\n";
        return $homeurl;
    }

    private function echoHead($str)
    {
        echo "#######  $str \n\n";
    }

    private function echoMessage($str)
    {
        echo " \t $str \n";
    }
}