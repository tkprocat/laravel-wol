@extends('layouts.app')

@section('content')
    <div class="container fluid" xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div id="users" class="panel panel-default">
                <div class="panel-heading">
                    <h1>Users</h1>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <th style="width: 100px">ID:</th>
                            <th>Username:</th>
                            <th style="width: 150px">Action:</th>
                        </thead>
                        <tbody>
                        <tr v-for="user in users">
                            <td>@{{ user.id }}</td>
                            <td>@{{ user.username }}</td>
                            <td>
                                <button v-on:click="showUpdateUserModal(user)" style="margin: 2px" class="btn btn-warning btn-sm">Update</button>
                                <button v-on:click="deleteUser(user)" style="margin: 2px" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <button data-toggle="modal" data-target="#addUserModal" class="btn btn-primary">Add new user</button>
                    </div>
                </div>
            </div>
        </div>
        <div tabindex="-1" role="dialog" id="addUserModal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div type="button" data-dismiss="modal" aria-label="Close" class="button close"><span aria-hidden="true">&times;</span></div>
                        <h4 class="modal-title">Create new user</h4>
                    </div>
                    <div class="modal-body">
                        <form name="addUser" action="/api/users" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-3">Username:</label>
                                <div class="col-md-7">
                                    <input type="text" name="username" v-model="username" placeholder="Username" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Password:</label>
                                <div class="col-md-7">
                                    <input type="password" name="password" v-model="password" placeholder="Password" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Confirm password:</label>
                                <div class="col-md-7">
                                    <input type="password" name="confirm_password" v-model="confirm_password" placeholder="Confirm password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8 col-md-offset-2 text-danger">
                                <ul>
                                    <li v-for="error_message in error_messages" track-by="$index">@{{ error_message }} </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div type="button" data-dismiss="modal" class="button btn btn-default">Close</div>
                        <div type="button" v-on:click="addUser()" class="button btn btn-primary">Create</div>
                    </div>
                </div>
            </div>
        </div>
        <div tabindex="-1" role="dialog" id="updateUserModal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div type="button" data-dismiss="modal" aria-label="Close" class="button close"><span aria-hidden="true">&times;</span>
                        </div>
                        <h4 class="modal-title">Update user information</h4>
                    </div>
                    <div class="modal-body">
                        <form name="updateUser" action="/api/users" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-3">Password:</label>
                                <div class="col-md-7">
                                    <input type="password" name="password" v-model="password" placeholder="Password" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Confirm password:</label>
                                <div class="col-md-7">
                                    <input type="password" name="confirm_password" v-model="confirm_password" placeholder="Confirm password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8 col-md-offset-2 text-danger">
                                <ul>
                                    <li v-for="error_message in error_messages" track-by="$index">@{{ error_message }} </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div type="button" data-dismiss="modal" class="button btn btn-default">Close</div>
                        <div type="button" v-on:click="updateUser()" class="button btn btn-primary">Update</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/all.js"></script>
    <script>
        new Vue({
            el: "body",
            data: {
                users: [],
                user_id: 0,
                username: '',
                password: '',
                confirm_password: '',
                error_messages: [],
            },
            ready: function() {
                this.getUsers();
            },
            methods: {
                getUsers: function() {
                    this.$http.get('/api/users').then(function(response) {
                        this.users = response.data;
                    }, function(response) {
                        alert(response);
                    });
                },
                deleteUser: function(user) {
                    this.$http.delete('/api/users', {
                        id: user.id
                    }).then(function(response) {
                        this.getUsers();
                    }, function(response) {
                        alert(response);
                    });
                },
                addUser: function() {
                    this.$http.post('/api/users', {
                        username: this.username,
                        password: this.password,
                        confirm_password: this.confirm_password
                    }).then(function(response) {
                        this.getUsers();
                        this.username = '';
                        this.password = '';
                        this.confirm_password = '';
                        this.error_messages = [];
                        $('#addUserModal').modal('hide');
                    }, function(response) {
                        var data = response.data;
                        this.error_messages = [];
                        for (var i = 0; i < data.length; i++) {
                            if (this.error_messages.indexOf(data[i].msg) < 0)
                                this.error_messages.push(data[i].msg);
                        }
                    })
                },
                updateUser: function() {
                    this.$http.post('/api/users/' + this.user_id, {
                        id: this.user_id,
                        password: this.password,
                        confirm_password: this.confirm_password
                    }).then(function(response) {
                        this.getUsers();
                        this.username = '';
                        this.password = '';
                        this.confirm_password = '';
                        this.error_messages = [];
                        $('#updateUserModal').modal('hide');
                    }, function(response) {
                        var data = response.data;
                        this.error_messages = [];
                        for (var i = 0; i < data.length; i++) {
                            if (this.error_messages.indexOf(data[i].msg) < 0)
                                this.error_messages.push(data[i].msg);
                        }
                    })
                },
                showUpdateUserModal: function(user) {
                    this.user_id = user.id;
                    $('#updateUserModal').modal('show');
                }
            }
        });
    </script>
@endsection
