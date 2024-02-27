
<style>
    label.col-form-label{
        font-weight: normal !important
    }

    .form-check-inline label{
        font-weight: normal !important
    }

</style>



<template>

    <Toast />








            <!-- Horizontal Form -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Бронювання</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal">
                    <div class="card-body">

                        <div  v-if="is_loaded"  class="container-fluid">
                            <div class="row">

                                <div class="col-md-6">
                                    <h5>Контакти</h5>
                                    <hr>

                                    <div class="form-group row">

                                        <label for="surname" class="col-sm-2 col-form-label">
                                            Прізвище
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.surname) ? 'is-invalid' : '')"
                                                id="surname"
                                                placeholder=""
                                                v-model="order.surname"
                                            >

                                            <span v-if="errors && errors.surname" class="error invalid-feedback">{{errors.surname[0]}}</span>

                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label for="name" class="col-sm-2 col-form-label">
                                            Імя
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.name) ? 'is-invalid' : '')"
                                                id="name"
                                                placeholder=""
                                                v-model="order.name"
                                            >

                                            <span v-if="errors && errors.name" class="error invalid-feedback">{{errors.name[0]}}</span>


                                        </div>
                                    </div>


                                    <div class="form-group row">

                                        <label for="phone" class="col-sm-2 col-form-label">
                                            Телефон
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.phone) ? 'is-invalid' : '')"
                                                id="phone"
                                                placeholder=""
                                                v-model="order.phone"
                                            >

                                            <span v-if="errors && errors.phone" class="error invalid-feedback">{{errors.phone[0]}}</span>

                                        </div>
                                    </div>


                                    <div class="form-group row">

                                        <label for="email" class="col-sm-2 col-form-label">
                                            Email
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.email) ? 'is-invalid' : '')"
                                                id="email"
                                                placeholder=""
                                                v-model="order.email"
                                            >

                                            <span v-if="errors && errors.email" class="error invalid-feedback">{{errors.email[0]}}</span>

                                        </div>
                                    </div>


                                    <div class="form-group row">

                                        <label for="status" class="col-sm-2 col-form-label">
                                            Статус
                                        </label>

                                        <div class="col-sm-10">

                                            <Dropdown
                                                inputId="dd-city"
                                                v-model="order.selectedStatus"
                                                :options="order_statuses"
                                                optionLabel="name"
                                                placeholder="Змінити статус"
                                                style="width: 100%;"

                                            />


                                        </div>
                                    </div>



                                </div>



                                <div class="col-md-6">
                                    <h5>Коментар бронювання</h5>
                                    <hr>

                                    <div class="form-group row">

                                        <div class="col-sm-12">

                                            <textarea v-model="order.comment" class="form-control" rows="8" ></textarea>

                                        </div>
                                    </div>


                                </div>


                            </div>


                            <div class="row pt-4">

                                <div class="col-md-6">
                                    <h5>Деталі розміщення</h5>
                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Дата заїзду</label>
                                                <input v-model="order.start_date" type="date" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Дата виїзду</label>
                                                <input  v-model="order.end_date" type="date" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-sm-12">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Обєкт</label>

                                                <select class="form-control" v-model="order.selected_robject_id" >

                                                    <option v-for="item in robjects"  :value="item.id">
                                                        {{ item.name }}
                                                    </option>

                                                </select>

                                            </div>
                                        </div>

                                    </div>



                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Категорія номеру</label>

                                                <select class="form-control" v-model="order.selected_categorie_id" >

                                                    <option v-for="item in categories"  :value="item.id">
                                                        {{ item.name }}
                                                    </option>

                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">

                                                <label>Тариф</label>

                                                <select class="form-control"  v-model="order.selected_tariff_id">

                                                    <option v-for="item in tariffs"  :value="item.tariff_id">
                                                        {{ item.tariff.name }}
                                                    </option>

                                                </select>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Кількість гостей</label>

                                                <select class="form-control" v-model="order.selected_number_of_seats">

                                                    <option v-for="(item, index) in number_of_seats"  :value="(index+1)">
                                                        {{ (index+1) }}
                                                    </option>

                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Вартість</label>
                                                <input type="text" class="form-control" placeholder="" :value="price_for_entire_period+' UAH'" disabled>
                                            </div>
                                        </div>
                                    </div>




                                </div>

                                <!---
                                <div class="col-md-6">
                                    <h5>Розрахунок</h5>
                                    <hr>
                                </div>
                                -->

                            </div>


                        </div>

                    </div>
                    <!-- /.card-body -->


                    <div class="card-footer">
                        <button @click="save" type="button" class="btn btn btn-primary float-right">Зберегти</button>
                    </div>
                    <!-- /.card-footer -->



                </form>
            </div>



</template>



<script>

    export default {

        data() {

            return {
                is_loaded: false,

                mode: 'create',

                robjects: [],

                order: {
                    /*
                    id: null,


                    start_date: null,
                    end_date: null,

                    selected_robject_id: 1,
                    selected_categorie_id: 1,
                    selected_tariff_id: 1,
                    selected_number_of_seats: 1
                    */

                },

                order_statuses: window.tmpl_data.order_statuses,

                errors: {}

            }
        },


        created() {

            var mode = (this.$route.params.hasOwnProperty('id')==false) ? 'create' : 'edit';

            if(mode == 'create'){

                this.axios
                    .get(`/api/order/create`)
                    .then((response) => {
                        this.robjects = response.data.robjects
                        this.order = response.data.order;
                        this.is_loaded = true;
                    });


            }else if(mode == 'edit'){

                this.axios
                    .get(`/api/order/edit/${this.$route.params.id}`)
                    .then((response) => {
                        this.robjects = response.data.robjects
                        this.order = response.data.order;
                        this.is_loaded = true;
                    });

            }


        },


        computed: {

            // вертає вибраний "robject"
            selected_robject(){
                return this.robjects.find(item => item.id === this.order.selected_robject_id);
            },


            //вертає список категорії для відповідного "robject"
            categories(){
                return this.selected_robject.categories;
            },

            // вертає вибрану категорю
            selected_categorie(){
                return this.categories.find(item => item.id === this.order.selected_categorie_id);
            },




            // масив тарифів доступних для вибраної категорії
            tariffs() {
                //return this.tmpl_data.robject.categories.find(item => item.id === this.tmpl_data.selected_categorie_id).tariffs;
                return this.selected_categorie.tariffs;
            },

            // вибраний тариф
            selected_tariff(){
                return this.tariffs.find(item => item.tariff_id === this.order.selected_tariff_id);
            },





            // ціна за день
            price_for_day(){
                return this.selected_tariff.price;
            },

            // кількість днів
            period(){

                const start_date = new Date( this.order.start_date );
                const end_date = new Date( this.order.end_date );

                const timeDifference = end_date.getTime() - start_date.getTime()
                const daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24))

                return daysDifference;

            },

            // ціна за всю кількість днів (за ввесь період)
            price_for_entire_period(){
                return this.price_for_day * this.period;
            },


            // доступна кількість гостей
            number_of_seats(){
                return this.selected_categorie.number_of_seats;
            },


        },


        watch: {

            'order.selected_order_id': function (newQuestion, oldQuestion) {

                if( oldQuestion !== undefined ) {
                    console.log('change order.selected_categorie_id');
                    this.order.selected_categorie_id = this.categories[0].id
                    this.order.selected_tariff_id = this.tariffs[0].tariff_id;
                    this.order.selected_number_of_seats = 1;
                }

            },

            // whenever question changes, this function will run
            'order.selected_categorie_id': function (newQuestion, oldQuestion) {

                console.log(newQuestion, oldQuestion);
                if( oldQuestion !== undefined ) {
                    console.log('change order.selected_categorie_id');
                    this.order.selected_tariff_id = this.tariffs[0].tariff_id;
                    this.order.selected_number_of_seats = 1;
                }
            }

        },



        methods:{

            save(){

                var mode = (this.$route.params.hasOwnProperty('id')==false) ? 'create' : 'edit';

                if(mode=='create'){
                    this.store();
                }else{
                    this.update();
                }
            },



            store() {

                let send_data = {
                    'start_date': this.order.start_date,
                    'end_date': this.order.end_date,

                    'robject_id': this.order.selected_robject_id,
                    'categorie_id': this.order.selected_categorie_id,
                    'tariff_id': this.order.selected_tariff_id,
                    'number_of_seats': this.order.selected_number_of_seats,

                    'name': this.order.name,
                    'surname': this.order.surname,
                    'phone': this.order.phone,
                    'email': this.order.email,
                    'comment': this.order.comment,

                    'status_id': this.order.selectedStatus.code
                };



                this.axios
                    .post('/api/order/store', send_data)
                    .then(response => {
                        this.errors = {};

                        if (response.data.status) {

                            this.showSuccess(
                                'Операцію успішно виконано',
                                'Бронювання створено.'
                            );

                            this.$router.push({name: 'order/index'})

                        }else{

                            this.showError(
                                'Виникла помилка на сервері',
                                'Невідома помилка'
                            );
                        }

                     })
                    .catch((e)=> {

                        console.log(e.response.status);

                        if (e.response.status === 422) {
                            console.log(e.response.data.errors);
                            this.errors = e.response.data.errors;
                        }

                    })
                    .finally(() => {})


            },



            update() {

                let send_data = {
                    'start_date': this.order.start_date,
                    'end_date': this.order.end_date,

                    'robject_id': this.order.selected_robject_id,
                    'categorie_id': this.order.selected_categorie_id,
                    'tariff_id': this.order.selected_tariff_id,
                    'number_of_seats': this.order.selected_number_of_seats,

                    'name': this.order.name,
                    'surname': this.order.surname,
                    'phone': this.order.phone,
                    'email': this.order.email,
                    'comment': this.order.comment,

                    'status_id': this.order.selectedStatus.code
                };

                this.axios
                    .post(`/api/order/update/${this.$route.params.id}`, send_data)
                    .then((response) => {

                        this.errors = {};

                        if (response.data.status) {

                            this.showSuccess(
                                'Операцію успішно виконано',
                                'Бронювання оновлено.'
                            );

                        }else{

                            this.showError(
                                'Виникла помилка на сервері',
                                'Невідома помилка'
                            );
                        }


                    })
                    .catch((e)=> {

                        console.log(e.response.status);

                        if (e.response.status === 422) {
                            console.log(e.response.data.errors);
                            this.errors = e.response.data.errors;
                        }

                    });


            },




            showSuccess(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'success', summary: psummary, detail: pdetail, life: 3000 });
            },

            showError(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'error', summary: psummary, detail: pdetail, life: 3000 });
            },

        }

    }






/*
    export default {

        data() {

            return {

                start_date: '',
                end_date: '',
                selected_categorie_id: 1,
                robject:{
                    categories:[
                        {
                            id: 1,
                            name: 'test'
                        }
                    ]
                },

                selected_tariff_id: 1,
                tariffs: [
                    {
                        tariff:{
                            id:1,
                            name: 'vip'
                        }
                    }
                ],
                selected_number_of_seats: 2,
                number_of_seats: 3,

                price_for_entire_period: 1200,



                selectedStatus: { name: 'Нове', code: 1 },
                statuses: [
                    { name: 'Нове', code: 1 },
                    { name: 'Підтверджене', code: 2 },
                ]

            }
        },
*/


        /*
              robject_tree ={
                categoryes:[
                    {
                    id: 1,
                    tariffs:[
                        {
                        id:1,
                        name: 'vip'
                        }
                    ]
                    }
                ]

              }

                robject_tree // обэкт бронювання з усіма підобєктами

                start_date
                end_date
                selected_categorie_id
                selected_tariff_id
                selected_number_of_seats

        *//*


    }*/

</script>

