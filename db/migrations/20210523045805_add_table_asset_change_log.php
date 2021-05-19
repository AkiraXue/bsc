<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableAssetChangeLog extends AbstractMigration
{
    const TABLE_NAME_ASSET_CHANGE_LOG = 'asset_change_log';

    public function up()
    {
        $this->down();

        $table = $this->table(self::TABLE_NAME_ASSET_CHANGE_LOG, [
            'signed' => false,
            'comment' => '资产表变动记录',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('unique_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '关联唯一码'])
            ->addColumn('source', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '资产来源 - string'])
            ->addColumn('type', 'char', ['null' => false, 'default' => 'jifen', 'length' => 50, 'comment' => '资产类型 jifen-个人积分 group_jifen-组别积分'])
            ->addColumn('act', 'char', ['null' => false, 'default' => 'jifen', 'length' => 50, 'comment' => '动作 increase / decrease'])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '变动金额', 'default' => '1'])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['unique_code'], ['name' => 'idx_unique_code'])
            ->create();
    }

    public function down()
    {
        if ($this->hasTable(self::TABLE_NAME_ASSET_CHANGE_LOG)) {
            $this->table(self::TABLE_NAME_ASSET_CHANGE_LOG)->drop()->save();
        }
    }
}
