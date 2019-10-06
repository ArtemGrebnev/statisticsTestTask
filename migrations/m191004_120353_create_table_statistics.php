<?php

use yii\db\Migration;

/**
 * Class m191004_120353_create_table_statistics
 */
class m191004_120353_create_table_statistics extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->createTable('statistics', [
			'id' => $this->bigPrimaryKey(20),
			'month' => $this->date()->notNull(),
			'user_id' => $this->string()->notNull()->defaultValue(''),
			'money' => $this->float()->notNull()->defaultValue(0),
			'hash_month_user_money' => $this->string()->notNull()->defaultValue(''),
		]);
		$this->createIndex('month','statistics', ['month']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function down()
	{

	}

}

