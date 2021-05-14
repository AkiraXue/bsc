<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

/**
 * Class AddBaseTable
 */
class AddBaseTable extends AbstractMigration
{
#region base func
    const TABLE_NAME_USE = 'user';

    const TABLE_NAME_GROUP = 'group';
    const TABLE_NAME_GROUP_ITEM = 'group_item';

    public function up()
    {
        $this->down();

        $this->saveUser();

        $this->saveGroup();

        $this->saveGroupItem();
    }

    public function down()
    {
        $this->dropUser();

        $this->dropGroup();
        $this->dropGroupItem();
    }
#endregion

#region db user
    public function saveUser()
    {
        $table = $this->table(self::TABLE_NAME_USE, [
            'signed' => false,
            'comment' => '用户',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '名称'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户account_id'])
            ->addColumn('openid', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '用户微信openid'])
            ->addColumn('birthday', 'char', ['null' => false, 'default' => '', 'length' => 20, 'comment' => '用户生日'])
            ->addColumn('phone', 'char', ['null' => false, 'default' => '', 'length' => 20, 'comment' => '座机'])
            ->addColumn('mobile', 'char', ['null' => false, 'default' => '', 'length' => 20, 'comment' => '手机号'])
            ->addColumn('register_time', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '注册时间'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
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
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['account_id'], ['name' => 'idx_account_id'])
            ->addIndex(['openid'], ['name' => 'idx_openid'])
            ->addIndex(['mobile'], ['name' => 'idx_mobile'])
            ->create();
    }

    public function dropUser()
    {
        if ($this->hasTable(self::TABLE_NAME_USE)) {
            $this->table(self::TABLE_NAME_USE)->drop()->save();
        }
    }
#endregion

#region group
    public function saveGroup()
    {
        $table = $this->table(self::TABLE_NAME_GROUP, [
            'signed' => false,
            'comment' => '分组',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '分组唯一码'])
            ->addColumn('name', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '名称'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['code'], ['name' => 'idx_code'])
            ->create();
    }

    public function dropGroup()
    {
        if ($this->hasTable(self::TABLE_NAME_GROUP)) {
            $this->table(self::TABLE_NAME_GROUP)->drop()->save();
        }
    }
#endregion

#region group item
    public function saveGroupItem()
    {
        $table = $this->table(self::TABLE_NAME_GROUP_ITEM, [
            'signed' => false,
            'comment' => '分组子项',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('group_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '分组唯一码'])
            ->addColumn('unique_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '分组关联子项唯一码'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
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
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['group_code'], ['name' => 'idx_group_code'])
            ->addIndex(['unique_code'], ['name' => 'idx_unique_code'])
            ->create();
    }

    public function dropGroupItem()
    {
        if ($this->hasTable(self::TABLE_NAME_GROUP_ITEM)) {
            $this->table(self::TABLE_NAME_GROUP_ITEM)->drop()->save();
        }
    }
#endregion
}
