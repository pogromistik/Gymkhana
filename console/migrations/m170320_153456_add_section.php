<?php

use yii\db\Migration;

class m170320_153456_add_section extends Migration
{
	public function safeUp()
	{
		$this->insert(\common\models\DocumentSection::tableName(), [
			'id'    => 1,
			'title' => 'Документы ассоциации'
		]);
		
		$this->insert(\common\models\DocumentSection::tableName(), [
			'id'    => 2,
			'title' => 'Частные регламенты'
		]);
	}
	
	public function safeDown()
	{
		return true;
	}
}
