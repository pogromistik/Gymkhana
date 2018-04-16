<?php

use yii\db\Migration;

class m171020_100002_add_params_for_translate extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Stage::tableName(), 'title_en', $this->string());
		$this->addColumn(\common\models\Stage::tableName(), 'descr_en', $this->text());
		
		$this->addColumn(\common\models\Championship::tableName(), 'title_en', $this->string());
		$this->addColumn(\common\models\Championship::tableName(), 'descr_en', $this->text());
		
		$this->addColumn(\common\models\SpecialStage::tableName(), 'title_en', $this->string());
		$this->addColumn(\common\models\SpecialStage::tableName(), 'descr_en', $this->text());
		
		$this->addColumn(\common\models\SpecialChamp::tableName(), 'title_en', $this->string());
		$this->addColumn(\common\models\SpecialChamp::tableName(), 'descr_en', $this->text());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Stage::tableName(), 'title_en');
		$this->dropColumn(\common\models\Stage::tableName(), 'descr_en');
		
		$this->dropColumn(\common\models\Championship::tableName(), 'title_en');
		$this->dropColumn(\common\models\Championship::tableName(), 'descr_en');
		
		$this->dropColumn(\common\models\SpecialStage::tableName(), 'title_en');
		$this->dropColumn(\common\models\SpecialStage::tableName(), 'descr_en');
		
		$this->dropColumn(\common\models\SpecialChamp::tableName(), 'title_en');
		$this->dropColumn(\common\models\SpecialChamp::tableName(), 'descr_en');
	}
}
