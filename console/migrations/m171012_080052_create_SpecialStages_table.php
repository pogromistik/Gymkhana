<?php

use yii\db\Migration;

/**
 * Handles the creation of table `SpecialStages`.
 */
class m171012_080052_create_SpecialStages_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('SpecialStages', [
			'id'                => $this->primaryKey(),
			'championshipId'    => $this->integer()->notNull(),
			'title'             => $this->string()->notNull(),
			'dateAdded'         => $this->integer()->notNull(),
			'dateUpdated'       => $this->integer()->notNull(),
			'description'       => $this->text(),
			'dateStart'         => $this->integer(),
			'dateEnd'           => $this->integer(),
			'dateResult'        => $this->integer(),
			'classId'           => $this->integer(),
			'status'            => $this->integer()->notNull(),
			'photoPath'         => $this->string(),
			'referenceTime'     => $this->integer(),
			'outOfCompetitions' => $this->integer()->notNull()->defaultValue(0)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('SpecialStages');
	}
}
