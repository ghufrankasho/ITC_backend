<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Vistor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $vistor=Vistor::where('ipaddress',$request->ip())->first();
        if(!$vistor){
            $vistor_new=new vistor();
            $vistor_new->ipaddress=$request->ip();
            $vistor_new->visitor_count =1;
            $vistor_new->save();
        }
        else{
          $vistor->visitor_count +=1; 
          $vistor->save();
        }
        return $next($request);
    }
}