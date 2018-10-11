<?php

use yii\db\Migration;

/**
 * Class m180911_045550_add_prev_to_Diplomas
 */
class m180911_045550_add_prev_to_Diplomas extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(\common\models\Diploma::tableName(), 'prevImg', $this->string());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(\common\models\Diploma::tableName(), 'prevImg');
	}
}
