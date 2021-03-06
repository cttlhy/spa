<?php

namespace frontend\models;

use api\modules\v1\models\Channel;
use Yii;
use yii\base\Model;

class PaymentForm extends Model
{
    public $payment_way;
//    public $payment_term;
    public $beneficiary_name;
    public $bank_country;
    public $bank_name;
    public $bank_address;
    public $swift;
    public $account_nu_iban;
    /**
     * @var Channel
     */
    private $channel;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_way', 'beneficiary_name'], 'safe'],
            [['bank_country', 'bank_name', 'bank_address', 'swift', 'account_nu_iban'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'payment_way' => 'Payment Way',
//            'payment_term' => 'Payment Term',
            'beneficiary_name' => 'Beneficiary Name',
            'bank_country' => 'Bank Country',
            'bank_name' => 'Bank Name',
            'bank_address' => 'Bank Address',
            'swift' => 'Swift',
            'account_nu_iban' => 'Account Nu Iban',

        ];
    }

    public function update()
    {
        if ($this->validate()) {
            $this->channel = Channel::findIdentity(Yii::$app->user->getId());
            $this->channel->payment_way = $this->payment_way;
            $this->channel->beneficiary_name = $this->beneficiary_name;
            $this->channel->bank_country = $this->bank_country;
            $this->channel->bank_name = $this->bank_name;
            $this->channel->bank_address = $this->bank_address;
            $this->channel->swift = $this->swift;
            $this->channel->account_nu_iban = $this->account_nu_iban;
            if ($this->channel->save()) {
                return true;
            }
        }
        return null;
    }

    public function beforeValidate()
    {
        if (is_array($this->payment_way)) {
            $this->payment_way = implode(',', $this->payment_way);
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

}
