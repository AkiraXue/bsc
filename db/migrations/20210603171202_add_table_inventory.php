<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableInventory extends AbstractMigration
{
#region base info
    const TABLE_PRODUCT_NAME= 'product';
    const TABLE_INVENTORY_NAME= 'inventory';

    public function up()
    {
        $this->down();

        $table = $this->table(self::TABLE_INVENTORY_NAME, [
            'signed' => false,
            'comment' => '库存',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '字段名称'])
            ->addColumn('sku', 'char', ['null' => false, 'length' => 50, 'default' => '', 'comment' => '商品sku'])
            ->addColumn('unique_code', 'char', ['null' => false, 'length' => 50, 'default' => '', 'comment' => '唯一码'])
            ->addColumn('unique_pass', 'string', ['signed' => false, 'default' => 0, 'comment' => '密码'])
            ->addColumn('status', 'integer', [
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '字段类型 1-已兑换 2-未兑换'
            ])
            ->addColumn('sort', 'integer', ['null' => false, 'signed' => false, 'default' => 0, 'comment' => '默认排序-升序'])
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
            ->save();

            $this->table(self::TABLE_PRODUCT_NAME)
                ->changeColumn('status', 'integer', [
                    'null' => false,
                    'default' => 1,
                    'limit' => MysqlAdapter::INT_TINY,
                    'signed' => false,
                    'comment' => '状态 1-补货 2-仓库正常'
                ])
                ->addColumn('storage', 'integer', ['after' => 'status', 'null' => false, 'signed' => false, 'default' => 0, 'comment' => '库存'])
                ->save();

    }

    public function down()
    {
        if ($this->table(self::TABLE_INVENTORY_NAME)->exists()) {
            $this->table(self::TABLE_INVENTORY_NAME)->drop()->save();
        }

        if ($this->table(self::TABLE_PRODUCT_NAME)->hasColumn('storage')) {
            $this->table(self::TABLE_INVENTORY_NAME)->removeColumn('storage')->save();
        }
    }
#endregion
}
