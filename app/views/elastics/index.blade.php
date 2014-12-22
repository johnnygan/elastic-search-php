@extends('layouts.elastics')

@section('main')
<h1>Elastic Search PHP Client Demo</h1>
@if ($result !== 'false')
<h3>Elastic Service is found on the host:{{ $ESClient->hosts }}!</h3>
@else
  <h3 class="text-warning">There are no Elastic Search Services Found on {{ $ESClient->hosts }}!</h3>
@endif
{{--<p>Type new host name below to switch to another Elastic Search Server.</p>--}}
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
    var children = [{{ $treeData }}];

    new Y.TreeView(
      {
        boundingBox: '#ElasticInfoTree',
        children: children
      }
    ).render();
  }
);
</script>

@stop