<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>@yield('title') Conversion App</title>
</head>
<body>
  <main class="bg-gray-950 min-h-screen flex justify-center items-center">
    <div class="max-w-7xl w-10/12 mx-auto">
      @yield('content')
    </div>
  </main>
</body>
</html>