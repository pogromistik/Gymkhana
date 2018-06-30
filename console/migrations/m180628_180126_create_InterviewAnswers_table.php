<?php

use yii\db\Migration;

/**
 * Handles the creation of table `InterviewAnswers`.
 */
class m180628_180126_create_InterviewAnswers_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('InterviewAnswers', [
			'id'          => $this->primaryKey(),
			'interviewId' => $this->integer()->notNull(),
			'imgPath'     => $this->string(),
			'text'        => $this->string()->notNull(),
			'textEn'      => $this->string(),
			'votesCount'  => $this->integer()->notNull()->defaultValue(0)
		]);
		$this->addForeignKey('fk_InterviewAnswers_interviewId', 'InterviewAnswers', 'interviewId', 'Interviews', 'id', 'CASCADE', 'CASCADE');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('InterviewAnswers');
	}
}
