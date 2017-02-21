<?php

namespace App;

use Auth;
use Illuminate\Support\Collection;
use Cache;
use Illuminate\Database\Eloquent\Model;
use App\SeatExchange;
use Log;

/**
 * Class Delegation
 * @property int id
 * @property User head_delegate
 * @property string head_delegate_name
 * @property Collection committee_seat
 * @property int seat_number
 * @property int delegate_number
 * @property Collection delegates
 * @property string name
 * @property Collection delegates
 * @property Collection seats
 *
 * @package App
 */
class Delegation extends Model
{
    protected $fillable = [
        "head_delegate_id", "name", "delegate_number", "seat_number"
    ];

    public function head_delegate()
    {
        return $this->belongsTo("App\\User", "head_delegate_id", "id");
    }

    public function delegates()
        //每个代表团有多个代表
    {
        return $this->hasMany("App\\Delegate", "delegation_id", "id");
    }

    public function seats()
    {//每个代表团有多个席位
        return $this->hasMany("App\\Seat", "delegation_id", "id");
    }

    public function getHeadDelegationNameAttribute()
    {
        return $this->head_delegate->name;
    }

    public function getCommitteeSeatsAttribute()
    {
        //返回 会场缩写 => 席位 关联数组
        $committees = Committee::allInCache();
        $result = [];
        foreach ($committees as $committee) {
            $result[$committee->abbreviation] = $this->seats()->where("committee_id", $committee->id)->count();
        }
        return $result;
    }

    /**
     * if Cache has key delegation_seats_count,then update corresponding delegation's count of seats in different committees
     * else create one
     * return the committee_seats
     * @return Collection|mixed
     */
    public function rememberCommitteeSeats()
    {
        if (Cache::has("delegation_seats_count")) {
            $cache = Cache::get("delegation_seats_count");
            if (array_has($cache,$this->id)) {
                return $cache[$this->id];
            }
            else{
                $cache[$this->id] = $this->committee_seats;
                Cache::put("delegation_seats_count",$cache);
            }
        } else {
            $cache = new Collection();
            $cache[$this->id] = $this->committee_seats;
            Cache::put("delegation_seats_count", $cache);
        }
        return $cache[$this->id];
    }

    /**
     *Event Handler
     * Update Cache
     */
    protected static function boot()
    {
        parent::boot(); //
        static::deleting(function (Delegation $delegation) {
            foreach ($delegation->seats as $seat) {
                $seat->is_distributed = false;
                $seat->delegation_id = null;
                $seat->save();
            }//释放席位
            $exchange_requests = SeatExchange::where("initiator", $delegation->id)->orWhere("target", $delegation->id)->get()->where("status", "pending");
            foreach ($exchange_requests as $exchange_request) {
                $exchange_request->status = "fail";
                $exchange_request->save();
            }//修改所有与被删除代表团相关的seat_exchange为fail
            //删除delegations的缓存
            if (Cache::has("delegations")) {
                $cache = Cache::get("delegations");
                if (array_has($cache, $delegation->id)) {
                    array_forget($cache, $delegation->id);
                    Cache::put("delegations", $cache, 24 * 60);
                }
            }
            if (Cache::has("delegation_seats_count")) {
                $cache = Cache::get("delegation_seats_count");
                if (array_has($cache, $delegation->id)) {
                    array_forget($cache, $delegation->id);
                    Cache::put("delegations", $cache, 24 * 60);
                }
            }
        });
        static::created(function (Delegation $delegation) {
            //Update Cache
            if (Cache::has("delegations")) {
                Cache::put("delegations", Cache::get("delegations")->add($delegation), 24 * 60);
            } else {
                Cache::put("delegations", Delegation::all()->keyBy("id"), 24 * 60);
            }
        });
        static::updated(function (Delegation $delegation) {
            if (Cache::has("delegations")) {
                $cache = Cache::get("delegations");
                if (array_has($cache, $delegation->id)) {
                    $cache[$delegation->id] = $delegation;
                } else {
                    $cache = Delegation::all()->keyBy("id");
                }
                Cache::put("delegations", $cache, 24 * 60);
            } else {
                Cache::put("delegations", Delegation::all()->keyBy("id"), 24 * 60);
            }
        });
    }
}
