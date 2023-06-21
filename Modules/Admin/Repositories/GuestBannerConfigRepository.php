<?php
/**
 * The repository class for managing AppVersion actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\GuestBannerConfig;
use Illuminate\Support\Facades\Redis;
use Exception;
use Route;
use Log;
use Cache;

class GuestBannerConfigRepository extends BaseRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  Modules\Admin\Models\AppVersion $AppVersion
     * @return void
     */
    public function __construct(GuestBannerConfig $guestbannerconfig)
    {
        $this->model = $guestbannerconfig;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listGuestBannerConfigData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(GuestBannerConfig::table())->remember($cacheKey, $this->ttlCache, function() {
            return GuestBannerConfig::orderBY('rank')->where('status',1)->where('is_screen',1)->lists('category','id');
        });
      
        return $response;
    }
}