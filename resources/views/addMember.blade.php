<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add A Family Member</title>
    <link href="/css/main.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row tree-1 padding-xs margin-top-lg">
            <div id="mainHeading" class="col-12 txt-white margin-left-auto margin-right-auto text-center">
                <h1 class="underline"><span class="shadow">Add A Family Member</span></h1>
            </div>
            <div id="root" class="col-6">
                <form class="needs-validation" @submit.prevent="addMember">
                    <div class="form-group">
                        <label class="txt-white" for="InputName">Name:</label>
                        <input type="text" class="form-control" name="name" id="InputName" placeholder="Name" required v-model="name">
                    </div>
                    <div class="form-group">
                        <label class="txt-white" for="InputBirthday">Birthday:</label>
                        <input type="date" class="form-control" name="birthday" id="InputBirthday" placeholder="Birthday" required v-model="birthday">
                    </div>
                    <div class="form-group">
                        <label class="txt-white" for="InputPhoneNumber">Phone Number:</label>
                        <input type="text" class="form-control" name="phoneNumber" id="InputPhoneNumber" placeholder="Phone Number" required v-model="phoneNumber">
                    </div>
                    <div class="form-group">
                        <label class="txt-white" for="InputEmail">Email address:</label>
                        <input type="email" class="form-control" name="email" id="InputEmail" placeholder="Email" required v-model="email">
                    </div>
                    <button class="btn btn-light" type="submit">Add Member</button>
                </form>
                <div class="alert alert-success" v-if="success != ''">
                    <ul>
                        <li>@{{success}}</li>
                    </ul>
                </div>
                <ul class="list-group" v-if="errors != ''">
                    <li class="list-group-item list-group-item-danger" v-for="error in errors">@{{error[0]}}</li>
                </ul>
            </div>
        </div>
    </div>
    @include('partials/footer/familyTreeFooter')
<script src="/js/addMember.js"></script>
</body>
</html>
