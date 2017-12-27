<?php

use yii\db\Migration;

/**
 * Class m171227_055526_add_status_to_news
 */
class m171227_055526_add_status_to_news extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->addColumn(\common\models\AssocNews::tableName(), 'status', $this->integer()->notNull()->defaultValue(1));
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropColumn(\common\models\AssocNews::tableName(), 'status');
	}
}
