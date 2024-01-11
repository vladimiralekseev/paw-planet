<?php

namespace api\models\forms;

use common\models\SiteUser;
use Yii;
use yii\base\Model;

class UserProfileForm extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $about;
    public $my_location;
    public $latitude;
    public $longitude;
    public $whats_app;
    public $facebook;
    public $country;
    public $state;
    public $city;
    public $address;

    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['first_name', 'email'], 'required'],
            [['about', 'my_location'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['email'], 'string', 'max' => 255],
            [['first_name', 'last_name', 'address'], 'string', 'max' => 128],
            [['phone_number', 'country', 'state', 'city'], 'string', 'max' => 64],
            [['whats_app', 'facebook'], 'string', 'max' => 256],
            [
                ['email'],
                'unique',
                'targetClass' => SiteUser::class,
                'message'     => 'This email has already been taken.',
                'on'          => 'update',
                'when'        => function ($model) {
                    return $model->isAttributeChanged('email');
                }
            ],
            ['email', 'email'],
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->username = $this->email;
        $user->email = $this->email;
        $user->about = $this->about;
        $user->my_location = $this->my_location;
        $user->phone_number = $this->phone_number;
        $user->whats_app = $this->whats_app;
        $user->facebook = $this->facebook;
        $user->latitude = $this->latitude;
        $user->longitude = $this->longitude;
        $user->country = $this->country;
        $user->state = $this->state;
        $user->city = $this->city;
        $user->address = $this->address;

        if ($user->validate() && $r = $user->save()) {
            return true;
        }
        $this->addErrors($user->getErrors());
        return false;
    }
}
