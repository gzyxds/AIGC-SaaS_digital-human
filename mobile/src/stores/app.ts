import { apiGetConfig } from '@/api/common';
import { VariableEnum } from '@/enums/variableEnum'
import { defineStore } from 'pinia'

import themeConfig from './../theme.json'

export const useAppStore = defineStore('app', () => {
  const THEME = uni.getStorageSync(VariableEnum.THEME)

  const siteConfig = ref<SiteConfig | null>(null)
  const theme = ref<themeType>(THEME)

  const _toggleTheme = (themeMode: themeType) => {
    if (themeMode === 'dark') {
      theme.value = 'light'
      uni.setNavigationBarColor({
        frontColor: '#000000',
        backgroundColor: themeConfig.light.bgColor,
      })
    }
    else {
      theme.value = 'dark'
      uni.setNavigationBarColor({
        frontColor: '#ffffff',
        backgroundColor: themeConfig.dark.bgColor,
      })
    }
    uni.setStorageSync(VariableEnum.THEME, theme.value)
  }

  const setTheme = (newTheme?: themeType) => {
    if (newTheme) {
      theme.value = newTheme
      uni.setStorageSync(VariableEnum.THEME, newTheme)
      uni.setNavigationBarColor({
        frontColor: theme.value === 'dark' ? '#ffffff' : '#000000',
        backgroundColor: theme.value === 'dark' ? themeConfig.dark.bgColor : themeConfig.light.bgColor,
      })
    }
    else {
      _toggleTheme(theme.value ?? 'light')
    }
  }

  const getConfig = async () => {
    siteConfig.value = await apiGetConfig();
  };

  return {
    siteConfig,
    setTheme,
    theme,
    getConfig
  }
})
