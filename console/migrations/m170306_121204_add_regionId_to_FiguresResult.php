<?php

use yii\db\Migration;

class m170306_121204_add_regionId_to_FiguresResult extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\FigureTime::tableName(), 'regionId', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\FigureTime::tableName(), 'regionId');
	}
}
