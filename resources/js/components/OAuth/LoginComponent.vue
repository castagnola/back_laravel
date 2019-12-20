<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form method="POST" v-on:submit.prevent="login()">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <input id="email" type="email" v-model="form.email" class="form-control"
                                           name="email"
                                           placeholder="Email" required autocomplete="email"
                                           autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <input id="password" type="password" v-model="form.password" class="form-control"
                                           name="password"
                                           placeholder="Password" required autocomplete="current-password"
                                           autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
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
                form: new Form({
                    email: '',
                    password: '',
                })
            }
        },
        methods: {
            login() {
                axios.post(`api/login`, this.form)
                    .then((res) => {
                        const token = res.data.access_token;
                        const expired = res.data.expires_in + Date.now();
                        this.$auth.setToken(token,expired);
                        this.$router.push(`/dashboard`);
                        toast.fire('Success!', 'bien', 'success')
                    })
                    .catch((error) => {
                        toast.fire('Uops!', error, 'warning');
                    })

            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>

</style>