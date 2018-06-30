<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Votes`.
 */
class m180628_180201_create_Votes_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('Votes', [
			'id'          => $this->primaryKey(),
			'athleteId'   => $this->integer()->notNull(),
			'interviewId' => $this->integer()->notNull(),
			'answerId'    => $this->integer()->notNull(),
			'dateAdded'   => $this->integer()->notNull()
		]);
		$this->addForeignKey('fk_Votes_athleteId', 'Votes', 'athleteId', \common\models\Athlete::tableName(), 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_Votes_interviewId', 'Votes', 'interviewId', 'Interviews', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_Votes_answerId', 'Votes', 'answerId', 'InterviewAnswers', 'id', 'CASCADE', 'CASCADE');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('Votes');
	}
}
