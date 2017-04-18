<?php
namespace grozzzny\call_back;

class Module extends \yii\easyii\components\Module
{
    public $settings = [
        'subject' => 'Заявка на обратный звонок',
        'template' => '@grozzzny/call_back/mail/ru/call_back',
    ];
}