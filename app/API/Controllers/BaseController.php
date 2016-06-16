<?php

namespace LaravelWOL\API\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use Helpers, ValidatesRequests;
}