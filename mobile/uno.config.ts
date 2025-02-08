import { presetUni } from '@uni-helper/unocss-preset-uni'
import {
  defineConfig,
  presetIcons,
  transformerDirectives,
  transformerVariantGroup,
} from 'unocss'

export default defineConfig({
  variants: [
    {
      name: 'test',
      autocomplete: 'test',
      match: (matcher, { theme }) => {
        return theme
      },
    },
  ],
  theme: {
    borderRadius: {
      ui: 'var(--ui-radius)',
    },
    colors: {
      tttt: {
        DEFAULT: '#ffffff',
        bold: 'rgb(var(--ui-primary-bold))',
        muted: 'rgb(var(--ui-primary-muted))',
      },
      primary: {
        DEFAULT: 'rgb(var(--ui-primary))',
        bold: 'rgb(var(--ui-primary-bold))',
        muted: 'rgb(var(--ui-primary-muted))',
      },
      warning: {
        DEFAULT: 'rgb(var(--ui-warning))',
        bold: 'rgb(var(--ui-warning-bold))',
        muted: 'rgb(var(--ui-warning-muted))',
      },
      danger: {
        DEFAULT: 'rgb(var(--ui-danger))',
        bold: 'rgb(var(--ui-danger-bold))',
        muted: 'rgb(var(--ui-danger-muted))',
      },
      success: {
        DEFAULT: 'rgb(var(--ui-success))',
        bold: 'rgb(var(--ui-success-bold))',
        muted: 'rgb(var(--ui-success-muted))',
      },
      background: {
        DEFAULT: 'rgb(var(--ui-background))',
        bold: 'rgb(var(--ui-background-bold))',
        muted: 'rgb(var(--ui-background-muted))',
      },
      form: {
        DEFAULT: 'rgb(var(--ui-form))',
        bold: 'rgb(var(--ui-form-bold))',
        muted: 'rgb(var(--ui-form-muted))',
      },
      border: {
        DEFAULT: 'rgb(var(--ui-border))',
        bold: 'rgb(var(--ui-border-bold))',
        muted: 'rgb(var(--ui-border-muted))',
      },
      foreground: {
        DEFAULT: 'rgb(var(--ui-foreground))',
        bold: 'rgb(var(--ui-foreground-bold))',
        muted: 'rgb(var(--ui-foreground-muted))',
        light: 'rgb(var(--ui-foreground-light))',
        placeholder: 'rgb(var(--ui-text-placeholder))',
      },
    },
  },
  shortcuts: {
    center: 'flex justify-center items-center',
    end: 'flex justify-end items-center',
    start: 'flex justify-start items-center',
    between: 'flex justify-between items-center',
  },
  presets: [
    presetUni(),
    presetIcons({
      scale: 1.2,
      warn: true,
      extraProperties: {
        'display': 'inline-block',
        'vertical-align': 'middle',
      },
    }),
  ],
  transformers: [
    transformerDirectives(),
    transformerVariantGroup(),
  ],
})
