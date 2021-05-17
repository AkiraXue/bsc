<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableProduct extends AbstractMigration
{
#region base func
    const TABLE_NAME_PRODUCT = 'product';

    public function up()
    {
        $this->down();

        $this->saveProduct();
    }

    public function down()
    {
        $this->dropProduct();
    }
#endregion

#region knowledge
    public function saveProduct()
    {
        $table = $this->table(self::TABLE_NAME_PRODUCT, [
            'signed' => false,
            'comment' => '商品',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('sku', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '商品sku'])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-实体商品 2-虚拟商品'
            ])
            ->addColumn('name', 'string', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '商品名称'])
            ->addColumn('pic', 'string', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '商品图片'])
            ->addColumn('price', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '商品价格', 'default' => '1'])
            ->addColumn('detail', 'text', ['null' => false, 'comment' => '商品详情'])
            ->addColumn('remark', 'text', ['null' => false, 'comment' => '商品备注'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-已兑换 2-未兑换'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->create();
    }

    public function dropProduct()
    {
        if ($this->hasTable(self::TABLE_NAME_PRODUCT)) {
            $this->table(self::TABLE_NAME_PRODUCT)->drop()->save();
        }
    }
#endregion
}
