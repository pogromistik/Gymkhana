<?php

use yii\db\Migration;

class m170711_055744_add_comment_to_Translate extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\TranslateMessageSource::tableName(), 'comment', $this->string());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\TranslateMessageSource::tableName(), 'comment');
	}
}
