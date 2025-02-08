import uni from '@uni-helper/eslint-config'

export default uni(
  {
    unocss: true,
    vue: true,
    extends: [
      'eslint:recommended', // 基础的 eslint 配置
      'plugin:prettier/recommended', // 启用 prettier 插件并禁用所有与 prettier 冲突的规则
    ],
    rules: {
      'no-console': 'off',
      'style/no-tabs': 'off',
      'jsdoc/require-returns-check': 'off',
      'jsdoc/check-param-names': 'off',
      'node/handle-callback-err': 'off',
      'unused-imports/no-unused-vars': 'off',
      'antfu/top-level-function': 'off',
      'ts/no-use-before-define': 'off',
      'max-len': [
        'warn',
        {
          code: 120, // 每行最多120个字符
          ignoreUrls: true, // 忽略URL
          ignoreStrings: true, // 忽略字符串
          ignoreTemplateLiterals: true, // 忽略模板字符串
        },
      ],
    },
  },
)
