<template>

    <Toast />

    <div class="card p-fluid">

        <div class="container-fluid p-3">
            <div class="row">
                <div class="col-11"><h5>Користувачі</h5></div>

            </div>
        </div>


        <hr class="m-0">


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


            <Column field="id" header="id" style="width:3%;"></Column>

            <Column field="name" header="Назва" style=""></Column>

            <Column field="role" header="Роль" style=""></Column>

            <Column field="created_at" header="Дата реєстрації" style=""></Column>

            <Column field="updated_at" header="Дата оновлення" style=""></Column>


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



            <Column  header="Дії" style="width:8%">
                <template #body="slotProps">

                    <div class="container-fluid">
                        <div class="row">


                            <div class="col-auto p-0">

                                <button
                                    @click="login_as_user(slotProps.data.id)"
                                    type="button"
                                    class="btn btn-default"

                                    v-tooltip.top="'Відвідати акаунт'"
                                >
                                    <i class="fas fa-sign-in-alt"></i>
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

                statuses: [
                    { name: 'Активний', code: 1 },
                    { name: 'Не активний', code: 2 },

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
                    const res = this.axios.get('api/user/index',{
                        params: {
                            dt_params: JSON.stringify(this.lazyParams)
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



            changeStatus(user){

                //console.log(order); return false;

                try {
                    const res = this.axios.post('api/user/update/'+user.id,{
                        status: user.status.code
                    }).then(response => {

                        if (response.data.status) {

                            this.showSuccess(
                                'Операцію успшно виконано',
                                'Статус користувача змінено.'
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





            login_as_user(id){
                let url = window.location.href.split('/').slice(0, -2).join('/') + '/api/user/loginas/' + id;   //console.log(url);
                window.location.href = url;
            }


        }
    }





</script>
