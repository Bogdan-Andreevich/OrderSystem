
<style>
    .p-dialog .p-dialog-footer button{
        width:100px !important;
    }


    .p-dialog .p-dialog-footer button.p-confirm-dialog-reject .p-button-icon::before {
        content: 'Ні';
    }

    .p-dialog .p-dialog-footer button.p-confirm-dialog-accept .p-button-icon::before {
        content: 'Так';
    }




    label.col-form-label{
        font-weight: normal !important
    }

    .form-check-inline label{
        font-weight: normal !important
    }


    .p-tabview .p-tabview-panels{
        border-left: 1px solid #ced4da !important;
        border-bottom: 1px solid #ced4da !important;
        border-right: 1px solid #ced4da !important;
    }



    .questions .d-flex:nth-child(odd) {
        background-color: #f2f2f2;
    }

    .questions .d-flex:nth-child(even) {
        background-color: #fff;
    }

    .cphone{
        color: rgb(0, 123, 255);
        cursor: pointer;
    }






</style>



<template>

    <Toast />
    <ConfirmDialog></ConfirmDialog>







            <!-- Horizontal Form -->
            <div class="card card-secondary">
                <div class="card-header">

                    <div class="d-flex">
                        <div class="p-2 flex-grow-1" >
                            <h3 class="card-title" style="font-size: 1.25rem;">Форма створення замовлення</h3>
                        </div>

                        <div>
                            <Button label="Зберегти"  @click="save" />
                        </div>
                    </div>



                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal">
                    <div class="card-body">

                        <div    class="container-fluid">


                            <!-- call data div -->
                            <div class="row">
                                <div class="d-flex align-items-center m-3" style="width: 100%;">
                                    <div class="p-2">
                                        <Button @click="clear_call" icon="pi pi-times" severity="danger" rounded aria-label="Cancel" />
                                    </div>

                                    <div class="p-2 pl-4">
                                        <h2>Поточний дзвінок</h2>
                                    </div>

                                    <div class="p-2">
                                        <Button label="Оновити" @click="get_call" />
                                    </div>

                                    <div class="p-2 pl-4 pr-4">
                                        <template v-if="call !== null">
                                            <h2><a class="cphone" @click="insert_phone"> {{ call.callerid }}</a></h2>
                                        </template>
                                    </div>

                                    <div class="ml-auto p-2">
                                        <Button label="Зберегти" />
                                    </div>

                                </div>
                            </div>


                            <div class="row">

                                <!-- left column -->
                                <div class="col-md-5 border rounded-lg">

                                    <div class="row justify-content-end p-3">
                                        <div class="col-md-auto">
                                            <Button label="Оновити" @click="get_client" />
                                        </div>
                                    </div>

                                    <div class="row p-2 ">
                                        <div class="col-md-12">
                                            <template v-if="client !== null">


                                                <template v-for="(item, index) in client">

                                                    Ім’я: {{ item.NAME }}<br>

                                                    Телефони:
                                                    <template v-for="phone in getPhones(item)">
                                                        <a
                                                            @click="insert_phone(phone)"
                                                            class="cphone pl-3"
                                                        >
                                                            {{ phone }}
                                                        </a>


                                                    </template>

                                                    <br>
                                                    <br>
                                                    Коментарі:<br>

                                                    <template v-for="comment in item.COMMMENTS">
                                                        {{comment.DT}}: - {{comment.OPERATOR}} - {{comment.TEXT}} <br>
                                                    </template>

                                                    <div v-if="index < client.length - 1">
                                                        ====================================
                                                    </div>

                                                </template>


                                            </template>
                                        </div>
                                    </div>


                                </div>


                                <!-- right column -->
                                <div class="col-md-7 border rounded-lg">

                                    <div class="row justify-content-end p-3">
                                        <div class="col-md-auto">
                                            <Button label="Оновити" />
                                        </div>
                                    </div>

                                    <div class="row p-2 ">
                                        <div class="col-md-12">
                                            <template v-if="call !== null">
                                            Дані по замовленням<br>
                                            Дані по замовленням<br>
                                            Дані по замовленням<br>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="row p-2 border-top">
                                        <div class="col-md-12">
                                            <template v-if="call !== null">
                                            Коментарі<br>
                                            Коментарі<br>
                                            </template>

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <br><hr><br>



                            <div class="row">

                                <div class="col-md-5">

                                    <div class="form-group row">

                                        <label for="name" class="col-sm-2 col-form-label">
                                            Імя

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

                                        <label for="phone1" class="col-sm-2 col-form-label">
                                            Телефон 1
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <!--
                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.phone1) ? 'is-invalid' : '')"
                                                id="phone1"
                                                placeholder=""
                                                v-model="order.phone1"
                                            >
                                            -->

                                            <InputMask
                                                v-model="order.phone1"
                                                date="phone"
                                                mask="(999) 999-9999"
                                                placeholder="(999) 999-9999"
                                                :autoClear=false



                                                id="phone1"
                                                class="form-control" :class="((errors.phone1) ? 'is-invalid' : '')"
                                            />

                                            <span v-if="errors && errors.phone1" class="error invalid-feedback">{{errors.phone1[0]}}</span>

                                        </div>
                                    </div>


                                    <div class="form-group row">

                                        <label for="phone2" class="col-sm-2 col-form-label">
                                            Телефон 2

                                        </label>

                                        <div class="col-sm-10">


                                            <!--
                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.phone2) ? 'is-invalid' : '')"
                                                id="phone2"
                                                placeholder=""
                                                v-model="order.phone2"
                                            >
                                            --->

                                            <InputMask
                                                v-model="order.phone2"
                                                date="phone"
                                                mask="(999) 999-9999"
                                                placeholder="(999) 999-9999"
                                                :autoClear=false

                                                id="phone2"
                                                class="form-control" :class="((errors.phone2) ? 'is-invalid' : '')"
                                            />


                                            <span v-if="errors && errors.phone2" class="error invalid-feedback">{{errors.phone2[0]}}</span>

                                        </div>
                                    </div>



                                    <div class="form-group row">

                                        <label for="phone3" class="col-sm-2 col-form-label">
                                            Телефон 3
                                        </label>

                                        <div class="col-sm-10">

                                            <!---
                                            <input

                                                type="text"
                                                class="form-control" :class="((errors.phone3) ? 'is-invalid' : '')"
                                                id="phone3"
                                                placeholder=""
                                                v-model="order.phone3"
                                            >
                                            --->

                                            <InputMask
                                                v-model="order.phone3"
                                                date="phone"
                                                mask="(999) 999-9999"
                                                placeholder="(999) 999-9999"
                                                :autoClear=false

                                                id="phone3"
                                                class="form-control" :class="((errors.phone3) ? 'is-invalid' : '')"
                                            />



                                            <span v-if="errors && errors.phone3" class="error invalid-feedback">{{errors.phone3[0]}}</span>

                                        </div>
                                    </div>






                                    <div class="form-group row">

                                        <label for="selected_order_type" class="col-sm-2 col-form-label">
                                            Тип замов.

                                        </label>

                                        <div class="col-sm-10">

                                            <Dropdown
                                                v-model="order.selected_order_type"
                                                :options="dictionaries.order_types"
                                                filter
                                                optionLabel="name"
                                                placeholder="Виберіть тип замововлення"
                                                class="w-full md:w-14rem"  :class="((errors.ordertype) ? 'is-invalid' : '')"
                                                style="width:100%;"
                                                @change="update_ordertype_detail_data"

                                            >

                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex align-items-center">
                                                        <div>{{ slotProps.value.name }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>

                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">
                                                        <div>{{ slotProps.option.name }}</div>
                                                    </div>
                                                </template>

                                            </Dropdown>

                                            <span v-if="errors && errors.ordertype" class="error invalid-feedback">{{errors.ordertype[0]}}</span>

                                        </div>
                                    </div>




                                </div> <!--left column--->






                                <div class="col-md-5 " style="margin-left: 3%;">

                                    <div class="form-group row">

                                        <label for="selectedСity" class="col-sm-2 col-form-label">
                                            Місто
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-sm-10">

                                            <Dropdown
                                                v-model="order.selectedСity"
                                                :options="dictionaries.cities"
                                                filter
                                                optionLabel="name"
                                                placeholder="Виберіть місто"
                                                class="w-full md:w-14rem"   :class="((errors.cityid) ? 'is-invalid' : '')"
                                                style="width:100%;"
                                                retchange="$forceUpdate()"
                                                @change="update_ordertype_detail_data"
                                            >

                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex align-items-center">
                                                        <div>{{ slotProps.value.name }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>

                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">
                                                        <div>{{ slotProps.option.name }}</div>
                                                    </div>
                                                </template>

                                            </Dropdown>

                                            <span v-if="errors && errors.cityid" class="error invalid-feedback">{{errors.cityid[0]}}</span>

                                        </div>
                                    </div>



                                    <div class="form-group row">

                                        <label for="surname" class="col-sm-2 col-form-label">
                                            Вулиця

                                        </label>

                                        <div class="col-sm-10">

                                            <Dropdown
                                                v-model="order.selectedStreet"
                                                :options="dictionaries_streets_filtered"
                                                filter
                                                editable
                                                optionLabel="name"
                                                placeholder="Виберіть вулицю"
                                                class="w-full md:w-14rem"  :class="((errors.streetid) ? 'is-invalid' : '')"
                                                style="width:100%;">

                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex align-items-center">
                                                        <div>{{ slotProps.value.name }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>

                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">
                                                        <div>{{ slotProps.option.name }}</div>
                                                    </div>
                                                </template>

                                            </Dropdown>

                                            <span v-if="errors && errors.streetid" class="error invalid-feedback">{{errors.streetid[0]}}</span>

                                        </div>
                                    </div>


                                    <!---будинок і квартира---->
                                    <div class="form-group row">

                                        <div class="col-sm-6">

                                            <div class="form-group row">

                                                <label for="house" class="col-sm-4 col-form-label">
                                                    Будинок
                                                </label>

                                                <div class="col-sm-8">

                                                    <input

                                                        type="text"
                                                        class="form-control" :class="((errors.house) ? 'is-invalid' : '')"
                                                        id="house"
                                                        name="house"
                                                        placeholder=""
                                                        v-model="order.house"
                                                    >

                                                    <span v-if="errors && errors.house" class="error invalid-feedback">{{errors.house[0]}}</span>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-group row">

                                                <label for="apartment" class="col-sm-4 col-form-label">
                                                    Квартира
                                                </label>

                                                <div class="col-sm-8">

                                                    <input

                                                        type="text"
                                                        class="form-control" :class="((errors.apartment) ? 'is-invalid' : '')"
                                                        id="apartment"
                                                        placeholder=""
                                                        v-model="order.apartment"
                                                    >

                                                    <span v-if="errors && errors.flat" class="error invalid-feedback">{{errors.flat[0]}}</span>

                                                </div>
                                            </div>

                                        </div>

                                    </div>



                                    <div class="form-group row">

                                        <div class="col-sm-6">

                                            <div class="form-group row">

                                                <label for="date" class="col-sm-4 col-form-label">
                                                    Дата роботи
                                                </label>

                                                <div class="col-sm-8">

                                                    <Calendar   v-model="order.date" dateFormat="dd/mm/yy"  showIcon  :class="((errors.date) ? 'is-invalid' : '')"  />

                                                    <span v-if="errors && errors.date" class="error invalid-feedback">{{errors.date[0]}}</span>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-group row">

                                                <label for="time" class="col-sm-2 col-form-label">
                                                    Час
                                                </label>

                                                <div class="col-sm-10">

                                                    <input

                                                        type="text"
                                                        class="form-control" :class="((errors.time) ? 'is-invalid' : '')"
                                                        id="time"
                                                        placeholder=""
                                                        v-model="order.time"
                                                    >

                                                    <span v-if="errors && errors.time" class="error invalid-feedback">{{errors.time[0]}}</span>

                                                </div>
                                            </div>

                                        </div>

                                    </div>





                                </div>




                            </div>




                            <div class="row pt-3">
                                <div class="col-sm-12">

                                    <TabView @update:activeIndex="status_change">
                                        <TabPanel header="Замовлення">
                                            <p class="m-0">
                                                Ви вибради статус "Замовлення"
                                            </p>
                                        </TabPanel>

                                        <TabPanel header="Повторне">
                                            <p class="m-0">
                                                Ви вибради статус "Повторне"
                                            </p>
                                        </TabPanel>

                                        <TabPanel header="Відмова">
                                            <p class="m-0">


                                            <div class="form-group row">

                                                <label for="selected_reasons_for_refusal" class="col-sm-2 col-form-label">
                                                    Причина відмови

                                                </label>

                                                <div class="col-sm-2">

                                                    <Dropdown
                                                        v-model="order.selected_reasons_for_refusal"
                                                        :options="dictionaries.reasons_for_refusal"
                                                        filter
                                                        optionLabel="name"
                                                        placeholder="Причина відмови"
                                                        class="w-full md:w-14rem" :class="((errors.reason) ? 'is-invalid' : '')"
                                                        style="width:100%;">

                                                        <template #value="slotProps">
                                                            <div v-if="slotProps.value" class="flex align-items-center">
                                                                <div>{{ slotProps.value.name }}</div>
                                                            </div>
                                                            <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                        </template>

                                                        <template #option="slotProps">
                                                            <div class="flex align-items-center">
                                                                <div>{{ slotProps.option.name }}</div>
                                                            </div>
                                                        </template>

                                                    </Dropdown>

                                                    <span v-if="errors && errors.reason" class="error invalid-feedback">{{errors.reason[0]}}</span>

                                                </div>
                                            </div>



                                            </p>
                                        </TabPanel>

                                        <!--
                                        <TabPanel header="Спілкуємося">
                                            <p class="m-0">

                                                <div class="form-group row">

                                                    <label for="date_of_next_contact" class="col-sm-2 col-form-label">
                                                        Дата наступного контакту
                                                    </label>

                                                    <div class="col-sm-3">

                                                        <Calendar
                                                            id="calendar-24h"
                                                            v-model="order.date_of_next_contact"
                                                            showTime
                                                            hourFormat="24"
                                                            dateFormat="dd/mm/yy"
                                                            showIcon
                                                            :class="((errors.nextcontact) ? 'is-invalid' : '')"
                                                        />

                                                        <span v-if="errors && errors.nextcontact" class="error invalid-feedback">{{errors.nextcontact[0]}}</span>
                                                    </div>

                                                </div>

                                            </p>
                                        </TabPanel>
                                        --->


                                    </TabView>

                                </div>

                            </div>



                            <div class="row pt-3">

                                <div class="col-sm-8">

                                    <textarea v-model="order.comment" class="form-control" rows="8" placeholder="Коментар *" :class="((errors.comment) ? 'is-invalid' : '')"></textarea>

                                    <span v-if="errors && errors.comment" class="error invalid-feedback">{{errors.comment[0]}}</span>
                                </div>

                                <div class="col-sm-4">

                                    <textarea
                                        v-model="order.internal_comment"
                                        class="form-control"  :class="((errors.comment_inner) ? 'is-invalid' : '')"
                                        rows="8"
                                        placeholder="внутрішній коментар"

                                    ></textarea>

                                    <span v-if="errors && errors.comment_inner" class="error invalid-feedback">{{errors.comment_inner[0]}}</span>

                                </div>

                            </div>



                            <div class="row pt-3">

                                <div class="col-sm-12">

                                    <textarea v-model="order.description" class="form-control" rows="4" placeholder="DESCRIPTION"></textarea>

                                    <span v-if="errors && errors.description" class="error invalid-feedback">{{errors.description[0]}}</span>

                                </div>

                            </div>


                            <div class="row pt-3">

                                <div class="col-sm-12 questions">


                                    <template v-if="order.ordertype_detail_data">

                                        <!-- <div class="d-flex justify-content-end question" v-for="item in order.ordertype_detail_data.QUESTIONS" :key="item">

                                            <div class="p-2 flex-fill">
                                                {{ item.question }}
                                            </div>

                                            <div class="p-2 ">
                                                <template v-if="item.answers.length < 1">
                                                    <input type="text" v-model="item.manager_anser"/>
                                                </template>

                                                <template v-else>
                                                    <select v-model="item.manager_anser">
                                                        <option  v-for="answer in item.answers" >{{ answer.title }}</option>
                                                    </select>

                                                    <input type="text" v-model="item.manager_anser"  style="margin-left: 10px;"  />
                                                </template>
                                            </div>
                                        </div> -->

                                        <NestedQuestion 
                                            v-for="item in order.ordertype_detail_data.QUESTIONS"  
                                            :key="item.id" 
                                            :question="item" 
                                        /> 

                                    </template>






                                </div>


                                <div class="col-sm-12">

                                    <template v-if="order.ordertype_detail_data">

                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col">Назва</th>
                                                <th scope="col">Ціна</th>
                                                <th scope="col">Од вим</th>
                                                <th scope="col">К-ть</th>
                                                <th scope="col">Вартість</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="item in order.ordertype_detail_data.PRICE">
                                                    <td>
                                                        <input type="checkbox" v-model="item.isActive">
                                                    </td>
                                                    <td>
                                                        <p>?</p>
                                                    </td>
                                                    <td>{{ item.name }}</td>
                                                    <td>{{ item.price }}грн.</td>
                                                    <td>{{ item.unit }}</td>
                                                    <td>{{ item.count }}</td>
                                                    <td>{{ item.price }}</td>
                                                </tr>



                                            </tbody>

                                        </table>

                                    </template>

                                </div>

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
import NestedQuestion from './NestedQuestion.vue'

    export default {
        components: {
        NestedQuestion
    },

        data() {

            return {


                /*
                selectedСity: null,

                selectedStreet:  null,

                date: null,

                selected_order_type: null,
                ordertype_detail_data: null,  // містить питання і ціни для даного типу замовлення

                selected_reasons_for_refusal: null,
                date_of_next_contact: null,
                */


                dictionaries:{
                    cities:[],
                    streets:[],
                    reasons_for_refusal:[],
                    order_types: [],
                },


                order: {
                    name: null,

                    phone1: null,
                    phone2: null,
                    phone3: null,
                    //phone4: null,
                    selected_order_type: null,

                    selectedСity: null,
                    selectedStreet:  null,
                    house:  null,
                    apartment:  null,
                    date: null,
                    time: null,

                    status: 'order',
                    selected_reasons_for_refusal: null,
                    date_of_next_contact: null,

                    comment: '',
                    internal_comment: null,
                    description: null,

                    //selected_order_type: null,
                    ordertype_detail_data: {
                        QUESTIONS: [],
                        PRICE: [],
                    },  // містить питання і ціни для даного типу замовлення

                    с_calldate: null, // - дата та час дзвінка
                    с_callerid: null, // - номер телефона клієнта з дзвінка
                    с_line: null, // - номер, на котрий дзвонив клієнт
                    с_recording: null // - ім’я файлу запису розмови
                },

                errors: {},

                active_tab:0,

                default_order_obj: null,


                call: null,
                client: null,




        }
        },


        created() {

            this.default_order_obj = { ...this.$data.order };


            // http://193.169.189.29/bot/api/dict.php?table=city
            this.axios
                .get(`http://crm-test.san-sanych.in.ua/account/api/order/create`)
                .then(async (response) => {
                    console.log(response.data);

                    this.dictionaries.cities = [];
                    response.data.city.forEach((element) => {

                        this.dictionaries.cities.push({
                            'name': element.NAME,
                            'code': element.ID
                        });
                    });

                    this.dictionaries.streets = [];
                    response.data.street.forEach((element) => {

                        this.dictionaries.streets.push({
                            'name': element.NAME,
                            'code': element.ID,
                            'city': element.CITY
                        });
                    });


                    this.dictionaries.reasons_for_refusal = [];
                    response.data.reasons_for_refusal.forEach((element) => {

                        this.dictionaries.reasons_for_refusal.push({
                            'name': element.NAME,
                            'code': element.ID
                        });
                    });

                    // this.dictionaries.order_types = [];
                    // response.data.ordertype.forEach((element) => {

                    //     this.dictionaries.order_types.push({
                    //         'name': element.NAME,
                    //         'code': element.ID
                    //     });
                    // });



                    const typeOfOrders = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/typeoforders`)

                    typeOfOrders.data.forEach((element) => {
                        this.dictionaries.order_types.push({
                            'name': element.name,
                            'code': element.searchId,
                            'id': element.id
                        });
                    });


                    /*this.robjects = response.data.robjects
                    this.order = response.data.order;
                    this.is_loaded = true;*/
                });


        },


        computed: {

            dictionaries_streets_filtered(){

                // Фільтруємо міста за обраною країною
                if(this.order.selectedСity == null) return [];

                return this.dictionaries.streets.filter(street => street.city === this.order.selectedСity.code);


            }





        },


        watch: {


        },



        methods:{


            status_change(i){
                //alert('status change '+i);

                let statuses = [
                    'order', //'order',
                    'repeat',
                    'refuse',
                    'commun'
                ];

                this.order.status = statuses[i];
            },





            get_questions_and_answers() {

                let res = [];

                if (this.order.ordertype_detail_data == null) return res;

                if (this.order.ordertype_detail_data.length == 0) return res;

                if ('QUESTIONS' in this.order.ordertype_detail_data){

                    if(this.order.ordertype_detail_data.QUESTIONS !=null ) {

                        this.order.ordertype_detail_data.QUESTIONS.forEach((element, index) => {
                            res.push({question: element.QUESTION, anser: element.manager_anser})
                        });
                    }
                }

                return res;
            },




            save(){

                if(this.call !== null) { // Коли з API завантажено дані по дзвінку
                    this.order.с_calldate = this.call.calldate;         //- дата та час дзвінка
                    this.order.с_callerid = this.call.callerid;          //- номер телефона клієнта з дзвінка
                    this.order.с_line = this.call.line;            // номер, на котрий дзвонив клієнт
                    this.order.с_recording = this.call.recording;             //- ім’я файлу запису розмови
                }


                var order_phones = [
                    String(this.order.phone1).replace(/\D/g, ""),
                    String(this.order.phone2).replace(/\D/g, ""),
                    String(this.order.phone3).replace(/\D/g, "")
                ];

                // якщо наявна інформація по дзвінку але немає співпадіння номера
                if( this.call !== null  && order_phones.includes( this.call.callerid ) == false ){

                    this.$confirm.require({
                        message: 'У заповненій формі немає номера, з якого зараз телефонує клієнт. Зв’язати це замовлення з дзвінком?',
                        header: 'Confirmation',
                        icon: 'pi pi-exclamation-triangle',
                        accept: () => {
                            this.store();
                        },
                        reject: () => {

                            this.order.с_calldate = null;        //- дата та час дзвінка
                            this.order.с_callerid = null;        //- номер телефона клієнта з дзвінка
                            this.order.с_line = null;            // номер, на котрий дзвонив клієнт
                            this.order.с_recording = null;       //- ім’я файлу запису розмови

                            this.store();
                        }
                    });


                }else{ //якщо відсутня інформація по дзвінку
                    this.store();
                }



            },



            store() {



                /*
                var order_phones = [this.order.phone1, this.order.phone2, this.order.phone3]
                if( order_phones.includes( this.call.callerid ) == false ){
                    show_poup('У заповненій формі немає номера, з якого зараз телефонує клієнт. Зв’язати це замовлення з дзвінком?')
                }
                */


                this.order.comment+= '\r\n' + ((this.order.date!=null)? this.converDate(this.order.date) : '') + ' ' + ((this.order.time!=null)? this.order.time : '');

                if(this.order.ordertype_detail_data != null){

                    if ('QUESTIONS' in this.order.ordertype_detail_data ) {

                        if(this.order.ordertype_detail_data.QUESTIONS !=null ) {

                            this.order.ordertype_detail_data.QUESTIONS.forEach((element) => {

                                if (element.manager_anser != null && element.manager_anser != '--') {
                                    this.order.comment += '\r\n' + element.DESCRIPTION + ' : ' + element.manager_anser;
                                }

                            });
                        }
                    }

                }


                if(this.order.status == 'repeat'){
                    this.order.comment+= '\r\n' + this.order.selectedСity?.name || '';

                    this.order.comment+= '\r\n' + (this.order.selectedStreet?.name  || this.order.selectedStreet);
                    //this.order.comment+= '\r\n' + this.order.selectedStreet.name + '(id:'+this.order.selectedStreet.code+')';

                    this.order.comment+= '\r\n Будинок: ' + this.order.house +' | Квартира: '+ this.order.apartment;
                }



                let send_data = {
                    status:  this.order.status,
                    ordertype: this.order.selected_order_type?.code || '',
                    name: this.order.name,
                    comment: this.order.comment,
                    comment_inner: this.order.internal_comment,

                    phone1: String(this.order.phone1).replace(/\D/g, ""),
                    phone2: String(this.order.phone2).replace(/\D/g, ""),
                    phone3: String(this.order.phone3).replace(/\D/g, ""),
                    //phone4: this.order.phone4,

                    cityid:   this.order.selectedСity?.code || '',                     //- ІД міста
                    streetid:  this.order.selectedStreet?.code || '',                 // - ІД вулиці, якщо вона обрана зі списку
                    street:  this.order.selectedStreet?.name  || this.order.selectedStreet,                    //- назва вулиці, передаємо завжди
                    house:  this.order.house,                                   //- номер будинку
                    flat:   this.order.apartment,                               //- номер квартири
                    operatorid:  13,                                             //дані користувача, що відправив заповнену форму з відповідного поля його налаштувань
                    //reason:  this.order.selected_reasons_for_refusal?.code || '',      //ID причини відмови зі словника, що створено в системі
                    nextcontact:  this.order.date_of_next_contact,                                              //- дата та час наступного контакту формат: YYYY-MM-DD hh:mm


                    date: this.converDate(this.order.date),
                    time: this.order.time,
                    questions_and_answers: this.get_questions_and_answers(),


                    с_calldate: this.order.с_calldate,       //- дата та час дзвінка
                    с_callerid: this.order.с_callerid,       //- номер телефона клієнта з дзвінка
                    с_line: this.order.с_line,           // номер, на котрий дзвонив клієнт
                    с_recording: this.order.с_recording,      //- ім’я файлу запису розмови


                    //параметри для логу
                    ordertype_name: this.order.selected_order_type?.name || '',
                    city_name: this.order.selectedСity?.name || '',
                    street_name: this.order.selectedStreet?.name  || this.order.selectedStreet
                };


                if(this.order.status == 'refuse'){
                    send_data.reason = this.order.selected_reasons_for_refusal?.code || '';
                }


                console.log(send_data);
                //this.resetData();


                this.axios
                    .post('http://crm-test.san-sanych.in.ua/account/api/order/store', send_data)
                    .then(response => {
                        this.errors = {};

                        if (response.data.status) {

                            this.showSuccess(
                                'Операцію успішно виконано',
                                'Замовлення створено.'
                            );

                            this.resetData();

                            //this.$router.push({name: 'order/index'})

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



            },





            update_ordertype_detail_data: function(){
                console.log('run');

                console.log(this.order.selectedСity); //це стрічка необхідна для того щоб при зміні this.selectedСity відбувався перерахунок ordertype_detail()

                if(this.order.selected_order_type == null) return [];

                return this.axios.get(`http://crm-test.san-sanych.in.ua/api/questionsTypeById/${this.order.selected_order_type.id}`).then(async (response) => {
                    //this.ordertype_detail_data = response.data;
                    // this.order.ordertype_detail_data = [];
                      if( response.data != null ){
                        response.data.forEach((element, index) => {
                            element.manager_anser = null;
                            element.answers = element.answers;
                            this.order.ordertype_detail_data.QUESTIONS.push(element);
                        });
                    }


                    // if( response.data[0].QUESTIONS != null ){

                    //     response.data[0].QUESTIONS.forEach((element, index) => {
                    //         element.manager_anser = null;
                    //         element.ANSWERS = (element.ANSWERS=="")? [] : element.ANSWERS.split(';');
                    //         if(element.ANSWERS.length > 0) element.ANSWERS.push('--');

                    //         response.data[0].QUESTIONS[index] = element;

                    //         ///this.order.ordertype_detail_data.push(element);
                    //     });


                    //     response.data[0].QUESTIONS.sort(function(a, b) {
                    //         return a.PRIORITY - b.PRIORITY;
                    //     });

                    // }

                    const priceList = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/price/rows/${this.order.selected_order_type.id}`);
                    this.order.ordertype_detail_data.PRICE = priceList.data.categories.map((item)=>({...item, count:1, isActive: true}));
                 



                    // if( response.data[0].PRICE != null){

                    //     response.data[0].PRICE.forEach((element, index) => {

                    //         if(this.order.selectedСity != null && element.PRICE_BY_CITY != null) {

                    //             var searchResults = element.PRICE_BY_CITY.filter(obj => obj.CITY === this.order.selectedСity.code);
                    //             element.price_for_selected_city = (searchResults.length >0)? searchResults[0].SUMM : '';

                    //         }else{
                    //             element.price_for_selected_city = '';
                    //         }

                    //         //element.price_for_selected_city =
                    //         response.data[0].PRICE[index] = element;
                    //     });


                    //     response.data[0].PRICE.sort(function(a, b) {
                    //         return b.PRIORITY - a.PRIORITY;
                    //     });

                    // }



                    // this.order.ordertype_detail_data =  response.data[0];


                    // this.order.description = this.order.ordertype_detail_data[0].description;
                    // this.order.description = this.order.ordertype_detail_data.DESCRIPTION;

                });

            },




            resetData() {
                Object.assign(this.$data.order, this.default_order_obj);
                this.active_tab = 0;
            },





            showSuccess(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'success', summary: psummary, detail: pdetail, life: 3000 });
            },

            showError(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'error', summary: psummary, detail: pdetail, life: 3000 });
            },

            converDate(date){
                console.log('--------selectDate----------')
                console.log(date);


                const dateString = date;
                const dateObj = new Date(dateString);

                const day = String(dateObj.getDate()).padStart(2, '0');
                const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Місяці в JavaScript починаються з 0, тому додаємо 1
                const year = String(dateObj.getFullYear());

                const formattedDate = `${day}/${month}/${year}`;
                console.log(formattedDate); // Виведе "21-12-2023"

                //this.order.date = formattedDate;

                return formattedDate;
            },



            get_call(){

                this.axios.get(`http://crm-test.san-sanych.in.ua/account/api/getcall`).then((response) => {
                    console.log(response.data);

                    this.call = response.data;

                    /*
                    calldate - дата та час дзвінка
                    callerid - номер телефона клієнта
                    line - номер, на котрий дзвонив клієнт
                    recording - ім’я файлу запису розмови
                    */

                });


            },


            get_client(){
                this.axios.get(`http://crm-test.san-sanych.in.ua/account/api/getclient?phone=`+this.call.callerid).then((response) => {
                    console.log(response.data);

                    this.client = response.data;
                    /*
                     [
                      {
                        "ID": 465712,
                        "NAME": "Юрий",
                        "PHONE": "0982710209",
                        "PHONE2": null,
                        "PHONE3": null,
                        "COMMMENTS": [
                          {
                            "TEXT": "тестовий коментар",
                            "DT": "23.12.2023 18:40",
                            "OPERATOR": "Дима Андрусык"
                          }
                        ],
                        "ORDERS": [
                          712846
                        ]
                      }
                    ]
                    */

                });
            },



            insert_phone(phone=null){

                var phone = (phone==null)? this.call.callerid : phone;

                if(this.order.phone1 == null || this.order.phone1.trim()==''){
                    this.order.phone1 = phone;
                    return true;
                }

                if(this.order.phone2 == null || this.order.phone2.trim()==''){
                    this.order.phone2 = phone;
                    return true;
                }

                if(this.order.phone3 == null || this.order.phone3.trim()==''){
                    this.order.phone3 = phone;
                    return true;
                }

                return false;
            },


            getPhones(client){
                var res = [];

                if(client.PHONE != null)  res.push(client.PHONE);
                if(client.PHONE2 != null)  res.push(client.PHONE2);
                if(client.PHONE3 != null)  res.push(client.PHONE3);

                return res;
            },


            clear_call(){
                this.call = null;
            }



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


