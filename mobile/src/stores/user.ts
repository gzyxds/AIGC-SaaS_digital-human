import type { RouteLocationNormalizedGeneric } from 'vue-router'

import { apiGetUser, type LoginResponse, type WechatLoginResponse } from '@/api/user'
import { PageEnum } from '@/enums/pageEnum'
import { VariableEnum } from '@/enums/variableEnum'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', () => {
  const TOKEN = uni.getStorageSync(VariableEnum.USER_TOKEN) || null
  const LOGIN_TIME_STAMP = Number(uni.getStorageSync(VariableEnum.LOGIN_TIME_STAMP).value || 0)

  /** token */
  const token = ref<string | null>(TOKEN)
  /** 用户信息 */
  const userInfo = ref<UserInfo | null>(null)
  /** 登录时间戳 */
  const loginTimeStamp = ref<number>(LOGIN_TIME_STAMP)

  /** 是否登录 */
  const isLogin = computed(() => {
    return token.value !== null && token.value !== undefined
  })

  /** 清空token */
  const clearToken = () => {
    token.value = null
    loginTimeStamp.value = 0

    uni.removeStorageSync(VariableEnum.USER_TOKEN)
    uni.removeStorageSync(VariableEnum.LOGIN_TIME_STAMP)
  }

  /** 设置登录时间 */
  const setLoginTimeStamp = () => {
    loginTimeStamp.value = Date.now()
    uni.setStorageSync(VariableEnum.LOGIN_TIME_STAMP, loginTimeStamp.value)
  }

  /** 获取用户信息 */
  const getUser = async () => {
    userInfo.value = await apiGetUser()
  }

  /** 设置token到cookie */
  const setToken = (newToken: string | null) => {
    token.value = newToken

    uni.setStorageSync(VariableEnum.USER_TOKEN, newToken)
  }

  /** 滑动刷新token */
  const refreshToken = () => {
    if (Date.now() - loginTimeStamp.value >= 3600 * 7) {
      setLoginTimeStamp()
    }
  }

  /** 登录 */
  const login = async (newToken: string) => {
    token.value = newToken
    setToken(newToken)
    getUser()
  }

  /** 退出登录 */
  const logout = async () => {
    clearToken()
    userInfo.value = null
  }

  return {
    token,
    isLogin,
    setToken,
    clearToken,
    userInfo,
    login,
    getUser,
    logout,
    refreshToken,
  }
})
