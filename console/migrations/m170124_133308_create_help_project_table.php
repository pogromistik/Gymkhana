<?php

use yii\db\Migration;

/**
 * Handles the creation of table `help_project`.
 */
class m170124_133308_create_help_project_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('help_project', [
			'id'        => $this->primaryKey(),
			'imgFolder' => $this->string(),
			'text1'     => $this->string(),
			'text2'     => $this->string()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('help_project');
	}
}
