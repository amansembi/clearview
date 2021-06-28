<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/userRegister', '/api/login', '/api/forgotpassword', '/api/serviceRequest', '/api/registerFinal', '/api/tonerorder', '/api/enterameter', '/api/contactus','/api/technicianregister', '/api/technicianlogin','/api/technicianforgotpassword', '/api/accept_request', '/api/customer_detail', '/api/work_complete', 	'/api/technicianlatlng',	'/api/userlatlng', '/api/userchat', '/api/chatList', '/api/technicianOnline', 	'/api/technicianOffline', '/api/technicianOnSite',	'/api/technicianEnRoute', '/api/news', '/api/allorders','/api/technicianById', '/api/completedRequests','/api/workStartTime','/api/totalTravelTime','/api/totalWorkTime','/api/distanceByLatLong','/api/reviews','/api/reachedTime',
		];
}
