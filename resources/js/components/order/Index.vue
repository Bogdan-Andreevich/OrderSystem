
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

</style>



<template>

    <Toast />


    <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title">Замовлення</h3>
        </div>


        <div class="card-body">


            <DataTable
                :value="items" lazy paginator  :rows="500" :rowsPerPageOptions="[50, 100, 200, 500]"  ref="dt" dataKey="id"
                :totalRecords="totalRecords"
                :loading="loading"

                v-model:filters="filters"
                :globalFilterFields="['order_id']"

                @filter="onFilter($event)"

                @page="onPage($event)"
                @sort="onSort($event)"

                showGridlines
                tableStyle="min-width: 75rem"

                v-model:expandedRows="expandedRows"

            >


                <template #header>

                    <span class="mr-3">Виберіть діапазон дат:</span>

                    <Calendar
                        v-model="filters.daterange.dates"
                        selectionMode="range"
                        :manualInput="false"

                        dateFormat="dd/mm/yy"
                        :minDate="filters.daterange.minDate"
                        :maxDate="filters.daterange.maxDate"

                        showIcon

                        v-tooltip.top="'Вкажіть діапазон дат (до 30 днів)'"

                        id="datarange"

                        @date-select="ChangeDateRange"
                    />

                    <span class="p-input-icon-left ml-4">
                        <i class="pi pi-search" />
                        <InputText v-model="filters.query_string" @input="ChangeQueryString"  placeholder="Введіть телефон або імя" />
                    </span>


                </template>






                <Column field="created_at" header="Дата">
                    <template #body="slotProps">
                        {{  formated_date(slotProps.data.created_at, 'YYYY-MM-DD H:mm:ss')  }}
                    </template>
                </Column>

                <Column field="name" header="Імя"></Column>
                <Column field="phone1" header="Телефон"></Column>
                <Column field="operatorid" header="Відправник"></Column>

                <Column field="status" header="Статус">
                    <template #body="slotProps">

                        <div
                            v-if="slotProps.data.requestStatus==1"
                            class="rounded-circle"
                            style="width:24px;height:24px;background:lawngreen; margin:0 auto;"
                            v-tooltip.top="'Успішний запит'"
                        >
                        </div>

                        <div
                            v-if="slotProps.data.requestStatus==0"
                            class="rounded-circle"
                            style="width:24px;height:24px;background:red; margin:0 auto;"
                            v-tooltip.top="'Невдалий запит'"
                        >
                        </div>

                    </template>  
                </Column>


                <Column expander style="width: 5rem" ></Column>


                <template #expansion="slotProps">
                    <div class="p-3">

                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <Button label="Відправити повторно" @click="send_request_again(slotProps.data)"  :loading="loading__send_request_again"/>
                            </div>
                        </div>


                        <h5>Замовлення від {{ slotProps.data.name }} ({{ slotProps.data.phone1 }})</h5>

                        <ul>
                            <li v-for="(value, key) in slotProps.data" :key="key">
                                <b>{{ prop_names[key] }}:</b>  <span v-html="formated_prop(key, value)"></span>
                            </li>
                        </ul>


                        <div v-if="slotProps.data.requests.length">
                            <hr>

                            <p class="h3" >Повторні запити:</p>

                            <ul>
                                <li v-for="(value, key) in slotProps.data.requests" :key="key">
                                    <b>{{ formated_date(value.created_at, 'YYYY-MM-DD H:mm:ss') }}:</b> {{ value.response }}
                                </li>
                            </ul>

                        </div>


                    </div>
                </template>


            </DataTable>



        </div>

    </div>


</template>


<script>
    import { FilterMatchMode, FilterOperator } from 'primevue/api';
    import moment from "moment";


    export default {
        data() {
            return {
                loading: false,
                totalRecords: 0,
                items: null,

                lazyParams: {},
                //filters: {},

                expandedRows: [],

                prop_names: [], // назви для властивостів

                loading__send_request_again: false,

                filters:{
                    daterange:{
                        dates: null,
                        minDate: null,
                        maxDate: null,
                    },
                    query_string: ''
                },

                typingTimeout: null

            }
        },

        created() {
            this.initFilters();

            this.init_datarange();
        },

        mounted() {

            this.loading = true;

            this.lazyParams = {
                first: 0,
                rows: this.$refs.dt.rows,
                sortField: null,
                sortOrder: null,
                filters: this.filters,
            };

            this.loadLazyData();
        },

        methods: {

            loadLazyData() {




                setTimeout(() => {

                    var copy_lazyParams = this.lazyParams;


                    this.loading = true;

                    try {
                        //const res = await axios.get('/api/posts',{
                        const res = this.axios.get('/api/order',{
                            params: {
                                dt_params: JSON.stringify(copy_lazyParams),  // JSON.stringify(this.lazyParams),
                                searchable_columns: JSON.stringify(['order_id']),
                            },
                        }).then(response => {
                            //alert('response ok');
                            this.items = response.data.items;
                            this.totalRecords = response.data.totalRecords;
                            this.prop_names = response.data.prop_names;

                            this.loading = false;
                        });

                    } catch (e) {

                        this.items = [];
                        this.totalRecords = 0;
                        this.loading = false;
                    }



                    console.log(JSON.stringify(this.lazyParams));

                }, 1000);



                    /*
                    let items = [
                        {
                            created_at: '2023-12-24 16:05:28',
                            name: 'Юрій',
                            phone1: '0982710209',
                            operatorid: 35,
                            status: 1
                        }
                    ];

                    let totalRecords = 1;





                    this.loading = true;

                    setTimeout(() => {

                        console.log("ended loaded");

                        this.items = items;
                        this.totalRecords = totalRecords;
                        this.loading = false;

                    }, 1000);
                    */



            },


            onPage(event) {
                this.lazyParams = event;
                this.loadLazyData();
            },

            onSort(event) {
                this.lazyParams = event;
                this.loadLazyData();
            },


            onFilter() {
                //alert('onFilter');
                this.lazyParams.filters = this.filters;
                this.loadLazyData();
            },

            initFilters() {

                /*this.filters = {
                    'selectedStatuses': {value: null, matchMode: FilterMatchMode.CONTAINS},
                    'only_call_with_events': {value: false, matchMode: FilterMatchMode.CONTAINS},
                    'time_to_near_event': {value: 0, matchMode: FilterMatchMode.CONTAINS},
                }*/

            },


            formated_date(value, format){
                return moment(value).format(format);
            },

            formated_prop(key, value){
                if( String(value).trim() == '') return value;

                if(key == 'с_recording'){
                    var url = 'http://178.20.158.150/Asterisk/'+value;
                    return '<a href="'+url+'">'+value+'</a>';
                }

                return value;
            },


            send_request_again( item ){

                //console.log('order_id = '+order_id); return false;

                this.loading__send_request_again = true;

                const _this = this;

                try {
                    //const res = await axios.get('/api/posts',{
                    const res = this.axios.post('/api/order/'+item.id+'/send_request_again',{
                        params: {},
                    }).then(response => {

                        console.log(['response.data', response.data]);
                        const index = _this.items.findIndex(i => i.id === item.id);
                        _this.items[index] = response.data.order;   //_this.$set(_this.items, index, response.data.order);

                        this.loading__send_request_again = false;

                    });

                } catch (e) {

                    //this.loading = false;
                    console.log(['ERROR','gdfgdfg']);
                }


            },







            init_datarange(){

                let today = new Date();

                let day = today.getDate();
                let month = today.getMonth();
                let year = today.getFullYear();


                let prevMonth = month === 0 ? 11 : month - 1;

                let def_start_date = new Date();
                def_start_date.setDate(def_start_date.getDate() - 1);

                this.filters.daterange.dates =[
                    def_start_date,
                    today
                ];


                this.filters.daterange.minDate = new Date();
                this.filters.daterange.minDate.setMonth(prevMonth);
                this.filters.daterange.minDate.setFullYear(year);

                this.filters.daterange.maxDate = new Date();
                this.filters.daterange.maxDate.setMonth(month);
                this.filters.daterange.maxDate.setFullYear(year);

            },

            ChangeDateRange(){

                if( this.filters.daterange.dates[0]!=null && this.filters.daterange.dates[1]!=null){
                    this.loadLazyData();
                }

            },


            ChangeQueryString(){

                clearTimeout(this.typingTimeout);

                this.typingTimeout = setTimeout(() => {
                    console.log('No input for N seconds');
                    this.loadLazyData();
                    // Ваш код, который должен сработать после N секунд без ввода
                }, 1000); // N секунд (в данном случае 3 секунды)

            }



        }


    }

</script>
