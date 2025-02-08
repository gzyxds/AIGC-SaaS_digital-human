/**
 * 前端加解密工具
 * 使用 AES-CBC 加密和 HMAC-SHA256 校验
 */
export const CryptoUtil = (() => {
    const ivLength = 16; // 初始化向量长度，固定为 16 字节
    const algorithm = 'AES-CBC';

    /**
     * 将字符串转换为 Uint8Array
     */
    const stringToUint8Array = (data: string): Uint8Array => {
        return new TextEncoder().encode(data);
    };

    /**
     * 将 Uint8Array 转换为字符串
     */
    const uint8ArrayToString = (data: Uint8Array): string => {
        return new TextDecoder().decode(data);
    };

    /**
     * 生成 AES 加密密钥
     */
    const getAesKey = async (secret: string): Promise<CryptoKey> => {
        return crypto.subtle.importKey(
            'raw',
            stringToUint8Array(secret),
            { name: algorithm },
            false,
            ['encrypt', 'decrypt']
        );
    };

    /**
     * 生成 HMAC 密钥
     */
    const getHmacKey = async (secret: string): Promise<CryptoKey> => {
        return crypto.subtle.importKey(
            'raw',
            stringToUint8Array(secret),
            { name: 'HMAC', hash: 'SHA-256' },
            false,
            ['sign', 'verify']
        );
    };

    /**
     * 加密数据
     * @param data 原始字符串
     * @param secret 密钥
     * @returns 加密后的 Base64 字符串
     */
    const encrypt = async (data: string, secret: string): Promise<string> => {
        const aesKey = await getAesKey(secret);
        const iv = crypto.getRandomValues(new Uint8Array(ivLength));
        const encrypted = await crypto.subtle.encrypt(
            { name: algorithm, iv },
            aesKey,
            stringToUint8Array(data)
        );

        // 计算 HMAC 校验
        const hmacKey = await getHmacKey(secret);
        const hmac = await crypto.subtle.sign(
            'HMAC',
            hmacKey,
            new Uint8Array([...iv, ...new Uint8Array(encrypted)])
        );

        // 拼接 IV + 加密数据 + HMAC
        const result = new Uint8Array([
            ...iv,
            ...new Uint8Array(encrypted),
            ...new Uint8Array(hmac),
        ]);
        return btoa(String.fromCharCode(...result));
    };

    /**
     * 解密数据
     * @param encryptedData 加密后的 Base64 字符串
     * @param secret 密钥
     * @returns 解密后的原始字符串
     */
    const decrypt = async (encryptedData: string, secret: string): Promise<string> => {
        const decodedData = new Uint8Array(
            atob(encryptedData)
                .split('')
                .map((char) => char.charCodeAt(0))
        );

        const iv = decodedData.slice(0, ivLength);
        const hmac = decodedData.slice(-32);
        const encrypted = decodedData.slice(ivLength, -32);

        // 校验 HMAC
        const hmacKey = await getHmacKey(secret);
        const valid = await crypto.subtle.verify(
            'HMAC',
            hmacKey,
            hmac,
            new Uint8Array([...iv, ...encrypted])
        );

        if (!valid) {
            throw new Error('数据校验失败，可能已被篡改');
        }

        // 解密
        const aesKey = await getAesKey(secret);
        const decrypted = await crypto.subtle.decrypt({ name: algorithm, iv }, aesKey, encrypted);

        return uint8ArrayToString(new Uint8Array(decrypted));
    };

    return { encrypt, decrypt };
})();

/**
 * 生成一个随机的 16 位 secret
 */
export function generateRandomSecret(): string {
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const secretLength = 16;
    let secret = '';
    for (let i = 0; i < secretLength; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        secret += charset[randomIndex];
    }
    return secret;
}
