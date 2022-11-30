<!DOCTYPE html>
<html lang="en">
<head>
  <title>BookMyShow</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Movies in Theaters </h2>
  <!-- <p>The .table-bordered class adds borders on all sides of the table and the cells:</p>             -->
  </br> 
  </br>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>theater_id</th>
        <th>theater_name</th>
        <th>Movie_name</th>
         <th>Movie_id</th>
        <th>Timing</th>  
        <th>Seat_book_app</th>  
      </tr>
    </thead>
    <tbody>
        @foreach($theaters as $theater)
      <tr>
        <td>{{$theater->id}}</td>
        <td>{{$theater->theater_name}}</td>
        <td>{{$theater->movie_name}}</td>
        <td>{{$theater->movie_id}}</td>
        <td>{{$theater->time}}</td>
        <td> <button type="submit" class="btn btn-outline-success btn-lg"><a href="{{ route('seat_booking') }}" class="link-product-add-cart" >seat_booking </a></button></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

</body>
</html>