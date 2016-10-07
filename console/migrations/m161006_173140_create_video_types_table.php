<?php

use yii\db\Migration;

/**
 * Handles the creation for table `video_types`.
 */
class m161006_173140_create_video_types_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('video_types', [
			'id'      => $this->primaryKey(),
			'title'   => $this->string()->notNull(),
			'status'  => $this->integer()->notNull()->defaultValue(1),
			'picture' => $this->string()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('video_types');
	}
}
