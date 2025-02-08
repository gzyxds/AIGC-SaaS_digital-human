import type { Component } from 'vue'
export interface Config {
    title: string
    name: string
    prop: Record<string, any>
}
const widgets: Record<string, any> = import.meta.glob('./**/index.ts', { eager: true })
interface Widget {
    prop: Component
    content: Component
    config(): Config
}

const exportWidgets: Record<string, Widget> = {}
Object.keys(widgets).forEach((key) => {
    const widgetName = key.replace(/^\.\/([\w-]+).*/gi, '$1')
    exportWidgets[widgetName] = widgets[key]?.default
})
export default exportWidgets
