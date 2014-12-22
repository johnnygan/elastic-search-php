<?php
/**
 * Created by PhpStorm.
 * User: jgan
 * Date: 12/10/14
 * Time: 5:54 PM
 */

class ESController extends BaseController
{
    public function index()
    {
        //check if we saved elastic setting in the session
        $params=array();
        if(Session::has('esparams'))
        {
            //Session::flush();
            $params=Session::get('esparams');
        }

        $ESClient = new LaravelElasticSearch($params);


        //make sure we found the elastic search host
        if($found = $ESClient->ping())
        {
            $result = $ESClient->status();
        }
        else
        {
            $result = false;
        }
        //return result is an array

        //result is array, we need convert into tree view data
        $treeData = $ESClient->arrayToJavaTree($result,'Info');
        $treeData=substr($treeData, 0, -1); //get rid of the extra comma at the end
        $ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer;
        $result = $ArrayToJson->serialize($result); //convert into Jason
        $result = $ESClient->JsonToHtml($result);
        return View::make('elastics.index', compact('treeData','result','ESClient'));
    }

    /* status was created for demo purpose
    * to use that you need add route into routes.php file
    */
    public function status()
    {
        //
        $ESClient = new LaravelElasticSearch();
        $ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer();
        $result = print_r($ESClient->info(),true).'<br>'.$ArrayToJson->serialize($ESClient->info());

        return View::make('elastics.status', compact('result'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
        $input = Input::all();
        $validation = Validator::make($input, LaravelElasticSearch::$rules);

        if ($validation->passes())
        {
            Session::put('esparams', ['hosts'=>[$input['host'].':9200']]);
            return Redirect::route('elastics.index');
        }
        else
        {
            return Redirect::route('elastics.index')
                ->withInput()
                ->withErrors($validation);
        }
    }

}