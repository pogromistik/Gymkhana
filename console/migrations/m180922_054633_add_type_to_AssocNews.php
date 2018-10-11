<?php

use yii\db\Migration;

/**
 * Class m180922_054633_add_type_to_AssocNews
 */
class m180922_054633_add_type_to_AssocNews extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(\common\models\AssocNews::tableName(), 'type', $this->integer()->notNull()->defaultValue(1));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(\common\models\AssocNews::tableName(), 'type');
	}
}
