@extends('layouts.app')

@section('content')

    <style>
        .btn{
            border-radius: 0.25rem !important;
        }
    </style>



    <div class="container-fluid">


        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">User Profile</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                         srcF="../../dist/img/user4-128x128.jpg"
                                         src="{{ asset('img/user_avatar.png') }}"
                                         alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                                <!---<p class="text-muted text-center">Software Engineer</p>-->


                                <p class="text-muted text-center">Зареєстрований {{ Auth::user()->created_at->format('d-m-Y') }}</p>

                                <!---
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Followers</b> <a class="float-right">1,322</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Following</b> <a class="float-right">543</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Friends</b> <a class="float-right">13,287</a>
                                    </li>
                                </ul>

                                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                --->

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->







                    </div>
                    <!-- /.col -->


                    <div class="col-md-9">

                        <div class="card p-4">

                                @if (session()->has('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('status') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <script>
                                        setTimeout(() => {
                                            document.location.href = "{{ route('home') }}";
                                        }, 2000);
                                    </script>

                                @endif


                                @if (session('error'))
                                   <div class="alert alert-danger" role="alert">
                                         {{ session('error') }}
                                   </div>
                                @endif



                            <form
                                method="POST"
                                action="{{ route('account/settings/update') }}"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">

                                    <label for="name">ФІО</label>

                                    <input
                                        name="name"
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        value="{{Auth::user()->name}}"
                                    />

                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                                <br>

                                <div class="mb-3">

                                    <label
                                        for="oldPasswordInput"
                                        class="form-label"
                                    >
                                        Old Password
                                    </label>


                                    <input
                                        name="old_password"
                                        type="password"
                                        class="form-control @error('old_password') is-invalid @enderror"
                                        id="oldPasswordInput"
                                        placeholder="Old Password"
                                    >
                                    @error('old_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="mb-3">

                                    <label
                                        for="newPasswordInput"
                                        class="form-label"
                                    >
                                        New Password
                                    </label>

                                    <input
                                        name="new_password"
                                        type="password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        id="newPasswordInput"
                                        placeholder="New Password"
                                    >

                                    @error('new_password')
                                         <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="mb-3">

                                    <label
                                        for="confirmNewPasswordInput"
                                        class="form-label"
                                    >
                                        Confirm New Password
                                    </label>

                                    <input
                                        name="new_password_confirmation"
                                        type="password"
                                        class="form-control"
                                        id="confirmNewPasswordInput"
                                        placeholder="Confirm New Password"
                                    >

                                </div>




                                <!--
                                <div class="form-group">
                                    <label for="date_of_birth">Date of birth</label>
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" value="{{Auth::user()->date_of_birth}}" />
                                </div>

                                <div class="form-group">
                                    <label for="education">Education</label>
                                    <input type="text" name="education" id="education" class="form-control" value="{{Auth::user()->education}}" />
                                </div>

                                <div class="form-group">
                                    <label for="profession">Profession</label>
                                    <input type="text" name="profession" id="profession" class="form-control" value="{{Auth::user()->profession}}" />
                                </div>
                                --->


                                <!--
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control"  />
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm New</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"  />
                                </div>
                                -->

                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Зберегти">
                                </div>

                            </form>

                        </div>








                    <!---other settings---->

                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Інші налаштування</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                            <form
                                method="POST"
                                action="{{ route('account/settings/update') }}"
                                enctype="multipart/form-data">

                                @csrf

                                <input type="hidden" name="other__settings" value="1">

                                <div class="form-group mb-3">

                                    <label for="email">
                                        Ел.Пошта

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-question-circle"
                                            viewBox="0 0 16 16"
                                            style="cursor:pointer;"


                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="Ел.Пошта"

                                        >

                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                        </svg>

                                    </label>


                                    <input
                                        name="email"
                                        type="text"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        value="{{ old('email', Auth::user()->email) }}"
                                    />

                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>




                                <div class="form-group mb-3">

                                    <label for="phone">
                                        Телефон

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-question-circle"
                                            viewBox="0 0 16 16"
                                            style="cursor:pointer;"


                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="Телефон"

                                        >

                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                        </svg>

                                    </label>


                                    <input
                                        name="phone"
                                        type="text"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        value="{{-- old('email', Auth::user()->phone) --}}"
                                    />

                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>



                                <div class="form-group mb-3">

                                    <label for="operatorid_in_crm">
                                        ID оператора в CRM

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-question-circle"
                                            viewBox="0 0 16 16"
                                            style="cursor:pointer;"


                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="ID оператора в CRM"

                                        >

                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                        </svg>

                                    </label>


                                    <input
                                        name="operatorid_in_crm"
                                        type="text"
                                        class="form-control @error('operatorid_in_crm') is-invalid @enderror"
                                        id="operatorid_in_crm"
                                        value="{{ old('operatorid_in_crm', Auth::user()->operatorid_in_crm) }}"
                                    />

                                    @error('operatorid_in_crm')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>



                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Зберегти">
                                </div>


                            </form>

                        </div>
                    </div>


                    </div>



                    <!-- /.col -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>


        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->








<script>
    document.addEventListener('DOMContentLoaded', function() {
        jQuery('[data-toggle="tooltip"]').tooltip();
    });
</script>


    </div>
@endsection
