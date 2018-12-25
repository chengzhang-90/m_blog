<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Location as LocationModel;
use app\admin\common\Func as FuncModel;
class Location extends Base
{
    public function location_data($travel_location_pid=""){
            $LocationModel = new LocationModel();
            $dataJson = $LocationModel->get_location_data($travel_location_pid);
            return $dataJson;
    }
}