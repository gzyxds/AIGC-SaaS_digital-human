/**
 * perm 操作权限处理
 * 指令用法：
 *  <el-button v-perms="['auth.menu/edit']">编辑</el-button>
 */

import useUserStore from '@/stores/modules/user'

export default {
    mounted: (el: HTMLElement, binding: any) => {
        const { value } = binding
        const userStore = useUserStore()
        const permissions = userStore.perms
        const all_permission = '*'
        if (Array.isArray(value)) {
            if (value.length > 0) {
                let hasPermission = true
                value.forEach((key: string) => {
                    if (!permissions.includes(key) && !permissions.includes(all_permission)) {
                        hasPermission = false
                    }
                })

                if (!hasPermission) {
                    el.parentNode && el.parentNode.removeChild(el)
                }
            }
        } else {
            throw new Error('like v-perms="[\'auth.menu/edit\']"')
        }
    }
}
