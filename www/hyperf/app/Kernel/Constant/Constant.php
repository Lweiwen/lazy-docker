<?php

/**
 * 平台端口
 */
const BACKSTAGE = 1;            //后台
const SERVICE = 2;              //服务商
const MERCHANT = 3;             //商家
const CLIENT = 4;               //会员

/**
 * 后台角色
 * Backstage Role
 */
const B_ROLE_CREATOR = 1;       //平台创建者
const B_ROLE_ADMIN = 2;         //平台管理员
const B_ROLE_STAFF = 3;         //平台员工

/**
 * 服务商角色
 * Service Role
 */
const S_ROLE_CREATOR = 101;       //服务商创建者
const S_ROLE_ADMIN = 102;         //服务商管理员
const S_ROLE_STAFF = 103;         //服务商员工

/**
 * 商家角色
 * Merchant Role
 */
const M_ROLE_CREATOR = 201;       //商家创建者
const M_ROLE_ADMIN = 202;         //商家管理员
const M_ROLE_STAFF = 203;         //商家员工

/**
 * 活动主办方角色
 * Activity Role
 */
const A_ROLE_SPONSOR_OWNER = 1;             //活动拥有者
const A_ROLE_SPONSOR_ADMIN = 2;             //活动管理员
const A_ROLE_SPONSOR_STAFF = 3;             //活动员工
const A_ROLE_MERCHANT_CREATOR = 4;          //活动参与商家负责人
const A_ROLE_MERCHANT_ADMIN = 5;            //活动参与商家管理员
const A_ROLE_MERCHANT_STAFF = 6;            //活动参与商家员工
const A_ROLE_SPONSOR_SERVICE_MANAGER = 7;   //主办方服务商管理员
const A_ROLE_MERCHANT_SERVICE_MANAGER = 8;  //商家服务商管理员
