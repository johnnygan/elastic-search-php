<?php
/**
 * Created by PhpStorm.
 * User: jgan
 * Date: 12/10/14
 * Time: 3:58 PM
 */

class LaravelElasticSearch extends Elasticsearch\Client
{
    public  $hosts = '127.0.0.1:9200'; //default hosts array

    public  $port = '9200'; //default port

    public  $ignore = [400,404]; //if not found the host, ignoring exceptions

    public static $rules = array(
        'host' => 'required'
    );
    /**
     * Client constructor
     *
     * @param array $params Array of injectable parameters
     */
    public function __construct($params = array())
    {
        //set default
        if(!isset($params['hosts']) || $params['hosts'] == [':9200'])
        {
            $params['hosts'] = [$this->hosts];
        }
        else
        {
            //elastics hosts can store multiple nodes address
            $this->hosts=$params['hosts'][0];
        }

        parent::__construct($params);
    }

    /**
     * Client ping function
     *
     * see if the host is available, if not available, then return false.
     * This function will let system go which will not cause the system halt
     * due to disconnect, or not found error
     */
    public function ping()
    {

        /** @var callback $endpointBuilder */
        $endpointBuilder = $this->dicEndpoints;

        /** @var \Elasticsearch\Endpoints\Ping $endpoint */
        $endpoint = $endpointBuilder('Ping');

        try {
            $response = $endpoint->performRequest();
        } catch (Missing404Exception $exception) {
            return false;
        } catch (RoutingMissingException $exception) {
            return false;
        } catch (Exception $exception) {
            return false;
        }

        if (isset($response['status']) === true && $response['status'] === 200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cluster read function group
     *
     * This function will return all available elastic cluster read function,
     * and their description.
     * Read function means only read cluster information, not change or update.
     */
    public function clusterFunctions()
    {
        return [
            'Health'=>'A very simple status on the health of the cluster',
            'State'=>'A comprehensive state information of the whole cluster.',
            'Stats'=>'Retrieve statistics from a cluster wide perspective',
            'PendingTasks'=>'Returns a list of any cluster-level changes (e.g. create index, update mapping, allocate or fail shard) which have not yet been executed.'
        ];
    }

    /**
     * Node read function group
     *
     * This function will return all available elastic node read function,
     * and their description.
     * Read function means only read cluster information, not change or update.
     */
    public function nodeFunctions()
    {
        return [
            'Info'=>'retrieve one or more (or all) of the cluster nodes information',
            'Stats'=>'retrieve one or more (or all) of the cluster nodes statistics',
            //'HotThreads'=>'get the current hot threads on each node in the cluster'
        ];
    }

    /**
     * Indices read function group
     *
     * This function will return all available elastic index read functions,
     * and their description.
     * Read function means only read cluster information, not change or update.
     */
    public function indicesFunctions()
    {
        return [
            'stats'=>'provides statistics on the index level scope',
            'status'=>'get a comprehensive status information of one or more indices',
            'getMapping'=>'retrieve mapping definitions for an index or index/type',
            //'HotThreads'=>'get the current hot threads on each node in the cluster'
        ];
    }

    /**
     * Elastic Search host Status information
     *
     */
    public function status()
    {
        //extract IP and Host info
        return array_merge(['ip'=>trim($this->cat()->master(['h'=>['ip']])),
            'host'=>trim($this->cat()->master(['h'=>['host']]))],
            $this->info());
    }

    /**
     * Elastic Search return all available index in array
     *
     * Return all index name with their docs count
     * [
     * index_name=>doc_count,
     * ]
     */
    public function findAllIndex()
    {
        $return=[];
        $indicesStats=$this->indices()->stats();

        foreach($indicesStats['indices'] as $indexName=>$subIndex)
        {
            $return[$indexName]=$subIndex['total']['docs']['count'];
        }

        return $indicesStats;
    }

    public function config($params = array())
    {
        //set default
        if(!isset($params['hosts']))
        {
            $params['hosts'] = [$this->hosts];
        }
        else
        {
            $this->hosts=$params['hosts'][0];
        }
        parent::__construct($params);
    }


    /**
     * Serialize assoc array into Javascript tree data string
     *
     * This is just very simple tree data without any other actions or types
     *
     * @param string|array $data Assoc array to encode into Javascript Tree
     *        string $root is the tree or branch label name
     *
     * @return tree data string, which like below.
     * var children = [
    {
    children: [
    {label: 'File X'},{label: 'File Y'},{label: 'File Z'}
    ],
    expanded: true,
    label: 'Root'
    }
    ];
     *
     */
    public function arrayToJavaTree($data,$root,$expand=0)
    {
        $expand++;
        if (!is_array($data)) {
            if($expand==1)
            {
                $data = str_replace("'","",$data); //".addslashes($data)."
                //if data is not array in the first level, we need create one leaf tree
                $return="{children:[{label:'".$root." : ".addslashes($data)."'}],expanded:true,label:'". $root ."'},";
            }
            else
            {
                $return="{label:'".$root." : ".addslashes($data)."'},";
            }
        }
        else
        {
            if (empty($data))
            {
                $return="{label:'".$root." : []'},";
            }
            else
            {
                $return = "{children:[";
                foreach ($data as $index => $value) {
                    $return .= $this->arrayToJavaTree($value, $index, $expand);
                }

                //get rid of the last ","
                $return = substr($return, 0, -1);
                if ($expand == 1) //we expand 1st level in the tree view by default
                {
                    $return .= "],expanded:true,label:'". $root ."'},";
                } else
                {
                    $return .= "],expanded:false,label:'". $root ."'},";
                }
            }
        }
        return $return;
    }

    /**
     * Convert JSON string into readable html output, which can directly display in the html page.
     *
     * @param JSON data string
     *
     * @return html string
     *
     */
    public function JsonToHtml($data)
    {
        $return = $data;
        $return = str_replace('{"','{<ul><li class="list-unstyled">"',$return);
        $return = str_replace(',"',',</li><li class="list-unstyled">"',$return);
        $return = str_replace('}','</li></ul>}',$return);

        return $return;
    }

    /**
     * Chop a string at given length, default length = 1000.
     *
     * @param string
     *        int length chop length at
     *
     * @return html string
     *
     */
    public function chopString($string, $length=1000)
    {
        $return = substr($string, 0, $length);

        return $return;
    }
}