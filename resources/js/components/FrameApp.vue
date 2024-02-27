<template>
    <div class="container-fluid">


        <div class="row">

            <div class="col-md-4">
                <div v-if="is_robject_on"  class="card card-primary">

                    <!--
                    <div class="card-header">
                        <h1 class="card-title"></h1>
                    </div>
                    -->

                    <div v-if="(step==1)" class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Забронювати</h1>
                            </div>
                        </div>

                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Дата заїзду</label>
                                        <input v-model="tmpl_data.start_date" type="date" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Дата виїзду</label>
                                        <input  v-model="tmpl_data.end_date" type="date" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Категорії номерів</label>

                                        <select class="form-control" v-model="tmpl_data.selected_categorie_id" >

                                            <option v-for="item in tmpl_data.robject.categories"  :value="item.id">
                                                {{ item.name }}
                                            </option>

                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <label>Тариф</label>

                                        <select class="form-control"  v-model="tmpl_data.selected_tariff_id">

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

                                        <select class="form-control" v-model="tmpl_data.selected_number_of_seats">

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


                            <button @click="step=2" type="button" class="btn btn-block btn-primary btn-lg">Забронювати</button>


                        </form>



                    </div> <!--- end step 1 ---->




                    <div v-if="(step==2)" class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Ваші дані</h1>
                            </div>
                        </div>

                        <form>
                            <div class="row">

                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">

                                        <label>
                                            Ім'я
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            v-model="name"
                                            type="text"
                                            class="form-control"  :class="((errors.name) ? 'is-invalid' : '')"
                                            placeholder="">

                                        <span v-if="errors && errors.name" class="error invalid-feedback">{{errors.name[0]}}</span>

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <label>
                                            Прізвище
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            v-model="surname"
                                            type="text"
                                            class="form-control"  :class="((errors.surname) ? 'is-invalid' : '')"
                                            placeholder="">

                                        <span v-if="errors && errors.surname" class="error invalid-feedback">{{errors.surname[0]}}</span>


                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">

                                        <label>
                                            Телефон
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            v-model="phone"
                                            type="text"
                                            class="form-control" :class="((errors.phone) ? 'is-invalid' : '')"
                                            placeholder="">

                                        <span v-if="errors && errors.phone" class="error invalid-feedback">{{errors.phone[0]}}</span>


                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>
                                            Email
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            v-model="email"
                                            type="text"
                                            class="form-control"  :class="((errors.email) ? 'is-invalid' : '')"
                                            placeholder="">

                                        <span v-if="errors && errors.email" class="error invalid-feedback">{{errors.email[0]}}</span>

                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Коментар</label>
                                        <input v-model="comment" type="text" class="form-control" placeholder="">
                                    </div>
                                </div>

                            </div>

                            <button @click="send" type="button" class="btn btn-block btn-primary btn-lg">Підтвердити</button>


                        </form>



                    </div>  <!---end step2----->





                    <div v-if="(step==3)" class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Дякуємо!</h1>
                                <h3 class="text-center">Ваше замовлення прийняте.</h3>
                                <h3 class="text-center">Ми зв'яжемося з Вами найближчим часом.</h3>
                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                            <div class="col-sm-6" style="padding:43px 0px 43px 0px;font-size:192px;fill:#46e326;text-align: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                            </div>
                        </div>

                    </div>










                </div>


                <div v-if="!is_robject_on"  class="card card-primary">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Бронювання не доступне :(</h1><br>

                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                            <div class="col-sm-6" style="padding:43px 0px 43px 0px;font-size:192px;fill:#52c9ff;text-align: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>

                            </div>
                        </div>


                    </div>
                </div>





            </div>
        </div>



    </div>

</template>

<script>
    export default {

        data() {

            return {
                step: 1,

                tmpl_data: window.tmpl_data,

                name: '',
                surname: '',
                phone: '',
                email: '',
                comment: '',

                errors: {}
            }
        },




        computed: {

            is_robject_on(){
                //return (this.tmpl_data.robject != null);
                return (this.tmpl_data.robject.status == 1);
            },

            // вибрана категорія
            selected_categorie(){

                return this.tmpl_data.robject.categories.find(item => item.id === this.tmpl_data.selected_categorie_id);
            },

            // масив тарифів доступних для вибраної категорії
            tariffs() {
                //return this.tmpl_data.robject.categories.find(item => item.id === this.tmpl_data.selected_categorie_id).tariffs;
                return this.selected_categorie.tariffs;
            },

            // вибраний тариф
            selected_tariff(){
                return this.tariffs.find(item => item.tariff_id === this.tmpl_data.selected_tariff_id);
            },


            // ціна за день
            price_for_day(){
                return this.selected_tariff.price;
            },

            // кількість днів
            period(){

                const start_date = new Date( this.tmpl_data.start_date );
                const end_date = new Date( this.tmpl_data.end_date );

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
            // whenever question changes, this function will run
            'tmpl_data.selected_categorie_id': function (newQuestion, oldQuestion) {
                console.log('change tmpl_data.selected_categorie_id');
                this.tmpl_data.selected_tariff_id = this.tariffs[0].tariff_id;
                this.tmpl_data.selected_number_of_seats = 1;
            }
        },


        methods:{

            send(){

                let send_data = {
                    'start_date': this.tmpl_data.start_date,
                    'end_date': this.tmpl_data.end_date,

                    'robject_id': this.tmpl_data.robject_id,
                    'categorie_id': this.tmpl_data.selected_categorie_id,
                    'tariff_id': this.tmpl_data.selected_tariff_id,
                    'number_of_seats': this.tmpl_data.selected_number_of_seats,

                    'name': this.name,
                    'surname': this.surname,
                    'phone': this.phone,
                    'email': this.email,
                    'comment': this.comment,
                };


                this.axios
                    .post('/order/store?frameform', send_data)
                    .then(response => {

                        if (response.data.status) {
                            this.step = 3;
                        }else{
                            alert("Виникла невідома  помилка :(  Спробуйте ща раз");
                        }
                        // console.log(response.data)
                    })
                    .catch((e)=> {

                        console.log(e.response.status);

                        if (e.response.status === 422) {
                            console.log(e.response.data.errors);
                            this.errors = e.response.data.errors;
                        }

                    })
                    .finally(() => {
                        //this.loading = false
                    })

            }

        }


    }
</script>
