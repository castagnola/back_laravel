<style>
    .widget-user-header {
        background-size: cover;
        height: 500px;
    }
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white"
                         style="background: url('./img/profile.jpg') center center;">
                        <h3 class="widget-user-username text-right">Elizabeth Pierce</h3>
                        <h5 class="widget-user-desc text-right">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input v-model="form.name" type="text" class="form-control" id="inputName"
                                           placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input v-model="form.email" type="email" class="form-control" id="email"
                                           placeholder="Email"
                                           :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputExperience" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <input v-model="form.role_id" class="form-control" id="inputExperience"
                                           placeholder="Role" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <div class="offset-sm-2 col-sm-10">
                                        <input type="file" v-on:change="updatePhoto" class="custom-file-input"
                                               id="photo"
                                               name="photo">
                                        <label class="custom-file-label" for="photo">Choose file</label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button v-on:click="updateProfile(form)" type="button" class="btn btn-success">Save
                                        <i
                                                class="fas fa-check"></i></button>
                                </div>
                            </div>
                        </form>
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
             * Update profile information
             * @param data
             */
            updateProfile(data) {
                this.form.put(`api/profile`, data)
                    .then(() => {
                        toast.fire('Success!', 'Profile has been update.', 'success')
                    })
                    .catch(() => {
                        toast.fire('Error!', 'There was something wronge.', 'error')
                    });
            },

            /**
             * Upload image user
             * @param event
             */
            updatePhoto(event) {
                let file = event.target.files[0];
                console.log(file);
                let reader = new FileReader();
                if (file['size'] < 2111775) {
                    reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                    };
                    reader.readAsDataURL(file)
                    toast.fire('Success!', 'File has been add.', 'success')

                } else {
                    toast.fire('Warning!', 'The File is too long.', 'warning')
                }
            },
            /**
             * Update Profile Picture Instantly
             * @param event
             */
            getProfilePhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo;
                return photo;
            }
        },

        /**
         * Methods first charge
         */
        created() {
            axios.get('api/profile')
                .then(res => {
                    this.form.fill(res.data);
                })
        }
    }


</script>

<style scoped>

</style>