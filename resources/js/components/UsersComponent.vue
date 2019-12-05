<template>
    <div class="container">
        <div class="row mt-5">
            <div style="margin-top: 10px" class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User List</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-block btn-outline-success" @click="newModal()">Add new
                                <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users" :key="user.id">

                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.roles.description | upText}}</td>
                                <td>{{user.status == 1 ? 'Activo' : 'Desactivo'}}</td>
                                <td>{{user.created_at | myDate}}</td>

                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" @click="editModal(user)">
                                        <i class="fa fa-edit blue"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash red"></i>
                                    </button>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->

        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addUserLabel">Add New <i class="fas fa-user"></i>
                        </h5>
                        <h5 class="modal-title" v-show="editmode" id="addUserLabel">Update User <i
                                class="fas fa-user"></i></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                       placeholder="Email Address"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" id="password"
                                       placeholder="Password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>


                            <div class="form-group">
                                <select name="role_id" v-model="form.role_id" id="role_id" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('role_id') }">
                                    <option value="">Select User Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Standard User</option>
                                    <option value="3">Author</option>
                                </select>
                                <has-error :form="form" field="role_id"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close <i
                                    class="fas fa-times"></i></button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Save <i
                                    class="fas fa-check"></i></button>
                            <button v-show="!editmode" type="submit" class="btn btn-success">Create <i
                                    class="fas fa-plus"></i></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                users: [],
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    role_id: '',
                    photo: ''
                })
            }
        },
        methods: {
            /**
             *
             */
            createUser() {
                this.editmode = false;
                this.form.post('api/user')
                    .then(() => {
                        vm.$emit('AfterCreate');
                        $('#addUser').modal('hide')
                        toast.fire('Success!', 'User Created in successfully.', 'success');
                    })
                    .catch((er) => {

                        toast.fire('Uops!', 'Complete all fields!', 'warning')
                    })
            },

            /**
             * update informatio user
             */
            updateUser() {
                // console.log('Editing data');
                this.form.put('api/user/' + this.form.id)
                    .then((res) => {
                        // success
                        $('#addUser').modal('hide');
                        toast.fire('Updated!', 'User: ' + res.data.name.toUpperCase() + ' has been updated.', 'success')
                        vm.$emit('afterUpdate', res);
                    })
                    .catch(() => {
                        toast.fire('Error!', 'There was something wronge.', 'error')
                    });
            },

            /**
             * Change status user
             * @param id
             */
            deleteUser(id) {
                swal.fire({
                    title: 'Are you sure?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/user/' + id).then(() => {
                            toast.fire('Success!', 'User has been deleted.', 'success'
                            );
                            vm.$emit('AfterCreate');
                        }).catch(() => {
                            toast.fire('Error!', 'There was something wronge.', 'error')
                        });
                    }
                })
            },

            /**
             * Show and complete user info
             * @param user
             */
            editModal(user) {
                this.editmode = true;
                this.form.reset();
                $('#addUser').modal('show');
                this.form.fill(user);
            },

            /**
             * Show modal to create new user
             */
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addUser').modal('show');
            },

            /**
             * Show all users
             */
            loadUsers() {
                axios.get("api/user").then(({data}) => (this.users = data.data));

            },
        },

        /**
         * Methods first charge
         */
        created() {
            this.loadUsers();

            vm.$on('AfterCreate', () => {
                this.loadUsers();
            });
            //event
            vm.$on('afterUpdate', (res) => {
                const index = this.users.findIndex(itemSearch => itemSearch.id === res.data.id)
                this.users[index].name = res.data.name;
                this.users[index].email = res.data.email;
                this.users[index].role_id = res.data.role_id;
            })
        }
    }
</script>
