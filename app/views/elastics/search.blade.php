@extends('layouts.elastics')

@section('main')
  <h1>Searching...</h1>
  <table class="table table-striped table-bordered">
    <th>Index</th>
    <th>Results</th>
    <tr><td>
        <div id="ElasticIndex"></div>
      </td>
      <td>
        <div id="ElasticResult"></div>
      </td>
    </tr>
  </table>


<h1>Searching docs in the indices: {{ link_to_route('elastics.index', $ESClient->hosts,array(),array('title'=>'Back to the Host',
'alt'=>'Back to the host')) }}</h1>
{{ Form::open(array('route' => 'elastics.store')) }}
<ul>
  <li>
    {{ Form::label('host', 'Change Host to: ') }}
    {{ Form::text('host') }} {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
  </li>
</ul>
{{ Form::close() }}
@if ($errors->any())
  <ul>
    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
  </ul>
@endif

@if ($result === 'false')
  <h3 class="text-warning">Empty Result or Result Contains Error!</h3>
@endif

<p>{{-- link_to_route('users.create', 'Add new user') --}}
@foreach ( $ESClient->nodeFunctions() as $index=>$item )
  {{ link_to_route('node.show', 'Node '.$index, array('command'=>strtolower($index)),
  array('class' => (strtolower($index)==$curComm?'btn btn-success':'btn btn-default'),
  'title'=>$item,
'alt'=>$item)) }}
@endforeach
  {{ link_to_route('nodes.threads', 'Node Hot Threads', array(),
  array('class' => ($curComm=='hotThreads'?'btn btn-success':'btn btn-default'),
  'title'=>'Showing hot threads',
'alt'=>'Showing hot threads')) }}
</p>

<table class="table table-striped table-bordered">
<th>TreeView</th>
<th>Json</th>
<tr><td>
<div id="ElasticInfoTree"></div>
</td><td>{{ $result }}</td></tr>
</table>

<script>
YUI().use(
  'aui-tree-view',
  function(Y) {
    /*var children = [{{ $treeData }}];*/
    var indices = [{{ $indices }}];

    /*new Y.TreeView(
      {
        boundingBox: '#ElasticInfoTree',
        children: children
      }
    ).render();*/

    new Y.TreeView(
            {
              boundingBox: '#ElasticIndex',
              children: indices
            }
    ).render();
  }
);
</script>

@stop