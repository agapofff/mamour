<?php
namespace dvizh\relations;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $fields = [];
    public $listView = 'list';

    public function init()
    {
        parent::init();
    }
}
