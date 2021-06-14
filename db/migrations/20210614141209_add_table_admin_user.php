<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableAdminUser extends AbstractMigration
{
    const TABLE_ADMIN_USER_NAME= 'admin_user';

    public function up()
    {
        $this->down();

        $table = $this->table(self::TABLE_ADMIN_USER_NAME, [
            'signed' => false,
            'comment' => '管理员账户',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '字段名称'])
            ->addColumn('password', 'char', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '密码'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户account_id'])
            ->addColumn('avatar', 'char', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '用户头像'])
            ->addColumn('token', 'text', ['null' => false, 'comment' => 'token'])
            ->addColumn('role', 'char', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '角色 - admin、editor、guest'])
            ->addColumn('description', 'text', ['null' => false, 'comment' => '描述'])
            ->addColumn('phone', 'char', ['null' => false, 'default' => '', 'length' => 20, 'comment' => '座机'])
            ->addColumn('mobile', 'char', ['null' => false, 'default' => '', 'length' => 20, 'comment' => '手机号'])
            ->addColumn('login_time', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '登陆时间'])
            ->addColumn('register_time', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '注册时间'])
            ->addColumn('ext_int_1', 'integer')
            ->addColumn('ext_int_2', 'integer')
            ->addColumn('ext_int_3', 'integer')
            ->addColumn('ext_int_4', 'integer')
            ->addColumn('ext_int_5', 'integer')
            ->addColumn('ext_varchar_1', 'string', ['limit' => 50])
            ->addColumn('ext_varchar_2', 'string', ['limit' => 50])
            ->addColumn('ext_varchar_3', 'string', ['limit' => 50])
            ->addColumn('ext_varchar_4', 'string', ['limit' => 50])
            ->addColumn('ext_varchar_5', 'string', ['limit' => 50])
            ->addColumn('state', 'integer', [
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '字段类型 1-有效 2-无效'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['account_id'], ['name' => 'idx_account_id'])
            ->addIndex(['mobile'], ['name' => 'idx_mobile'])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_ADMIN_USER_NAME)->exists()) {
            $this->table(self::TABLE_ADMIN_USER_NAME)->drop()->save();
        }
    }
}
