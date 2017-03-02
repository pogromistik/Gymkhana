<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Figures`.
 */
class m170302_051913_create_Figures_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Figures', [
			'id'                  => $this->primaryKey(),
			'title'               => $this->string()->notNull(),
			'description'         => $this->text(),
			'file'                => $this->string(),
			'picture'             => $this->string(),
			'bestTime'            => $this->integer()->notNull(),
			'bestAthlete'         => $this->text(),
			'bestTimeInRussia'    => $this->integer(),
			'bestAthleteInRussia' => $this->text()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Figures');
	}
}
