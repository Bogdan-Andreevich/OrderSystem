<style>

    tr.p-rowgroup-header, tr.p-rowgroup-header div, table.p-datatable-table {
        background: #f3f4f6 !important;
    }

    .small-box .inner h3{
        font-weight: 500;
    }

    @media only screen and (max-width: 600px) {
        .small-box .inner h3 {
            font-size: 1.4em;
        }
    }


</style>




<template>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">


                <!-- ./col -->
                <div class="col-lg-3 col-6" >
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ widgets.w1.value }} UAH</h3>

                            <p>Погоджено</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>

                        <a v-tooltip.top="'Сума замовлень з статусом погоджено за останні 30 днів'" href="#" class="small-box-footer">
                            Детально <i class="fas fa fa-question-circle"></i>
                        </a>

                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ widgets.w2.value }} UAH</h3>

                            <p>Відмінено</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>

                        <a v-tooltip.top="'Сума замовлень з статусом відмінено за останні 30 днів'" href="#" class="small-box-footer">
                            Детально <i class="fas fa fa-question-circle"></i>
                        </a>

                    </div>
                </div>
                <!-- ./col -->


                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info" style="background-color: #1cc3ff !important;">
                        <div class="inner">
                            <h3>{{ widgets.w3.value }} UAH</h3>

                            <p>Нові</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>

                        <a v-tooltip.top="'Сума замовлень з статусом нове за останні 30 днів'" href="#" class="small-box-footer">
                            Детально <i class="fas fa fa-question-circle"></i>
                        </a>

                    </div>
                </div>



            </div>
        </div>
    </section>


    <div class="card p-fluid">

        <div class="container-fluid p-3">
            <div class="row">
                <div class="col-11">
                    <h5>Актуальні бронювання</h5>
                </div>
            </div>
        </div>


        <hr class="m-0">




        <DataTable
            :value="records"

            rowGroupMode="subheader"
            groupRowsBy="day_date"

            sortMode="single"
            sortField="day_date"
            :sortOrder="1"

            tableStyle="min-width: 50rem"
        >



            <Column field="id" header="id"></Column>

            <Column  header="Гість">
                <template #body="slotProps">
                    {{ slotProps.data.name }} {{ slotProps.data.surname }}
                </template>
            </Column>

            <Column field="categorie_name" header="Категорія"></Column>

            <Column field="robject_name" header="Обєкт"></Column>

            <Column  header="Сума">
                <template #body="slotProps">
                    {{ slotProps.data.amount }} UAH
                </template>
            </Column>


            <Column field="status_name" header="Статус">
                <template #body="slotProps">
                    <span class="badge p-2" :class="slotProps.data.status_class">{{ slotProps.data.status_name }}</span>
                </template>
            </Column>



            <template #groupheader="slotProps">
                <div class="flex align-items-center gap-2" >

                    <span>{{ slotProps.data.day_date }}</span>
                </div>
            </template>

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


                widgets:{
                    w1:{value:0},
                    w2:{value:0},
                    w3:{value:0},
                }


            }
        },

        computed:{


        },


        mounted() {
            this.loading = true;

            this.lazyParams = {
                first: 0,
                rows: 100,//this.$refs.dt.rows,
                sortField: null,
                sortOrder: null,
            };

            this.loadLazyData();
        },


        methods: {


            loadLazyData() {

                this.loading = true;


                try {
                    const res = this.axios.get('api/dashboard/index',{
                        params: {
                            dt_params: JSON.stringify(this.lazyParams)
                        },
                    }).then(response => {
                        //alert('response ok');
                        this.records = response.data.records;  //console.log(response.data.customers);
                        this.totalRecords = response.data.totalRecords;
                        this.widgets = response.data.widgets;
                        this.loading = false;
                    });

                } catch (e) {

                    this.records = [];
                    this.totalRecords = 0;
                    this.loading = false;
                }


            },



        }
    }







</script>
