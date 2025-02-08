/**
 * @description: 开发模式
 */
export function isDevMode(): boolean {
  return import.meta.env.DEV
}

/**
 * @description: 生成模式
 */
export function isProdMode(): boolean {
  return import.meta.env.PROD
}

export const isMiniProgram = (() => {
  // #ifdef H5
  const userAgent = navigator.userAgent
  if (/miniProgram/i.test(userAgent) && /micromessenger/i.test(userAgent)) {
    return true
  }
  else {
    return false
  }
  // #endif
  // #ifndef H5
  return false
  // #endif
})()
