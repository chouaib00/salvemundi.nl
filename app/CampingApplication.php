<?php

    namespace App;

    use App\Helpers\HasEncryptedAttributes;
    use Carbon\Carbon;
    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use InvalidArgumentException;

    /**
     * App\CampingApplication
     *
     * @property int              $id
     * @property string           $member_id
     * @property string           $first_name
     * @property string           $last_name
     * @property string           $phone
     * @property string           $email
     * @property string           $status
     * @property string           $ip_address
     * @property string|null      $email_confirmation_token
     * @property Carbon|null      $created_at
     * @property Carbon|null      $updated_at
     * @property string           $address
     * @property string           $city
     * @property string           $postal
     * @property Carbon           $birthday
     * @property string|null      $remarks
     * @property string           $transaction_id
     * @property string           $transaction_status
     * @property float            $transaction_amount
     * @method static Builder|CampingApplication whereAddress($value)
     * @method static Builder|CampingApplication whereBirthday($value)
     * @method static Builder|CampingApplication whereCity($value)
     * @method static Builder|CampingApplication whereCreatedAt($value)
     * @method static Builder|CampingApplication whereEmail($value)
     * @method static Builder|CampingApplication whereEmailConfirmationToken($value)
     * @method static Builder|CampingApplication whereFirstName($value)
     * @method static Builder|CampingApplication whereId($value)
     * @method static Builder|CampingApplication whereIpAddress($value)
     * @method static Builder|CampingApplication whereLastName($value)
     * @method static Builder|CampingApplication whereMemberId($value)
     * @method static Builder|CampingApplication wherePhone($value)
     * @method static Builder|CampingApplication wherePostal($value)
     * @method static Builder|CampingApplication whereRemarks($value)
     * @method static Builder|CampingApplication whereStatus($value)
     * @method static Builder|CampingApplication whereTransactionAmount($value)
     * @method static Builder|CampingApplication whereTransactionId($value)
     * @method static Builder|CampingApplication whereTransactionStatus($value)
     * @method static Builder|CampingApplication whereUpdatedAt($value)
     * @mixin Eloquent
     * @property-read Member      $member
     * @property-read Transaction $transaction
     * @property int|null         $camp_id
     * @method static Builder|CampingApplication whereCampId($value)
     * @property-read Camp|null   $camp
     * @method static Builder|CampingApplication newModelQuery()
     * @method static Builder|CampingApplication newQuery()
     * @method static Builder|CampingApplication query()
     */
    class CampingApplication extends Model {
        use HasEncryptedAttributes;
        const STATUS_APPROVED = 'approved', STATUS_REFUNDED = 'on_hold',
            STATUS_NEW = 'new', STATUS_DENIED = 'denied',
            STATUS_UNDER_REVIEW = 'under_review', STATUS_BLOCKED = 'blocked',
            STATUS_EMAIL_UNCONFIRMED = 'email_unconfirmed';
        public $fillable = ['first_name', 'last_name', 'phone', 'email', 'birthday', 'address', 'city', 'postal', 'remarks'];
        protected $encrypted = ['first_name', 'last_name', 'phone', 'email', 'ip_address', 'address', 'city', 'postal', 'remarks'];
        protected $attributes = [
            'status' => self::STATUS_NEW
        ];
        protected $casts = [
            'birthday' => 'date'
        ];

        protected $dates = [
            'birthday'
        ];

        /**
         * @return BelongsTo
         */
        public function member() {
            return $this->belongsTo(Member::class);
        }

        /**
         * @return BelongsTo
         */
        public function transaction() {
            return $this->belongsTo(Transaction::class);
        }

        /**
         * @return BelongsTo
         */
        public function camp() {
            return $this->belongsTo(Camp::class);
        }

        /**
         * @param $birthday
         *
         * @return Carbon
         */
        public function setBirthdayAttribute($birthday) {
            try {
                return $this->attributes['birthday'] = Carbon::createFromTimestamp(strtotime($birthday));
            } catch (InvalidArgumentException $exception) {
                return $this->attributes['birthday'] = null;
            }
        }

        /**
         * Save the model to the database.
         *
         * @param array $options
         *
         * @return bool
         */
        public function save(array $options = []) {
            return parent::save($options);
        }
    }
