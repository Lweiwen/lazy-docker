<?php

/**
 * 活动主办方角色
 * Activity Role
 */
const A_ROLE_OWNER = 1;         //活动拥有者
const A_ROLE_CREATOR = 2;       //活动创建者
const A_ROLE_ADMIN = 3;         //活动管理员
const A_ROLE_WATCHER = 4;       //活动观察者
const A_ROLE_CAPTAIN = 5;       //活动队长
const A_ROLE_BOSS = 6;          //活动老板
const A_ROLE_MANAGER = 7;       //活动店长
const A_ROLE_STAFF = 8;         //活动员工
const A_ROLE_LJB = 9;           //总平台账号
const A_ROLE_SIGN_IN = 10;      //签到员
const A_ROLE_CASHIER = 11;      //收银员

/**
 * 活动状态
 * Activity Status
 */
const A_STATUS_DELETE = -1;     //删除
const A_STATUS_NO = 0;          //未发布(未提交)
const A_STATUS_CHECKING= 1;     //待审核
const A_STATUS_NO_PASS = 2;     //不通过
const A_STATUS_PUBLISHED = 3;   //已发布 进行中 已截止
const A_STATUS_CLOSED = 4;      //已关闭

/**
 * 平台角色
 * Platform Role
 */
const P_ROLE_CREATOR = 1;       //平台创建者
const P_ROLE_ADMIN = 2;         //平台管理员
const P_ROLE_WATCHER = 3;       //平台观察者
const P_ROLE_MERCHANT = 4;      //平台商户

/**
 * 认证类型
 * Auth Type
 */
const AUTH_NO = 0;              //未认证
const AUTH_PERSONAL = 1;        //个人认证
const AUTH_COMPANY = 2;         //企业认证
const AUTH_ORGANIZATION = 3;    //组织认证
const AUTH_PLATFORM = 4;        //平台认证

/**
 * 认证状态
 * Auth Status
 */
const AUTH_STATUS_NO = 0;           //未发布(未提交)
const AUTH_STATUS_CHECKING= 1;      //审核中
const AUTH_STATUS_PASS = 2;         //已审核
const AUTH_STATUS_NO_PASS = 3;      //审核失败
const AUTH_STATUS_DELETE = -1;      //删除

/**
 * 报名状态
 * Partake Status
 */
const PT_STATUS_NORMAL = 0;         //正常
const PT_STATUS_CLOSE = 1;          //报名关闭
const PT_STATUS_REFUND = 2;         //报名退票
const PT_STATUS_DELETE = -1;        //删除

/**
 * 用户登录，openid黑名单
 */
const USER_OPENID_BLACK_LIST = 211;

//每个用户每日能申请提现到微信钱包的金额(元整)
const WITHDRAW_TO_WX_POCKET_DAY_LIMIT_PER_USER = 10000;
