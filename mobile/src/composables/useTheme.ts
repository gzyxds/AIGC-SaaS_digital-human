import themeConfig from './../theme.json'

export function useTheme(theme: themeType) {
  const inital = () => {
    uni.setNavigationBarColor({
      frontColor: theme === 'dark' ? '#ffffff' : '#000000',
      backgroundColor: theme === 'dark' ? themeConfig.dark.bgColorTop : themeConfig.light.bgColorTop,
    })
  }

  return {
    inital,
  }
}
