<?php

namespace App\Context\Representation\Controller;

use App\Context\Domain\Entity\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request): String
    {
        $leapYear = new LeapYear();
        if ($leapYear->isLeapYear($request->attributes->get('year'))) {
            $response = new Response("yes, its a leap year");
        }else{
            $response = new Response("No, its not a leap year");
        }

        $response->setEtag('abc123');
        
        if ($response->isNotModified($request)) {
            return $response; #now status code 302 and body is removed
        }
           
        // $response->setTtl(10);
        return $response->getContent();
    }
}
