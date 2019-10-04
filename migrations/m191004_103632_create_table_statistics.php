<?php

use yii\db\Migration;

/**
 * Class m191004_103632_create_table_statistics
 */
class m191004_103632_create_table_statistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->createTable('statistics', [
		    'id' => $this->bigPrimaryKey(20),
		    'month' => $this->date()->notNull(),
		    'user_id' => $this->string()->notNull(),
		    'money' => $this->double()->notNull()->defaultValue(0),
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {

    }

}
