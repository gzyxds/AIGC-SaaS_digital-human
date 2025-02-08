import { apiGetCloneVoiceList, apiPostCloneVoiceCreate } from '~/api/audio';
import commonVoices from '~/assets/commonVoices.json';

const scriptCreate = async () => {
    const taskList: Promise<any>[] = [];
    for (const item of commonVoices) {
        taskList.push(
            new Promise<any>((resolve) => {
                setTimeout(async () => {
                    try {
                        const result = await apiPostCloneVoiceCreate({
                            cover: '',
                            title: item.name,
                            content:
                                '世界很大，我们很小。但声音可以穿越时空，连接你我。一起聆听，这美好的世界!',
                            remark: '',
                            speed: 1,
                            timbre_name: item.voice_name,
                            voice_id: '',
                        });
                        resolve(result);
                    } catch (error) {
                        console.error(`Error creating voice for ${item.name}:`, error);
                        resolve(null); // 错误处理，继续执行
                    }
                }, 1000 * taskList.length); // 延迟每个任务执行 1 秒
            })
        );
    }

    // 执行所有任务（顺序执行，每个任务延迟 1 秒）
    await Promise.all(taskList);
};

const batchDownload = async () => {
    const { getList } = apiGetCloneVoiceList();
    const list = await getList({ newParams: { page_no: 5, page_size: 10 } });
    // const list = await getList({ newParams: { page_no: 2, page_size: 20 } });
    // const list = await getList({ newParams: { page_no: 3, page_size: 20 } });
    list.lists.forEach((item) => {
        downloadFile({
            src: item.voice_url,
            fileName: item.timbre_name || '不需要',
            suffix: 'mp3',
        });
    });
};
