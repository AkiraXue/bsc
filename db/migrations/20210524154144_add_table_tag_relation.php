<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

/**
 * Class AddTableTagRelation
 */
class AddTableTagRelation extends AbstractMigration
{

#region base info
    const TABLE_TAG_NAME= 'tag';
    const TABLE_TAG_RELATION_NAME= 'tag_relation';

    public function up()
    {
        $this->down();

        $this->saveTag();
    }

    public function down()
    {
        if ($this->table(self::TABLE_TAG_NAME)->exists()) {
            $this->table(self::TABLE_TAG_NAME)->drop()->save();
        }
        if ($this->table(self::TABLE_TAG_RELATION_NAME)->exists()) {
            $this->table(self::TABLE_TAG_RELATION_NAME)->drop()->save();
        }
    }
#endregion

#region
    public function saveTag()
    {
        $table = $this->table(self::TABLE_TAG_NAME, [
            'signed' => false,
            'comment' => '标签表',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '字段名称'])
            ->addColumn('sub_name', 'char', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '字段子名称'])
            ->addColumn('parent_tag_id', 'integer', ['signed' => false, 'default' => 0, 'comment' => '默认父级id'])
            ->addColumn('relation_type', 'string', ['limit' => 254, 'default' => '', 'comment' => '关联的数据类型'])
            ->addColumn('desc', 'string', ['null' => false, 'limit' => 254, 'default' => '', 'comment' => '字段描述'])
            ->addColumn('bg_pic', 'string', ['null' => false, 'limit' => 254, 'default' => '', 'comment' => '背景图片地址'])
            ->addColumn('pic_type', 'integer', [
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '图片类型 1-小图 2-大图'
            ])
            ->addColumn('bg_video', 'string', ['null' => false, 'limit' => 254, 'default' => '', 'comment' => '背景视频地址'])
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

        $table = $this->table(self::TABLE_TAG_RELATION_NAME, [
            'signed' => false,
            'comment' => '标签关联关系表',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('unique_code', 'string', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '唯一code'])
            ->addColumn('type', 'string', ['null' => false, 'limit' => 254, 'default' => '', 'comment' => '唯一code类型'])
            ->addColumn('tag_id', 'integer', ['null' => false, 'signed' => false, 'default' => 0, 'comment' => '用户标签关联id'])
            ->addColumn('desc', 'string', ['null' => false, 'limit' => 254, 'default' => '', 'comment' => '关联信息描述'])
            ->addColumn('sort', 'integer', ['null' => false, 'signed' => false, 'default' => 0, 'comment' => '用户标签关联id排序'])
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
            ->addIndex(['tag_id'], ['name' => 'idx_tag_id'])
            ->addIndex(['unique_code'], ['name' => 'idx_unique_code'])
            ->save();
    }
#endregion
}
