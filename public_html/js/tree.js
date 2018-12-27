new Vue({
   el: '#tree',
   data: {
       tree: [],
       parents: []
   },
   methods: {
       getTree: function() {
           var vm = this;
           axios.get('/api/members')
               .then(function(response){
                   vm.tree = response.data;
                   vm.extractParents();
               })
               .catch(function(error){
                   console.log(error);
               })
       },
       extractParents: function(){
           var vm = this;
           this.tree.forEach(function(family){
               if(family.hasOwnProperty('parents')){
                   vm.parents.push(family.parents);
               }else{
                   vm.parents.push(family.couple);
               }
           });
       }
   },
    mounted(){
       this.getTree();
    }
});
