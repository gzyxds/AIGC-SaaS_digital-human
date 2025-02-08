import { useGetRequest } from '@/composables/useRequest'

export function testRequest() {
  return useGetRequest('/avatar.aiAvatarRecord/lists')
}
