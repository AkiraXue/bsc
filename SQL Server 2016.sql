/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : localhost:3306
 Source Schema         : bsc

 Target Server Type    : SQL Server
 Target Server Version : 13000000
 File Encoding         : 65001

 Date: 09/08/2021 13:22:05
*/


-- ----------------------------
-- Table structure for activity
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[activity]') AND type IN ('U'))
	DROP TABLE [dbo].[activity]
GO

CREATE TABLE [dbo].[activity] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [code] nchar(32) NOT NULL DEFAULT '',
  [days] int NOT NULL DEFAULT 0,
  [start_date] date NOT NULL,
  [end_date] date NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'名称',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动周期',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'days'
GO

EXEC sp_addextendedproperty
'MS_Description', N'起始日期',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'start_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'截止日期',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'end_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'activity',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动',
'SCHEMA', N'dbo',
'TABLE', N'activity'
GO


-- ----------------------------
-- Records of activity
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[activity] ON
GO

INSERT INTO [dbo].[activity] ([id], [name], [code], [days], [start_date], [end_date], [state], [created_at], [updated_at]) VALUES (N'1', N'新员工21天挑战', N'837cbd81efa44e31bc127696592cc539', N'21', N'2021-06-07', N'', N'1', N'2021-05-24 00:41:55', N'2021-06-22 16:47:48')
GO

SET IDENTITY_INSERT [dbo].[activity] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for activity_participate_record
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[activity_participate_record]') AND type IN ('U'))
	DROP TABLE [dbo].[activity_participate_record]
GO

CREATE TABLE [dbo].[activity_participate_record] (
  [id] int NOT NULL identity(1,1),
  [activity_code] nchar(32) NOT NULL DEFAULT '',
  [account_id] nchar(32) NOT NULL DEFAULT '',
  [day] int NOT NULL DEFAULT 0,
  [activity_schedule_id] int NOT NULL DEFAULT 0,
  [is_related_knowledge] tinyint NOT NULL DEFAULT 2,
  [knowledge_id] int NOT NULL DEFAULT 0,
  [is_asset_award] tinyint NOT NULL DEFAULT 2,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [is_knowledge] tinyint NOT NULL DEFAULT 2,
  [knowledge_time] datetime2 NOT NULL,
  [is_knowledge_asset_award] tinyint NOT NULL DEFAULT 2,
  [knowledge_asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [is_punch] tinyint NOT NULL DEFAULT 2,
  [punch_time] datetime2 NOT NULL,
  [punch_date] date NOT NULL,
  [recent_punch_date] date NOT NULL,
  [next_punch_date] date NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'activity_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动第几天',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'day'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动关联排期id',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'activity_schedule_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否关联知识科普 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'is_related_knowledge'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库id',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'knowledge_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'is_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否已知识科普 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'is_knowledge'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识科普时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'knowledge_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'is_knowledge_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'knowledge_asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否打卡  1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'is_punch'
GO

EXEC sp_addextendedproperty
'MS_Description', N'打卡时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'punch_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'当前打卡日期',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'punch_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'上次打卡日期',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'recent_punch_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'下次打卡日期',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'next_punch_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动排期参与记录',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_record'
GO


-- ----------------------------
-- Records of activity_participate_record
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for activity_participate_schedule
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[activity_participate_schedule]') AND type IN ('U'))
	DROP TABLE [dbo].[activity_participate_schedule]
GO

CREATE TABLE [dbo].[activity_participate_schedule] (
  [id] int NOT NULL identity(1,1),
  [activity_code] nchar(32) NOT NULL DEFAULT '',
  [account_id] nchar(32) NOT NULL DEFAULT '',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule',
'COLUMN', N'activity_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动参与排期关联',
'SCHEMA', N'dbo',
'TABLE', N'activity_participate_schedule'
GO


-- ----------------------------
-- Records of activity_participate_schedule
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for activity_schedule
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[activity_schedule]') AND type IN ('U'))
	DROP TABLE [dbo].[activity_schedule]
GO

CREATE TABLE [dbo].[activity_schedule] (
  [id] int NOT NULL identity(1,1),
  [activity_code] nchar(32) NOT NULL DEFAULT '',
  [day] int NOT NULL DEFAULT 0,
  [is_related_knowledge] tinyint NOT NULL DEFAULT 1,
  [knowledge_id] int NOT NULL DEFAULT 0,
  [is_knowledge_asset_award] tinyint NOT NULL DEFAULT 1,
  [knowledge_asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [is_asset_award] tinyint NOT NULL DEFAULT 1,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动唯一code',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'activity_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动第几天',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'day'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否关联知识科普 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'is_related_knowledge'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库id',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'knowledge_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'is_knowledge_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'knowledge_asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'is_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'活动排期',
'SCHEMA', N'dbo',
'TABLE', N'activity_schedule'
GO


-- ----------------------------
-- Records of activity_schedule
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[activity_schedule] ON
GO

INSERT INTO [dbo].[activity_schedule] ([id], [activity_code], [day], [is_related_knowledge], [knowledge_id], [is_knowledge_asset_award], [knowledge_asset_num], [is_asset_award], [asset_num], [state], [created_at], [updated_at]) VALUES (N'1', N'837cbd81efa44e31bc127696592cc539', N'1', N'1', N'1', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-05-25 22:46:18', N'2021-06-11 16:24:36'), (N'2', N'837cbd81efa44e31bc127696592cc539', N'2', N'1', N'2', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-05-25 22:46:18', N'2021-06-11 16:24:36'), (N'3', N'837cbd81efa44e31bc127696592cc539', N'3', N'1', N'3', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-07 17:17:31', N'2021-06-11 16:24:36'), (N'4', N'837cbd81efa44e31bc127696592cc539', N'4', N'1', N'4', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-08 01:40:45', N'2021-06-11 16:24:36'), (N'5', N'837cbd81efa44e31bc127696592cc539', N'5', N'1', N'7', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:07:04', N'2021-06-11 16:24:36'), (N'6', N'837cbd81efa44e31bc127696592cc539', N'6', N'1', N'8', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:07:26', N'2021-06-11 16:24:36'), (N'7', N'837cbd81efa44e31bc127696592cc539', N'7', N'1', N'9', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:08:05', N'2021-06-11 16:24:36'), (N'8', N'837cbd81efa44e31bc127696592cc539', N'8', N'1', N'10', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:08:33', N'2021-06-11 16:24:36'), (N'9', N'837cbd81efa44e31bc127696592cc539', N'9', N'1', N'11', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:08:53', N'2021-06-11 16:24:36'), (N'10', N'837cbd81efa44e31bc127696592cc539', N'10', N'1', N'13', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:09:13', N'2021-06-11 16:24:36'), (N'11', N'837cbd81efa44e31bc127696592cc539', N'11', N'1', N'14', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:09:31', N'2021-06-11 16:24:36'), (N'12', N'837cbd81efa44e31bc127696592cc539', N'12', N'1', N'15', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:09:55', N'2021-06-11 16:24:36'), (N'13', N'837cbd81efa44e31bc127696592cc539', N'13', N'1', N'16', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:10:14', N'2021-06-11 16:24:36'), (N'14', N'837cbd81efa44e31bc127696592cc539', N'14', N'1', N'18', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:10:32', N'2021-06-11 16:24:36'), (N'15', N'837cbd81efa44e31bc127696592cc539', N'15', N'1', N'19', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:10:52', N'2021-06-11 16:24:36'), (N'16', N'837cbd81efa44e31bc127696592cc539', N'16', N'1', N'20', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:11:14', N'2021-06-11 16:24:36'), (N'17', N'837cbd81efa44e31bc127696592cc539', N'17', N'1', N'22', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:11:37', N'2021-06-11 16:24:36'), (N'18', N'837cbd81efa44e31bc127696592cc539', N'18', N'1', N'39', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:11:59', N'2021-06-11 16:24:36'), (N'19', N'837cbd81efa44e31bc127696592cc539', N'19', N'1', N'42', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:12:18', N'2021-06-11 16:24:36'), (N'20', N'837cbd81efa44e31bc127696592cc539', N'20', N'1', N'43', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:12:39', N'2021-06-11 16:24:36'), (N'21', N'837cbd81efa44e31bc127696592cc539', N'21', N'1', N'60', N'1', N'150.00', N'1', N'30.00', N'1', N'2021-06-09 17:12:59', N'2021-06-11 16:24:36')
GO

SET IDENTITY_INSERT [dbo].[activity_schedule] OFF
GO

COMMIT
GO

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[admin_user]') AND type IN ('U'))
	DROP TABLE [dbo].[admin_user]
GO

CREATE TABLE [dbo].[admin_user] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(32) NOT NULL DEFAULT '',
  [password] nvarchar(254) NOT NULL DEFAULT '',
  [account_id] nchar(32) NOT NULL DEFAULT '',
  [avatar] nvarchar(254) NOT NULL DEFAULT '',
  [token] nvarchar(max) NOT NULL,
  [role] nvarchar(254) NOT NULL DEFAULT '',
  [description] nvarchar(max) NOT NULL,
  [phone] nvarchar(20) NOT NULL DEFAULT '',
  [mobile] nvarchar(20) NOT NULL DEFAULT '',
  [login_time] datetime2 NOT NULL,
  [register_time] datetime2 NOT NULL,
  [ext_int_1] int NOT NULL DEFAULT 0,
  [ext_int_2] int NOT NULL DEFAULT 0,
  [ext_int_3] int NOT NULL DEFAULT 0,
  [ext_int_4] int NOT NULL DEFAULT 0,
  [ext_int_5] int NOT NULL DEFAULT 0,
  [ext_varchar_1] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_2] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_3] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_4] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_5] nvarchar(50) NOT NULL DEFAULT '',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段名称',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'密码',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'password'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户account_id',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户头像',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'avatar'
GO

EXEC sp_addextendedproperty
'MS_Description', N'token',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'token'
GO

EXEC sp_addextendedproperty
'MS_Description', N'角色 - admin、editor、guest',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'role'
GO

EXEC sp_addextendedproperty
'MS_Description', N'描述',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'description'
GO

EXEC sp_addextendedproperty
'MS_Description', N'座机',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'phone'
GO

EXEC sp_addextendedproperty
'MS_Description', N'手机号',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'mobile'
GO

EXEC sp_addextendedproperty
'MS_Description', N'登陆时间',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'login_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'注册时间',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'register_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-有效 2-无效',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'admin_user',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'管理员账户',
'SCHEMA', N'dbo',
'TABLE', N'admin_user'
GO


-- ----------------------------
-- Records of admin_user
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[admin_user] ON
GO

INSERT INTO [dbo].[admin_user] ([id], [name], [password], [account_id], [avatar], [token], [role], [description], [phone], [mobile], [login_time], [register_time], [ext_int_1], [ext_int_2], [ext_int_3], [ext_int_4], [ext_int_5], [ext_varchar_1], [ext_varchar_2], [ext_varchar_3], [ext_varchar_4], [ext_varchar_5], [state], [created_at], [updated_at]) VALUES (N'1', N'admin', N'd93a5def7511da3d0f2d171d9c344e91', N'd8262d73375b4becbd3f08c18bcbb279', N'', N'{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJKV1RfVE9LRU4iLCJpYXQiOjE2MjQyNjM2NjgsIm5iZiI6MTYyNDI2MzY2OCwiZXhwIjoxNjI2ODU1NjY4LCJhY2NvdW50X2lkIjoiZDgyNjJkNzMzNzViNGJlY2JkM2YwOGMxOGJjYmIyNzkiLCJuYW1lIjoiYWRtaW4iLCJyb2xlIjoiYWRtaW4iLCJkZXNjcmlwdGlvbiI6Ilx1NjJlNVx1NjcwOVx1N2NmYlx1N2VkZlx1NTE4NVx1NjI0MFx1NjcwOVx1ODNkY1x1NTM1NVx1NTQ4Y1x1OGRlZlx1NzUzMVx1Njc0M1x1OTY1MCJ9.Kt85z355pFydStWgkL4kOCOPox0qSqBgAb8VITX-eP8","expired_at":1626855668}', N'admin', N'拥有系统内所有菜单和路由权限', N'', N'', N'2021-06-21 16:21:08', N'2021-06-15 02:13:27', N'0', N'0', N'0', N'0', N'0', N'', N'', N'', N'', N'', N'1', N'2021-06-15 02:13:27', N'2021-06-21 16:21:08')
GO

SET IDENTITY_INSERT [dbo].[admin_user] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for asset
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[asset]') AND type IN ('U'))
	DROP TABLE [dbo].[asset]
GO

CREATE TABLE [dbo].[asset] (
  [id] int NOT NULL identity(1,1),
  [unique_code] nchar(32) NOT NULL DEFAULT '',
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [source] nvarchar(50) NOT NULL DEFAULT '',
  [type] nvarchar(50) NOT NULL DEFAULT '',
  [total] decimal(10,2) NOT NULL DEFAULT '1.00',
  [used] decimal(10,2) NOT NULL DEFAULT '1.00',
  [remaining] decimal(10,2) NOT NULL DEFAULT '1.00',
  [state] tinyint NOT NULL DEFAULT 1,
  [ext_int_1] int NOT NULL DEFAULT 0,
  [ext_int_2] int NOT NULL DEFAULT 0,
  [ext_int_3] int NOT NULL DEFAULT 0,
  [ext_int_4] int NOT NULL DEFAULT 0,
  [ext_int_5] int NOT NULL DEFAULT 0,
  [ext_varchar_1] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_2] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_3] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_4] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_5] nvarchar(50) NOT NULL DEFAULT '',
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联唯一码',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产名称',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产来源 - string',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'source'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产类型 1-个人积分 2-组别积分 3-实体资金',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'总数',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'total'
GO

EXEC sp_addextendedproperty
'MS_Description', N'已用',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'used'
GO

EXEC sp_addextendedproperty
'MS_Description', N'剩余',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'remaining'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'asset',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产',
'SCHEMA', N'dbo',
'TABLE', N'asset'
GO


-- ----------------------------
-- Records of asset
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for asset_change_log
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[asset_change_log]') AND type IN ('U'))
	DROP TABLE [dbo].[asset_change_log]
GO

CREATE TABLE [dbo].[asset_change_log] (
  [id] int NOT NULL identity(1,1),
  [unique_code] nvarchar(32) NOT NULL DEFAULT '',
  [source] nvarchar(50) NOT NULL DEFAULT '',
  [type] nvarchar(50) NOT NULL DEFAULT 'jifen',
  [act] nvarchar(50) NOT NULL DEFAULT 'increase',
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联唯一码',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产来源 - string',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'source'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产类型 jifen-个人积分 group_jifen-组别积分',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'动作 increase / decrease',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'act'
GO

EXEC sp_addextendedproperty
'MS_Description', N'变动金额',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产表变动记录',
'SCHEMA', N'dbo',
'TABLE', N'asset_change_log'
GO


-- ----------------------------
-- Records of asset_change_log
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for group
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[group]') AND type IN ('U'))
	DROP TABLE [dbo].[group]
GO

CREATE TABLE [dbo].[group] (
  [id] int NOT NULL identity(1,1),
  [code] nchar(32) NOT NULL DEFAULT '',
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'分组唯一码',
'SCHEMA', N'dbo',
'TABLE', N'group',
'COLUMN', N'code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'名称',
'SCHEMA', N'dbo',
'TABLE', N'group',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'group',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'group',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'group',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'分组',
'SCHEMA', N'dbo',
'TABLE', N'group'
GO


-- ----------------------------
-- Records of group
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for group_item
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[group_item]') AND type IN ('U'))
	DROP TABLE [dbo].[group_item]
GO

CREATE TABLE [dbo].[group_item] (
  [id] int NOT NULL identity(1,1),
  [group_code] nchar(32) NOT NULL DEFAULT '',
  [unique_code] nchar(32) NOT NULL DEFAULT '',
  [state] tinyint NOT NULL DEFAULT 1,
  [ext_int_1] int NOT NULL DEFAULT 0,
  [ext_int_2] int NOT NULL DEFAULT 0,
  [ext_int_3] int NOT NULL DEFAULT 0,
  [ext_int_4] int NOT NULL DEFAULT 0,
  [ext_int_5] int NOT NULL DEFAULT 0,
  [ext_varchar_1] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_2] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_3] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_4] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_5] nvarchar(50) NOT NULL DEFAULT '',
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'分组唯一码',
'SCHEMA', N'dbo',
'TABLE', N'group_item',
'COLUMN', N'group_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'分组关联子项唯一码',
'SCHEMA', N'dbo',
'TABLE', N'group_item',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'group_item',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'group_item',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'group_item',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'分组子项',
'SCHEMA', N'dbo',
'TABLE', N'group_item'
GO


-- ----------------------------
-- Records of group_item
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for inventory
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[inventory]') AND type IN ('U'))
	DROP TABLE [dbo].[inventory]
GO

CREATE TABLE [dbo].[inventory] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(32) NOT NULL DEFAULT '',
  [sku] nvarchar(50) NOT NULL DEFAULT '',
  [unique_code] nvarchar(50) NOT NULL DEFAULT '',
  [unique_pass] nvarchar(255) NOT NULL DEFAULT '',
  [status] tinyint NOT NULL DEFAULT 1,
  [sort] int NOT NULL DEFAULT 0,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段名称',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品sku',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'sku'
GO

EXEC sp_addextendedproperty
'MS_Description', N'唯一码',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'密码',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'unique_pass'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-已兑换 2-未兑换',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'默认排序-升序',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-有效 2-无效',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'inventory',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'库存',
'SCHEMA', N'dbo',
'TABLE', N'inventory'
GO


-- ----------------------------
-- Records of inventory
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for knowledge
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[knowledge]') AND type IN ('U'))
	DROP TABLE [dbo].[knowledge]
GO

CREATE TABLE [dbo].[knowledge] (
  [id] int NOT NULL identity(1,1),
  [title] nvarchar(50) NOT NULL DEFAULT '',
  [type] nvarchar(50) NOT NULL DEFAULT '',
  [pic] nvarchar(254) NOT NULL DEFAULT '',
  [content] nvarchar(max) NOT NULL,
  [sort] int NOT NULL DEFAULT 0,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'标题',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'title'
GO

EXEC sp_addextendedproperty
'MS_Description', N'类型 - 视频 - video，图片 - pic， 图文 - graphic，文案 - text',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'图片地址',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'内容',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'content'
GO

EXEC sp_addextendedproperty
'MS_Description', N'默认排序-升序',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'knowledge',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识库',
'SCHEMA', N'dbo',
'TABLE', N'knowledge'
GO


-- ----------------------------
-- Records of knowledge
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[knowledge] ON
GO

INSERT INTO [dbo].[knowledge] ([id], [title], [type], [pic], [content], [sort], [state], [created_at], [updated_at]) VALUES (N'1', N'关于波士顿科学', N'graphic', N'', N'{"title":"\u5173\u4e8e\u6ce2\u58eb\u987f\u79d1\u5b66","img":"\/upload\/2021\/06\/02\/20210602023123_qln6O0Wv.JPG","text":"\u7b80\u5355\u6765\u8bf4\uff0c\u6211\u4eec\u662f\u4e00\u5bb6\u533b\u7597\u79d1\u6280\u516c\u53f8\uff1b\u590d\u6742\u4e00\u70b9\u6765\u8bf4\uff0c\u6211\u4eec\u662f\u4e00\u5bb6\u4e13\u6ce8\u4e8e\u5fae\u521b\u4ecb\u5165\u3001\u6781\u5177\u521b\u65b0\u6d3b\u529b\u3001\u5168\u7403\u6027\u7684\u533b\u7597\u79d1\u6280\u516c\u53f8\uff0c\u5e0c\u671b\u901a\u8fc7\u521b\u65b0\u7684\u5fae\u521b\u4ecb\u5165\u89e3\u51b3\u65b9\u6848\uff0c\u6539\u5584\u4eba\u4eec\u7684\u751f\u6d3b\u8d28\u91cf\uff0c\u63d0\u5347\u5168\u4e16\u754c\u60a3\u8005\u7684\u5065\u5eb7\u54c1\u8d28\u3002","is_contain":"2"}', N'1', N'1', N'2021-05-30 17:54:13', N'2021-06-10 15:10:57'), (N'2', N'我们到底是做什么的？', N'graphic', N'', N'{"title":"\u6211\u4eec\u5230\u5e95\u662f\u505a\u4ec0\u4e48\u7684\uff1f","img":"\/upload\/2021\/05\/31\/20210531040600_ZcPRUTut.JPG","text":"\u4f5c\u4e3a\u4e00\u5bb6\u6709\u7740\u6d53\u539a\u521b\u65b0\u6c1b\u56f4\u7684\u516c\u53f8\uff0c\u6211\u4eec\u5171\u62e5\u670917000\u591a\u4ef6\u6539\u5584\u751f\u547d\u7684\u4ea7\u54c1\uff0c\u8fd9\u4e9b\u4ea7\u54c1\u3001\u6280\u672f\u6216\u89e3\u51b3\u65b9\u6848\u5728\u5168\u7403\u53d1\u5e03\u63a5\u8fd118000\u9879\u6388\u6743\u4e13\u5229\u3002\n\n\u6bcf\u5e74\uff0c\u5168\u7403\u91c7\u7528\u6211\u4eec\u7684\u4ea7\u54c1\u6cbb\u7597\u7684\u60a3\u8005\u4eba\u6570\u7ea63000\u4e07\uff0c\u4e5f\u5c31\u662f\u6bcf250\u4eba\u4e2d\u5c31\u6709\u4e00\u4e2a\u4eba\u4f7f\u7528\u8fc7\u6211\u4eec\u7684\u4ea7\u54c1\u3002\u501f\u52a9\u8fd9\u4e9b\u4ea7\u54c1\u548c\u89e3\u51b3\u65b9\u6848\uff0c\u6211\u4eec\u5e2e\u52a9\u8fd9\u4e9b\u60a3\u8005\u6d3b\u5f97\u66f4\u5065\u5eb7\u3001\u66f4\u957f\u5bff\u3002\n\n\u8ba9\u4eba\u5174\u594b\u548c\u6fc0\u52a8\u7684\u662f\uff0c2013\u5e74\u81f3\u4eca\uff0c\u6211\u4eec\u5171\u67096\u6b3e\u4ea7\u54c1\u6216\u6280\u672f\u65a9\u83b7\u201c\u76d6\u4f26\u5956\u201d\u6216\u63d0\u540d\u3002\uff08\u76d6\u4f26\u5956\u88ab\u8a89\u4e3a\u201c\u533b\u836f\u754c\u7684\u8bfa\u8d1d\u5c14\u5956\u201d\uff09\n","is_contain":2}', N'2', N'1', N'2021-05-30 17:54:13', N'2021-06-10 15:11:04'), (N'3', N'改善全世界人的生命质量', N'graphic', N'', N'{"title":"\u6539\u5584\u5168\u4e16\u754c\u4eba\u7684\u751f\u547d\u8d28\u91cf","img":"\/upload\/2021\/05\/31\/20210531040637_exnO4Mu8.JPG","text":"\u76ee\u524d\uff0c\u6ce2\u58eb\u987f\u79d1\u5b66\u5168\u7403\u51713.8\u4e07\u540d\u5458\u5de5\u670d\u52a1\u4e8e178\u4e2a\u673a\u6784\uff0c\u5176\u4e2d\u5305\u62ec16\u4e2a\u5206\u5e03\u5728\u4e16\u754c\u5404\u5730\u7684\u4e3b\u8981\u5236\u9020\u4e2d\u5fc3\uff0c\u4e1a\u52a1\u904d\u53ca\u5927\u7ea6120\u4e2a\u56fd\u5bb6\uff0c\u5728\u534e\u5458\u5de51300\u540d\u3002\u603b\u90e8\u4f4d\u4e8e\u4e0a\u6d77\uff0c\u7b2c\u4e8c\u603b\u90e8\u4e8e2020\u5e74\u5168\u65b0\u843d\u5ea7\u4e8e\u6210\u90fd\uff0c\u540c\u65f6\u5728\u5317\u4eac\uff0c\u5e7f\u5dde\uff0c\u9999\u6e2f\u548c\u53f0\u6e7e\u4e5f\u5206\u522b\u6709\u529e\u516c\u5904\u3002","is_contain":2}', N'3', N'1', N'2021-05-30 17:54:13', N'2021-06-21 18:01:08'), (N'4', N'波士顿科学的40年奋进之路', N'graphic', N'', N'{"title":"\u6ce2\u58eb\u987f\u79d1\u5b66\u768440\u5e74\u594b\u8fdb\u4e4b\u8def","img":"\/upload\/2021\/05\/31\/20210531043157_uKyoOcIA.JPG","text":"1979\u5e74\uff0cJohn Abele\u548cPeter Nicholas\u5728\u7f8e\u56fd\u9a6c\u8428\u8bf8\u585e\u5dde\u6210\u7acb\u6ce2\u58eb\u987f\u79d1\u5b66\u516c\u53f8\uff0c\u4e13\u6ce8\u4e8e\u5fae\u521b\u533b\u7597\u5668\u68b0\u7684\u7814\u53d1\uff0c\u5e76\u5728\u6b27\u6d32\u548c\u65e5\u672c\u7b49\u6d77\u5916\u5e02\u573a\u6269\u5c55\u4e1a\u52a1\u3002\n\n2014\u5e74\uff0c\u6ce2\u58eb\u987f\u79d1\u5b66\u6b63\u5f0f\u6210\u7acb\u5927\u4e2d\u534e\u533a\uff0c\u4e00\u8def\u9ad8\u6b4c\u731b\u8fdb\uff0c\u5728\u5927\u4e2d\u534e\u533a\u5b9e\u73b0\u4e86\u4e1a\u52a1\u7684\u5feb\u901f\u53d1\u5c55\u3002\n","is_contain":2}', N'4', N'1', N'2021-05-30 17:54:13', N'2021-06-10 15:13:22'), (N'5', N'T3创库', N'graphic', N'', N'{"title":"\u5317\u4eacT3\u521b\u5e93","img":"\/upload\/2021\/05\/31\/20210531041725_sQWrvPic.JPG","text":"T3\u521b\u5e93\u662f\u6e05\u534e\u6280\u672f\u8f6c\u79fb\u7814\u7a76\u9662\u4e0e\u6ce2\u58eb\u987f\u79d1\u5b66\u5171\u540c\u521b\u7acb\u7684\u5f00\u653e\u5f0f\u521b\u65b0\u5e73\u53f0\uff0c\u5750\u843d\u4e8e\u4e2d\u5173\u6751\u4e1c\u5347\u56fd\u9645\u521b\u4e1a\u56ed\uff0c\u8be5\u56ed\u4e3a\u5317\u4eac\u5e02\u653f\u5e9c\u7275\u5934\u6240\u6210\u7acb\u7684\u5168\u7403\u5065\u5eb7\u4ea7\u4e1a\u521b\u65b0\u5b75\u5316\u7684\u91cd\u70b9\u57fa\u5730\u3002","is_contain":2}', N'5', N'1', N'2021-05-30 17:54:13', N'2021-06-10 15:13:22'), (N'6', N'创新专业教育', N'graphic', N'', N'{"title":"\u521b\u65b0\u4e13\u4e1a\u6559\u80b2","img":"\/upload\/2021\/05\/31\/20210531041759_jLrSCFzR.JPG","text":"\u6ce2\u58eb\u987f\u79d1\u5b66\u5728\u5168\u7403\u5171\u5efa\u7acb\u4e8614\u6240\u521b\u65b0\u57f9\u8bad\u5b66\u9662\u3002\u4e2d\u56fd\u521b\u65b0\u57f9\u8bad\u5b66\u9662\uff0c\u4f9d\u6258\u56fd\u9645\u8d44\u6e90\uff0c\u65e8\u5728\u901a\u8fc7\u5148\u8fdb\u7684\u57f9\u8bad\u8bbe\u65bd\uff0c\u7ed3\u5408EDUCARE\u591a\u6837\u5316\u7684\u4e13\u4e1a\u57f9\u8bad\u8bfe\u7a0b\uff0c\u4e3a\u4e2d\u56fd\u5e7f\u5927\u533b\u52a1\u5de5\u4f5c\u8005\u63d0\u4f9b\u6559\u80b2\u4e0e\u57f9\u8bad\u589e\u503c\u670d\u52a1\u3002","is_contain":2}', N'6', N'1', N'2021-05-30 17:54:13', N'2021-06-10 15:13:22'), (N'7', N'荣誉奖项', N'graphic', N'', N'{"title":"\u8363\u8a89\u5956\u9879","img":"\/upload\/2021\/06\/02\/20210602024442_5gMbpRFZ.JPG","text":"\u6ce2\u79d1\u4ece\u521b\u7acb\u4ee5\u6765\uff0c\u5728\u5168\u4e16\u754c\u7684\u5728\u591a\u4e2a\u9886\u57df\u90fd\u83b7\u5f97\u4e86\u5353\u8d8a\u7684\u6210\u5c31\u3002","is_contain":"2"}', N'7', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'8', N'卓越的产品创新', N'graphic', N'', N'{"title":"\u5353\u8d8a\u7684\u4ea7\u54c1\u521b\u65b0","img":"\/upload\/2021\/06\/02\/20210602024148_O4f1nArD.JPG","text":"\u6ce2\u79d1\u6709\u79cd\u4f17\u591a\u5353\u8d8a\u7684\u4ea7\u54c1\u521b\u65b0\uff0c\u4ee5\u4e0b\u662f\u90e8\u5206\u4ea7\u54c1\u7684\u7b80\u4ecb\u3002","is_contain":2}', N'8', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'9', N'你眼中的波士顿科学是一家什么样的公司？', N'graphic', N'', N'{"title":"\u4f60\u773c\u4e2d\u7684\u6ce2\u58eb\u987f\u79d1\u5b66\u662f\u4e00\u5bb6\u4ec0\u4e48\u6837\u7684\u516c\u53f8\uff1f","img":"\/upload\/2021\/06\/08\/20210608071258_soUx2Zeu.jpg","text":"\u5728\u8fd9\u4e2a\u95ee\u9898\u4e4b\u4e0b\uff0c\u7ea6\u6709\u4e00\u534a\u7684\u7b54\u6848\u90fd\u63d0\u5230\u201c\u521b\u65b0\u201d\u3002\u82f1\u96c4\u6240\u89c1\u7565\u540c\uff0c\u201c\u521b\u65b0\u201d\u4f5c\u4e3a\u6df1\u6df1\u6839\u690d\u4e8e\u6ce2\u58eb\u987f\u79d1\u5b66\u8840\u6db2\u4e2d\u7684\u57fa\u56e0\uff0c\u8eab\u5904\u5176\u4e2d\u7684\u6bcf\u4e00\u4e2a\u4eba\u90fd\u53ef\u4ee5\u611f\u53d7\u5230\u3002\u540c\u65f6\uff0c\u5728\u6ce2\u79d1\u4eba\u773c\u91cc\uff0c\u201c\u4e13\u4e1a\u6027\u5f3a\u201d\u3001\u201c\u4eba\u6027\u5316\u201d\u3001\u201c\u5145\u6ee1\u6d3b\u529b\u201d\u3001\u201c\u52a1\u5b9e\u201d\u3001\u201c\u4eba\u6027\u5316\u201d\u3001\u201c\u5f00\u653e\u201d\u4e5f\u90fd\u662f\u6ce2\u58eb\u987f\u79d1\u5b66\u8eab\u4e0a\u6700\u660e\u663e\u7684\u6807\u7b7e\u3002","is_contain":"1"}', N'9', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'10', N'在波士顿科学工作究竟有什么感受？', N'graphic', N'', N'{"title":"\u5728\u6ce2\u58eb\u987f\u79d1\u5b66\u5de5\u4f5c\u7a76\u7adf\u6709\u4ec0\u4e48\u611f\u53d7\uff1f","img":"\/upload\/2021\/06\/08\/20210608071313_thmIvgHC.jpg","text":"\u201c\u521b\u65b0\u201d\u3001\u201c\u4e13\u4e1a\u201d\u3001\u201c\u5f00\u5fc3\u201d\u3001\u201c\u5f52\u5c5e\u611f\u201d\u3001\u201c\u6e29\u6696\u201d\u2026\u2026\u6211\u4eec\u6536\u5230\u4e86\u5f88\u591a\u9f13\u52b1\u548c\u8ba4\u53ef\uff0c\u8fd9\u4e5f\u8ba9\u6211\u4eec\u66f4\u52a0\u81ea\u4fe1\u548c\u575a\u5b9a\u3002","is_contain":"1"}', N'10', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'11', N'波士顿科学有哪些深受员工喜爱的福利和活动？', N'graphic', N'', N'{"title":"\u6ce2\u58eb\u987f\u79d1\u5b66\u6709\u54ea\u4e9b\u6df1\u53d7\u5458\u5de5\u559c\u7231\u7684\u798f\u5229\u548c\u6d3b\u52a8\uff1f","img":"\/upload\/2021\/06\/08\/20210608071335_gzmXDahR.jpg","text":"\u4e94\u82b1\u516b\u95e8\u7684\u5404\u79cd\u6d25\u8d34\u3001\u4fdd\u9669\u548c\u5047\u671f\u3001\u8282\u65e5\u559c\u4e27\u6170\u95ee\u793c\u3001\u5e26\u85aa\u5bb6\u5c5e\u62a4\u7406\u5047\u3001\u5f39\u6027\u5de5\u4f5c\u5e26\u85aa\u966a\u5a03\u2026\u2026\u8be5\u6709\u7684\u90fd\u6709\uff0c\u6ce2\u79d1\u4eba\u81ea\u8c6a\u5730\u8868\u793a\u4e60\u60ef\u5c31\u597d\u3002","is_contain":"1"}', N'11', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'12', N'我的健康', N'text', N'', N'{"title":"\u6211\u7684\u5065\u5eb7","img":"","text":"\u6211\u4eec\u4e0d\u4ec5\u5173\u6ce8\u75c5\u60a3\u7684\u751f\u6d3b\u8d28\u91cf\uff0c\u4e5f\u5173\u5fc3\u5458\u5de5\u7684\u8eab\u4f53\u5065\u5eb7\u3002\u5065\u5eb7\u662f\u7cbe\u5f69\u4eba\u751f\u7684\u57fa\u7840\uff0c\u5e74\u5ea6\u4f53\u68c0\uff0c\u9632\u62a4\u7528\u54c1\uff0c\u5065\u8eab\u8ba1\u5212\uff0c\u8865\u5145\u533b\u7597\u4fdd\u9669\u8ba1\u5212\u2026\u2026\u8ba9\u5458\u5de5\u5728\u751f\u6d3b\u5de5\u4f5c\u4e4b\u4f59\uff0c\u4eab\u53d7360\u00b0\u5168\u65b9\u4f4d\u7684\u5173\u6000\u4fdd\u969c\u3002"}', N'12', N'2', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'13', N'年度体检 & 防护用品', N'graphic', N'', N'{"title":"\u5e74\u5ea6\u4f53\u68c0 & \u9632\u62a4\u7528\u54c1","img":"\/upload\/2021\/06\/08\/20210608072711_N7n5qBrG.JPG","text":"\u6211\u4eec\u4e0d\u4ec5\u5173\u6ce8\u75c5\u60a3\u7684\u751f\u6d3b\u8d28\u91cf\uff0c\u4e5f\u5173\u5fc3\u5458\u5de5\u7684\u8eab\u4f53\u5065\u5eb7\u3002\u5065\u5eb7\u662f\u7cbe\u5f69\u4eba\u751f\u7684\u57fa\u7840\uff0c\u5e74\u5ea6\u4f53\u68c0\u548c\u9632\u62a4\u7528\u54c1\uff0c\u4e3a\u5927\u5bb6\u63d0\u4f9b\u4e86\u6700\u57fa\u672c\u7684\u5065\u5eb7\u4fdd\u969c\u3002","is_contain":"2"}', N'13', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'14', N'健身计划', N'graphic', N'', N'{"title":"\u5065\u8eab\u8ba1\u5212","img":"\/upload\/2021\/06\/02\/20210602025401_6XBbv2rn.JPG","text":"\u751f\u547d\u5728\u4e8e\u8fd0\u52a8\uff0c\u6ce2\u79d1\u63a8\u51fa\u7684\u5168\u65b0\u5065\u8eab\u798f\u5229\uff0c\u53ef\u6839\u636e\u6bcf\u4e2a\u4eba\u4e0d\u540c\u7684\u9700\u6c42\uff0c\u9009\u62e9\u4e0d\u540c\u7684\u5065\u8eab\u8ba1\u5212\u3002\u540c\u65f6\uff0c\u4e0d\u5b9a\u671f\u4e3e\u529e\u7684\u7ebf\u4e0a\u5065\u5eb7\u8bad\u7ec3\u8425\u5c06\u9080\u8bf7\u5927\u5bb6\u52a0\u5165\uff0c\u5c0f\u73ed\u91cf\u8eab\u5b9a\u5236\u3002\u65e0\u8bba\u662f\u51cf\u8102\u8fd8\u662f\u4f53\u6001\uff0c\u5851\u6027\u8fd8\u662f\u745c\u4f3d\uff0c\u603b\u6709\u4e00\u6b3e\u9002\u5408\u4f60\u3002","is_contain":"2"}', N'14', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'15', N'补充医疗保险计划', N'graphic', N'', N'{"title":"\u8865\u5145\u533b\u7597\u4fdd\u9669\u8ba1\u5212","img":"\/upload\/2021\/06\/02\/20210602025419_tfXBuPmC.JPG","text":"\u6ce2\u79d1\u4e3a\u5458\u5de5\u53ca\u5176\u5bb6\u4eba\u63d0\u4f9b\u5168\u65b9\u4f4d\u7684\u533b\u7597\u4fdd\u969c\u8ba1\u5212\u3002\u540c\u65f6\uff0c\u516c\u53f8\u8fd8\u63d0\u4f9b\u5458\u5de5\u81ea\u4ed8\u8d39\u81ea\u9009\u8ba1\u5212\uff0c\u5305\u62ec\u5458\u5de5\u53ca\u5b50\u5973\u7684\u533b\u7597\u4fdd\u9669\u8ba1\u5212\u5347\u7ea7\u548c\u914d\u5076\u4fdd\u9669\u8ba1\u5212\uff0c\u4ee5\u6ee1\u8db3\u5458\u5de5\u66f4\u591a\u6837\u5316\u7684\u9700\u6c42\uff0c\u8ba9\u5458\u5de5\u5728\u751f\u6d3b\u5de5\u4f5c\u4e4b\u4f59\uff0c\u4eab\u53d7360\u00b0\u5168\u65b9\u4f4d\u7684\u5173\u6000\u4fdd\u969c\u3002","is_contain":"2"}', N'15', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'16', N'如何申请医疗报销', N'graphic', N'', N'{"title":"\u5982\u4f55\u7533\u8bf7\u533b\u7597\u62a5\u9500","img":"\/upload\/2021\/06\/02\/20210602025444_NIf7heyQ.JPG","text":"\u5f53\u5458\u5de5\u5728\u533b\u9662\u5c31\u533b\u4ea7\u751f\u4e86\u6cbb\u7597\u8d39\u7528\u540e\uff0c\u53ef\u901a\u8fc7\u5e73\u5b89\u597d\u798f\u5229app\u81ea\u52a9\u7533\u8bf7\u7406\u8d54\uff0c\u62a5\u9500\u533b\u4fdd\u90e8\u5206100%\u7684\u8d39\u7528\u54e6\u3002","is_contain":"2"}', N'16', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'17', N'我的生活', N'pic', N'', N'{"is_contain":1,"title":"我的生活","text":"工作累了就休假出去旅游，节日到了就收获慰问礼金，生日可以和家人一起过，圣诞可以去见喜欢的人……当我们逐一将这些小确幸拾起的时候，也就找到了最简单的快乐。","img":""}', N'17', N'2', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'18', N'带薪年假', N'graphic', N'', N'{"title":"\u5e26\u85aa\u5e74\u5047","img":"\/upload\/2021\/06\/02\/20210602035630_9W4wZLYv.JPG","text":"\u8bfb\u4e07\u5377\u4e66\u4e0d\u5982\u884c\u4e07\u91cc\u8def\uff0c\u5de5\u4f5c\u7d2f\u4e86\u5c31\u4f11\u5047\u51fa\u53bb\u65c5\u6e38\uff0c\u5bfb\u627e\u751f\u547d\u4e2d\u4e0d\u671f\u800c\u9047\u7684\u7f8e\u4e3d\u9082\u9005\u3002","is_contain":"2"}', N'18', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'19', N'其他带薪福利假期', N'graphic', N'', N'{"title":"\u5176\u4ed6\u5e26\u85aa\u798f\u5229\u5047\u671f","img":"\/upload\/2021\/06\/02\/20210602035725_xW30Z6je.JPG","text":"\u751f\u65e5\u53ef\u4ee5\u548c\u5bb6\u4eba\u4e00\u8d77\u8fc7\uff0c\u5723\u8bde\u53ef\u4ee5\u53bb\u89c1\u559c\u6b22\u7684\u4eba\u2026\u2026\u5f53\u6211\u4eec\u9010\u4e00\u5c06\u8fd9\u4e9b\u5c0f\u786e\u5e78\u62fe\u8d77\u7684\u65f6\u5019\uff0c\u4e5f\u5c31\u627e\u5230\u4e86\u6700\u7b80\u5355\u7684\u5feb\u4e50\u3002","is_contain":"2"}', N'19', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'20', N'假期申请和查询', N'graphic', N'', N'{"title":"\u5047\u671f\u7533\u8bf7\u548c\u67e5\u8be2-\u7535\u8111\u7aef","img":"\/upload\/2021\/06\/02\/20210602035840_mLbpYlo6.JPG","text":"\u5458\u5de5\u53ef\u4ee5\u901a\u8fc7PC\u7aef\u548c\u624b\u673a\u7aef\uff0c\u5feb\u901f\u4fbf\u6377\u5730\u67e5\u8be2\u548c\u7533\u8bf7\u4f11\u5047\u54e6\u3002","is_contain":"2"}', N'20', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'21', N'假期申请和查询-手机端', N'pic', N'', N'{"title":"\u5047\u671f\u7533\u8bf7\u548c\u67e5\u8be2-\u624b\u673a\u7aef","img":"\/upload\/2021\/06\/02\/20210602035859_O4Bto9ZV.JPG","text":"","is_contain":2}', N'21', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'22', N'员工关爱', N'graphic', N'', N'{"title":"\u5458\u5de5\u5173\u7231","img":"\/upload\/2021\/06\/02\/20210602040106_6XhUOuaP.JPG","text":"365\u5929\u7684\u5168\u65b9\u4f4d\u5173\u6000\uff0c\u8ba9\u5927\u5bb6\u611f\u53d7\u6ce2\u79d1\u5bb6\u5ead\u7684\u6e29\u6696\u3002","is_contain":"2"}', N'22', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'23', N'购买公司产品', N'pic', N'', N'{"title":"\u8d2d\u4e70\u516c\u53f8\u4ea7\u54c1","img":"\/upload\/2021\/06\/02\/20210602040137_yUru05OJ.JPG","text":"","is_contain":2}', N'23', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'24', N'EAP员工协助计划', N'pic', N'', N'{"title":"EAP\u5458\u5de5\u534f\u52a9\u8ba1\u5212","img":"\/upload\/2021\/06\/02\/20210602040154_lh8EcnwC.JPG","text":"","is_contain":2}', N'24', N'1', N'2021-05-30 17:54:14', N'2021-06-10 15:13:22'), (N'25', N'我的财富', N'pic', N'', N'{"title":"\u6211\u7684\u8d22\u5bcc","img":"","text":"\u4e00\u751f\u80fd\u591f\u79ef\u7d2f\u591a\u5c11\u8d22\u5bcc\uff0c\u4e0d\u53d6\u51b3\u4e8e\u80fd\u8d5a\u591a\u5c11\u94b1\uff0c\u800c\u53d6\u51b3\u4e8e\u5982\u4f55\u6295\u8d44\u7406\u8d22\u3002\u901a\u8fc7\u4e0d\u65ad\u7684\u6295\u8d44\u548c\u79ef\u7d2f\uff0c\u624d\u80fd\u6210\u5c31\u5b89\u9038\u7684\u672a\u6765\u3002\u6ce2\u79d1\u7684\u5171\u8944\u8d22\u5bcc\u8ba1\u52122.0\u548c\u5168\u7403\u5458\u5de5\u4f18\u60e0\u8d2d\u80a1\u8ba1\u5212\uff0c\u8ba9\u5927\u5bb6\u7684\u8d22\u5bcc\u589e\u76ca\uff0c\u83b7\u5f97\u66f4\u52a0\u5b89\u5fc3\u7684\u4fdd\u969c\u3002","is_contain":1}', N'25', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'26', N'共享财富计划2.0', N'pic', N'', N'{"title":"\u5171\u4eab\u8d22\u5bcc\u8ba1\u52122.0","img":"\/upload\/2021\/06\/02\/20210602042428_Cs5Ipc6f.JPG","text":"","is_contain":2}', N'26', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'27', N'全球员工优惠购股计划（GESOP）', N'pic', N'', N'{"title":"\u5168\u7403\u5458\u5de5\u4f18\u60e0\u8d2d\u80a1\u8ba1\u5212\uff08GESOP\uff09","img":"\/upload\/2021\/06\/02\/20210602042444_Enx2hoQd.JPG","text":"","is_contain":2}', N'27', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'28', N'我的发展', N'pic', N'', N'{"title":"\u6211\u7684\u53d1\u5c55","img":"","text":"\u5458\u5de5\u4e00\u76f4\u662f\u6211\u4eec\u6700\u4e3a\u91cd\u8981\u7684\u8d22\u5bcc\uff0c\u6211\u4eec\u81f4\u529b\u4e8e\u5f15\u5165\u4e0e\u6211\u4eec\u5fd7\u540c\u9053\u5408\u7684\u4f18\u79c0\u4eba\u624d\uff0c\u79c9\u627f\u201c\u521b\u65b0\u521b\u4e1a\u201d\u7684\u4f01\u4e1a\u6587\u5316\uff0c\u6fc0\u52b1\u5458\u5de5\u53d1\u6325\u5176\u6700\u5927\u7684\u6f5c\u80fd\uff0c\u5b9e\u73b0\u4e2a\u4eba\u7684\u53d1\u5c55\u76ee\u6807\uff0c\u6210\u5c31\u4e2a\u4eba\u4ef7\u503c\u3002","is_contain":1}', N'28', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'29', N'公司培训', N'pic', N'', N'{"title":"","img":"\/upload\/2021\/06\/07\/20210607044331_p2kcBmXq.JPG","text":"","is_contain":1}', N'29', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'30', N'差旅申请及预审批', N'pic', N'', N'{"title":"\u5dee\u65c5\u7533\u8bf7\u53ca\u9884\u5ba1\u6279","img":"\/upload\/2021\/06\/02\/20210602043545_DjfmKCqB.JPG","text":"","is_contain":2}', N'30', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'31', N'机票/火车/个人交通费', N'pic', N'', N'{"title":"\u673a\u7968\/\u706b\u8f66\/\u4e2a\u4eba\u4ea4\u901a\u8d39","img":"\/upload\/2021\/06\/02\/20210602043614_atcu8O4s.JPG","text":"","is_contain":2}', N'31', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'32', N'AMEX/CTRIP/滴滴企业版', N'pic', N'', N'{"title":"AMEX\/CTRIP\/\u6ef4\u6ef4\u4f01\u4e1a\u7248","img":"\/upload\/2021\/07\/09\/20210709012313_r0C51NR6.jpeg","text":"","is_contain":2}', N'32', N'1', N'2021-05-30 17:54:15', N'2021-07-09 01:23:17'), (N'33', N'差旅酒店', N'pic', N'', N'{"title":"\u5dee\u65c5\u9152\u5e97","img":"\/upload\/2021\/06\/02\/20210602043723_3fejqm6l.JPG","text":"","is_contain":2}', N'33', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'34', N'手机费报销', N'pic', N'', N'{"title":"\u624b\u673a\u8d39\u62a5\u9500","img":"\/upload\/2021\/06\/02\/20210602043908_Z8jWGbtP.JPG","text":"","is_contain":2}', N'34', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'35', N'餐费报销', N'pic', N'', N'{"title":"\u9910\u8d39\u62a5\u9500","img":"\/upload\/2021\/06\/02\/20210602043935_U8TK70dO.JPG","text":"","is_contain":2}', N'35', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'36', N'发票及费用明细清单', N'pic', N'', N'{"title":"\u53d1\u7968\u53ca\u8d39\u7528\u660e\u7ec6\u6e05\u5355","img":"\/upload\/2021\/06\/02\/20210602044011_Q2TAW4fZ.JPG","text":"","is_contain":2}', N'36', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'37', N'付款凭据要求', N'pic', N'', N'{"title":"\u4ed8\u6b3e\u51ed\u636e\u8981\u6c42","img":"\/upload\/2021\/06\/02\/20210602044031_gMhnozLk.JPG","text":"","is_contain":2}', N'37', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'38', N'公务费用报告流程和相关期限', N'pic', N'', N'{"title":"\u516c\u52a1\u8d39\u7528\u62a5\u544a\u6d41\u7a0b\u548c\u76f8\u5173\u671f\u9650","img":"\/upload\/2021\/06\/02\/20210602044051_dbsW7R9v.JPG","text":"","is_contain":2}', N'38', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'39', N'公司信用卡', N'graphic', N'', N'{"title":"\u516c\u53f8\u4fe1\u7528\u5361","img":"\/upload\/2021\/06\/02\/20210602044306_egGhIMP5.JPG","text":"\u6bcf\u4e00\u4f4d\u5165\u804c\u7684\u65b0\u5458\u5de5\uff0c\u6211\u4eec\u9f13\u52b1\u5927\u5bb6\u7533\u8bf7\u516c\u53f8\u4fe1\u7528\u5361\uff0c\u4ee5\u65b9\u4fbf\u65e5\u540e\u652f\u4ed8\u6240\u6709\u4e0e\u5dee\u65c5\u76f8\u5173\u6216\u5176\u4ed6\u516c\u52a1\u76f8\u5173\u7684\u8d39\u7528\u5f00\u652f\u3002","is_contain":"2"}', N'39', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'40', N'信用卡申请', N'pic', N'', N'{"title":"\u4fe1\u7528\u5361\u7533\u8bf7","img":"\/upload\/2021\/06\/02\/20210602045004_isodVDAe.JPG","text":"","is_contain":2}', N'40', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'41', N'信用卡申请后', N'pic', N'', N'{"title":"\u4fe1\u7528\u5361\u7533\u8bf7\u540e","img":"\/upload\/2021\/06\/02\/20210602044934_BNRAmYQ8.JPG","text":"","is_contain":2}', N'41', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'42', N'波科价值认同卡', N'graphic', N'', N'{"title":"\u6ce2\u79d1\u4ef7\u503c\u8ba4\u540c\u5361","img":"\/upload\/2021\/06\/02\/20210602044948_zlgGx4HY.JPG","text":"\u6ce2\u79d1\u4ef7\u503c\u8ba4\u540c\u5361\u662f\u7531\u516c\u53f8\u59d4\u6258\u62db\u5546\u94f6\u884c\u4e3a\u4f01\u4e1a\u4e3a\u5458\u5de5\u529e\u7406\u7684\u4e00\u5f20\u50a8\u84c4\u94f6\u884c\u5361\uff0c\u72ec\u6709\u7684\u6ce2\u79d1\u8bbe\u8ba1\uff0c\u5c55\u73b0\u4e86\u516c\u53f8\u521b\u65b0\u521b\u4e1a\u7684\u7cbe\u795e\u7406\u5ff5\uff0c\u6b22\u8fce\u5927\u5bb6\u7533\u8bf7\u54e6\uff01","is_contain":"2"}', N'42', N'1', N'2021-05-30 17:54:15', N'2021-06-10 15:13:22'), (N'43', N'Concur报销', N'graphic', N'', N'{"title":"Concur\u62a5\u9500","img":"\/upload\/2021\/06\/02\/20210602050204_X38rHMSi.JPG","text":"\u6bcf\u4e00\u4f4d\u5165\u804c\u7684\u65b0\u5458\u5de5\uff0c\u65e5\u540e\u90fd\u4f1a\u9047\u5230\u8d39\u7528\u7684\u62a5\u9500\u95ee\u9898\uff0c\u5728\u6240\u6709\u6d41\u7a0b\u4e4b\u524d\uff0c\u65b0\u5458\u5de5\u9700\u5b8c\u6210\u5728\u7ebf\u57f9\u8bad\u548c\u8003\u8bd5\uff0c\u5e76\u5728\u7cfb\u7edf\u4e2d\u5c06\u81ea\u5df1\u4f5c\u4e3a\u4f9b\u5e94\u5546\u5efa\u5165\u540e\uff0c\u624d\u53ef\u8fdb\u884c\u540e\u7eed\u7684\u62a5\u9500\u54e6\u3002","is_contain":"2"}', N'43', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'44', N'Concur系统', N'pic', N'', N'{"title":"Concur\u7cfb\u7edf","img":"\/upload\/2021\/06\/02\/20210602050318_YyuDvKtn.JPG","text":"","is_contain":2}', N'44', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'45', N'T&E相关', N'graphic', N'', N'{"title":"T&E\u9000\u5355\u539f\u56e0","img":"\/upload\/2021\/06\/02\/20210602050336_gIrKORMJ.JPG","text":"\u6211\u4eec\u6574\u7406\u4e86\u4e00\u4e9b\u5e38\u89c1\u7684T&E\u62a5\u9500\u9000\u5355\u539f\u56e0\uff0c\u6ce8\u610f\u4e8b\u9879\uff0c\u5e38\u89c1\u95ee\u9898\u7b49\u4f9b\u5927\u5bb6\u53c2\u8003\u548c\u67e5\u9605\u3002","is_contain":"2"}', N'45', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'46', N'T&E报销注意事项 – 易混淆的费用类型', N'pic', N'', N'{"title":"T&E\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u6613\u6df7\u6dc6\u7684\u8d39\u7528\u7c7b\u578b","img":"\/upload\/2021\/06\/02\/20210602050354_c26p4nti.JPG","text":"","is_contain":2}', N'46', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'47', N'T&E报销注意事项 – 需备注的费用报销', N'pic', N'', N'{"title":"T&E\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u9700\u5907\u6ce8\u7684\u8d39\u7528\u62a5\u9500","img":"\/upload\/2021\/06\/02\/20210602050409_rWy7lEGs.JPG","text":"","is_contain":2}', N'47', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'48', N'T&E报销注意事项 – 其他注意事项', N'pic', N'', N'{"title":"T&E\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u5176\u4ed6\u6ce8\u610f\u4e8b\u9879","img":"\/upload\/2021\/06\/02\/20210602050427_4pOJG3Ql.JPG","text":"","is_contain":2}', N'48', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'49', N'T&E报销注意事项 – 需BU Head特批的情况', N'pic', N'', N'{"title":"T&E\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u9700BU Head\u7279\u6279\u7684\u60c5\u51b5","img":"\/upload\/2021\/06\/02\/20210602050544_gYQPVKcb.JPG","text":"","is_contain":2}', N'49', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'50', N'T&E报销注意事项 – Helpdesk常见问题', N'pic', N'', N'{"title":"T&E\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 Helpdesk\u5e38\u89c1\u95ee\u9898","img":"\/upload\/2021\/06\/02\/20210602050605_tPgUacB7.JPG","text":"","is_contain":2}', N'50', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'51', N'CR费用相关', N'graphic', N'', N'{"title":"CR\u8d39\u7528\u9000\u5355\u539f\u56e0","img":"\/upload\/2021\/06\/02\/20210602050628_cz5sTalF.JPG","text":"\u6211\u4eec\u6574\u7406\u4e86\u4e00\u4e9b\u5e38\u89c1\u7684CR\u62a5\u9500\u9000\u5355\u539f\u56e0\uff0c\u6ce8\u610f\u4e8b\u9879\uff0c\u5e38\u89c1\u95ee\u9898\u7b49\u4f9b\u5927\u5bb6\u53c2\u8003\u548c\u67e5\u9605\u3002\n","is_contain":"2"}', N'51', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'52', N'CR报销注意事项 – 一般注意事项', N'pic', N'', N'{"title":"CR\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u4e00\u822c\u6ce8\u610f\u4e8b\u9879","img":"\/upload\/2021\/06\/02\/20210602050651_ioT0WzVJ.JPG","text":"","is_contain":2}', N'52', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'53', N'CR报销注意事项 – 具体注意事项 餐费 & 住宿费', N'pic', N'', N'{"title":"CR\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u5177\u4f53\u6ce8\u610f\u4e8b\u9879 \u9910\u8d39 & \u4f4f\u5bbf\u8d39","img":"\/upload\/2021\/06\/02\/20210602050709_9mfWpRnX.JPG","text":"","is_contain":2}', N'53', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'54', N'CR报销注意事项 – 具体注意事项 机票费 & 讲课费 & 交通费', N'pic', N'', N'{"title":"CR\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u5177\u4f53\u6ce8\u610f\u4e8b\u9879 \u673a\u7968\u8d39 & \u8bb2\u8bfe\u8d39 & \u4ea4\u901a\u8d39","img":"\/upload\/2021\/06\/02\/20210602050738_JK1AOeWX.JPG","text":"","is_contain":2}', N'54', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'55', N'CR报销注意事项 – 具体注意事项 展台费 & 卫星会 & 捐赠 & 注册费', N'pic', N'', N'{"title":"CR\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u5177\u4f53\u6ce8\u610f\u4e8b\u9879 \u5c55\u53f0\u8d39 & \u536b\u661f\u4f1a & \u6350\u8d60 & \u6ce8\u518c\u8d39","img":"\/upload\/2021\/06\/02\/20210602050813_IptSsxGi.JPG","text":"","is_contain":2}', N'55', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'56', N'CR报销注意事项 – 具体注意事项 市场部费用 & 部门内部会议', N'pic', N'', N'{"title":"CR\u62a5\u9500\u6ce8\u610f\u4e8b\u9879 \u2013 \u5177\u4f53\u6ce8\u610f\u4e8b\u9879 \u5e02\u573a\u90e8\u8d39\u7528 & \u90e8\u95e8\u5185\u90e8\u4f1a\u8bae","img":"\/upload\/2021\/06\/02\/20210602050835_ZVJtmiBW.JPG","text":"","is_contain":2}', N'56', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'57', N'Concur纸质报告样本', N'graphic', N'', N'{"title":"Concur\u7eb8\u8d28\u62a5\u544a\u5408\u683c\u6837\u672c","img":"\/upload\/2021\/06\/02\/20210602050900_iyho1J2r.JPG","text":"\u4ee5\u4e0b\u662fConcur\u7eb8\u8d28\u62a5\u544a\u5408\u683c\u548c\u4e0d\u5408\u683c\u7684\u6837\u672c\uff0c\u5927\u5bb6\u4e00\u5b9a\u8981\u4ed4\u7ec6\u6574\u7406\u81ea\u5df1\u7684\u7eb8\u8d28\u62a5\u544a\uff0c\u65b9\u4fbfGBS\u6574\u7406\u548c\u67e5\u8be2\uff0c\u8fd9\u6837\u624d\u80fd\u66f4\u5feb\u5730\u83b7\u5f97\u62a5\u9500\u6b3e\u54e6\u3002","is_contain":"2"}', N'57', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'58', N'Concur纸质报告不合格样本', N'pic', N'', N'{"title":"Concur\u7eb8\u8d28\u62a5\u544a\u4e0d\u5408\u683c\u6837\u672c","img":"\/upload\/2021\/06\/02\/20210602050915_x29EgNY6.JPG","text":"","is_contain":2}', N'58', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'59', N'GBS单据审核时间 & 联系方式', N'graphic', N'', N'{"title":"GBS\u5355\u636e\u5ba1\u6838\u65f6\u95f4 & \u8054\u7cfb\u65b9\u5f0f","img":"\/upload\/2021\/06\/02\/20210602050955_hJ3pGlXa.JPG","text":"\u6700\u540e\uff0c\u5927\u5bb6\u5982\u679c\u6709\u62a5\u9500\u3001\u5ba1\u5355\u7b49\u7591\u95ee\uff0c\u53ef\u5728\u5de5\u4f5c\u65f6\u95f4\u8054\u7cfbGBS\u7684\u76f8\u5173\u540c\u4e8b\u54e6\u3002","is_contain":"2"}', N'59', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'60', N'关键联系人', N'graphic', N'', N'{"title":"\u5173\u952e\u8054\u7cfb\u4eba","img":"\/upload\/2021\/06\/02\/20210602051014_DjJeUGtl.JPG","text":"\u5927\u5bb6\u5728\u5165\u804c\u540e\uff0c\u9047\u4efb\u4f55\u95ee\u9898\uff0c\u53ef\u8054\u7cfb\u4e0b\u65b9\u7684\u76f8\u5173\u540c\u4e8b\u8fdb\u884c\u89e3\u51b3\u54e6\u3002","is_contain":"2"}', N'60', N'1', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'61', N'关键联系人', N'pic', N'', N'{"title":"\u5173\u952e\u8054\u7cfb\u4eba","img":"\/upload\/2021\/06\/04\/20210604030358_DB7sUvwY.JPG","text":"","is_contain":"1"}', N'61', N'2', N'2021-05-30 17:54:16', N'2021-06-10 15:13:22'), (N'65', N'人才发展项目', N'img', N'', N'{"title":"\u4eba\u624d\u53d1\u5c55\u9879\u76ee","img":"\/upload\/2021\/06\/02\/20210602043028_tDFN3Jwa.JPG","text":"","is_contain":2}', N'65', N'1', N'2021-06-02 16:30:30', N'2021-06-10 15:13:22'), (N'66', N'业务部门', N'img', N'', N'{"title":"\u4e1a\u52a1\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607045018_zOMlrfm9.JPG","text":"","is_contain":2}', N'66', N'1', N'2021-06-07 16:50:21', N'2021-06-10 15:13:22'), (N'67', N'业务部门', N'img', N'', N'{"title":"\u4e1a\u52a1\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607045223_PeUQvq7p.JPG","text":"","is_contain":2}', N'67', N'1', N'2021-06-07 16:52:26', N'2021-06-10 15:13:22'), (N'68', N'支持部门', N'img', N'', N'{"title":"\u652f\u6301\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607050011_4Tr7aYtx.JPG","text":"","is_contain":"1"}', N'68', N'1', N'2021-06-07 16:58:42', N'2021-06-10 15:13:22'), (N'69', N'支持部门', N'img', N'', N'{"title":"\u652f\u6301\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607045943_6kb8fuqh.JPG","text":"","is_contain":"1"}', N'69', N'1', N'2021-06-07 16:59:45', N'2021-06-10 15:13:22'), (N'70', N'支持部门', N'img', N'', N'{"title":"\u652f\u6301\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607050043_KH7RGVvX.JPG","text":"","is_contain":"1"}', N'70', N'1', N'2021-06-07 17:00:45', N'2021-06-10 15:13:22'), (N'71', N'支持部门', N'img', N'', N'{"title":"\u652f\u6301\u90e8\u95e8","img":"\/upload\/2021\/06\/07\/20210607050144_zoWjhSts.JPG","text":"","is_contain":"1"}', N'71', N'1', N'2021-06-07 17:01:12', N'2021-06-10 15:13:22')
GO

SET IDENTITY_INSERT [dbo].[knowledge] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for order
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[order]') AND type IN ('U'))
	DROP TABLE [dbo].[order]
GO

CREATE TABLE [dbo].[order] (
  [id] int NOT NULL identity(1,1),
  [unique_code] nchar(32) NOT NULL DEFAULT '',
  [trade_no] nvarchar(50) NOT NULL DEFAULT '',
  [price] decimal(10,2) NOT NULL DEFAULT '1.00',
  [purchase_time] datetime2 NOT NULL,
  [status] tinyint NOT NULL DEFAULT 1,
  [remark] nvarchar(max) NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联唯一码',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单编号',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'trade_no'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单总价',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'付款时间',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'purchase_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-创建未付款 2-创建已付款，未发货 3-已付款，未收货 4.已付款，已收货 5. 订单失败',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单备注',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'remark'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'order',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单',
'SCHEMA', N'dbo',
'TABLE', N'order'
GO


-- ----------------------------
-- Records of order
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for order_item
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[order_item]') AND type IN ('U'))
	DROP TABLE [dbo].[order_item]
GO

CREATE TABLE [dbo].[order_item] (
  [id] int NOT NULL identity(1,1),
  [sku] nvarchar(50) NOT NULL DEFAULT '',
  [trade_no] nvarchar(50) NOT NULL DEFAULT '',
  [unique_code] nchar(32) NOT NULL DEFAULT '',
  [type] tinyint NOT NULL DEFAULT 1,
  [price] decimal(10,2) NOT NULL DEFAULT '1.00',
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [pic] nvarchar(254) NOT NULL DEFAULT '',
  [detail] nvarchar(max) NOT NULL,
  [remark] nvarchar(max) NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品sku',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'sku'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单编号',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'trade_no'
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联唯一码',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-实体商品 2-虚拟商品',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单总价',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品名称',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品图片',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品详情',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'detail'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品备注',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'remark'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'order_item',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'订单子项',
'SCHEMA', N'dbo',
'TABLE', N'order_item'
GO


-- ----------------------------
-- Records of order_item
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for phinxlog
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[phinxlog]') AND type IN ('U'))
	DROP TABLE [dbo].[phinxlog]
GO

CREATE TABLE [dbo].[phinxlog] (
  [version] bigint NOT NULL,
  [migration_name] nvarchar(100) NULL,
  [start_time] datetime2 NULL,
  [end_time] datetime2 NULL,
  [breakpoint] tinyint NOT NULL
)
GO


-- ----------------------------
-- Records of phinxlog
-- ----------------------------
BEGIN TRANSACTION
GO

INSERT INTO [dbo].[phinxlog] ([version],[migration_name], [start_time], [end_time], [breakpoint]) VALUES (N'20210514060625', N'AddBaseTable', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210514073240', N'AddAssetTable', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210515015114', N'AddTableActivity', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210515023351', N'AddTableKnowledge', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210515023636', N'AddTablePrizeContest', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210516174203', N'AddTableProduct', N'2021-05-23 10:59:01', N'2021-05-23 10:59:01', N'0'), (N'20210523045805', N'AddTableAssetChangeLog', N'2021-05-23 13:16:38', N'2021-05-23 13:16:38', N'0'), (N'20210523055714', N'AddTablePrizeContestRank', N'2021-05-23 14:26:32', N'2021-05-23 14:26:32', N'0'), (N'20210524154144', N'AddTableTagRelation', N'2021-05-30 18:59:02', N'2021-05-30 18:59:02', N'0'), (N'20210602094413', N'AddColumnSortToKnowLedge', N'2021-06-03 01:05:52', N'2021-06-03 01:05:52', N'0'), (N'20210603171202', N'AddTableInventory', N'2021-06-04 02:09:22', N'2021-06-04 02:09:22', N'0'), (N'20210604085251', N'AddTableRule', N'2021-06-04 17:15:58', N'2021-06-04 17:15:58', N'0'), (N'20210607162531', N'AddColumnToActivitySetting', N'2021-06-08 01:28:29', N'2021-06-08 01:28:29', N'0'), (N'20210609083949', N'AddColumnToPrizeContestSetting', N'2021-06-10 01:34:47', N'2021-06-10 01:34:47', N'0'), (N'20210614141209', N'AddTableAdminUser', N'2021-06-15 02:08:07', N'2021-06-15 02:08:07', N'0')
GO

COMMIT
GO


-- ----------------------------
-- Table structure for prize_contest
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[prize_contest]') AND type IN ('U'))
	DROP TABLE [dbo].[prize_contest]
GO

CREATE TABLE [dbo].[prize_contest] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [entry_num] int NOT NULL DEFAULT 3,
  [topic_num] int NOT NULL DEFAULT 0,
  [pic] nvarchar(254) NOT NULL DEFAULT '',
  [is_through] tinyint NOT NULL DEFAULT 2,
  [remark] nvarchar(max) NOT NULL,
  [is_asset_award_section] tinyint NOT NULL DEFAULT 1,
  [is_asset_award] tinyint NOT NULL DEFAULT 1,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [start_date] date NOT NULL,
  [end_date] date NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶标题',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'每日参与名额',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'entry_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'题库数目',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'topic_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'图片地址',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'回答全部题目 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'is_through'
GO

EXEC sp_addextendedproperty
'MS_Description', N'内容备注',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'remark'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否分段结算资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'is_asset_award_section'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'is_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'起始日期',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'start_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'截止日期',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'end_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'有奖竞猜 - 冲顶配置',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest'
GO


-- ----------------------------
-- Records of prize_contest
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[prize_contest] ON
GO

INSERT INTO [dbo].[prize_contest] ([id], [name], [entry_num], [topic_num], [pic], [is_through], [remark], [is_asset_award_section], [is_asset_award], [asset_num], [start_date], [end_date], [state], [created_at], [updated_at]) VALUES (N'14', N' 冲顶配置', N'1', N'5', N' http://pic', N'2', N' remark', N'1', N'1', N'50.00', N'2021-06-07', N'2022-06-07', N'2', N'2021-06-10 14:54:28', N'2021-06-23 18:07:20'), (N'15', N' 冲顶配置', N'1', N'5', N' http://pic', N'2', N' remark', N'1', N'1', N'50.00', N'2021-06-07', N'2029-08-31', N'2', N'2021-06-23 18:07:20', N'2021-07-14 15:47:53'), (N'16', N' 冲顶配置', N'100', N'5', N' http://pic', N'2', N' remark', N'1', N'1', N'50.00', N'2021-06-07', N'2029-08-31', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53')
GO

SET IDENTITY_INSERT [dbo].[prize_contest] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for prize_contest_rank
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[prize_contest_rank]') AND type IN ('U'))
	DROP TABLE [dbo].[prize_contest_rank]
GO

CREATE TABLE [dbo].[prize_contest_rank] (
  [id] int NOT NULL identity(1,1),
  [account_id] nchar(32) NOT NULL,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联唯一码',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_rank',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'变动金额',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_rank',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_rank',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_rank',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶排行榜',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_rank'
GO


-- ----------------------------
-- Records of prize_contest_rank
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for prize_contest_record
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[prize_contest_record]') AND type IN ('U'))
	DROP TABLE [dbo].[prize_contest_record]
GO

CREATE TABLE [dbo].[prize_contest_record] (
  [id] int NOT NULL identity(1,1),
  [prize_contest_id] int NOT NULL DEFAULT 0,
  [account_id] nchar(32) NOT NULL DEFAULT '',
  [date] date NOT NULL,
  [is_through] tinyint NOT NULL DEFAULT 2,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [state] tinyint NOT NULL DEFAULT 1,
  [problem_set] nvarchar(max) NOT NULL,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶赛程id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'prize_contest_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户account_id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'参与日期',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否通关 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'is_through'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'题集- topic id集合 - json',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'problem_set'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'有奖竞猜 - 冲顶记录',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record'
GO


-- ----------------------------
-- Records of prize_contest_record
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for prize_contest_record_item
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[prize_contest_record_item]') AND type IN ('U'))
	DROP TABLE [dbo].[prize_contest_record_item]
GO

CREATE TABLE [dbo].[prize_contest_record_item] (
  [id] int NOT NULL identity(1,1),
  [prize_contest_record_id] int NOT NULL DEFAULT 0,
  [prize_contest_id] int NOT NULL DEFAULT 0,
  [account_id] nchar(32) NOT NULL,
  [date] date NOT NULL,
  [knowledge_id] int NOT NULL DEFAULT 0,
  [topic_id] int NOT NULL DEFAULT 0,
  [sort] int NOT NULL DEFAULT 0,
  [draft] nvarchar(50) NOT NULL,
  [answer] nvarchar(50) NOT NULL,
  [is_correct] tinyint NOT NULL DEFAULT 1,
  [is_asset_award] tinyint NOT NULL DEFAULT 1,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶赛程记录id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'prize_contest_record_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶赛程id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'prize_contest_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户account_id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'参与日期',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识点id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'knowledge_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'题目id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'topic_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'顺序',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'草稿',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'draft'
GO

EXEC sp_addextendedproperty
'MS_Description', N'答案',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'answer'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否正确 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'is_correct'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'is_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'有奖竞猜 - 冲顶 - 答题记录',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_record_item'
GO


-- ----------------------------
-- Records of prize_contest_record_item
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Table structure for prize_contest_schedule
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[prize_contest_schedule]') AND type IN ('U'))
	DROP TABLE [dbo].[prize_contest_schedule]
GO

CREATE TABLE [dbo].[prize_contest_schedule] (
  [id] int NOT NULL identity(1,1),
  [sort] int NOT NULL DEFAULT 0,
  [prize_contest_id] int NOT NULL DEFAULT 0,
  [is_asset_award] tinyint NOT NULL DEFAULT 1,
  [asset_num] decimal(10,2) NOT NULL DEFAULT '1.00',
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'顺序',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'冲顶赛程id',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'prize_contest_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'是否资产奖励 1-是 2-否',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'is_asset_award'
GO

EXEC sp_addextendedproperty
'MS_Description', N'资产奖励额度',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'asset_num'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'有奖竞猜 - 冲顶赛程设置',
'SCHEMA', N'dbo',
'TABLE', N'prize_contest_schedule'
GO


-- ----------------------------
-- Records of prize_contest_schedule
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[prize_contest_schedule] ON
GO

INSERT INTO [dbo].[prize_contest_schedule] ([id], [sort], [prize_contest_id], [is_asset_award], [asset_num], [state], [created_at], [updated_at]) VALUES (N'20', N'1', N'14', N'1', N'50.00', N'1', N'2021-06-10 14:54:28', N'2021-06-10 14:54:28'), (N'21', N'2', N'14', N'1', N'50.00', N'1', N'2021-06-10 14:54:28', N'2021-06-10 14:54:28'), (N'22', N'3', N'14', N'1', N'50.00', N'1', N'2021-06-10 14:54:28', N'2021-06-10 14:54:28'), (N'23', N'4', N'14', N'1', N'50.00', N'1', N'2021-06-10 14:54:55', N'2021-06-10 14:54:55'), (N'24', N'5', N'14', N'1', N'50.00', N'1', N'2021-06-10 14:55:11', N'2021-06-10 14:55:11'), (N'25', N'1', N'15', N'1', N'50.00', N'1', N'2021-06-23 18:07:20', N'2021-06-23 18:07:20'), (N'26', N'2', N'15', N'1', N'50.00', N'1', N'2021-06-23 18:07:20', N'2021-06-23 18:07:20'), (N'27', N'3', N'15', N'1', N'50.00', N'1', N'2021-06-23 18:07:20', N'2021-06-23 18:07:20'), (N'28', N'4', N'15', N'1', N'50.00', N'1', N'2021-06-23 18:07:20', N'2021-06-23 18:07:20'), (N'29', N'5', N'15', N'1', N'50.00', N'1', N'2021-06-23 18:07:20', N'2021-06-23 18:07:20'), (N'30', N'1', N'16', N'1', N'50.00', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53'), (N'31', N'2', N'16', N'1', N'50.00', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53'), (N'32', N'3', N'16', N'1', N'50.00', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53'), (N'33', N'4', N'16', N'1', N'50.00', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53'), (N'34', N'5', N'16', N'1', N'50.00', N'1', N'2021-07-14 15:47:53', N'2021-07-14 15:47:53')
GO

SET IDENTITY_INSERT [dbo].[prize_contest_schedule] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for product
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[product]') AND type IN ('U'))
	DROP TABLE [dbo].[product]
GO

CREATE TABLE [dbo].[product] (
  [id] int NOT NULL identity(1,1),
  [sku] nvarchar(50) NOT NULL DEFAULT '',
  [type] tinyint NOT NULL DEFAULT 1,
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [pic] nvarchar(254) NOT NULL DEFAULT '',
  [price] decimal(10,2) NOT NULL DEFAULT '1.00',
  [detail] nvarchar(max) NOT NULL,
  [remark] nvarchar(max) NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [status] tinyint NOT NULL DEFAULT 1,
  [storage] int NOT NULL DEFAULT 0,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品sku',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'sku'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-实体商品 2-虚拟商品',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品名称',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品图片',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品价格',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品详情',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'detail'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品备注',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'remark'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-补货 2-仓库正常',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'库存',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'storage'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'product',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'商品',
'SCHEMA', N'dbo',
'TABLE', N'product'
GO


-- ----------------------------
-- Records of product
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[product] ON
GO

INSERT INTO [dbo].[product] ([id], [sku], [type], [name], [pic], [price], [detail], [remark], [state], [status], [storage], [created_at], [updated_at]) VALUES (N'1', N'B130794', N'2', N'QQ音乐绿钻月卡', N'/upload/2021/06/04/20210604021643_VI1E5efZ.png', N'880.00', N'', N'', N'1', N'1', N'998', N'2021-06-04 02:16:49', N'2021-07-28 16:23:30'), (N'2', N'B972640', N'2', N'网易云音乐黑胶绿卡', N'/upload/2021/06/04/20210604021733_4Q6XxJ1T.png', N'2800.00', N'', N'', N'1', N'1', N'1000', N'2021-06-04 02:17:33', N'2021-06-24 15:24:31'), (N'3', N'A678413', N'1', N'泡泡玛特盲盒', N'/upload/2021/06/04/20210604021814_zwtCPRNs.png', N'5800.00', N'', N'', N'1', N'1', N'999', N'2021-06-04 02:18:16', N'2021-07-28 16:24:04'), (N'4', N'A134092', N'1', N'Keep健身包（黑）', N'/upload/2021/06/04/20210604021905_ZI975prm.png', N'10800.00', N'', N'', N'1', N'1', N'1000', N'2021-06-04 02:19:07', N'2021-06-24 15:24:35'), (N'5', N'A962574', N'1', N'Keep健身包（粉）', N'/upload/2021/06/04/20210604022115_f85k2Jxo.png', N'10800.00', N'', N'', N'1', N'1', N'1000', N'2021-06-04 02:21:19', N'2021-06-24 15:24:37')
GO

SET IDENTITY_INSERT [dbo].[product] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for rule
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[rule]') AND type IN ('U'))
	DROP TABLE [dbo].[rule]
GO

CREATE TABLE [dbo].[rule] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(32) NOT NULL,
  [type] tinyint NOT NULL DEFAULT 1,
  [remark] nvarchar(max) NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段名称',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-总规则 2-冲顶规则',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'备注',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'remark'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-有效 2-无效',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'rule',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'规则',
'SCHEMA', N'dbo',
'TABLE', N'rule'
GO


-- ----------------------------
-- Records of rule
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[rule] ON
GO

INSERT INTO [dbo].[rule] ([id], [name], [type], [remark], [state], [created_at], [updated_at]) VALUES (N'1', N'活动规则', N'1', N'1.  每日打卡
活动期间，每人每日可打卡一次，每次打卡可获得30积分。

2.  每日学习
活动期间，每次打卡后，系统会自动推送1个知识点学习，完成阅读可获得150积分，每日学习封顶150积分。

3.  冲顶答题
活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得50积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。

4.  活动周期
活动周期21天，从员工登录小程序第1天起始

5.  积分兑换
积分有效期28天，从员工登录小程序第1天起始。已兑换的积分将做扣除，过期后所有积分清零，请及时兑换。', N'1', N'2021-06-04 17:28:30', N'2021-06-21 17:58:58'), (N'2', N'冲顶答题规则', N'2', N'活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得30积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。

', N'1', N'2021-06-04 17:29:23', N'2021-06-08 15:30:00')
GO

SET IDENTITY_INSERT [dbo].[rule] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for tag
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tag]') AND type IN ('U'))
	DROP TABLE [dbo].[tag]
GO

CREATE TABLE [dbo].[tag] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(32) NOT NULL DEFAULT '',
  [sub_name] nvarchar(32) NOT NULL DEFAULT '',
  [parent_tag_id] int NOT NULL DEFAULT 0,
  [relation_type] nvarchar(254) NOT NULL DEFAULT '',
  [desc] nvarchar(254) NOT NULL DEFAULT '',
  [bg_pic] nvarchar(254) NOT NULL DEFAULT '',
  [pic_type] tinyint NOT NULL DEFAULT 1,
  [bg_video] nvarchar(254) NOT NULL DEFAULT '',
  [is_show_title] tinyint NOT NULL DEFAULT 0,
  [top_pic] nvarchar(254) NOT NULL DEFAULT '',
  [sort] int NOT NULL DEFAULT 0,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段名称',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段子名称',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'sub_name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'默认父级id',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'parent_tag_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联的数据类型',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'relation_type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段描述',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'desc'
GO

EXEC sp_addextendedproperty
'MS_Description', N'背景图片地址',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'bg_pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'图片类型 1-小图 2-大图',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'pic_type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'背景视频地址',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'bg_video'
GO

EXEC sp_addextendedproperty
'MS_Description', N'标题 1-显示 2-不显示',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'is_show_title'
GO

EXEC sp_addextendedproperty
'MS_Description', N'顶部图片地址',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'top_pic'
GO

EXEC sp_addextendedproperty
'MS_Description', N'默认排序-升序',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-有效 2-无效',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'tag',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'标签表',
'SCHEMA', N'dbo',
'TABLE', N'tag'
GO


-- ----------------------------
-- Records of tag
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[tag] ON
GO

INSERT INTO [dbo].[tag] ([id], [name], [sub_name], [parent_tag_id], [relation_type], [desc], [bg_pic], [pic_type], [bg_video], [is_show_title], [top_pic], [sort], [state], [created_at], [updated_at]) VALUES (N'1', N'企业概览', N'', N'0', N'graphic', N'banner', N'/upload/2021/06/08/20210608040739_sHhPiZ9U.png', N'1', N'', N'1', N'/upload/2021/06/04/20210604061149_zZa5cwpj.png', N'1', N'1', N'2021-05-27 16:30:15', N'2021-06-08 16:07:41'), (N'2', N'发展历程', N'', N'0', N'graphic', N'banner', N'https://lib.sixtyden.com/k_history_bg.png', N'1', N'', N'1', N'', N'2', N'1', N'2021-05-27 16:30:15', N'2021-06-03 02:07:02'), (N'3', N'荣誉奖项', N'', N'0', N'graphic', N'banner', N'https://lib.sixtyden.com/k_medal_bg.png', N'1', N'', N'1', N'', N'3', N'1', N'2021-05-27 16:30:15', N'2021-06-03 02:07:07'), (N'4', N'产品简介', N'', N'0', N'graphic', N'banner', N'https://lib.sixtyden.com/k_product_bg.png', N'1', N'', N'1', N'', N'4', N'1', N'2021-05-27 16:30:15', N'2021-06-03 02:07:13'), (N'5', N'企业文化', N'', N'0', N'graphic', N'banner', N'/upload/2021/06/08/20210608010833_0CJlEAvD.png', N'1', N'/upload/video/culture.mp4', N'1', N'', N'5', N'1', N'2021-05-27 16:30:15', N'2021-06-08 13:08:35'), (N'6', N'Employee Benefits', N'员工福利', N'0', N'graphic', N'guide', N'', N'2', N'', N'1', N'', N'6', N'1', N'2021-05-27 16:30:15', N'2021-06-03 01:39:22'), (N'7', N'Finance', N'财务报销', N'0', N'graphic', N'guide', N'', N'2', N'', N'1', N'', N'7', N'1', N'2021-05-27 16:30:15', N'2021-06-03 01:39:29'), (N'8', N'IT  & Contacts', N'联系人', N'0', N'graphic', N'guide', N'', N'2', N'', N'1', N'', N'8', N'1', N'2021-05-27 16:30:15', N'2021-06-21 18:18:37'), (N'9', N'我的健康', N'', N'6', N'pic', N'guide', N'/upload/2021/06/03/20210603015806_snokdzUL.png', N'2', N'', N'2', N'/upload/2021/06/03/20210603015815_FcD3tC1H.png', N'9', N'1', N'2021-05-27 16:30:16', N'2021-06-03 01:58:16'), (N'10', N'我的生活', N'', N'6', N'pic', N'guide', N'/upload/2021/06/03/20210603015830_i7P85SUM.png', N'2', N'', N'2', N'/upload/2021/06/03/20210603015850_QBdnzmq2.png', N'10', N'1', N'2021-05-27 16:30:16', N'2021-06-03 01:58:51'), (N'11', N'我的财富', N'', N'6', N'pic', N'guide', N'/upload/2021/06/03/20210603015924_V3ejCnhy.png', N'2', N'', N'2', N'/upload/2021/06/03/20210603015937_H2l9uFmX.png', N'11', N'1', N'2021-05-27 16:30:16', N'2021-06-03 01:59:39'), (N'12', N'我的发展', N'', N'6', N'pic', N'guide', N'/upload/2021/06/03/20210603020020_g7W0z1LH.png', N'2', N'', N'2', N'/upload/2021/06/03/20210603020031_wp9WCBYa.png', N'12', N'1', N'2021-05-27 16:30:16', N'2021-06-03 02:00:33'), (N'13', N'差旅政策', N'', N'7', N'graphic', N'guide', N'/upload/2021/06/03/20210603020152_z1Wy3Yjk.png', N'2', N'', N'1', N'/upload/2021/06/03/20210603020202_v4FmDnzk.png', N'13', N'1', N'2021-05-27 16:30:16', N'2021-06-03 02:02:04'), (N'14', N'信用卡申请', N'', N'7', N'graphic', N'guide', N'/upload/2021/06/03/20210603020235_BLofFEhm.png', N'2', N'', N'1', N'/upload/2021/06/03/20210603020227_iPq2I8GR.png', N'14', N'1', N'2021-05-27 16:30:16', N'2021-06-03 02:02:40'), (N'15', N'费用报销', N'', N'7', N'graphic', N'guide', N'/upload/2021/06/03/20210603020252_PJ2t3n19.png', N'2', N'', N'1', N'/upload/2021/06/03/20210603020257_ejBKMx19.png', N'15', N'1', N'2021-05-27 16:30:16', N'2021-06-03 02:02:58'), (N'16', N'部门联系人', N'', N'8', N'graphic', N'guide', N'/upload/2021/06/10/20210610064411_CLas1Nzu.jpg', N'2', N'', N'1', N'/upload/2021/06/10/20210610065047_RyAEK9wq.jpg', N'16', N'1', N'2021-05-27 16:30:16', N'2021-06-10 18:50:49'), (N'17', N'test', N'', N'0', N'text', N'banner', N'upload/2021/05/30/20210530014519_ydNmrUGC.JPG', N'1', N'', N'1', N'', N'1', N'2', N'2021-05-30 01:49:13', N'2021-05-30 17:33:53'), (N'35', N'部门简介', N'', N'0', N'graphic', N'banner', N'/upload/2021/06/08/20210608040750_WUSaR04m.png', N'1', N'', N'1', N'/upload/2021/06/04/20210604061135_IsMiKxOk.png', N'1', N'1', N'2021-06-02 17:12:53', N'2021-06-08 16:07:53')
GO

SET IDENTITY_INSERT [dbo].[tag] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for tag_relation
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tag_relation]') AND type IN ('U'))
	DROP TABLE [dbo].[tag_relation]
GO

CREATE TABLE [dbo].[tag_relation] (
  [id] int NOT NULL identity(1,1),
  [unique_code] nvarchar(32) NOT NULL DEFAULT '',
  [type] nvarchar(254) NOT NULL DEFAULT '',
  [tag_id] int NOT NULL DEFAULT 0,
  [desc] nvarchar(254) NOT NULL DEFAULT '',
  [sort] int NOT NULL DEFAULT 0,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'唯一code',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'unique_code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'唯一code类型',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户标签关联id',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'tag_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'关联信息描述',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'desc'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户标签关联id排序',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'sort'
GO

EXEC sp_addextendedproperty
'MS_Description', N'字段类型 1-有效 2-无效',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'标签关联关系表',
'SCHEMA', N'dbo',
'TABLE', N'tag_relation'
GO


-- ----------------------------
-- Records of tag_relation
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[tag_relation] ON
GO

INSERT INTO [dbo].[tag_relation] ([id], [unique_code], [type], [tag_id], [desc], [sort], [state], [created_at], [updated_at]) VALUES (N'1', N'65', N'knowledge_id', N'12', N'', N'2', N'1', N'2021-05-30 19:15:20', N'2021-06-09 18:14:24'), (N'2', N'2', N'knowledge_id', N'1', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:56:22'), (N'3', N'3', N'knowledge_id', N'1', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:56:31'), (N'4', N'4', N'knowledge_id', N'2', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:56:55'), (N'5', N'5', N'knowledge_id', N'2', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:57:04'), (N'6', N'6', N'knowledge_id', N'2', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:57:09'), (N'7', N'7', N'knowledge_id', N'3', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-06-10 14:59:25'), (N'8', N'8', N'knowledge_id', N'4', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:31:26'), (N'9', N'9', N'knowledge_id', N'5', N'', N'2', N'1', N'2021-05-30 19:15:20', N'2021-06-08 19:55:50'), (N'10', N'10', N'knowledge_id', N'5', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:33:01'), (N'11', N'11', N'knowledge_id', N'5', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:33:13'), (N'12', N'12', N'knowledge_id', N'9', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'13', N'13', N'knowledge_id', N'9', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:48:10'), (N'14', N'14', N'knowledge_id', N'9', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:55:12'), (N'15', N'15', N'knowledge_id', N'9', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 15:57:28'), (N'16', N'16', N'knowledge_id', N'9', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:17:21'), (N'17', N'17', N'knowledge_id', N'10', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'18', N'18', N'knowledge_id', N'10', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:35:35'), (N'19', N'19', N'knowledge_id', N'10', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:35:51'), (N'20', N'20', N'knowledge_id', N'10', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:36:34'), (N'21', N'21', N'knowledge_id', N'10', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 19:31:35'), (N'22', N'22', N'knowledge_id', N'10', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:40:33'), (N'23', N'23', N'knowledge_id', N'10', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'24', N'24', N'knowledge_id', N'10', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'25', N'25', N'knowledge_id', N'11', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 18:52:56'), (N'26', N'26', N'knowledge_id', N'11', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'27', N'27', N'knowledge_id', N'11', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'28', N'28', N'knowledge_id', N'12', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-09 18:14:43'), (N'29', N'29', N'knowledge_id', N'12', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'30', N'30', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'31', N'31', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'32', N'32', N'knowledge_id', N'13', N'', N'0', N'1', N'2021-05-30 19:15:20', N'2021-07-09 01:23:17'), (N'33', N'33', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'34', N'34', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'35', N'35', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'36', N'36', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'37', N'37', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'38', N'38', N'knowledge_id', N'13', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'39', N'39', N'knowledge_id', N'14', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:47:19'), (N'40', N'40', N'knowledge_id', N'14', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'41', N'41', N'knowledge_id', N'14', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'42', N'42', N'knowledge_id', N'14', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:51:33'), (N'43', N'43', N'knowledge_id', N'15', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:55:41'), (N'44', N'44', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'45', N'45', N'knowledge_id', N'15', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:57:49'), (N'46', N'46', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'47', N'47', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'48', N'48', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'49', N'49', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'50', N'50', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'51', N'51', N'knowledge_id', N'15', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 17:58:53'), (N'52', N'52', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'53', N'53', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'54', N'54', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'55', N'55', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'56', N'56', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'57', N'57', N'knowledge_id', N'15', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 18:00:53'), (N'58', N'58', N'knowledge_id', N'15', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'59', N'59', N'knowledge_id', N'15', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 18:02:50'), (N'60', N'60', N'knowledge_id', N'16', N'', N'1', N'1', N'2021-05-30 19:15:20', N'2021-06-08 18:04:21'), (N'61', N'61', N'knowledge_id', N'16', N'1', N'1', N'1', N'2021-05-30 19:15:20', N'2021-05-30 19:15:20'), (N'62', N'1', N'knowledge_id', N'1', N'', N'0', N'1', N'2021-06-07 22:41:15', N'2021-06-10 01:15:24'), (N'63', N'64', N'knowledge_id', N'1', N'', N'63', N'1', N'2021-06-07 22:41:34', N'2021-06-07 22:41:34'), (N'64', N'66', N'knowledge_id', N'35', N'', N'64', N'1', N'2021-06-08 18:04:46', N'2021-06-08 18:04:46'), (N'65', N'67', N'knowledge_id', N'35', N'', N'65', N'1', N'2021-06-08 18:04:53', N'2021-06-08 18:04:53'), (N'66', N'68', N'knowledge_id', N'35', N'', N'66', N'1', N'2021-06-08 18:05:00', N'2021-06-08 18:05:00'), (N'67', N'69', N'knowledge_id', N'35', N'', N'67', N'1', N'2021-06-08 18:05:07', N'2021-06-08 18:05:07'), (N'68', N'70', N'knowledge_id', N'35', N'', N'68', N'1', N'2021-06-08 18:05:14', N'2021-06-08 18:05:14'), (N'69', N'71', N'knowledge_id', N'35', N'', N'69', N'1', N'2021-06-08 18:05:20', N'2021-06-08 18:05:20')
GO

SET IDENTITY_INSERT [dbo].[tag_relation] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for topic
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[topic]') AND type IN ('U'))
	DROP TABLE [dbo].[topic]
GO

CREATE TABLE [dbo].[topic] (
  [id] int NOT NULL identity(1,1),
  [title] nvarchar(254) NOT NULL DEFAULT '',
  [type] nvarchar(50) NOT NULL DEFAULT '',
  [answer_type] nvarchar(50) NOT NULL DEFAULT '',
  [knowledge_id] int NOT NULL DEFAULT 0,
  [content] nvarchar(max) NOT NULL,
  [state] tinyint NOT NULL DEFAULT 1,
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'问题名称',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'title'
GO

EXEC sp_addextendedproperty
'MS_Description', N'类型: 视频 - video，图片 - pic， 图文 - graphic，文案 - text',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'答题方式: 阅读-read 上传-upload 选择-choice',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'answer_type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'知识点id',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'knowledge_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'题目内容 - json',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'content'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'topic',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'题库',
'SCHEMA', N'dbo',
'TABLE', N'topic'
GO


-- ----------------------------
-- Records of topic
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[topic] ON
GO

INSERT INTO [dbo].[topic] ([id], [title], [type], [answer_type], [knowledge_id], [content], [state], [created_at], [updated_at]) VALUES (N'1', N'波士顿科学全球共有_____名员工，服务于共_____个机构，业务遍及全球约_____个国家？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["3.8万，178，120","3.2万，141，130","2.9万，137，125","3.7万，183，135"],"pic":""}', N'1', N'2021-05-31 01:26:07', N'2021-05-31 01:26:07'), (N'2', N'波士顿科学每年治疗约_____名患者，拥有_____件改善生命的产品，累计获得授权专利_____项？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["2500万，11000，1.6万","2700万，12000，1.7万","3000万，13000，1.8万","3200万，12000，2.1万"],"pic":""}', N'1', N'2021-05-31 01:26:45', N'2021-05-31 01:26:45'), (N'3', N'波士顿科学全球共建立了_____所创新培训学院，每年在学院举办的专业培训超过_____       场？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["13，150","14，200","15，250","16，300"],"pic":""}', N'1', N'2021-05-31 01:28:28', N'2021-05-31 01:28:28'), (N'4', N'波士顿科学于_____年由_____和_____在美国的_____州成立，专注于微创医疗器械的研发。', N'text', N'choice', N'1', N'{"answer_num":3,"list":["1960，John Abele，Peter Nelson，蒙大拿","1969，John Smith，Peter Nicholas，马里兰","1970，John Davis，Peter Walker，密歇根","1979，John Abele，Peter Nicholas，马萨诸塞"],"pic":""}', N'1', N'2021-05-31 01:31:59', N'2021-05-31 01:31:59'), (N'5', N'波士顿科学共有_____款产品或技术斩获“盖伦奖”或提名（盖伦奖被誉为“医药界的诺贝尔奖”）?', N'text', N'choice', N'1', N'{"answer_num":1,"list":["5","6","7","8"],"pic":""}', N'1', N'2021-05-31 01:32:55', N'2021-05-31 01:32:55'), (N'6', N'波士顿科学于_____年进入中国，并于_____年正式成立大中华区，目前在华人数共_____人。', N'text', N'choice', N'1', N'{"answer_num":3,"list":["1960，2000，12000","1979，2004，11000","1986，2006，13000","1997，2014，13000"],"pic":""}', N'1', N'2021-05-31 01:33:27', N'2021-05-31 01:33:27'), (N'7', N'波士顿科学中国总部位于_____，第二总部位于_____。', N'text', N'choice', N'1', N'{"answer_num":1,"list":["上海，北京","上海，成都","北京，上海","北京，广州"],"pic":""}', N'1', N'2021-05-31 01:33:58', N'2021-05-31 01:33:58'), (N'8', N'北京T3创库的全称为_____?', N'text', N'choice', N'1', N'{"answer_num":3,"list":["Technology, Transparent, Think Tank","Transformation, Target, Trail","Technical, Talent, Target","Technology, Transformation, Think Tank "],"pic":""}', N'1', N'2021-05-31 01:34:29', N'2021-05-31 01:34:29'), (N'10', N'波士顿科学获得了以下哪些奖项（多选）？', N'', N'dupChoice', N'1', N'{"answer_num":[0,3,1,2],"list":["爱迪生奖","盖伦奖","2019/2020/2021中国杰出雇主认证","企业社会责任典范奖","中国百强创新机构","","",""],"pic":""}', N'1', N'2021-05-31 21:53:57', N'2021-05-31 21:53:57'), (N'11', N'波士顿科学在中国主要的产品线包括以下哪些（多选）？', N'', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3,4,5],"list":["心脏介入及结构性心脏病","心脏节律管理","外周及肿瘤介入","电生理","泌尿及盆底健康","内窥镜介入","神经调节",""],"pic":""}', N'1', N'2021-05-31 21:56:37', N'2021-05-31 21:56:37'), (N'12', N'波士顿科学坚守6大核心价值观为关爱之心，有效创新，卓越之志，全球协作，致赢精神，多元融汇。', N'', N'judge', N'1', N'{"answer_num":0,"list":["正确","错误"],"pic":""}', N'1', N'2021-05-31 21:57:03', N'2021-05-31 21:57:03'), (N'13', N'波科企业文化的行为准则分别为快，精，韧，合，以下哪条是“韧”的详细解释？', N'pic', N'choice', N'1', N'{"answer_num":2,"list":["优化流程，精细管理，工匠精神，追求卓越","资源整合，多元融合，区域合作，协作共赢","敢于尝试，持续探索，锐意进取，永不放弃","敏于洞察，勤于思考，快于行动，善于总结"],"pic":""}', N'1', N'2021-05-31 22:06:34', N'2021-05-31 22:06:34'), (N'14', N'公司鼓励员工在当年休完可休的法定年休假，可若是工作繁忙来不及休假怎么办？假期能够顺延到第二年年底吗？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["不能顺延","可以顺延","法定年休假不能顺延，公司福利年休假可以顺延",""],"pic":""}', N'1', N'2021-05-31 22:08:11', N'2021-05-31 22:08:11'), (N'15', N'公司在以下哪些特殊日子或节假日，会额外多放1天假期？（多选）', N'', N'dupChoice', N'1', N'{"answer_num":[0,3],"list":["生日","端午","中秋","圣诞","","","",""],"pic":""}', N'1', N'2021-05-31 22:08:47', N'2021-05-31 22:08:47'), (N'16', N'公司在以下哪些特殊日子或节假日会发放福利？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,3,4,1,2],"list":["春节","中秋","端午","新婚","新生儿","","",""],"pic":""}', N'1', N'2021-05-31 22:09:29', N'2021-05-31 22:09:29'), (N'17', N'定时工作制的员工，若上个月的加班忘记提交申请了，这个月还能申请上个月的加班吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["能","不能"],"pic":""}', N'1', N'2021-05-31 22:10:00', N'2021-05-31 22:10:00'), (N'18', N'员工协助计划（EAP）为以下哪些人群提供心理健康咨询，沙龙会，午餐分享会等服务内容？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3,4],"list":["员工","父母","配偶","配偶父母","子女","兄弟姐妹","",""],"pic":""}', N'1', N'2021-05-31 22:10:37', N'2021-05-31 22:10:37'), (N'19', N'员工协助计划（EAP）的热线是否会保密？', N'text', N'judge', N'1', N'{"answer_num":0,"list":["会","不会"],"pic":""}', N'1', N'2021-05-31 22:11:02', N'2021-05-31 22:11:02'), (N'20', N'购买公司产品后，该如何进行报销申请？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["拨打员工服务热线","发送申请邮件至员工服务邮箱","",""],"pic":""}', N'1', N'2021-05-31 22:11:34', N'2021-05-31 22:11:34'), (N'21', N'以下哪些员工可以申请防辐射铅防护用品？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1],"list":["销售员工","需要跟台的市场部员工","职能部门员工","","","","",""],"pic":""}', N'1', N'2021-05-31 22:12:03', N'2021-05-31 22:12:03'), (N'22', N'每年的第_____季度，将会由公司统一安排体检，标准为_____元？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["二，500","三，800","四，1,000",""],"pic":""}', N'1', N'2021-05-31 22:12:35', N'2021-05-31 22:12:35'), (N'23', N'公司提供哪些健身福利选项，可供员工选择其一？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,2,1,3],"list":["健身津贴","超级猩猩超猩卡代金券","一兆韦德/威尔士健身次卡","一兆韦德/威尔士健身卡有效周期延长","","","",""],"pic":""}', N'1', N'2021-05-31 22:13:10', N'2021-05-31 22:13:10'), (N'24', N'新员工子女医疗加保，需在入职后_____天内申请办理加保手续？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["14","30","40","60"],"pic":""}', N'1', N'2021-05-31 22:13:38', N'2021-05-31 22:13:38'), (N'25', N'平安好福利app包含以下哪些功能操作？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2],"list":["注册参与员工自付费升级计划","申请家属自选计划","在线理赔医疗费用","","","","",""],"pic":""}', N'1', N'2021-05-31 22:14:13', N'2021-05-31 22:14:13'), (N'26', N'员工子女、配偶的医疗费用是否可以报销？', N'text', N'judge', N'1', N'{"answer_num":0,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:14:33', N'2021-05-31 22:14:33'), (N'27', N'新员工入职多久可以参加共享财富计划2.0？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["入职当月","入职次月","",""],"pic":""}', N'1', N'2021-05-31 22:14:55', N'2021-05-31 22:15:08'), (N'28', N'每年的_____月将开放共襄财富2.0的领取申请（包括在职领取）？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["3","4","5","6"],"pic":""}', N'1', N'2021-05-31 22:15:31', N'2021-05-31 22:15:31'), (N'29', N'共襄财富的供款组合包括？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2],"list":["公司基本供款","员工自愿供款","公司匹配供款","","","","",""],"pic":""}', N'1', N'2021-05-31 22:15:55', N'2021-05-31 22:15:55'), (N'30', N'共襄财富根据员工不同的服务年限，公司缴纳部分的归属比例不同。以下哪个比例为入职满3年的员工可获得的比例归属？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["20%","40%","60%","80%","100%",""],"pic":""}', N'1', N'2021-05-31 22:17:32', N'2021-05-31 22:17:32'), (N'31', N'员工购股计划中，给予员工的折扣是多少？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["8折","8.5折","9折","","",""],"pic":""}', N'1', N'2021-05-31 22:17:58', N'2021-05-31 22:17:58'), (N'32', N'正式员工如何加入购股计划？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["收到邮件后，注册Etrade股票平台","发送邮件至员工服务邮箱申请","","","",""],"pic":""}', N'1', N'2021-05-31 22:18:24', N'2021-05-31 22:18:24'), (N'33', N'差旅预定的4步骤包括：1) 登录携程；2)领导审批；3)Swift差旅申请；4)提交订单。请问正确的顺序为：', N'text', N'choice', N'1', N'{"answer_num":3,"list":["1234","2314","1324","3214","",""],"pic":""}', N'1', N'2021-05-31 22:22:03', N'2021-05-31 22:22:03'), (N'34', N'差旅申请获批后，出差行程有变化，是否需要重新申请？', N'text', N'judge', N'1', N'{"answer_num":0,"list":["需要","不需要"],"pic":""}', N'1', N'2021-05-31 22:22:40', N'2021-05-31 22:22:40'), (N'35', N'出差期间的火车，可以申请以下哪些类型？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1],"list":["二等座","一等座","特等座","商务座","","","",""],"pic":""}', N'1', N'2021-05-31 22:23:13', N'2021-05-31 22:23:13'), (N'36', N'员工是否允许携带医生自驾出差？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["允许","不允许"],"pic":""}', N'1', N'2021-05-31 22:23:34', N'2021-05-31 22:23:34'), (N'37', N'自驾出差来回大于_____公里，需事先获得部门Head的批准？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["200","400","600","800","",""],"pic":""}', N'1', N'2021-05-31 22:24:04', N'2021-05-31 22:24:04'), (N'38', N'出差期间，在酒店住宿_____晚或以上，可报销洗衣和干洗费用？洗衣费用每周可报销最高_____元？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["2，100","2，200","3，200","3，300","",""],"pic":""}', N'1', N'2021-05-31 22:24:30', N'2021-05-31 22:24:30'), (N'39', N'员工太忙，想图个方便，直接通过电话自行向航空公司订购机票，这个费用可以报销吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:24:52', N'2021-05-31 22:24:52'), (N'40', N'经理级别以下的员工在中国大陆境内的出差，早餐，午餐和晚餐的标准分别是_____元？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["30，100，100","30，100，110","50，100，100","50，110，110","",""],"pic":""}', N'1', N'2021-05-31 22:25:22', N'2021-05-31 22:25:22'), (N'41', N'经理级别及以上的员工在中国大陆境内的出差，早餐，午餐和晚餐的标准分别是_____元？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["50，100，100","50，110，110","80，150，150","80，150，300","",""],"pic":""}', N'1', N'2021-05-31 22:26:12', N'2021-05-31 22:26:12'), (N'42', N'同事团队就餐，应该由谁来买单？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["任意同事","有公司信用卡的同事","团餐中最高职位者","","",""],"pic":""}', N'1', N'2021-05-31 22:26:37', N'2021-05-31 22:26:37'), (N'43', N'在出差期间，员工用自己的卡支付了300元餐费，这项花费可以直接报销吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:26:58', N'2021-05-31 22:26:58'), (N'44', N'小于_____元的费用，允许不提交明细收据/清单？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["100","200","300","400","",""],"pic":""}', N'1', N'2021-05-31 22:27:20', N'2021-05-31 22:27:20'), (N'45', N'小于_____元的员工餐费/招待费，允许不提交明细收据/清单（跟台餐费除外）？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["400","500","600","700","",""],"pic":""}', N'1', N'2021-05-31 22:27:58', N'2021-05-31 22:27:58'), (N'46', N'员工必须在费用/活动发生日的_____天之内提交报销，_____天之内完成GBS的在线审核并通过，_____天之内需要将实物报告递交至GBS？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["30，60，90","60，90，30","90，60，30","30，90，60","",""],"pic":""}', N'1', N'2021-05-31 22:28:29', N'2021-05-31 22:28:29'), (N'47', N'公司信用卡可以用于个人消费吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:28:48', N'2021-05-31 22:28:48'), (N'48', N'公司信用卡不用自己还款，公司会直接还款的。以上说法是否正确？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["正确","不正确"],"pic":""}', N'1', N'2021-05-31 22:29:07', N'2021-05-31 22:29:07'), (N'49', N'公司信用卡的账单到期日和还款日分别为每月的_____日和_____日？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["5，15","5，25","10，25","10，28","",""],"pic":""}', N'1', N'2021-05-31 22:29:44', N'2021-05-31 22:29:44'), (N'50', N'资金管理关注的企业经营与现金流周期之间的哪两大问题？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["数量不确定，时间不同步","数量不同步，时间不确定","","","",""],"pic":""}', N'1', N'2021-05-31 22:30:06', N'2021-05-31 22:30:06'), (N'51', N'资金管理的关注点是以下哪4个字？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["现金为王","时间为王","周期为王","","",""],"pic":""}', N'1', N'2021-05-31 22:30:29', N'2021-05-31 22:30:29'), (N'52', N'以下哪些选项是不可以报销的？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3,4],"list":["话费充值卡","酒店拨打国内/国际长途","用餐时为了优惠办理的会员卡/打折卡","鱼翅","HCP礼品","","",""],"pic":""}', N'1', N'2021-05-31 22:31:04', N'2021-05-31 22:31:04'), (N'53', N'员工未刷公司卡的IRF费用，可以走concur报销吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:31:29', N'2021-05-31 22:31:29'), (N'54', N'员工在报销时忘记上传波科模板的签到表和活动照片了，IRF费用还可以报销吗', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:31:49', N'2021-05-31 22:31:49'), (N'55', N'在报销过程中，以下关于发票的描述，哪些是错误的？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3],"list":["未上传发票","发票抬头错误","发票税号错误","提供的手机话费发票无账期月","","","",""],"pic":""}', N'1', N'2021-05-31 22:32:43', N'2021-05-31 22:32:43'), (N'56', N'以下哪些情况报销将被退回？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3,4,5],"list":["无酒店水单","酒店水单中入住者信息不是本人","无用餐明细水单（院外餐费600元）","无办公用品明细（办公用品费用300元）","未备注原因的餐费超标","携程网上预订酒店，无电子水单","",""],"pic":""}', N'1', N'2021-05-31 22:33:26', N'2021-05-31 22:33:26'), (N'57', N'以下哪些情况报销将被退回？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2],"list":["上传的图像模糊不清","发票重叠","发票遮盖","","","","",""],"pic":""}', N'1', N'2021-05-31 22:33:52', N'2021-05-31 22:33:52'), (N'58', N'在报销过程中，以下关于发票的描述，哪些是错误的？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3],"list":["未上传发票","发票抬头错误","发票税号错误","提供的手机话费发票无账期月","","","",""],"pic":""}', N'1', N'2021-05-31 22:34:29', N'2021-05-31 22:34:29'), (N'59', N'请选出以下哪个费用类型是OUS-HCP-院外用餐？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["和美国医生发生的用餐","与非美国医生发生的用餐","","","",""],"pic":""}', N'1', N'2021-05-31 22:34:48', N'2021-05-31 22:34:48'), (N'60', N'员工2月度的手机费最晚在哪一天需要提交报销？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["2月最后一天","3月最后一天","4月最后一天","从2月最后一天算起的60日内","",""],"pic":""}', N'1', N'2021-05-31 22:35:19', N'2021-05-31 22:35:19'), (N'61', N'员工邀请经销商和几名波科同事共进晚餐，应该选择哪个费用类型？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["OUS-HCP-晚餐","HCP-晚餐","晚餐（团体）","晚餐（个人）","",""],"pic":""}', N'1', N'2021-05-31 22:35:47', N'2021-05-31 22:35:47'), (N'62', N'员工请2名非美国HCP，2名经销商和2名波科同事在新天地某家餐厅共进晚餐，应该选择哪个费用类型？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["OUS-HCP-院外用餐","HCP-院外晚餐","晚餐（团体）","晚餐（个人）","",""],"pic":""}', N'1', N'2021-05-31 22:36:18', N'2021-05-31 22:36:18'), (N'63', N'所有费用在系统提交完成后，需要将封面打印出来和发票原件一起邮寄到上海办GBS收。以上说法是否正确？', N'text', N'judge', N'1', N'{"answer_num":0,"list":["正确","不正确"],"pic":""}', N'1', N'2021-05-31 22:36:38', N'2021-05-31 22:36:38'), (N'64', N'员工在2月22日发生了一笔餐饮费，发票在当日开具，报销时交易日期应该填几号？员工在3月10日收到2月度的手机费发票，报销时，交易日期应该填几号？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["2月任何一天，3月10日","2月22日，3月10日","2月22日，2月任何一天","2月任何一天，2月任何一天","",""],"pic":""}', N'1', N'2021-05-31 22:37:11', N'2021-05-31 22:37:11'), (N'65', N'在线报销提交后，_____天内未递交纸质报告的，所有报销都会暂停？GBS发送邮件提醒后，若_____天内仍未递交纸质报告，GBS会通知BU Head，并扣除报告金额的25%进行报销？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["30，7","30，14","60，7","60，14","",""],"pic":""}', N'1', N'2021-05-31 22:37:52', N'2021-05-31 22:37:52'), (N'66', N'GBS的邮箱是哪个？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["ChinaGBSPTP@bsci.com","ChinaGBSHelpdesk@bsci.com","ChinaGBSATR@bsci.com","","",""],"pic":""}', N'1', N'2021-05-31 22:38:15', N'2021-05-31 22:38:15'), (N'67', N'费用发生的交易日期与实际开票日期不一致，如何填写交易日期？', N'text', N'choice', N'1', N'{"answer_num":2,"list":["按照发票日期填写，直接提交报销","按照实际交易日期填写，不写备注，直接提交报销","按照实际交易日期填写，备注不一致原因，提交报销","按照发生月份随意填写一天","",""],"pic":""}', N'1', N'2021-05-31 22:38:41', N'2021-05-31 22:38:41'), (N'68', N'DRM的主要工作职责有哪些？（多选）', N'text', N'dupChoice', N'1', N'{"answer_num":[0,1,2,3],"list":["渠道管理","渠道运营","渠道创新","渠道职能","","","",""],"pic":""}', N'1', N'2021-05-31 22:39:15', N'2021-05-31 22:39:15'), (N'69', N'以下哪个项目是经销商预警机制？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["波乐荟","迪乐评分","一带一路","","",""],"pic":""}', N'1', N'2021-05-31 22:39:40', N'2021-05-31 22:39:40'), (N'70', N'以下哪个项目是招商项目？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["波乐荟","迪乐评分","一带一路","","",""],"pic":""}', N'1', N'2021-05-31 22:40:05', N'2021-05-31 22:40:05'), (N'71', N'经销商如果发现疑似非正规渠道销售的波科产品，应该采取什么措施？', N'text', N'choice', N'1', N'{"answer_num":0,"list":["向波科商务部举报，举报属实者将获得奖励","购买非正规渠道的产品","不采取任何措施","","",""],"pic":""}', N'1', N'2021-05-31 22:40:30', N'2021-05-31 22:40:30'), (N'72', N'小林女士在微信上发表了一条言论，开头写着“波士顿科学”字眼，可以吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:40:52', N'2021-05-31 22:40:52'), (N'73', N'小林女士在知乎上看到了一条赞美公司的言论，她可以回复吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:41:14', N'2021-05-31 22:41:14'), (N'74', N'小林女士这周末有个私人的社交媒体聚会，结束时留下了自己@bsci.com结尾的电子邮箱，可以吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:41:33', N'2021-05-31 22:41:33'), (N'75', N'小林女士这天接到媒体问询，关于公司患者不良事件的详情，小林女士应该如何处理？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["跟媒体解释本次的患者不良事件","将此事告知公司的媒体专员","","","",""],"pic":""}', N'1', N'2021-05-31 22:41:59', N'2021-05-31 22:41:59'), (N'76', N'这天，小李先生被临时叫去了会议室，配合政府人员进行调查，回来后，小林女士向小李先生八卦关于调查的详情，小李先生可以回答吗？', N'text', N'judge', N'1', N'{"answer_num":1,"list":["可以","不可以"],"pic":""}', N'1', N'2021-05-31 22:42:19', N'2021-05-31 22:42:19'), (N'77', N'产品从国外仓库发货到中国后，库存可用于销售一共需要多少天？', N'text', N'choice', N'1', N'{"answer_num":3,"list":["7天","16天","25天","32天","",""],"pic":""}', N'1', N'2021-05-31 22:42:51', N'2021-05-31 22:42:51'), (N'78', N'以下哪个月提交的预测会用于评估10月份的预测准确度？', N'text', N'choice', N'1', N'{"answer_num":1,"list":["6月","7月","8月","9月","",""],"pic":""}', N'1', N'2021-05-31 22:43:21', N'2021-05-31 22:43:21')
GO

SET IDENTITY_INSERT [dbo].[topic] OFF
GO

COMMIT
GO


-- ----------------------------
-- Table structure for user
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[user]') AND type IN ('U'))
	DROP TABLE [dbo].[user]
GO

CREATE TABLE [dbo].[user] (
  [id] int NOT NULL identity(1,1),
  [name] nvarchar(50) NOT NULL DEFAULT '',
  [account_id] nchar(32) NOT NULL DEFAULT '',
  [avatar] nvarchar(254) NOT NULL DEFAULT '',
  [session_key] nvarchar(254) NOT NULL DEFAULT '',
  [openid] nvarchar(254) NOT NULL DEFAULT '',
  [unionid] nvarchar(254) NOT NULL DEFAULT '',
  [nickname] nvarchar(50) NOT NULL DEFAULT '',
  [birthday] nvarchar(20) NOT NULL DEFAULT '',
  [phone] nvarchar(20) NOT NULL DEFAULT '',
  [mobile] nvarchar(20) NOT NULL DEFAULT '',
  [register_time] datetime2 NOT NULL,
  [state] tinyint NOT NULL DEFAULT 0,
  [ext_int_1] int NOT NULL DEFAULT 0,
  [ext_int_2] int NOT NULL DEFAULT 0,
  [ext_int_3] int NOT NULL DEFAULT 0,
  [ext_int_4] int NOT NULL DEFAULT 0,
  [ext_int_5] int NOT NULL DEFAULT 0,
  [ext_varchar_1] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_2] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_3] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_4] nvarchar(50) NOT NULL DEFAULT '',
  [ext_varchar_5] nvarchar(50) NOT NULL DEFAULT '',
  [created_at] datetime2 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  [updated_at] datetime2 NULL
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'名称名称',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'name'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户account_id',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'account_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户头像',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'avatar'
GO

EXEC sp_addextendedproperty
'MS_Description', N'会话密钥',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'session_key'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户微信openid',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'openid'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户在开放平台的唯一标识符，若当前小程序已绑定到微信开放平台帐号下会返回',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'unionid'
GO

EXEC sp_addextendedproperty
'MS_Description', N'昵称',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'nickname'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户生日',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'birthday'
GO

EXEC sp_addextendedproperty
'MS_Description', N'座机',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'phone'
GO

EXEC sp_addextendedproperty
'MS_Description', N'手机号',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'mobile'
GO

EXEC sp_addextendedproperty
'MS_Description', N'注册时间',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'register_time'
GO

EXEC sp_addextendedproperty
'MS_Description', N'状态 1-正常 2-关闭',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'state'
GO

EXEC sp_addextendedproperty
'MS_Description', N'创建时间',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'created_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'更新时间',
'SCHEMA', N'dbo',
'TABLE', N'user',
'COLUMN', N'updated_at'
GO

EXEC sp_addextendedproperty
'MS_Description', N'用户',
'SCHEMA', N'dbo',
'TABLE', N'user'
GO


-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN TRANSACTION
GO

COMMIT
GO


-- ----------------------------
-- Indexes structure for table activity
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[activity] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_code]
ON [dbo].[activity] (
  [code]
)
GO


-- ----------------------------
-- Primary Key structure for table activity
-- ----------------------------
ALTER TABLE [dbo].[activity] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table activity_participate_record
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[activity_participate_record] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_activity_code]
ON [dbo].[activity_participate_record] (
  [activity_code]
)
GO

CREATE NONCLUSTERED INDEX [idx_account_id]
ON [dbo].[activity_participate_record] (
  [account_id]
)
GO


-- ----------------------------
-- Primary Key structure for table activity_participate_record
-- ----------------------------
ALTER TABLE [dbo].[activity_participate_record] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table activity_participate_schedule
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[activity_participate_schedule] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_activity_code]
ON [dbo].[activity_participate_schedule] (
  [activity_code]
)
GO

CREATE NONCLUSTERED INDEX [idx_account_id]
ON [dbo].[activity_participate_schedule] (
  [account_id]
)
GO


-- ----------------------------
-- Primary Key structure for table activity_participate_schedule
-- ----------------------------
ALTER TABLE [dbo].[activity_participate_schedule] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table activity_schedule
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[activity_schedule] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_activity_code]
ON [dbo].[activity_schedule] (
  [activity_code]
)
GO


-- ----------------------------
-- Primary Key structure for table activity_schedule
-- ----------------------------
ALTER TABLE [dbo].[activity_schedule] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table admin_user
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[admin_user] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_account_id]
ON [dbo].[admin_user] (
  [account_id]
)
GO

CREATE NONCLUSTERED INDEX [idx_mobile]
ON [dbo].[admin_user] (
  [mobile]
)
GO


-- ----------------------------
-- Primary Key structure for table admin_user
-- ----------------------------
ALTER TABLE [dbo].[admin_user] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table asset
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[asset] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[asset] (
  [unique_code]
)
GO


-- ----------------------------
-- Primary Key structure for table asset
-- ----------------------------
ALTER TABLE [dbo].[asset] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table asset_change_log
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[asset_change_log] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[asset_change_log] (
  [unique_code]
)
GO


-- ----------------------------
-- Primary Key structure for table asset_change_log
-- ----------------------------
ALTER TABLE [dbo].[asset_change_log] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table group
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[group] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_code]
ON [dbo].[group] (
  [code]
)
GO


-- ----------------------------
-- Primary Key structure for table group
-- ----------------------------
ALTER TABLE [dbo].[group] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table group_item
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[group_item] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_group_code]
ON [dbo].[group_item] (
  [group_code]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[group_item] (
  [unique_code]
)
GO


-- ----------------------------
-- Primary Key structure for table group_item
-- ----------------------------
ALTER TABLE [dbo].[group_item] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table inventory
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[inventory] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table inventory
-- ----------------------------
ALTER TABLE [dbo].[inventory] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table knowledge
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[knowledge] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table knowledge
-- ----------------------------
ALTER TABLE [dbo].[knowledge] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table order
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[order] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[order] (
  [unique_code]
)
GO

CREATE NONCLUSTERED INDEX [idx_trade_no]
ON [dbo].[order] (
  [trade_no]
)
GO


-- ----------------------------
-- Primary Key structure for table order
-- ----------------------------
ALTER TABLE [dbo].[order] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table order_item
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[order_item] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_sku]
ON [dbo].[order_item] (
  [sku]
)
GO

CREATE NONCLUSTERED INDEX [idx_trade_no]
ON [dbo].[order_item] (
  [trade_no]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[order_item] (
  [unique_code]
)
GO


-- ----------------------------
-- Primary Key structure for table order_item
-- ----------------------------
ALTER TABLE [dbo].[order_item] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Primary Key structure for table phinxlog
-- ----------------------------
ALTER TABLE [dbo].[phinxlog] ADD PRIMARY KEY CLUSTERED ([version])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table prize_contest
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[prize_contest] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table prize_contest
-- ----------------------------
ALTER TABLE [dbo].[prize_contest] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table prize_contest_rank
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[prize_contest_rank] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_account_id]
ON [dbo].[prize_contest_rank] (
  [account_id]
)
GO


-- ----------------------------
-- Primary Key structure for table prize_contest_rank
-- ----------------------------
ALTER TABLE [dbo].[prize_contest_rank] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table prize_contest_record
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[prize_contest_record] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table prize_contest_record
-- ----------------------------
ALTER TABLE [dbo].[prize_contest_record] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table prize_contest_record_item
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[prize_contest_record_item] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table prize_contest_record_item
-- ----------------------------
ALTER TABLE [dbo].[prize_contest_record_item] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table prize_contest_schedule
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[prize_contest_schedule] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table prize_contest_schedule
-- ----------------------------
ALTER TABLE [dbo].[prize_contest_schedule] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table product
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[product] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table product
-- ----------------------------
ALTER TABLE [dbo].[product] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table rule
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[rule] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table rule
-- ----------------------------
ALTER TABLE [dbo].[rule] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table tag
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[tag] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table tag
-- ----------------------------
ALTER TABLE [dbo].[tag] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table tag_relation
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[tag_relation] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_tag_id]
ON [dbo].[tag_relation] (
  [tag_id]
)
GO

CREATE NONCLUSTERED INDEX [idx_unique_code]
ON [dbo].[tag_relation] (
  [unique_code]
)
GO


-- ----------------------------
-- Primary Key structure for table tag_relation
-- ----------------------------
ALTER TABLE [dbo].[tag_relation] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table topic
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[topic] (
  [id]
)
GO


-- ----------------------------
-- Primary Key structure for table topic
-- ----------------------------
ALTER TABLE [dbo].[topic] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO


-- ----------------------------
-- Indexes structure for table user
-- ----------------------------
CREATE UNIQUE NONCLUSTERED INDEX [id]
ON [dbo].[user] (
  [id]
)
GO

CREATE NONCLUSTERED INDEX [idx_account_id]
ON [dbo].[user] (
  [account_id]
)
GO

CREATE NONCLUSTERED INDEX [idx_openid]
ON [dbo].[user] (
  [openid]
)
GO

CREATE NONCLUSTERED INDEX [idx_unionid]
ON [dbo].[user] (
  [unionid]
)
GO

CREATE NONCLUSTERED INDEX [idx_mobile]
ON [dbo].[user] (
  [mobile]
)
GO


-- ----------------------------
-- Primary Key structure for table user
-- ----------------------------
ALTER TABLE [dbo].[user] ADD PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
GO

