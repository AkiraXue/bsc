<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddAssetTable extends AbstractMigration
{
#region base func
    const TABLE_NAME_ASSET = 'asset';

    const TABLE_NAME_ORDER = 'order';

    const TABLE_NAME_ORDER_ITEM = 'order_item';

    public function up()
    {
        $this->down();

        $this->saveAsset();

        $this->saveOrder();

        $this->saveOrderItem();
    }

    public function down()
    {
        $this->dropAsset();

        $this->dropOrder();

        $this->dropOrderItem();
    }
#endregion

#region asset
    public function saveAsset()
    {
        $table = $this->table(self::TABLE_NAME_ASSET, [
            'signed' => false,
            'comment' => '资产',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('unique_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '关联唯一码'])
            ->addColumn('name', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '资产名称'])
            ->addColumn('source', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '资产来源 - string'])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '资产类型 1-个人积分 2-组别积分 3-实体资金'
            ])
            ->addColumn('total', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '总数', 'default' => '1'])
            ->addColumn('used', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '已用', 'default' => '1'])
            ->addColumn('remaining', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '剩余', 'default' => '1'])
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
            ->addIndex(['unique_code'], ['name' => 'idx_unique_code'])
            ->create();
    }

    public function dropAsset()
    {
        if ($this->hasTable(self::TABLE_NAME_ASSET)) {
            $this->table(self::TABLE_NAME_ASSET)->drop()->save();
        }
    }
#endregion

#region order
    public function saveOrder()
    {
        $table = $this->table(self::TABLE_NAME_ORDER, [
            'signed' => false,
            'comment' => '订单',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('unique_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '关联唯一码'])
            ->addColumn('trade_no', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '订单编号'])
            ->addColumn('price', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '订单总价', 'default' => '1'])
            ->addColumn('purchase_time', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '付款时间'])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-创建未付款 2-创建已付款，未发货 3-已付款，未收货 4.已付款，已收货 5. 订单失败'
            ])
            ->addColumn('remark', 'text', ['null' => false, 'default' => '', 'comment' => '订单备注'])
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
            ->addIndex(['unique_code'], ['name' => 'idx_unique_code'])
            ->addIndex(['trade_no'], ['name' => 'idx_trade_no'])
            ->create();
    }

    public function dropOrder()
    {
        if ($this->hasTable(self::TABLE_NAME_ORDER)) {
            $this->table(self::TABLE_NAME_ORDER)->drop()->save();
        }
    }
#endregion

#region order item
    public function saveOrderItem()
    {
        $table = $this->table(self::TABLE_NAME_ORDER_ITEM, [
            'signed' => false,
            'comment' => '订单',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('sku', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '商品sku'])
            ->addColumn('trade_no', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '订单编号'])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-实体商品 2-虚拟商品'
            ])
            ->addColumn('price', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '订单总价', 'default' => '1'])
            ->addColumn('name', 'string', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '商品名称'])
            ->addColumn('pic', 'string', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '商品图片'])
            ->addColumn('detail', 'text', ['null' => false, 'default' => '', 'comment' => '商品详情'])
            ->addColumn('remark', 'text', ['null' => false, 'default' => '', 'comment' => '商品备注'])
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
            ->addIndex(['sku'], ['name' => 'idx_sku'])
            ->addIndex(['trade_no'], ['name' => 'idx_trade_noe'])
            ->create();
    }

    public function dropOrderItem()
    {
        if ($this->hasTable(self::TABLE_NAME_ORDER_ITEM)) {
            $this->table(self::TABLE_NAME_ORDER_ITEM)->drop()->save();
        }
    }

#endregion
}
