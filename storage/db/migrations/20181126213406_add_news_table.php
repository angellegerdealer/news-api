<?php


use Phinx\Migration\AbstractMigration;

class AddNewsTable extends AbstractMigration
{
    public function up()
    {

        $table = $this->table(
            'news',
            [
                'id' => 'id',
                'signed' => false,
            ]
        );

        $table
            ->addColumn(
                'title',
                'string',
                [
                    'limit' => 255,
                    'null' => false,
                    'default' => '',
                ]
            )
            ->addColumn(
                'content',
                'text',
                [
                    'null' => false
                ]
            )
            ->addColumn(
                'user_id',
                'integer',
                [
                    'limit' => 11,
                    'null' => false,
                ]
            )
            ->addColumn(
                'created_date',
                'datetime',
                [
                    'null' => true,
                    'after' => 'user_id']
            )
            ->addColumn(
                'updated_date',
                'datetime',
                [
                    'null' => true,
                    'after' => 'created_date']
            )
            ->addColumn(
                'status',
                'string',
                [
                    'limit' => 1,
                    'null' => false,
                    'default' => 'N',
                ]
            )
            ->addColumn(
                'views',
                'integer',
                [
                    'limit' => 11,
                    'null' => false,
                    'default' => 0,
                ]
            )
            ->addIndex('title')
            ->save();

        $this->execute(
            'ALTER TABLE news ' .
            'CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
        );

    }

    public function down()
    {
        $this->dropTable('news');
    }
}
