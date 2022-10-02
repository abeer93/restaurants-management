<!DOCTYPE html>
<html>
    <head>
        <title>Ingredients Low Stocks</title>
    </head>
    <body>
        <h1>Some Ingredients have low stock that be updated</h1>
        <table>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Original Weight</th>
                    <th>Current Weight</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingredients as $ingredient)
                <tr>
                    <td>{{$ingredient['name']}}</td>
                    <td>{{$ingredient['original_weight']/1000}} KG</td>
                    <td>{{$ingredient['available_weight']/1000}} KG</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>