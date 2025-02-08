"use strict";const e=require("../composables/useRequest.js");exports.apiPostPrePay=s=>e.usePostRequest("/pay/prepay",s),exports.apiPostRecharge=s=>e.usePostRequest("/recharge/recharge",s);
