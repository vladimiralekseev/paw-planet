<?php

/**
 * @var yii\web\View $this
 * @var array        $petsCount
 * @var array        $lostPetsCount
 * @var array        $siteUserCount
 * @var array        $petsRequests
 * @var array        $responsesCount
 * @var array        $breedsCount
 * @var array        $colorsCount
 * @var array        $reviewsCount
 */

use common\models\LostPet;
use common\models\Pet;
use common\models\ResponseLostPet;
use common\models\SiteUser;
use common\models\UserRequestPet;

$this->title = 'Paw planet';
?>
<div class="site-index">

    <div class="row">
        <?php if (isset($siteUserCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Site Users</span>
                        <div class="info-box-number">
                            <small>Active: <?= $siteUserCount[SiteUser::STATUS_ACTIVE]['count'] ?? 0 ?></small><br />
                            <small>Inactive: <?= $siteUserCount[SiteUser::STATUS_INACTIVE]['count'] ?? 0 ?></small><br />
                            <small>Deleted: <?= $siteUserCount[SiteUser::STATUS_DELETED]['count'] ?? 0 ?></small><br />
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($breedsCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-linux"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Breeds</span>
                        <span class="info-box-number">
                        <?= $breedsCount['count'] ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($colorsCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-adjust"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Colors</span>
                        <span class="info-box-number">
                        <?= $colorsCount['count'] ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($reviewsCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-commenting"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Reviews</span>
                        <span class="info-box-number">
                        <?= $reviewsCount['count'] ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <?php if (isset($petsCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-linux"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pets</span>
                        <div class="info-box-number">
                            <small>Active: <?= $petsCount[Pet::STATUS_ACTIVE]['count'] ?? 0 ?></small><br />
                            <small>Inactive: <?= $petsCount[Pet::STATUS_INACTIVE]['count'] ?? 0 ?></small><br />
                            <small>Blocked: <?= $petsCount[Pet::STATUS_BLOCKED]['count'] ?? 0 ?></small><br />
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($petsRequests)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-linux"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Requests</span>
                        <div class="info-box-number">
                            <small>New: <?= $petsRequests[UserRequestPet::STATUS_NEW]['count'] ?? 0 ?></small><br />
                            <small>Approved: <?= $petsRequests[UserRequestPet::STATUS_APPROVED]['count'] ?? 0 ?></small><br />
                            <small>Rejected: <?= $petsRequests[UserRequestPet::STATUS_REJECTED]['count'] ?? 0 ?></small><br />
                            <small>Canceled: <?= $petsRequests[UserRequestPet::STATUS_CANCELED]['count'] ?? 0 ?></small><br />
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
        <div class="row">
        <?php if (isset($lostPetsCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-linux"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lost Pets</span>
                        <div class="info-box-number">
                            <small>Active: <?= $lostPetsCount[LostPet::STATUS_ACTIVE]['count'] ?? 0 ?></small><br />
                            <small>Finished: <?= $lostPetsCount[LostPet::STATUS_FINISHED]['count'] ?? 0 ?></small><br />
                            <small>Blocked: <?= $lostPetsCount[LostPet::STATUS_BLOCKED]['count'] ?? 0 ?></small><br />
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($responsesCount)) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-linux"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Responses</span>
                        <div class="info-box-number">
                            <small>New: <?= $responsesCount[ResponseLostPet::STATUS_NEW]['count'] ?? 0 ?></small><br />
                            <small>Approved: <?= $responsesCount[ResponseLostPet::STATUS_APPROVED]['count'] ?? 0 ?></small><br />
                            <small>Rejected: <?= $responsesCount[ResponseLostPet::STATUS_REJECTED]['count'] ?? 0 ?></small><br />
                            <small>Canceled: <?= $responsesCount[ResponseLostPet::STATUS_CANCELED]['count'] ?? 0 ?></small><br />
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
