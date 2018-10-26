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

 Date: 26/10/2018 14:39:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sn_flow_log
-- ----------------------------
DROP TABLE IF EXISTS `sn_flow_log`;
CREATE TABLE `sn_flow_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT 0 COMMENT '用户id',
  `order_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '0' COMMENT '订单编号',
  `number` decimal(11, 2) DEFAULT 0.00 COMMENT '数量',
  `balance` decimal(11, 2) DEFAULT 0.00 COMMENT '剩余',
  `type` tinyint(1) DEFAULT 1 COMMENT '类型1开仓缴纳保证金2平仓盈亏 3申请提现',
  `mold` tinyint(1) DEFAULT 0 COMMENT '类型 0 加钱 1减钱',
  `time` int(11) UNSIGNED DEFAULT 0 COMMENT '时间',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '资金流水' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sn_flow_log
-- ----------------------------
INSERT INTO `sn_flow_log` VALUES (1, 60, '0', 0.00, 0.00, 1, 0, 0, '111');
INSERT INTO `sn_flow_log` VALUES (2, 64, '0', 0.00, 0.00, 1, 0, 0, NULL);

SET FOREIGN_KEY_CHECKS = 1;
