<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Model\Activity;
use App\Model\ActivitySponsor;
use App\Services\AuthLogin\UserAuthenticate;
use App\Services\BaseService;

class ActivityBase extends BaseService
{
    /** @var int 活动ID */
    protected int $activityId = 0;

    /** @var string 活动code */
    protected string $activityCode = '';

    /** @var object|null 活动保存 */
    protected object|null $activity = null;

    /** @var int 主办方成员角色 */
    protected int $activityRoleNum = 0;

    /** @var int 主办方成员状态 */
    protected int $activitySponsorStatus = 0;

    /**
     * 获取活动数据
     * @param string $activityCode
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public function init(string $activityCode): bool
    {
        $actId = code_to_id_lj($activityCode);
        if (empty($actId)) {
            return false;
        }
        $objAct = Activity::findWithId($actId);
        if (!$objAct) {
            return false;
        }
        $this->activityCode = $activityCode;
        $this->activityId = $actId;
        $this->activity = $objAct;
        $objAct = null;
        return true;
    }

    /**
     * 主办方成员角色
     * @param array $checkActivityRole
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public function checkActivitySponsor(array $checkActivityRole = []): bool
    {
        $userId = UserAuthenticate::id();
        if (empty($userId)) {
            return false;
        }
        $objActSponsor = ActivitySponsors::findWithUserId($userId);
        if (!$objActSponsor) {
            return false;
        }
        //设置活动主办方数据
        $this->activitySponsorStatus = $objActSponsor->status;
        $this->activityRoleNum = $objActSponsor->role;
        //是否在权限组里面
        if (!in_array($objActSponsor->role, $checkActivityRole)) {
            return false;
        }
        return $this->checkSponsorStatus();
    }

    /**
     * 判断状态
     * @return bool
     * @author LWW
     */
    public function checkSponsorStatus(): bool
    {
        return $this->activitySponsorStatus == 1;
    }

    /**
     * 获取主办方成员权限
     * @return int
     * @author LWW
     */
    public function sponsorRole(): int
    {
        return $this->activityRoleNum;
    }
}