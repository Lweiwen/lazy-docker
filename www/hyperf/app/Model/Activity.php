<?php
namespace App\Model;
class Activity extends Model
{
    protected ?string $table = 'activity';

    /**
     * 更具活动ID查询数据
     * @param int $activityId
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     * @author LWW
     */
    public static function findWithId(int $activityId)
    {
        return self::where('id','=',$activityId)
            ->first();
    }
}