<?php

use yii\db\Migration;

/**
 * Handles the creation of table `overall_ducuments`.
 */
class m170127_062355_create_overall_files_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('overall_files', [
			'id'         => $this->primaryKey(),
			'userId'     => $this->integer(),
			'date'       => $this->integer(),
			'modelClass' => $this->string(),
			'modelId'    => $this->string(),
			'title'      => $this->string(),
			'fileName'   => $this->string(),
			'filePath'   => $this->string()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('overall_files');
	}
}
