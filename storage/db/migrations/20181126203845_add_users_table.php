<?php


use Phinx\Migration\AbstractMigration;

class AddUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table(
            'users',
            [
                'id'     => 'id',
                'signed' => false,
            ]
        );

        $table
            ->addColumn(
                'status',
                'string',
                [
                    'limit'   => 1,
                    'null'    => false,
                    'default' => 'N'
                ]
            )
            ->addColumn(
                'username',
                'string',
                [
                    'limit'   => 128,
                    'null'    => false,
                ]
            )
            ->addColumn(
                'password',
                'string',
                [
                    'limit'   => 128,
                    'null'    => false,
                    'default' => '',
                ]
            )
            ->addColumn(
                'issuer',
                'string',
                [
                    'limit'   => 128,
                    'null'    => false,
                    'default' => '',
                ]
            )
            ->addColumn(
                'tokenPassword',
                'string',
                [
                    'limit'   => 256,
                    'null'    => false,
                    'default' => '',
                ]
            )
            ->addColumn(
                'tokenId',
                'string',
                [
                    'limit'   => 128,
                    'null'    => false,
                    'default' => '',
                ]
            )
            ->addIndex('tokenId')
            ->addIndex('status')
            ->addIndex('tokenId')
            ->addIndex('username', ['unique' => true])
            ->addIndex('password')
            ->save();

        $this->execute(
            'ALTER TABLE users ' .
            'CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
        );
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
