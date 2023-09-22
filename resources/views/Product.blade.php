
<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>
<div class="table-responsive pt-3">

    <table class="table table-bordered" style="border: solid">
        <thead>
            <tr>
                <th>id</th>
                <th>title</th>
                <th>description</th>
                <th>status</th>
                <th>image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datass as $showdatas)
            <tr>
                <td>{{$showdatas['id']}}</td>
                <td>{{$showdatas['title']}}</td>
                 <td>{{$showdatas['description']}}</td>
                 <td>{{$showdatas['status']}}</td>
                <td>
                    <img src="{{'storage/product/images/'.$showdatas['image']}}" width="40px" >
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
