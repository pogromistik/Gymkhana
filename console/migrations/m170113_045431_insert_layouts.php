<?php

use yii\db\Migration;

class m170113_045431_insert_layouts extends Migration
{
	public function safeUp()
	{
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'notFound',
			'title' => 'Страница не найдена'
		]);
		$this->insert(\common\models\Layout::tableName(), [
			'id'    => 'inDevelop',
			'title' => 'Страница в разработке'
		]);
	}
	
	public function safeDown()
	{
		return true;
	}
}
