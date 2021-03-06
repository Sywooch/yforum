<?php

namespace common\components\db;
class Migration extends \yii\db\Migration
{
    public $useTransaction = false;
    public $tableOptions=null;
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        if ($this->db->driverName === 'mysql') { 
            $engine = $this->useTransaction ? 'InnoDB' : 'MyISAM';
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=' . $engine;
        }
    }
}