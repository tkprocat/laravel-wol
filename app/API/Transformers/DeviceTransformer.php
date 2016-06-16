<?php

namespace LaravelWOL\API\Transformers;

use LaravelWOL\Device;
use League\Fractal\TransformerAbstract;

class DeviceTransformer extends TransformerAbstract
{
	public function transform(Device $device)
	{
		return [
			'id' 	    => (int) $device->id,
			'name'  => $device->name,
			'ip'	    => $device->ip,
			'mac'		=> $device->mac
		];
	}
}