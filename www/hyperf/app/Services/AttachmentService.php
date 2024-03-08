<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\ApiException;
use App\Model\Attachments;
use App\Services\AuthLogin\UserAuthenticate;
use Hyperf\DbConnection\Db;
use Hyperf\Support\Filesystem\Filesystem;

use function Hyperf\Support\env;

class AttachmentService extends BaseService
{

    /** @var array 存放当前已检查可写并且已创建的目录 */
    protected array $_cacheSavePath;

    /**
     * 图片上传
     * @param array $files
     * @param array $allowType
     * @return array
     * @throws \Throwable
     * @author LWW
     */
    public function upload(array $files, array $allowType = []): array
    {
        if (empty($allowType) || !is_array($allowType)) {
            $allowType = Attachments::allowType['image'];
        }
        $maxSize = Attachments::maxSize;
        $arrFiles = [];
        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                //判断文件是否超过大小
                if ($maxSize < $file->getSize()) {
                    throw new ApiException('单个文件超出大小');
                }
                // 计算文件的 MD5 值
                $tmpHash = md5_file($file->getRealPath());
                //保留重复md5文件的文件信息
                $arrFiles[$tmpHash]['file'][$key] = $file;
            }
        }
        //处理数据库已存在数据
        $arrFileHash = array_keys($arrFiles);
        $objAttachment = Attachments::listForCheckSameMd5($arrFileHash);
        //如果存在相同文件,则为该md5组增加一个下标url标记url
        if ($objAttachment->count() > 0) {
            foreach ($objAttachment as $attachment) {
                if (isset($arrFiles[$attachment->hash])) {
                    $arrFiles[$attachment->hash]['url'] = $attachment->url;
                    $arrFiles[$attachment->hash]['id'] = $attachment->id;
                }
            }
        }
        //判断此次上传文件是上传图片
        $isImage = (isset($allowType) && $allowType == Attachments::allowType['image']) ? 1 : 0;
        try {
            Db::beginTransaction();
            $result = [];
            foreach ($arrFiles as $fileMd5 => $item) {
                if (isset($item['url'])) {
                    foreach ($item['file'] as $key => $val) {
                        $result[$key] = ['id' => $item['id'], 'url' => $item['url']];
                        @unlink($val->getRealPath());
                    }
                } else {
                    $files = $item['file'];
                    $file = array_shift($item['file']);
                    $ext = $file->getExtension();
                    $saveFilename = self::generateFilename($ext);  //新的文件名称
                    $saveDir = self::generateSaveDir();       //保存目录
                    $size = $file->getSize();              //大小
                    $uploadType = env('FILE_UPLOAD_TYPE', 0);    //上传类型
                    $mime = $file->getMimeType();
                    list($currYear, $currMonth, $currDay, $currHour) = explode('/', $saveDir);
                    switch ($uploadType) {
                        case 0: //上传到本地
                            $path = $this->checkAndCreateSavePath(BASE_PATH . '/public' . '/uploads', $saveDir);
                            $file->moveTo($path.$saveFilename);
                            if (!$file->isMoved()) {
                                throw new ApiException('图片上传失败');
                            }
                            $url = env('APP_URL') . '/uploads/' . $saveDir . $saveFilename;
                            break;
                        case 1:
                            throw new ApiException('上传设置错误');
                        default:
                            throw new ApiException('上传设置错误');
                    }
                    $data = [
                        'type'       => $uploadType,
                        'user_id'    => UserAuthenticate::id(),
                        'name'       => $saveFilename,
                        'url'        => $url,
                        'ext'        => $ext,
                        'size'       => $size,
                        'is_image'   => $isImage,
                        'hash'       => $fileMd5,
                        'mime'       => $mime,
                        'year'       => $currYear,
                        'month'      => $currMonth,
                        'day'        => $currDay,
                        'hour'       => $currHour,
                        'deleted_at' => null,
                    ];
                    $objAttachment = Attachments::findForDeleteFile($fileMd5);
                    if ($objAttachment) {
                        Attachments::edit($objAttachment, $data);
                    } else {
                        // 创建一个上传文件记录
                        $objAttachment = Attachments::add($data);
                        if (!$objAttachment) {
                            throw new ApiException('添加上传文件记录失败');
                        }
                    }
                    foreach ($files as $key => $val) {
                        $result[$key] = [
                            'id'  => $objAttachment->id,
                            'url' => $url,
                        ];
                    }
                }
            }
            Db::commit();
            return $result;
        } catch (\Throwable $e) {
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * 生成文件名(时间+随机字符)
     * @param string $ext
     * @param string $prefix
     * @return string
     */
    private static function generateFilename(string $ext, string $prefix = ''): string
    {
        $tmp_name = date('His');
        $tmp_name .= sprintf('%06d', (float)microtime() * 1000);
        $tmp_name .= sprintf('%04d', mt_rand(0, 9999));
        $tmp_name .= self::createRandom(6, false);
        return (empty($prefix) ? '' : $prefix . '_') . $tmp_name . '.' . $ext;
    }

    /**
     * 生成随机字符串
     * @param int $lenth 长度
     * @param bool $strong
     * @return string 字符串
     */
    private static function createRandom(int $lenth = 6, bool $strong = false): string
    {
        $string = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        if (isset($strong) && $strong == true) {
            $string .= '~!@#$%^*(){}[],.;|';
        }
        return self::random($lenth, $string);
    }

    /**
     * 产生随机字符串
     * @param int $length 输出长度
     * @param string $chars 可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    private static function random(int $length, string $chars = '0123456789'): string
    {
        $hash = '';
        $max = mb_strlen($chars, 'utf-8') - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }

        return $hash;
    }

    /**
     * 获取保存路径
     * @return false|string
     */
    public static function generateSaveDir(): bool|string
    {
        return date('Y/m/d/H/');
    }

    /**
     * 创建一个目录，并检测能否写入
     * @param string $rootPath
     * @param string $saveDir
     * @return string
     * @author LWW
     */
    public function checkAndCreateSavePath(string $rootPath, string $saveDir): string
    {
        //完整的保存路径，不包括文件名
        $savePath = $rootPath . '/' . $saveDir;
        //路径已检查无需再检查
        if (isset($this->_cacheSavePath[$savePath])) {
            return $savePath;
        }
        $filesystem = new Filesystem();
        //检测目录存在或创建目录
        if (!$filesystem->isDirectory($savePath) && !$filesystem->makeDirectory($savePath, 0755, true, true)) {
            throw new ApiException('新建文件夹失败');
        }
        if (!$filesystem->isWritable($savePath)) {
            throw new ApiException('无法上传，目录不可写', 0);
        }
        //缓存已检查的目录
        $this->_cacheSavePath[$savePath] = 1;
        return $savePath;
    }
}