<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * UserSearch represents the model behind the search form of `src\user\entities\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'safe'],
        ];
    }
}
