<template>

    <Toast />

    <div class="card p-fluid">

        <div class="container-fluid p-3">
            <div class="row">
                <div class="col-md-1">

                    <SplitButton
                        label="Експорт"
                        :model="export_variants"
                        @click="export_to_csv"
                        class="mb-2">

                    </SplitButton>

                </div>

                <div class="col-auto mr-auto"></div>
                <div class="col-md-1">
                    <button  @click="$router.push({ name: 'order/create' })" type="button" class="btn btn-block bg-gradient-success">Додати</button>
                </div>
            </div>
        </div>


        <hr class="m-0">

        <h4 class="text-center p-2">Бронювання</h4>


        <DataTable
            :value="records"

            lazy paginator

            :rows="5"
            :rowsPerPageOptions="[5, 10, 20, 50]"

            ref="dt"

            dataKey="id"

            :totalRecords="totalRecords"
            :loading="loading"


            @page="onPage($event)"
            @sort="onSort($event)"

            showGridlines
            tableStyle="min-width: 75rem;padding:10px;"
        >




            <Column field="id" header="id" style=""></Column>

            <Column  header="Гість"  style="">
                <template #body="slotProps">
                    {{ slotProps.data.name }} {{ slotProps.data.surname }}
                </template>
            </Column>


            <Column field="categorie_name" header="Помешкання"  style=""></Column>

            <Column field="robject_name" header="Обєкт"  style=""></Column>



            <Column field="" header="Сума"  style="">
                <template #body="slotProps">
                    {{ slotProps.data.amount }} UAH
                </template>
            </Column>


            <Column field="created_at" header="Дата створення"  style="width:12%;"></Column>


            <Column field="" header="Період проживання"  style="width:15%;">
                <template #body="slotProps">
                    {{ slotProps.data.start_date }} --- {{ slotProps.data.end_date }}
                </template>
            </Column>




            <Column header="Статус"  style="width:10%;">
                <template #body="slotProps">

                    <Dropdown
                        inputId="dd-city"
                        v-model="slotProps.data.status"
                        :options="statuses"
                        optionLabel="name"
                        placeholder="Змінити статус"

                        @change="changeStatus(slotProps.data)"
                    />


                </template>
            </Column>


            <Column  header="Дії" style="width:5%">
                <template #body="slotProps">

                    <div class="container-fluid">
                        <div class="row p-2">


                            <div class="col-auto">

                                <button
                                    @click="$router.push({ name: 'order/edit', params: {id: slotProps.data.id} })"
                                    type="button"
                                    class="btn btn-default"

                                    v-tooltip.top="'Редагувати'"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>


                            </div>


                        </div>
                    </div>








                </template>
            </Column>






        </DataTable>

    </div>
</template>

<script>

    export default {
        data() {

            return {
                records: null,
                totalRecords: 0,

                loading: false,
                lazyParams: {},


                statuses: window.tmpl_data.order_statuses,
                /*
                selectedStatus: { name: 'Нове', code: 1 },
                statuses: [
                    { name: 'Нове', code: 1 },
                    { name: 'Підтверджене', code: 2 },
                ]
                */

                export_variants: [
                    {
                        label: 'в CSV',
                        ///icon: 'pi pi-refresh',
                        command: () => {
                            //this.$toast.add({ severity: 'success', summary: 'Updated', detail: 'Data Updated', life: 3000 });
                            window.location.href = '/account/api/order/export_to_csv';
                        }
                    },
                    {
                        label: 'в iCal',
                        ///icon: 'pi pi-times',
                        command: () => {
                            //this.$toast.add({ severity: 'warn', summary: 'Delete', detail: 'Data Deleted', life: 3000 });
                            window.location.href = '/account/api/order/export_to_ical';
                        }
                    },
                ]

            }
        },

        computed:{


        },


        mounted() {
            this.loading = true;

            this.lazyParams = {
                first: 0,
                rows: this.$refs.dt.rows,
                sortField: null,
                sortOrder: null,
            };

            this.loadLazyData();
        },

        methods: {


            loadLazyData() {


                this.loading = true;


                try {
                    const res = this.axios.get('api/order/index',{
                        params: {
                            dt_params: JSON.stringify(this.lazyParams),
                            searchable_columns: JSON.stringify(['order_id']),
                        },
                    }).then(response => {
                        //alert('response ok');
                        this.records = response.data.records;  //console.log(response.data.customers);
                        this.totalRecords = response.data.totalRecords;
                        this.loading = false;
                    });

                } catch (e) {

                    this.records = [];
                    this.totalRecords = 0;
                    this.loading = false;
                }



                /*
                this.records = [
                    {
                        c1: 45,
                        c2: 5,
                    },
                    {
                        c1: 54,
                        c2: 5,
                    },
                    {
                        c1: 23,
                        c2: 5,
                    }
                ];
                this.totalRecords = 3;
                this.loading = false;
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

            showSuccess(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'success', summary: psummary, detail: pdetail, life: 3000 });
            },

            showError(psummary='Success Message', pdetail='Message Content') {
                this.$toast.add({ severity: 'error', summary: psummary, detail: pdetail, life: 3000 });
            },






            changeStatus(order){

                //console.log(order); return false;

                try {
                    const res = this.axios.post('api/order/update/'+order.id,{
                        status_id: order.status.code
                        }).then(response => {

                        if (response.data.status) {

                            this.showSuccess(
                                'Операцію успшно виконано',
                                'Статус замовлення змінено.'
                            );

                        }else{

                            this.showError(
                                'Виникла помилка на сервері',
                                'Невідома помилка'
                            );
                        }

                    });


                } catch (e) {

                    console.log(e.response.status);

                    this.showError(
                        'Виникла помилка на сервері',
                        'Error '+e.response.status
                    );

                }

            },



            export_to_csv(){
                window.location.href = '/account/api/order/export_to_csv';

            }



        }
    }





</script>
