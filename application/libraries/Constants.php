<?php
/**
 * Constants.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/7/21 1:13 PM
 */

namespace Lib;

class Constants
{
    // 添加-add 修改-modify
    const ACT_ADD = 'add';
    const ACT_MODIFY = 'modify';

    // 值 1-是 2-否
    const YES_VALUE = 1;
    const NO_VALUE  = 2;

    // 视频 - video，图片 - pic， 图文 - graphic，文案 - text
    const MATERIAL_TYPE_VIDEO = 'video';
    const MATERIAL_TYPE_PIC = 'pic';
    const MATERIAL_TYPE_GRAPHIC = 'graphic';
    const MATERIAL_TYPE_TEXT = 'text';

    const KNOWLEDGE_TYPE_BANNER = 'banner';
    const KNOWLEDGE_TYPE_GUIDE = 'guide';

    // tag type
    const TAG_RELATION_TYPE_KNOWLEDGE_ID = 'knowledge_id';

    // 1-总规则 2-冲顶规则
    const RULE_TYPE_ALL = 1;
    const RULE_TYPE_PRIZE = 2;

    // asset type - 1.积分 jifen  2.现金 cash
    const ASSET_TYPE_JIFEN = 'jifen';
    const ASSET_TYPE_CASH = 'cash';

    // product type - 1-实体商品 2-虚拟商品
    const PRODUCT_TYPE_PHYSICAL = 1;
    const PRODUCT_TYPE_VIRTUAL = 2;

    // order status - 1. 已下单，未付款 2. 已付款，未发货 3. 已付款已发货 4. 已付款已收货 5. 订单关闭
    const ORDER_STATUS_PROCESSING = 1;
    const ORDER_STATUS_PURCHASED = 2;
    const ORDER_STATUS_DELIVER = 3;
    const ORDER_STATUS_RECEIVED = 4;
    const ORDER_STATUS_CLOSED = 5;

    // 商品 1-已兑换 2-未兑换
    const PRODUCT_STATUS_DELIVER = 1;
    const PRODUCT_STATUS_STORAGE = 2;
}

