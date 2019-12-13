<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home Page</title>
        <link href="/css/main.css" rel="stylesheet">
        <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <div class="row tree-1 margin-top-lg" id="root" style="height:500px;">
            <div class="col-12 padding-xs">
                <div id="mainHeading" class="txt-white margin-left-auto margin-right-auto text-center">
                    <h1 class="underline"><span class="shadow">Family Tree</span></h1>
                </div>
            </div>
            <div class="col-12 margin-top-lg">
                <div class="row" style="background-color: #5e5e5e;">
                    <ul v-if="birthdays.length > 0" class="txt-white special-bullets mt-3">
                        <li v-for="person in birthdays"><span class="shadow">@{{person['name']}} turned @{{person['age']}} today!</span></li>
                    </ul>
                    <ul v-else class="txt-white special-bullets mt-3">
                        <li><span class="shadow">No Birthdays today!</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('partials/footer/familyTreeFooter')
    <script src="/js/homepage.js"></script>
    </body>
</html>
