<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Config;

class ConfigController extends Controller
{

    public function get(Request $request )
    {
        switch( $request->resource )
        {
            case 'meta': return $this->meta($request);
        }
    }

    public function post(Request $request )
    {
        switch( $request->resource )
        {
            case 'clear-cache': return $this->ClearCache($request);
            case 'save-var': return $this->saveVar($request);
        }
    }

    // POST
    public function clearCache( $request )
    {
        $request->validate([ 'caches' => 'required|array|min:1' ]);

        foreach($request->caches as $cache) 
        {
            if( $cache['check'] ){
                Config::clearCache( _const($cache['key']) );
            }
        }

        return response()->json(true, 200);
    }

    // POST
    public function saveVar( $request )
    {
        $request->validate([ 'var' => 'required' ]);

        $save = Config::saveVar( $request->var['key'], $request->var['value']);

        return response()->json($save, 200);
    }


    public function meta()
    {
        $data['cache'] = Config::listCache();
        $data['vars'] = Config::listVars();
        return $data;
    }
}
