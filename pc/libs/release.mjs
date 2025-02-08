import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// 检测 Node.js 版本
const requiredNodeVersion = '14.0.0';
const currentNodeVersion = process.versions.node;

function checkNodeVersion() {
    console.log(`当前 Node.js 版本为 ${currentNodeVersion}，开始检测是否满足脚本执行要求`);
    const [requiredMajor, requiredMinor, requiredPatch] = requiredNodeVersion
        .split('.')
        .map(Number);
    const [currentMajor, currentMinor, currentPatch] = currentNodeVersion.split('.').map(Number);

    if (
        currentMajor < requiredMajor ||
        (currentMajor === requiredMajor && currentMinor < requiredMinor) ||
        (currentMajor === requiredMajor &&
            currentMinor === requiredMinor &&
            currentPatch < requiredPatch)
    ) {
        console.error(
            `⚠️ 当前 Node.js 版本（${currentNodeVersion}）不满足最低要求（${requiredNodeVersion}）。`
        );
        process.exit(1); // 退出程序，返回非零状态码
    } else {
        console.log(`✅ 当前 Node.js 版本（${currentNodeVersion}）符合要求。`);
    }
}

// 获取当前模块的目录
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// 设置源目录和目标目录，根据实际项目地址进行替换
const distDir = path.resolve(__dirname, '../.output/public');
const targetDir = path.resolve(__dirname, '../../server/public');

// 递归拷贝文件夹并显示进度
async function copyDir(src, dest, totalFiles, copiedFiles = 0) {
    const entries = await fs.promises.readdir(src, { withFileTypes: true });

    // 创建目标目录（如果不存在的话）
    await fs.promises.mkdir(dest, { recursive: true });

    for (let entry of entries) {
        const srcPath = path.join(src, entry.name);
        const destPath = path.join(dest, entry.name);

        if (entry.isDirectory()) {
            // 递归复制子目录
            copiedFiles = await copyDir(srcPath, destPath, totalFiles, copiedFiles);
        } else {
            // 复制文件（覆盖已存在的文件）
            await fs.promises.copyFile(srcPath, destPath);
            copiedFiles += 1;
            const progress = ((copiedFiles / totalFiles) * 100).toFixed(2);
            console.log(`(${progress}%)已复制文件: 【${srcPath}】到【${destPath}】`);
        }
    }

    return copiedFiles;
}

// 递归获取目录中的文件数量
async function getTotalFiles(src) {
    let totalFiles = 0;

    const entries = await fs.promises.readdir(src, { withFileTypes: true });

    for (let entry of entries) {
        const srcPath = path.join(src, entry.name);

        if (entry.isDirectory()) {
            // 递归统计子目录中的文件数量
            totalFiles += await getTotalFiles(srcPath);
        } else {
            // 文件计数
            totalFiles += 1;
        }
    }

    return totalFiles;
}

// 执行拷贝操作
async function run() {
    checkNodeVersion(); // 检查 Node.js 版本
    const totalFiles = await getTotalFiles(distDir); // 获取文件总数
    console.log(`📦 总共需要复制 ${totalFiles} 个文件`);

    await copyDir(distDir, targetDir, totalFiles); // 递归复制文件并显示进度
    console.log('✅ 打包文件拷贝完成');
}

run().catch((err) => console.error('⚠️ 发生错误:', err));
