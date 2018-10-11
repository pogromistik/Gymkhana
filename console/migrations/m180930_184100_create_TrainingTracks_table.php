t<?php

use yii\db\Migration;

/**
 * Handles the creation of table `TrainingTracks`.
 */
class m180930_184100_create_TrainingTracks_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('TrainingTracks', [
			'id'            => $this->primaryKey(),
			'title'         => $this->string(),
			'description'   => $this->text(),
			'imgPath'       => $this->string()->notNull(),
			'status'        => $this->integer()->notNull()->defaultValue(2),
			'minWidth'      => $this->float(),
			'minHeight'     => $this->float(),
			'level'         => $this->integer(),
			'conesCount'    => $this->integer(),
			'dateAdded'     => $this->integer()->notNull(),
			'dateUpdated'   => $this->integer()->notNull(),
			'creatorUserId' => $this->integer()
		]);
		$this->createIndex('TrainingTracks_title', 'TrainingTracks', 'title');
		$this->createIndex('TrainingTracks_minWidth', 'TrainingTracks', 'minWidth');
		$this->createIndex('TrainingTracks_minHeight', 'TrainingTracks', 'minHeight');
		$this->createIndex('TrainingTracks_level', 'TrainingTracks', 'level');
		$this->createIndex('TrainingTracks_conesCount', 'TrainingTracks', 'conesCount');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('TrainingTracks');
	}
}
