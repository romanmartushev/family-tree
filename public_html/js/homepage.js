/**
 * Created by Roman on 1/4/18.
 */
new Vue({
    el:'#root',
    data:{
        errors: [],
        birthdays: [],
        welcome_message: 'Hello',
        timeout: 1000
    },
    methods: {
        checkBirthdays: function(){
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
