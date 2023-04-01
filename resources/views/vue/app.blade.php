<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/favicon.ico" />
    <title>Laratoon</title>
    @vite(['resources/js/vue/app/main.ts'])
  </head>
    <body>
        <script>
          window.userDataJson = {{ Illuminate\Support\Js::from($userDataJson) }};
        </script>
        <div id="app"></div>
    </body>
</html>
