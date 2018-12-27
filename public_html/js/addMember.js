/**
 * Created by Roman on 1/4/18.
 */
new Vue({
    el:'#root',
    data:{
        success: '',
        errors:'',
        name: '',
        birthday: '',
        phoneNumber: '',
        email: ''
    },
    methods: {
        addMember: function(){
            var vm = this;
            axios.post('/api/add-new-member', {
                name: vm.name,
                birthday: vm.birthday,
                phoneNumber: vm.phoneNumber,
                email: vm.email
            }).then(function(response){
                if(response.data.hasOwnProperty('success')){
                    vm.name = '';
                    vm.birthday = '';
                    vm.phoneNumber = '';
                    vm.email = '';
                    vm.success = response.data.success;
                }else{
                    vm.errors = response.data.errors;
                    console.log(vm.errors)
                }
            }).catch(function(errors){
               vm.errors = errors.response.data.errors;
            });
            setTimeout(function(){
                vm.error = '';
                vm.success = '';
            },5000);
        }

    }
});
