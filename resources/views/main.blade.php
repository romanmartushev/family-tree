<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Your Family Tree</title>
    <link href="/css/main.css" rel="stylesheet">
</head>
<body>
<div class="container" id="tree">
    <div class="row tree-1 mt-5">
        <div class="col-12 padding-xs">
            <div id="mainHeading" class="txt-white margin-left-auto margin-right-auto text-center">
                <h1 class="underline"><span class="shadow">Family Tree</span></h1>
            </div>
        </div>
        <div class="col-12">
            <ul class="nav nav-pills">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Select A Family<span class="caret"></span></a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-toggle="tab" :href="'#f'+index" v-if="parents.length > 0" v-for="parent, index in parents">@{{ parent[0].name }} @{{ parent.length == 2 ? '& ' + parent[1].name : '' }}</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-12 tab-content">
            <div v-for="family, index in tree" :id="'f'+index" class="tab-pane" role="tabpanel" :aria-labelledby="'f'+index+'-tab'" :class="{'active': index == 0}">
                <div class="row row-eq-height justify-content-center text-center txt-white">
                    <div class="col-12 txt-white margin-left-auto margin-right-auto text-center">
                        <h2 v-if="family.hasOwnProperty('parents')">Parents:</h2>
                        <h2 v-if="family.hasOwnProperty('couple')">Couple:</h2>
                    </div>
                    <div v-if="family.hasOwnProperty('parents')" v-for="parent in family.parents" class="col-6 leaf">
                        <ul class="no-bullets">
                            <li>Name: @{{ parent.name }}</li>
                            <li>Birthday: @{{ parent.birthday }}</li>
                        </ul>
                    </div>
                    <div v-if="family.hasOwnProperty('couple')" v-for="person in family.couple" class="col-6 leaf">
                        <ul class="no-bullets">
                            <li>Name: @{{ person.name }}</li>
                            <li>Birthday: @{{ person.birthday }}</li>
                        </ul>
                    </div>
                </div>
                <div v-if="family.hasOwnProperty('children')" class="row row-eq-height justify-content-center text-center txt-white">
                    <div class="col-12 txt-white margin-left-auto margin-right-auto text-center">
                        <h3>Children:</h3>
                    </div>
                    <div v-for="child in family.children" class="col-4 leaf">
                        <ul class="no-bullets">
                            <li>Name: @{{ child.name }}</li>
                            <li>Birthday: @{{ child.birthday }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials/footer/familyTreeFooter')
<script src="/js/tree.js"></script>
</body>
</html>
