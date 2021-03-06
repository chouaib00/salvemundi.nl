<?php

    namespace App\Store;

    use Carbon\Carbon;
    use Eloquent;
    use Exception;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Image;
    use Intervention\Image\Constraint;
    use Storage;

    /**
 * App\Store\StockImage
 *
 * @property int         $id
 * @property string|null $image_name
 * @property int         $store_stock_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|StockImage whereCreatedAt($value)
 * @method static Builder|StockImage whereId($value)
 * @method static Builder|StockImage whereImageName($value)
 * @method static Builder|StockImage whereStoreStockId($value)
 * @method static Builder|StockImage whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|StockImage newModelQuery()
 * @method static Builder|StockImage newQuery()
 * @method static Builder|StockImage query()
 * @property-read Stock  $stock
 */
    class StockImage extends Model {
        protected $table = 'store_stock_images';
        protected $fillable = ['image_name'];

        /**
         * @return BelongsTo
         */
        public function stock() {
            return $this->belongsTo(Stock::class);
        }

        /** @noinspection PhpMethodMayBeStaticInspection */
        /**
         * @param      $width
         * @param null $height
         * @param bool $returnObj
         *
         * @return Image|\Intervention\Image\Image
         */
        public function getResizedCachedImage($width = null, $height = null, $returnObj = false) {
            ini_set('memory_limit', '256M');
            return Image::cache(function ($image) use ($height, $width) {
                /** @var \Intervention\Image\Image $image */
                $image->make($this->getImagePath());
                $image->orientate();
                $image->resize($width, $height, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }, null, $returnObj);
        }

        /**
         * @return string
         */
        public function getImagePath() {
            return storage_path('app/store_photos/' . $this->image_name);
        }

        /**
         * @param bool $returnObj
         *
         * @return Image|\Intervention\Image\Image
         */
        public function getCachedImage($returnObj = false) {
            ini_set('memory_limit', '256M');
            return Image::cache(function ($image) {
                /** @var \Intervention\Image\Image $image */
                $image->make($this->getImagePath());
                $image->orientate();
            }, null, $returnObj);
        }

        /**
         * Delete the model from the database.
         *
         * @return bool|null
         *
         * @throws Exception
         */
        public function delete() {
            $this->deletePicture();
            return parent::delete();
        }

        /**
         * @throws Exception
         */
        public function deletePicture() {
            if (Storage::exists('store_photos/' . $this->image_name) && !Storage::delete('store_photos/' . $this->image_name)) {
                throw new Exception("Kon pasfoto niet verwijderen bij het verwijderen van een Member");
            }

        }
    }
