import Vue from 'vue';
import axios from 'axios';
import 'bootstrap';
import 'jquery';

new Vue({
    el:'#root',
    data:{
        errors: [],
        birthdays: [],
        welcome_message: 'Hello',
        timeout: 1000
    },
    methods: {
        checkBirthdays() {
            const vm = this;
            axios.get("/homepage-birthdays")
                .then(function (response) {
                    vm.birthdays = response.data;
                }).catch(function(e) {
                    vm.errors.push(e);
            });
        }
    },
    mounted: function(){
        this.checkBirthdays();
    }
});
