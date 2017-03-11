<?php

use yii\db\Migration;

/**
 * Handles the creation of table `TmpFiguresResults`.
 */
class m170311_171548_create_TmpFiguresResults_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('TmpFiguresResults', [
			'id'           => $this->primaryKey(),
			'athleteId'    => $this->integer()->notNull(),
			'motorcycleId' => $this->integer()->notNull(),
			'figureId'     => $this->integer()->notNull(),
			'date'         => $this->integer()->notNull(),
			'time'         => $this->integer()->notNull(),
			'fine'         => $this->integer()->notNull()->defaultValue(0),
			'videoLink'    => $this->string()->notNull(),
			'isNew'        => $this->integer()->notNull()->defaultValue(1),
			'dateAdded'    => $this->integer()->notNull(),
			'dateUpdated'  => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('TmpFiguresResults');
	}
}
