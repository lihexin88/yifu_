/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.1.232
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : 192.168.1.232:3306
 Source Schema         : yifu1

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 26/10/2018 11:56:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sn_order(暂未用)
-- ----------------------------
DROP TABLE IF EXISTS `sn_order(暂未用)`;
CREATE TABLE `sn_order(暂未用)`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `order_sn` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '订单号',
  `order_number` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购买个数',
  `price` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单价',
  `order_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单状态：0待支付,1已支付，2已确认支付，3已撤单',
  `buyer_id` int(11) NOT NULL COMMENT '买家id',
  `seller_id` int(11) UNSIGNED NOT NULL COMMENT '卖家id',
  `addtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `done_time` int(11) DEFAULT NULL COMMENT '确认收到支付时间',
  `trade_id` int(11) DEFAULT NULL COMMENT '交易表ID',
  `trade_type` tinyint(1) DEFAULT NULL COMMENT '交易类型：1求购，2出售',
  `residual_quantity` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '剩余数量',
  `voucher` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '凭证',
  `cur_id` int(11) DEFAULT NULL COMMENT '虚拟币ID',
  `cancel_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '撤单原因',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sn_order(暂未用)
-- ----------------------------
INSERT INTO `sn_order(暂未用)` VALUES (1, NULL, '100', '10', 0, 2, 1, NULL, NULL, NULL, 1, 1, '9', '/upload/id1/20180706\\5e39193aa00a2c2e860af4bfc56e480b.jpg', 2, NULL);

SET FOREIGN_KEY_CHECKS = 1;
