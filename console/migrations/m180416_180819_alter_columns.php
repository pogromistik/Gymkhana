<?php

use yii\db\Migration;

/**
 * Class m180416_180819_alter_columns
 */
class m180416_180819_alter_columns extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->alterColumn(\common\models\AssocNews::tableName(), 'previewText', $this->text());
		$this->alterColumn(\common\models\Notice::tableName(), 'text', $this->text());
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->alterColumn(\common\models\AssocNews::tableName(), 'previewText', $this->string());
		$this->alterColumn(\common\models\Notice::tableName(), 'text', $this->string());
	}
}
