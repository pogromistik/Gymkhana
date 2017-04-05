<?php

use yii\db\Migration;

/**
 * Handles the creation of table `FigureTimes`.
 */
class m170302_051925_create_FigureTimes_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('FigureTimes', [
			'id'                    => $this->primaryKey(),
			'figureId'              => $this->integer()->notNull(),
			'athleteId'             => $this->integer()->notNull(),
			'motorcycleId'          => $this->integer()->notNull(),
			'yearId'                => $this->integer()->notNull(),
			'athleteClassId'        => $this->integer(),
			'newAthleteClassId'     => $this->integer(),
			'newAthleteClassStatus' => $this->integer(),
			'date'                  => $this->integer()->notNull(),
			'percent'               => $this->float()->notNull(),
			'time'                  => $this->integer()->notNull(),
			'fine'                  => $this->integer()->notNull()->defaultValue(0),
			'dateAdded'             => $this->integer()->notNull(),
			'dateUpdated'           => $this->integer()->notNull(),
			'resultTime'            => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('FigureTimes');
	}
}
