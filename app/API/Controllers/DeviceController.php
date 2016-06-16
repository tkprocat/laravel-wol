<?php

namespace LaravelWOL\API\Controllers;

use JJG\Ping;
use Illuminate\Http\Request;
use LaravelWOL\Device;
use LaravelWOL\API\Transformers\DeviceTransformer;

class DeviceController extends BaseController
{
    /**
     * DeviceController constructor.
     */
    public function __construct()
    {
       $this->middleware('jwt.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->collection(Device::all(), new DeviceTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|max:255', 'ip' => 'required|ip', 'mac' => 'required|macAddress']);
        $device = new Device;
        $device->name = $request->name;
        $device->ip = $request->ip;
        $device->mac = $request->mac;
        $device->save();

        return $device;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->item(Device::findOrFail($id), new DeviceTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required|max:255', 'ip' => 'required|ip', 'mac' => 'required|macAddress']);

        $device = Device::findOrFail($id);
        $device->name = $request->name;
        $device->ip = $request->ip;
        $device->mac = $request->mac;
        $device->save();
        return $device;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Device::destroy($id);
    }

    /**
     * @param Request $request
     */
    public function boot(Request $request)
    {
        $f = new \Phpwol\Factory();
        $magicPacket = $f->magicPacket();

        $macAddress = $request->mac;
        $ip_segments = explode(".", $request->ip);

        $broadcastIP = $ip_segments[0].'.'.$ip_segments[1].'.'.$ip_segments[1].'.255';
        $magicPacket->send($macAddress, $broadcastIP);
    }

    /**
     *
     *
     * @param Request $request
     * @return string
     */
    public function ping(Request $request)
    {
        $ping = new Ping($request->ip);
        if ($ping->ping() !== false)
            return ['status' => 'up', 'message' => 'Device is running.'];
        else
            return ['status' => 'down', 'message' => 'No reply from device.'];
    }
}
