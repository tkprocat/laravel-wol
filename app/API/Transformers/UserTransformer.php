<?php

namespace LaravelWOL\API\Transformers;

use LaravelWOL\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'id' 	    => (int) $user->id,
			'username'  => $user->username,
			'email'	    => $user->email
		];
	}
}