@extends('frontend')
@section('title','edulab || HOME PAGE')
@section('main-content')

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="shortcut icon" href="assets/img/logo aslab.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- icon -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.css">
    <!-- aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
      * {
        margin: 0;
        padding: 0;
        font-family: "Montserrat", sans-serif;
      }
    </style>
  </head>
  <body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js"></script>
    <script>
      new Splide('.splide').mount(window.splide.Extensions);
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const videoIframe = document.getElementById("video");
        const videos = ["gIXPFv_B1rI", // Video 1
          "7HxTFFqDmmw", // Video 2
          "9Gr3BbzUsL4", // Video 3
        ];
        let currentIndex = 0;

        function updateVideo(index) {
          const videoId = videos[index];
          videoIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&loop=1&playlist=${videoId}`;
        }
        document.getElementById("prev-btn").addEventListener("click", function() {
          currentIndex = (currentIndex - 1 + videos.length) % videos.length;
          updateVideo(currentIndex);
        });
        document.getElementById("next-btn").addEventListener("click", function() {
          currentIndex = (currentIndex + 1) % videos.length;
          updateVideo(currentIndex);
        });
      });
    </script>
  </body>
</html>
@endif
