<template>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6" style="font-size: 20px;font-weight: 500;">

                    <ol v-if="this.$route.name=='home'" class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item active">Головна</li>
                    </ol>

                </div>


                <div class="col-sm-6">

                </div>


            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                       <router-view></router-view>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


</template>

<script>
    export default {}
</script>
