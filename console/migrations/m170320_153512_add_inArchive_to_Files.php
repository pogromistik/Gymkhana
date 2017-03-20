<?php

use yii\db\Migration;

class m170320_153512_add_inArchive_to_Files extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\OverallFile::tableName(), 'inArchive', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\OverallFile::tableName(), 'inArchive');
	}
}
