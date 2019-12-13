<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update A Family Member</title>
    <link href="/css/main.css" rel="stylesheet">
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row tree-1 padding-xs margin-top-lg">
        <div id="mainHeading" class="col-12 txt-white margin-left-auto margin-right-auto text-center">
            <h1 class="underline"><span class="shadow">Update A Family Member</span></h1>
        </div>
        <div id="root" class="col-6">
            <div class="form-group" v-if="members.length !== 0">
                <label class="txt-white" for="familyMemberSelector">Select A Family Member:</label>
                <br>
                <select id="familyMemberSelector" name="main" v-model="main">
                    <option value="">Family Member</option>
                    <option v-for="member in members" :value="member">@{{member.name}} Birthday: @{{member.birthday}}</option>
                </select>
                <br>
                <label class="txt-white" for="Mother">Select Family Member's Mother:</label>
                <br>
                <select id="Mother" name="mother" v-model="mother">
                    <option value="">Mother</option>
                    <option v-for="member in members" :value="member">@{{member.name}} Birthday: @{{member.birthday}}</option>
                </select>
                <br>
                <label class="txt-white" for="Father">Select Family Member's Father:</label>
                <br>
                <select id="Father" name="father" v-model="father">
                    <option value="">Father</option>
                    <option v-for="member in members" :value="member">@{{member.name}} Birthday: @{{member.birthday}}</option>
                </select>
                <br>
                <label class="txt-white" for="Spouse">Select Family Member's Spouse:</label>
                <br>
                <select id="Spouse" name="spouse" v-model="spouse">
                    <option value="">Spouse</option>
                    <option v-for="member in members" :value="member">@{{member.name}} Birthday: @{{member.birthday}}</option>
                </select>

                <div class="form-group">
                    <label class="txt-white" for="InputPhoneNumber">Phone Number:</label>
                    <input type="text" class="form-control" name="phoneNumber" id="InputPhoneNumber" placeholder="Phone Number" v-model="phoneNumber">
                </div>
                <div class="form-group">
                    <label class="txt-white" for="InputAddress">Address:</label>
                    <input type="text" class="form-control" name="address" id="InputAddress" placeholder="Address" v-model="address">
                </div>
                <div class="form-group">
                    <label class="txt-white" for="InputEmail">Email address:</label>
                    <input type="email" class="form-control" name="email" id="InputEmail" placeholder="Email" v-model="email">
                </div>
                <button class="btn btn-light" @click="updateMember">Update Member</button>
            </div>
            <h1 v-else class="text-white">Sorry No Family Members Exist</h1>
            <div class="alert alert-success" v-if="success">
                <ul>
                    <li>@{{success}}</li>
                </ul>
            </div>
            <div>
                <div class="alert alert-danger" v-if="error">
                    <ul>
                        <li>@{{error}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials/footer/familyTreeFooter')
<script src="/js/updateMember.js"></script>
</body>
</html>
