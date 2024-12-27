<!DOCTYPE html>
<html>

<head>
    @include('home.css')

</head>

<body>
    <!-- Konten Utama -->
    <div class="hero_area">
        @include('home.header')
        @include('home.slider')
        @include('home.product')
    </div>

    <!-- Footer -->
    @include('home.footer')
</body>

</html>
