<?php
declare(strict_types=1);
namespace App\Model;
use Hyperf\Database\Model\SoftDeletes;

class Attachments extends Model
{
    use SoftDeletes;
    protected ?string $table='attachments';

    //允许最大文件大小，以b做单位(1kb=1024b)
    const maxSize = 20 * 1024 * 1024;     //20MB
    //文件上传类型
    const allowType = [
        'image' => [
            'extension' => 'jpg,gif,png,bmp,jpeg',
            'mime_type' => 'image/jpeg,image/pjpeg,image/gif,image/png,image/x-png,image/bmp',
        ],
    ];

    /**
     * 添加数据
     * @param array $param
     * @return Attachment|Model
     * @author LWW
     */
    public static function add(array $param): Model|Attachment
    {
        return self::create($param);
    }

    /**
     * 查找存在的文件
     * @param array $fileHash
     * @return \Hyperf\Collection\Collection
     * @author LWW
     */
    public static function listForCheckSameMd5(array $fileHash): \Hyperf\Collection\Collection
    {
        return self::whereIn('hash',$fileHash)->get();
    }

    /**
     * 根据hash查找已删除数据
     * @param string $fileHash
     * @return Attachment|null
     * @author LWW
     */
    public static function findForDeleteFile(string $fileHash): Attachment|null
    {
        return self::onlyTrashed()->where('hash', $fileHash)->first();
    }

    /**
     * 更新资料
     * @param Attachment $obj
     * @param array $param
     * @return bool
     * @author LWW
     */
    public static function edit(Attachment $obj,array $param): bool
    {
        foreach ($param as $key => $val){
            $obj->$key = $val;
        }
        return $obj->save();
    }

}