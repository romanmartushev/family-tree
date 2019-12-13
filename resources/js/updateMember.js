import Vue from 'vue';
import axios from 'axios';
import 'bootstrap';
import 'jquery';

new Vue({
    el:'#root',
    data:{
        success: null,
        error: null,
        main: '',
        mother: '',
        father: '',
        spouse: '',
        phoneNumber: '',
        address: '',
        email: '',
        members:[]
    },
    methods: {
        updateMember () {
            var vm = this;
            axios.post('/update-a-member',{
                main: vm.main,
                mother: vm.mother,
                father: vm.father,
                spouse: vm.spouse,
                phoneNumber: vm.phoneNumber,
                address: vm.address,
                email: vm.email
            })
                .then((response) => {
                    if(response.data.hasOwnProperty('success')) {
                        vm.success = response.data.success;
                        setTimeout(function(){
                            vm.success = '';
                        },5000);
                    } else {
                        vm.error = response.data.error;
                        setTimeout(function(){
                            vm.error = '';
                        },5000);
                    }
                })
                .catch((error) => {
                    vm.error = error.response.data;
                    setTimeout(function(){
                        vm.error = '';
                    },5000);
                });
        },
        getMembers() {
            const vm = this;
            axios.get('/api/members/all')
                .then((response) => {
                    vm.members = response.data;
                })
        }

    },
    mounted() {
        this.getMembers();
    }
});
