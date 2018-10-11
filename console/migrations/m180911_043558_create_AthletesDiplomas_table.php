<?php

use yii\db\Migration;

/**
 * Handles the creation of table `AthletesDiplomas`.
 */
class m180911_043558_create_AthletesDiplomas_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('AthletesDiplomas', [
			'id'        => $this->primaryKey(),
			'athleteId' => $this->integer()->notNull(),
			'diplomaId' => $this->integer()->notNull(),
			'dateAdded' => $this->integer()->notNull()
		]);
		
		$this->addForeignKey("AthletesDiplomas_athleteId", "AthletesDiplomas", "athleteId", \common\models\Athlete::tableName(), "id", 'CASCADE', 'CASCADE');
		$this->addForeignKey("AthletesDiplomas_diplomaId", "AthletesDiplomas", "diplomaId", \common\models\Diploma::tableName(), "id", 'CASCADE', 'CASCADE');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('AthletesDiplomas');
	}
}
