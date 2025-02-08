/**
 * 复制文本
 */

import useClipboard from 'vue-clipboard3'

import feedback from '@/utils/feedback'

const clipboard = 'data-clipboard-text'
export default {
    mounted: (el: HTMLElement, binding: any) => {
        el.setAttribute(clipboard, binding.value)
        const { toClipboard } = useClipboard()

        el.onclick = () => {
            toClipboard(el.getAttribute(clipboard)!)
                .then(() => {
                    feedback.msgSuccess('复制成功')
                })
                .catch(() => {
                    feedback.msgError('复制失败')
                })
        }
    },
    updated: (el: HTMLElement, binding: any) => {
        el.setAttribute(clipboard, binding.value)
    }
}
