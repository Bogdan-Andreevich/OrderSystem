
//Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

Vue.config.devtools = true;

var vm = new Vue({


    el: '#app', //'#manage-vue',


    data: {

        editable: window.editable_obj,

        server_url: window.base_url

    },


    computed: {

        /*----'Founder/Team'---*/
        i17: function(){

            return this.avg([
                this.editable.i13,
                this.editable.i14,
                this.editable.i15,
                this.editable.i16
            ]);
        },

        /*----'Market Opportunity'---*/
        i23: function(){

            return this.avg([
                this.editable.i20,
                this.editable.i21,
                this.editable.i22
            ]);
        },

        /*----'Product/Sales/Marketing'---*/
        i31: function(){

            return this.avg([
                this.editable.i26,
                this.editable.i27,
                this.editable.i28,
                this.editable.i29,
                this.editable.i30
            ]);
        },

        /*----'Competitive Environment'---*/
        i37: function(){

            return this.avg([
                this.editable.i34,
                this.editable.i35,
                this.editable.i36
            ]);
        },

        /*----'Financing'---*/
        i43: function(){

            return this.avg([
                this.editable.i40,
                this.editable.i41,
                this.editable.i42
            ]);
        },

        /*----"Bonus (internal, don't ask founder)"---*/
        i48: function(){

            return this.avg([
                this.editable.i46,
                this.editable.i47
            ]);
        },



        /*---result table---*/

        e6: function(){
            var percent = 40;
            return ((this.i17 / 100) * percent).toFixed(2);
        },

        e7: function(){
            var percent = 20;
            return ((this.i23 / 100) * percent).toFixed(2);
        },

        e8: function(){
            var percent = 20;
            return ((this.i31 / 100) * percent).toFixed(2);
        },


        e9: function(){
            var percent = 10;
            return ((this.i37 / 100) * percent).toFixed(2);
        },


        e10: function(){
            var percent = 5;
            return ((this.i43 / 100) * percent).toFixed(2);
        },

        e11: function(){
            var percent = 15;
            return ((this.i48 / 100) * percent).toFixed(2);
        },


        // total
        e12: function() {
            return (parseFloat(this.e6) + parseFloat(this.e7) + parseFloat(this.e8) + parseFloat(this.e9) + parseFloat(this.e10) + parseFloat(this.e11)).toFixed(2);
        },


        // result (reomedation)
        e3: function() {

            // =ЕСЛИ(E12<4; "Hard No";ЕСЛИ(E12<5;"No"; ЕСЛИ(E12<6; "Areas of concerns"; ЕСЛИ(E12>=6; "Move forward with DD"))))

            if(this.e12 < 4){
                return "Hard No";
            }else if(this.e12 < 5){
                return "No";
            }else if(this.e12 < 6){
                return "Areas of concerns";
            }else if(this.e12 >= 6){
                return  "Move forward with DD";
            }
        },

        action: function() {

            return (this.editable.id==0)? 'store' : 'update';

        }

    },

    methods: {

        avg: function( arr ){
            return (arr.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / arr.length).toFixed(1);
        },

        save: function(){

            var method = (this.action == 'store')? 'post' : 'put';
            var path   = (this.action == 'store')? '' : '/'+this.editable.id;

            var sending_obj = {
                'editable': this.editable,
                'computed_properties': {
                    'e12': this.e12,
                    'e3': this.e3
                }
            };

            this.$http[method](this.server_url+path, sending_obj).then((response) => {

                var res = (response.data);

                if( res.status ) {
                    this.editable.id = res.data.id;
                    //alert('Successfully.');
                    toastr.success('Saved successfully.', {timeOut: 5000});
                }else{
                    //alert('Error: '+res.error);
                    toastr.error( res.error, {timeOut: 5000});
                    //toastr.warning(res.error, {timeOut: 5000});
                }

            }, (response) => {

                //alert('save error');
                toastr.error('save error', {timeOut: 5000});
            });

        },


        editable_obj_change: function(event, min=0, max=10){

            var path_to_prop = event.target.name.split('.');

            if(min >= 0) {

                this[path_to_prop[0]][path_to_prop[1]] = parseFloat(event.target.value);

                if (event.target.value < min) this[path_to_prop[0]][path_to_prop[1]] = min;
                if (event.target.value > max) this[path_to_prop[0]][path_to_prop[1]] = max;
                if (event.target.value.trim() == '') this[path_to_prop[0]][path_to_prop[1]] = 0;

            }else{
                //this[path_to_prop[0]][path_to_prop[1]] = parseFloat(event.target.value);
                if (event.target.value > max) this[path_to_prop[0]][path_to_prop[1]] = max;
                if (event.target.value < min) this[path_to_prop[0]][path_to_prop[1]] = min;
                //if (event.target.value.trim() == '') this[path_to_prop[0]][path_to_prop[1]] = -5;

            }

            /*if( /^\d+$/.test(event.target.value) == false ){
                this[path_to_prop[0]][path_to_prop[1]] = 0;
            }*/

        }

    },


    /*watch: {
        editable(newValue, oldValue) {
            console.log("----------someData changed!----------");
            console.log([newValue, oldValue])
        },
    },*/

    /*watch: {
        editable: {
            handler(newValue, oldValue) {
                // console.log(newValue, oldValue);
                console.log("someData changed!");
            },
            deep: true,
        },
    },*/



    mounted: function(){
        //alert('test');
    }


});
