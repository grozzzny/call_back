<?php
namespace grozzzny\call_back\models;


class CallBack extends Base
{
    const CACHE_KEY = 'gr_call_back';

    const TITLE = 'Обратная связь';
    const ALIAS = 'call_back';

    const SUBMENU_PHOTOS = false;
    const SUBMENU_FILES = false;
    const SHOW_ORDER_NUM = true;
    const PRIMARY_MODEL = true;


    const SCENARIO_EMAIL = 'scenario_email';
    const SCENARIO_PHONE = 'scenario_phone';
    const SCENARIO_EMAIL_AND_PHONE = 'scenario_email_and_phone';

    public static function tableName()
    {
        return 'gr_call_back';
    }

    public function rules()
    {
        return [
            ['id', 'number', 'integerOnly' => true],
            [['name', 'ip'], 'string'],
            [['datetime'], 'integer'],
            [['email'], 'email'],
            ['phone', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            ['status', 'default', 'value' => self::STATUS_ON],
            [['order_num'], 'integer'],

            ['email', 'required', 'on' => self::SCENARIO_EMAIL],
            ['phone', 'required', 'on' => self::SCENARIO_PHONE],
            [['email', 'phone'], 'required', 'on' => self::SCENARIO_EMAIL_AND_PHONE],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'datetime' => 'Дата и время',
            'email' => 'Электронный адрес',
            'phone' => 'Телефон',
            'ip' => 'IP',
            'order_num' => 'Индекс сортировки',
            'status' => 'Состояние',
        ];
    }


//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $scenarios[static::SCENARIO_USER] = [
//            'title',
//            'image',
//            'description',
//            'price',
//            'discount'
//        ];
//        return $scenarios;
//    }

    public static function queryFilter(&$query, $get)
    {
        if(!empty($get['text'])){
            $query->andFilterWhere([
                'OR',
                ['LIKE', 'name', $get['text']],
                ['LIKE', 'email', $get['text']],
                ['LIKE', 'phone', $get['text']],
            ]
            );
        }
    }

    public static function querySort(&$provider)
    {
        $sort = [];

        $attributes = [
            'id',
            'name',
            'email',
            'phone',
            'datetime',
            'status',
            'order_num'
        ];

        if(self::SHOW_ORDER_NUM){
            $sort = $sort + ['defaultOrder' => ['order_num' => SORT_DESC]];
            $attributes = $attributes + ['order_num'];
        }

        $sort = $sort + ['attributes' => $attributes];

        $provider->setSort($sort);
    }

}
