<?php

use yii\db\Migration;

class m170907_041542_add_language_to_athlete extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Athlete::tableName(),
			'language', $this->string()->notNull()->defaultValue(\common\models\TranslateMessage::LANGUAGE_RU));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Athlete::tableName(), 'language');
	}
}
